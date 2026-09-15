<?php

namespace App\Controllers;

use App\Models\PerguntaModel;
use App\Models\AlternativaModel;
use App\Models\RespondeModel;

/**
 * QuizController
 * Fluxo completo do quiz para o usuário logado, integrado ao MySQL.
 * Rotas protegidas pelo filtro 'userAuth' definido em Routes.php.
 *
 * Rotas:
 *   GET  /quiz           → index()    — inicia o quiz
 *   GET  /quiz/pergunta  → pergunta() — exibe pergunta atual (ou feedback, se já respondida)
 *   POST /quiz/responder → responder() — registra resposta e calcula o feedback
 *   POST /quiz/avancar   → avancar()  — avança para a próxima pergunta (ou resultado)
 *   GET  /quiz/resultado → resultado() — exibe resultado final
 */
class QuizController extends BaseController
{
    // CORRIGIDO: nenhum dos 5 métodos respondia JSON — só redirect()/view().
    // A correção do baseUrl (feita antes) já fazia o app acertar as rotas
    // certas, mas o quiz continuava quebrando porque a resposta nunca vinha
    // no formato que questoes.dart espera. Mesma receita usada em
    // Auth/Usuario/SensorController: um branch antes de cada retorno.
    private function querJson(): bool
    {
        return $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');
    }

    // ADICIONADO: estas 5 rotas agora também são alcançáveis via /api (ver
    // Routes.php), grupo que só tem o filtro 'cors' — o filtro 'userAuth'
    // continua protegendo apenas as rotas sem prefixo, usadas pelo site.
    // Sem esta checagem, uma chamada direta e não-autenticada a
    // api/quiz/responder chegaria com $cpf nulo e tentaria gravar
    // FK_CPF_USUARIO NULL (coluna NOT NULL) — mesmo padrão de autochecagem
    // já usado em UsuarioController::perfil()/atualizarPerfil().
    private function exigirLogin()
    {
        if (session()->get('usuario_cpf')) {
            return null;
        }

        if ($this->querJson()) {
            return $this->response->setStatusCode(401)->setJSON([
                'message' => 'Você precisa estar logado para acessar o quiz.',
            ]);
        }

        return redirect()->to('/login')
            ->with('erro', 'Você precisa estar logado para acessar o quiz.');
    }

    // ---------------------------------------------------------------
    // INICIAR QUIZ
    // ---------------------------------------------------------------

    public function index()
    {
        if ($resp = $this->exigirLogin()) {
            return $resp;
        }

        $model    = new PerguntaModel();
        $perguntas = $model->listarAleatorio(10);

        if (empty($perguntas)) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Ainda não há perguntas suficientes cadastradas para iniciar o quiz.',
                ]);
            }
            return redirect()->to('/inicio')
                ->with('erro', 'Ainda não há perguntas suficientes cadastradas para iniciar o quiz.');
        }

        // CORRIGIDO — mesma causa raiz do bug relatado (agora em
        // "correta_id"): array_column() pegava ID_PERGUNTA cru do banco
        // (string, via listarAleatorio/findAll sem conversão) e colocava
        // direto na sessão (quiz_ids). Dali esse valor reaparece em vários
        // pontos do fluxo (responder(), avancar()) — corrigindo na
        // origem, onde os IDs entram na sessão, evita que a mesma string
        // vaze de novo em outro ponto do JSON no futuro.
        $ids = array_map('intval', array_column($perguntas, 'ID_PERGUNTA'));

        session()->set([
            'quiz_ids'     => $ids,
            'quiz_indice'  => 0,
            'quiz_acertos' => 0,
            'quiz_total'   => count($ids),
        ]);

        if ($this->querJson()) {
            return $this->response->setJSON([
                'status'  => 200,
                'message' => 'Quiz iniciado.',
                'total'   => count($ids),
            ]);
        }

        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // EXIBIR PERGUNTA ATUAL
    // ---------------------------------------------------------------

    public function pergunta()
    {
        if ($resp = $this->exigirLogin()) {
            return $resp;
        }

        $ids    = session()->get('quiz_ids') ?? [];
        $indice = session()->get('quiz_indice') ?? 0;

        if (empty($ids) || $indice >= count($ids)) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Quiz não iniciado ou já finalizado. Inicie o quiz novamente.',
                ]);
            }
            return redirect()->to('/quiz/resultado');
        }

        $idAtual  = $ids[$indice];
        $model    = new PerguntaModel();
        $pergunta = $model->buscarComAlternativas($idAtual);

        if (!$pergunta) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Pergunta não encontrada.',
                ]);
            }
            return redirect()->to('/quiz/resultado');
        }

        // CORRIGIDO: buscarComAlternativas() traz IS_CORRETA em cada
        // alternativa (é usado também por listarComAlternativas(), da tela
        // administrativa, onde isso é necessário). Na página web isso nunca
        // vazava porque o HTML renderizado não expõe esse dado ao navegador
        // — mas devolvendo isso como JSON puro pro app, a resposta certa
        // fica visível antes do usuário responder. Removido só aqui, no
        // ponto que é exposto ao usuário, sem tocar no Model.
        if (isset($pergunta['alternativas']) && is_array($pergunta['alternativas'])) {
            foreach ($pergunta['alternativas'] as &$alt) {
                unset($alt['IS_CORRETA']);
            }
            unset($alt);
        }

        // Se a pergunta atual já foi respondida, exibe o feedback (modo revisão)
        $feedback = session()->get('quiz_feedback');
        if (!is_array($feedback) || (int) $feedback['id_pergunta'] !== (int) $idAtual) {
            $feedback = null;
        }

        $dados = [
            'pergunta' => $pergunta,
            'indice'   => $indice + 1,
            'total'    => session()->get('quiz_total'),
            'feedback' => $feedback,
            'ultima'   => ($indice + 1) >= session()->get('quiz_total'),
        ];

        if ($this->querJson()) {
            return $this->response->setJSON($dados);
        }

        return view('sistema/usuario/questoes/pergunta', $dados);
    }

    // ---------------------------------------------------------------
    // REGISTRAR RESPOSTA
    // ---------------------------------------------------------------

    public function responder()
    {
        if ($resp = $this->exigirLogin()) {
            return $resp;
        }

        $idAlternativa = (int) $this->request->getPost('id_alternativa');
        $cpf           = session()->get('usuario_cpf');

        if (!$idAlternativa) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Selecione uma alternativa antes de avançar.',
                ]);
            }
            return redirect()->to('/quiz/pergunta')
                ->with('erro', 'Selecione uma alternativa antes de avançar.');
        }

        $ids    = session()->get('quiz_ids') ?? [];
        $indice = session()->get('quiz_indice') ?? 0;

        if (empty($ids) || $indice >= count($ids)) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Quiz não iniciado ou já finalizado. Inicie o quiz novamente.',
                ]);
            }
            return redirect()->to('/quiz/resultado');
        }

        $idPergunta = $ids[$indice];

        // Já respondida? (evita registrar de novo se o usuário voltar/recarregar)
        $feedbackAtual = session()->get('quiz_feedback');
        if (is_array($feedbackAtual) && (int) $feedbackAtual['id_pergunta'] === (int) $idPergunta) {
            if ($this->querJson()) {
                // Idempotente: devolve o feedback já registrado em vez de erro
                // — o app pode chamar isso de novo num refresh/retry sem que
                // isso seja de fato um problema.
                return $this->response->setJSON(['feedback' => $feedbackAtual]);
            }
            return redirect()->to('/quiz/pergunta');
        }

        // Registra no banco
        (new RespondeModel())->registrar($cpf, $idAlternativa);

        // Verifica acerto usando IS_CORRETA (coluna correta do banco)
        $alternativaModel = new AlternativaModel();
        $alternativa      = $alternativaModel->find($idAlternativa);
        $acertou          = $alternativa && (int) $alternativa['IS_CORRETA'] === 1;

        if ($acertou) {
            session()->set('quiz_acertos', session()->get('quiz_acertos') + 1);
        }

        // Busca a alternativa correta da pergunta para exibir no feedback
        $correta = $alternativaModel
            ->where('FK_ID_PERGUNTA', $idPergunta)
            ->where('IS_CORRETA', 1)
            ->first();

        $feedback = [
            // CORRIGIDO (bug relatado: "'33': type 'String' is not a
            // subtype of type 'int?'"): correta_id vinha direto de
            // $correta['ID_ALTERNATIVA'] — resultado cru de first(), sem
            // conversão — o mesmo tipo de problema já corrigido em
            // PerguntaModel::buscarComAlternativas, só que aqui. id_pergunta
            // convertido também, por precaução (vem da sessão).
            'id_pergunta' => (int) $idPergunta,
            'escolhida'   => $idAlternativa,
            'correta_id'  => isset($correta['ID_ALTERNATIVA']) ? (int) $correta['ID_ALTERNATIVA'] : null,
            'acertou'     => $acertou,
        ];

        session()->set('quiz_feedback', $feedback);

        if ($this->querJson()) {
            return $this->response->setJSON(['feedback' => $feedback]);
        }

        // Permanece na mesma pergunta para mostrar o feedback;
        // o avanço para a próxima ocorre em /quiz/avancar
        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // AVANÇAR PARA A PRÓXIMA PERGUNTA (após o feedback)
    // ---------------------------------------------------------------

    public function avancar()
    {
        if ($resp = $this->exigirLogin()) {
            return $resp;
        }

        $ids    = session()->get('quiz_ids') ?? [];
        $indice = session()->get('quiz_indice') ?? 0;

        // Só avança se a pergunta atual já tiver feedback registrado
        $feedback = session()->get('quiz_feedback');
        if (!is_array($feedback) || empty($ids) || $indice >= count($ids)
            || (int) $feedback['id_pergunta'] !== (int) $ids[$indice]) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Nenhuma resposta pendente para avançar.',
                ]);
            }
            return redirect()->to('/quiz/pergunta');
        }

        session()->remove('quiz_feedback');

        $indice++;
        session()->set('quiz_indice', $indice);

        $terminou = $indice >= session()->get('quiz_total');

        if ($this->querJson()) {
            return $this->response->setJSON(['terminou' => $terminou]);
        }

        if ($terminou) {
            return redirect()->to('/quiz/resultado');
        }

        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // RESULTADO FINAL
    // ---------------------------------------------------------------

    public function resultado()
    {
        if ($resp = $this->exigirLogin()) {
            return $resp;
        }

        $dados = [
            'acertos' => session()->get('quiz_acertos') ?? 0,
            'total'   => session()->get('quiz_total')   ?? 0,
        ];

        session()->remove(['quiz_ids', 'quiz_indice', 'quiz_acertos', 'quiz_total', 'quiz_feedback']);

        if ($this->querJson()) {
            return $this->response->setJSON($dados);
        }

        return view('sistema/usuario/questoes/resultado', $dados);
    }
}
