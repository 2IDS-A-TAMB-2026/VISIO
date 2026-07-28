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
    // ---------------------------------------------------------------
    // INICIAR QUIZ
    // ---------------------------------------------------------------

    public function index()
    {
        $model    = new PerguntaModel();
        $perguntas = $model->listarAleatorio(10);

        if (empty($perguntas)) {
            return redirect()->to('/inicio')
                ->with('erro', 'Ainda não há perguntas suficientes cadastradas para iniciar o quiz.');
        }

        $ids = array_column($perguntas, 'ID_PERGUNTA');

        session()->set([
            'quiz_ids'     => $ids,
            'quiz_indice'  => 0,
            'quiz_acertos' => 0,
            'quiz_total'   => count($ids),
        ]);

        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // EXIBIR PERGUNTA ATUAL
    // ---------------------------------------------------------------

    public function pergunta()
    {
        $ids    = session()->get('quiz_ids') ?? [];
        $indice = session()->get('quiz_indice') ?? 0;

        if (empty($ids) || $indice >= count($ids)) {
            return redirect()->to('/quiz/resultado');
        }

        $idAtual  = $ids[$indice];
        $model    = new PerguntaModel();
        $pergunta = $model->buscarComAlternativas($idAtual);

        if (!$pergunta) {
            return redirect()->to('/quiz/resultado');
        }

        // Se a pergunta atual já foi respondida, exibe o feedback (modo revisão)
        $feedback = session()->get('quiz_feedback');
        if (!is_array($feedback) || (int) $feedback['id_pergunta'] !== (int) $idAtual) {
            $feedback = null;
        }

        return view('sistema/usuario/questoes/pergunta', [
            'pergunta' => $pergunta,
            'indice'   => $indice + 1,
            'total'    => session()->get('quiz_total'),
            'feedback' => $feedback,
            'ultima'   => ($indice + 1) >= session()->get('quiz_total'),
        ]);
    }

    // ---------------------------------------------------------------
    // REGISTRAR RESPOSTA
    // ---------------------------------------------------------------

    public function responder()
    {
        $idAlternativa = (int) $this->request->getPost('id_alternativa');
        $cpf           = session()->get('usuario_cpf');

        if (!$idAlternativa) {
            return redirect()->to('/quiz/pergunta')
                ->with('erro', 'Selecione uma alternativa antes de avançar.');
        }

        $ids    = session()->get('quiz_ids') ?? [];
        $indice = session()->get('quiz_indice') ?? 0;

        if (empty($ids) || $indice >= count($ids)) {
            return redirect()->to('/quiz/resultado');
        }

        $idPergunta = $ids[$indice];

        // Já respondida? (evita registrar de novo se o usuário voltar/recarregar)
        $feedbackAtual = session()->get('quiz_feedback');
        if (is_array($feedbackAtual) && (int) $feedbackAtual['id_pergunta'] === (int) $idPergunta) {
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

        session()->set('quiz_feedback', [
            'id_pergunta' => $idPergunta,
            'escolhida'   => $idAlternativa,
            'correta_id'  => $correta['ID_ALTERNATIVA'] ?? null,
            'acertou'     => $acertou,
        ]);

        // Permanece na mesma pergunta para mostrar o feedback;
        // o avanço para a próxima ocorre em /quiz/avancar
        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // AVANÇAR PARA A PRÓXIMA PERGUNTA (após o feedback)
    // ---------------------------------------------------------------

    public function avancar()
    {
        $ids    = session()->get('quiz_ids') ?? [];
        $indice = session()->get('quiz_indice') ?? 0;

        // Só avança se a pergunta atual já tiver feedback registrado
        $feedback = session()->get('quiz_feedback');
        if (!is_array($feedback) || empty($ids) || $indice >= count($ids)
            || (int) $feedback['id_pergunta'] !== (int) $ids[$indice]) {
            return redirect()->to('/quiz/pergunta');
        }

        session()->remove('quiz_feedback');

        $indice++;
        session()->set('quiz_indice', $indice);

        if ($indice >= session()->get('quiz_total')) {
            return redirect()->to('/quiz/resultado');
        }

        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // RESULTADO FINAL
    // ---------------------------------------------------------------

    public function resultado()
    {
        $dados = [
            'acertos' => session()->get('quiz_acertos') ?? 0,
            'total'   => session()->get('quiz_total')   ?? 0,
        ];

        session()->remove(['quiz_ids', 'quiz_indice', 'quiz_acertos', 'quiz_total', 'quiz_feedback']);

        return view('sistema/usuario/questoes/resultado', $dados);
    }
}
