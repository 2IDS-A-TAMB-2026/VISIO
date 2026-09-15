<?php

namespace App\Controllers;

use App\Models\RespondeModel;

/**
 * RespostaController
 * Gerencia o histórico de respostas da área do usuário logado.
 * Rotas protegidas pelo filtro 'userAuth' definido em Routes.php.
 *
 * Rotas:
 *   GET  /historico              → historico() — lista respostas do usuário logado
 *   GET  /resposta/excluir/(:num) → excluir($id) — remove uma resposta própria
 */
class RespostaController extends BaseController
{
    // ACHADO NA AUDITORIA (Etapa 4): historico.dart (Flutter) foi escrito
    // pra ler exatamente os campos que historicoPorUsuario() já devolve
    // (ID_RESPONDE, PERGUNTA_TEXTO, ALTERNATIVA_TEXTO, NIVEL_DIFICULDADE,
    // IS_CORRETA, RESPONDIDO_EM) — mas nenhum dos dois métodos deste
    // Controller respondia em JSON (só view()/redirect()), e nenhuma rota
    // pra cá existia dentro do grupo 'api' (só fora dele, protegida por
    // userAuth, usada pelo site). Mesmo padrão de causa raiz do quiz:
    // cliente e Model corretos, faltava a ligação no Controller/rotas.
    private function querJson(): bool
    {
        return $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');
    }

    // Também alcançável via /api agora (sem userAuth) — mesmo motivo e
    // mesmo padrão de autochecagem já usados em QuizController.
    private function exigirLogin()
    {
        if (session()->get('usuario_cpf')) {
            return null;
        }

        if ($this->querJson()) {
            return $this->response->setStatusCode(401)->setJSON([
                'message' => 'Você precisa estar logado para ver o histórico.',
            ]);
        }

        return redirect()->to('/login')
            ->with('erro', 'Você precisa estar logado para ver o histórico.');
    }

    public function historico()
    {
        if ($resp = $this->exigirLogin()) {
            return $resp;
        }

        $cpf   = session()->get('usuario_cpf');
        $model = new RespondeModel();

        $respostas     = $model->historicoPorUsuario($cpf);
        $total         = $model->totalPorUsuario($cpf);
        $total_acertos = $model->totalAcertosPorUsuario($cpf);

        if ($this->querJson()) {
            return $this->response->setJSON([
                'respostas'      => $respostas,
                'total'          => $total,
                'total_acertos'  => $total_acertos,
            ]);
        }

        return view('sistema/usuario/questoes/historico', compact(
            'respostas',
            'total',
            'total_acertos'
        ));
    }

    public function excluir(int $id)
    {
        if ($resp = $this->exigirLogin()) {
            return $resp;
        }

        $cpf   = session()->get('usuario_cpf');
        $model = new RespondeModel();

        $registro = $model->find($id);

        // Segurança: só permite excluir registros do próprio usuário
        if (!$registro || $registro['FK_CPF_USUARIO'] !== $cpf) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(404)->setJSON([
                    'message' => 'Registro não encontrado ou sem permissão para excluir.',
                ]);
            }
            return redirect()->to('/historico')
                ->with('erro', 'Registro não encontrado ou sem permissão para excluir.');
        }

        $model->excluir($id);

        if ($this->querJson()) {
            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Resposta removida do histórico.',
            ]);
        }

        return redirect()->to('/historico')
            ->with('sucesso', 'Resposta removida do histórico.');
    }
}
