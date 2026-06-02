<?php

namespace App\Controllers;

use App\Models\PerguntaModel;
use App\Models\AlternativaModel;
use App\Models\RespondeModel;

/**
 * QuizController
 * Fluxo completo do quiz para o usuário logado, integrado ao MySQL.
 */
class QuizController extends BaseController
{
    public function index()
    {
        $model = new PerguntaModel();
        $perguntas = $model->listarAleatorio(10); // Método que você criará no PerguntaModel

        // Validação de segurança: se não houver perguntas no banco, evita erro na tela
        if (empty($perguntas)) {
            return redirect()->to('/usuario/inicio')->with('erro', 'Ainda não há perguntas suficientes cadastradas para iniciar o quiz.');
        }

        $ids = array_column($perguntas, 'ID_PERGUNTA');

        session()->set([
            'quiz_ids' => $ids,
            'quiz_indice' => 0,
            'quiz_acertos' => 0,
            'quiz_total' => count($ids),
        ]);

        return redirect()->to('/quiz/pergunta');
    }

    public function pergunta()
    {
        $ids = session()->get('quiz_ids') ?? [];
        $indice = session()->get('quiz_indice') ?? 0;

        if (empty($ids) || $indice >= count($ids)) {
            return redirect()->to('/quiz/resultado');
        }

        $idAtual = $ids[$indice];
        $model = new PerguntaModel();

        // Vai buscar a pergunta e fazer um JOIN (ou buscar separado) as alternativas dela
        $pergunta = $model->buscarComAlternativas($idAtual);

        if (!$pergunta) {
            return redirect()->to('/quiz/resultado');
        }

        return view('sistema/usuario/questoes/pergunta', [
            'pergunta' => $pergunta,
            'indice' => $indice + 1,
            'total' => session()->get('quiz_total'),
        ]);
    }

    public function responder()
    {
        $idAlternativa = (int) $this->request->getPost('id_alternativa');
        $cpf = session()->get('usuario_cpf');

        if (!$idAlternativa) {
            return redirect()->to('/quiz/pergunta')
                ->with('erro', 'Selecione uma alternativa antes de avançar.');
        }

        // Registra a resposta na tabela RESPONDE
        (new RespondeModel())->registrar($cpf, $idAlternativa);

        // Busca a alternativa selecionada usando o método nativo find() do CI4
        $alternativaModel = new AlternativaModel();
        $alternativa = $alternativaModel->find($idAlternativa);

        // CORREÇÃO CRÍTICA: O nome da coluna no novo BD é IS_CORRETA (1 = sim, 0 = não)
        if ($alternativa && $alternativa['IS_CORRETA'] == 1) {
            session()->set('quiz_acertos', session()->get('quiz_acertos') + 1);
        }

        $indice = session()->get('quiz_indice') + 1;
        session()->set('quiz_indice', $indice);

        if ($indice >= session()->get('quiz_total')) {
            return redirect()->to('/quiz/resultado');
        }

        return redirect()->to('/quiz/pergunta');
    }

    public function resultado()
    {
        $dados = [
            'acertos' => session()->get('quiz_acertos') ?? 0,
            'total' => session()->get('quiz_total') ?? 0,
        ];

        // Limpa a sessão para poder jogar novamente
        session()->remove(['quiz_ids', 'quiz_indice', 'quiz_acertos', 'quiz_total']);

        return view('sistema/usuario/questoes/resultado', $dados);
    }
}