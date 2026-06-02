<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

/**
 * UsuarioController
 * Gerencia o cadastro e perfil dos usuários comuns integrado ao MySQL.
 */
class UsuarioController extends BaseController
{
    // ---------------------------------------------------------------
    // CADASTRO PÚBLICO
    // ---------------------------------------------------------------

    public function cadastroForm(): string
    {
        return view('sistema/usuario/cadastro/index');
    }

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

        // 1. Verifica se CPF já existe
        if ($model->where('CPF', $cpf)->first()) {
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'CPF já cadastrado.');
        }

        // 2. Verifica se o E-mail já existe (Regra UNIQUE do Banco)
        if ($model->where('EMAIL', $email)->first()) {
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'Este e-mail já está em uso.');
        }

        // Inserção de dados usando o método nativo 'insert' do CI4
        $model->insert([
            'CPF' => $cpf,
            'EMAIL' => $email,
            'SENHA' => password_hash($senha, PASSWORD_BCRYPT),
            'CARTAO' => $cartao,
            'DATA_NASCIMENTO' => $data,
            'TELEFONE' => $tel,
        ]);

        // Redireciona para o LOGIN, pois a área de início agora é protegida
        return redirect()->to('/login')
            ->with('sucesso', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    // ---------------------------------------------------------------
    // INÍCIO DO USUÁRIO LOGADO
    // ---------------------------------------------------------------

    public function inicio(): string
    {
        return view('sistema/usuario_logado/inicio/index');
    }

    // ---------------------------------------------------------------
    // PERFIL DO USUÁRIO LOGADO
    // ---------------------------------------------------------------

    public function perfil(): string
    {
        $cpf = session()->get('usuario_cpf');
        $model = new UsuarioModel();

        return view('sistema/usuario/perfil_usuario/index', [
            'usuario' => $model->where('CPF', $cpf)->first(),
        ]);
    }

    public function atualizarPerfil()
    {
        $cpf = session()->get('usuario_cpf');
        $model = new UsuarioModel();
        $email = $this->request->getPost('email');

        // Verifica se o NOVO e-mail escolhido já não está a ser usado por outra pessoa
        $emailExistente = $model->where('EMAIL', $email)->where('CPF !=', $cpf)->first();
        if ($emailExistente) {
            return redirect()->to('/usuario/perfil')
                ->with('erro', 'Este e-mail já está em uso por outra conta.');
        }

        $dados = [
            'EMAIL' => $email,
            'CARTAO' => $this->request->getPost('cartao') ?? '',
            'DATA_NASCIMENTO' => $this->request->getPost('data_nascimento'),
            'TELEFONE' => $this->request->getPost('telefone'),
        ];

        $novaSenha = $this->request->getPost('senha');
        if (!empty($novaSenha)) {
            $dados['SENHA'] = password_hash($novaSenha, PASSWORD_BCRYPT);
        }

        // Método nativo do CI4 para atualizar a linha cujo ID é o $cpf
        $model->update($cpf, $dados);


        return redirect()->to('/usuario/perfil')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    // ---------------------------------------------------------------
    // HISTÓRICO DE RESPOSTAS DO USUÁRIO LOGADO
    // ---------------------------------------------------------------

    public function historico(): string
    {
        $cpf = session()->get('usuario_cpf');
        $model = new UsuarioModel();

        // Assumindo que você criará esse método no UsuarioModel depois (usaremos JOIN para buscar do BD)
        return view('sistema/usuario/questoes/historico', [
            'historico' => $model->historicoPorCpf($cpf),
        ]);
    }
}