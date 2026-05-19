<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

/**
 * Usuario
 * Gerencia o cadastro e perfil dos usuários comuns.
 * Rotas esperadas:
 *   GET  /usuario/cadastro       → formulário de cadastro
 *   POST /usuario/cadastro       → processa cadastro
 *   GET  /usuario/perfil         → exibe perfil do usuário logado
 *   POST /usuario/perfil         → atualiza perfil
 *   GET  /usuario/historico      → histórico de respostas do usuário
 *
 * Acesso admin:
 *   GET  /admin/usuarios         → lista todos (via AdminController)
 *   GET  /admin/usuario/(:cpf)   → detalhe (via AdminController)
 *   GET  /admin/usuario/excluir/(:cpf) → remoção (via AdminController)
 */
class Usuario extends BaseController
{
    // ---------------------------------------------------------------
    // CADASTRO PÚBLICO
    // ---------------------------------------------------------------

    /**
     * Exibe o formulário de cadastro de novo usuário.
     */
    public function cadastroForm(): string
    {
        return view('usuario/cadastro');
    }

    /**
     * Processa o cadastro do usuário.
     */
    public function cadastrar()
    {
        $cpf = $this->request->getPost('cpf');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $cartao = $this->request->getPost('cartao') ?? '';
        $data = $this->request->getPost('data_nascimento');
        $tel = $this->request->getPost('telefone');

        if (empty($cpf) || empty($email) || empty($senha)) {
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'CPF, e-mail e senha são obrigatórios.');
        }

        $model = new UsuarioModel();

        // Verifica duplicidade
        if ($model->buscarPorCpf($cpf)) {
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'CPF já cadastrado.');
        }

        $model->inserir([
            'CPF' => $cpf,
            'EMAIL' => $email,
            'SENHA' => password_hash($senha, PASSWORD_DEFAULT),
            'CARTAO' => $cartao,
            'DATA_NASCIMENTO' => $data,
            'TELEFONE' => $tel,
        ]);

        return redirect()->to('/login')
            ->with('sucesso', 'Cadastro realizado com sucesso!');
    }

    // ---------------------------------------------------------------
    // PERFIL DO USUÁRIO LOGADO
    // ---------------------------------------------------------------

    /**
     * Exibe o perfil do usuário logado.
     */
    public function perfil(): string
    {
        $cpf = session()->get('usuario_cpf');
        $model = new UsuarioModel();

        $dados['usuario'] = $model->buscarPorCpf($cpf);

        return view('usuario/perfil', $dados);
    }

    /**
     * Processa a atualização de perfil.
     */
    public function atualizarPerfil()
    {
        $cpf = session()->get('usuario_cpf');

        $dados = [
            'EMAIL' => $this->request->getPost('email'),
            'CARTAO' => $this->request->getPost('cartao') ?? '',
            'DATA_NASCIMENTO' => $this->request->getPost('data_nascimento'),
            'TELEFONE' => $this->request->getPost('telefone'),
        ];

        // Atualização de senha (opcional)
        $novaSenha = $this->request->getPost('senha');
        if (!empty($novaSenha)) {
            $dados['SENHA'] = password_hash($novaSenha, PASSWORD_DEFAULT);
        }

        $model = new UsuarioModel();
        $model->atualizar($cpf, $dados);

        return redirect()->to('/usuario/perfil')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    // ---------------------------------------------------------------
    // HISTÓRICO DE RESPOSTAS DO USUÁRIO LOGADO
    // ---------------------------------------------------------------

    /**
     * Exibe o histórico de quiz do usuário logado.
     */
    public function historico(): string
    {
        $cpf = session()->get('usuario_cpf');
        $model = new UsuarioModel();

        $dados['historico'] = $model->historicoPorCpf($cpf);

        return view('usuario/historico', $dados);
    }
}
