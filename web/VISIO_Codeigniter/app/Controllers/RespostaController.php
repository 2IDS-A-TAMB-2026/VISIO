<?php

namespace App\Controllers;

use App\Models\RespondeModel;

/**
 * Resposta
 * Gerencia o histórico de respostas (área do usuário logado).
 *
 * Rotas:
 *   GET /resposta/historico       → histórico completo do usuário logado
 *   GET /resposta/excluir/(:id)   → remove um registro específico (próprio)
 */
class RespostaController extends BaseController
{
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

    public function historico(): string
    {
        $cpf   = session()->get('usuario_cpf');
        $model = new RespondeModel();

        return view('sistema/usuario/questoes/historico', [
            'respostas'      => $model->historicoPorUsuario($cpf),
            'total'          => $model->totalPorUsuario($cpf),
            'total_acertos'  => $model->totalAcertosPorUsuario($cpf),
        ]);
    }

    public function excluir(int $id)
    {
        $cpf   = session()->get('usuario_cpf');
        $model = new RespondeModel();

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
