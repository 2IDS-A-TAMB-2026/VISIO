<?php

namespace App\Controllers;

use App\Models\PerguntaModel;
use App\Models\AlternativaModel;
use App\Models\RespondeModel;

/**
 * Quiz
 * Fluxo completo do quiz para o usuário logado.
 *
 * Rotas esperadas:
 *   GET  /quiz           → inicia o quiz (carrega perguntas na sessão)
 *   GET  /quiz/pergunta  → exibe a pergunta atual
 *   POST /quiz/responder → registra a resposta e avança
 *   GET  /quiz/resultado → exibe o resultado final
 */
class QuizController extends BaseController
{
    // Verifica sessão de usuário em toda requisição
    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        if (!session()->get('usuario_logado')) {
            header('Location: ' . base_url('/login'));
            exit;
        }
    }

    // ---------------------------------------------------------------
    // INICIAR QUIZ
    // ---------------------------------------------------------------

    /**
     * Carrega as perguntas na sessão e redireciona para a primeira.
     */
    public function index()
    {
        $model    = new PerguntaModel();
        $perguntas = $model->listarAleatorio(10);

        // Guarda apenas os IDs na sessão para controle do fluxo
        $ids = array_column($perguntas, 'ID_PERGUNTA');

        session()->set([
            'quiz_ids'    => $ids,
            'quiz_indice' => 0,
            'quiz_acertos' => 0,
            'quiz_total'   => count($ids),
        ]);

        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // EXIBIR PERGUNTA ATUAL
    // ---------------------------------------------------------------

    /**
     * Exibe a pergunta na posição atual do quiz.
     */
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

        $dados = [
            'pergunta' => $pergunta,
            'indice'   => $indice + 1,
            'total'    => session()->get('quiz_total'),
        ];

        return view('quiz/pergunta', $dados);
    }

    // ---------------------------------------------------------------
    // REGISTRAR RESPOSTA
    // ---------------------------------------------------------------

    /**
     * Recebe a alternativa escolhida, registra e avança para a próxima.
     */
    public function responder()
    {
        $idAlternativa = (int) $this->request->getPost('id_alternativa');
        $cpf           = session()->get('usuario_cpf');

        if (!$idAlternativa) {
            return redirect()->to('/quiz/pergunta')
                             ->with('erro', 'Selecione uma alternativa.');
        }

        // Registra a resposta
        $respondeModel = new RespondeModel();
        $respondeModel->registrar($cpf, $idAlternativa);

        // Verifica se a alternativa era correta e contabiliza
        $altModel    = new AlternativaModel();
        $alternativa = $altModel->buscarPorId($idAlternativa);

        if ($alternativa && $alternativa['CORRETA_ERRADA'] == 1) {
            $acertos = session()->get('quiz_acertos') + 1;
            session()->set('quiz_acertos', $acertos);
        }

        // Avança para a próxima pergunta
        $indice = session()->get('quiz_indice') + 1;
        session()->set('quiz_indice', $indice);

        $total = session()->get('quiz_total');
        if ($indice >= $total) {
            return redirect()->to('/quiz/resultado');
        }

        return redirect()->to('/quiz/pergunta');
    }

    // ---------------------------------------------------------------
    // RESULTADO FINAL
    // ---------------------------------------------------------------

    /**
     * Exibe o resultado do quiz ao final de todas as perguntas.
     */
    public function resultado()
    {
        $dados = [
            'acertos' => session()->get('quiz_acertos') ?? 0,
            'total'   => session()->get('quiz_total')   ?? 0,
        ];

        // Limpa dados do quiz da sessão
        session()->remove(['quiz_ids', 'quiz_indice', 'quiz_acertos', 'quiz_total']);

        return view('quiz/resultado', $dados);
    }
}
