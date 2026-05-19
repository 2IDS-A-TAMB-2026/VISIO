<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\UsuarioModel;

/**
 * Auth
 * Gerencia login e logout de usuários e administradores.
 * Rotas esperadas:
 *   GET  /login          → formulário de login do usuário
 *   POST /login          → processa login do usuário
 *   GET  /login/admin    → formulário de login do admin
 *   POST /login/admin    → processa login do admin
 *   GET  /logout         → encerra sessão
 */
class Auth extends BaseController
{
    // ---------------------------------------------------------------
    // LOGIN DE USUÁRIO
    // ---------------------------------------------------------------

    /**
     * Exibe o formulário de login do usuário.
     */
    public function index(): string
    {
        return view('auth/login_usuario');
    }

    /**
     * Processa o login do usuário.
     */
    public function loginUsuario()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        if (empty($email) || empty($senha)) {
            return redirect()->to('/login')
                ->with('erro', 'E-mail e senha são obrigatórios.');
        }

        $model = new UsuarioModel();
        $usuario = $model->buscarPorEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['SENHA'])) {
            return redirect()->to('/login')
                ->with('erro', 'E-mail ou senha incorretos.');
        }

        // Grava sessão do usuário
        session()->set([
            'usuario_logado' => true,
            'usuario_cpf' => $usuario['CPF'],
            'usuario_email' => $usuario['EMAIL'],
            'tipo' => 'usuario',
        ]);

        return redirect()->to('/quiz');
    }

    // ---------------------------------------------------------------
    // LOGIN DE ADMINISTRADOR
    // ---------------------------------------------------------------

    /**
     * Exibe o formulário de login do administrador.
     */
    public function loginAdminForm(): string
    {
        return view('auth/login_admin');
    }

    /**
     * Processa o login do administrador.
     */
    public function loginAdmin()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        if (empty($email) || empty($senha)) {
            return redirect()->to('/login/admin')
                ->with('erro', 'E-mail e senha são obrigatórios.');
        }

        $model = new AdminModel();
        $admin = $model->buscarPorEmail($email);

        if (!$admin) {
            return redirect()->to('/login/admin')
                ->with('erro', 'Credenciais inválidas.');
        }

        // Suporte a senha com hash bcrypt ou texto puro (legado)
        $senhaValida = (strpos($admin['SENHA'], '$2y$') === 0)
            ? password_verify($senha, $admin['SENHA'])
            : hash_equals($admin['SENHA'], $senha);

        if (!$senhaValida) {
            return redirect()->to('/login/admin')
                ->with('erro', 'Credenciais inválidas.');
        }

        // Grava sessão do administrador
        session()->set([
            'admin_logado' => true,
            'admin_cnpj' => $admin['CNPJ'],
            'admin_email' => $admin['EMAIL'],
            'tipo' => 'admin',
        ]);

        return redirect()->to('/admin/dashboard');
    }

    // ---------------------------------------------------------------
    // LOGOUT
    // ---------------------------------------------------------------

    /**
     * Encerra a sessão e redireciona para o login.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
