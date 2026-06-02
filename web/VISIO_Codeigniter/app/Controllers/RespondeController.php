<?php

namespace App\Controllers;

use App\Models\RespondeModel;

/**
 * Resposta
 * Gerencia o histórico de respostas (área do usuário logado).
 *
 * Rotas esperadas:
 *   GET /resposta/historico       → histórico completo do usuário logado
 *   GET /resposta/excluir/(:id)   → remove um registro específico (próprio)
 */
class RespostaController extends BaseController
{
    // Verifica sessão de usuário
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

    /**
     * Exibe o histórico de respostas do usuário logado.
     */
    public function historico(): string
    {
        $cpf = session()->get('usuario_cpf');
        $model = new RespondeModel();

        $dados['respostas'] = $model->historicoPorUsuario($cpf);
        $dados['total'] = $model->totalPorUsuario($cpf);
        $dados['total_acertos'] = $model->totalAcertosPorUsuario($cpf);

        return view('resposta/historico', $dados);
    }

    /**
     * Remove um registro de resposta do histórico do usuário logado.
     * Somente permite remover registros próprios.
     */
    public function excluir(int $id)
    {
        $cpf = session()->get('usuario_cpf');
        $model = new RespondeModel();

        // Segurança: verifica se o registro pertence ao usuário logado
        $registro = $model->find($id);
        if (!$registro || $registro['FK_CPF_USUARIO'] !== $cpf) {
            return redirect()->to('/resposta/historico')
                ->with('erro', 'Registro não encontrado.');
        }

        $model->excluir($id);

        return redirect()->to('/resposta/historico')
            ->with('sucesso', 'Resposta removida do histórico.');
    }
}
