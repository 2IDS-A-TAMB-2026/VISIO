<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\UsuarioModel;

/**
 * AuthController
 * Gerencia login e logout de usuários e administradores integrados ao banco de dados MySQL.
 */
class AuthController extends BaseController
{
    // ---------------------------------------------------------------
    // LOGIN DO USUÁRIO COMUM
    // ---------------------------------------------------------------

    public function index(): string
    {
        return view('sistema/usuario/login/index');
    }

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

        session()->set([
            'usuario_logado' => true,
            'usuario_cpf' => $usuario['CPF'],
            'usuario_email' => $usuario['EMAIL'],
            'tipo' => 'usuario',
        ]);

        return redirect()->to('/perfil');
    }

    // ---------------------------------------------------------------
    // LOGIN DO ADMINISTRADOR — CORRIGIDO: autentica via banco de dados
    // ---------------------------------------------------------------

    public function loginAdminForm(): string
    {
        return view('sistema/admin/login_adm');
    }

    public function esqueceuSenhaAdmForm(): string
    {
        return view('sistema/admin/esqueceu_senha_adm/index');
    }

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

        // Verifica se o admin existe e valida o hash da senha
        if (!$admin || !password_verify($senha, $admin['SENHA'])) {
            return redirect()->to('/login/admin')
                ->with('erro', 'E-mail ou senha incorretos.');
        }

        session()->set([
            'admin_logado' => true,
            'admin_cnpj'   => $admin['CNPJ'],
            'admin_email'  => $admin['EMAIL'],
            'tipo'         => 'admin',
        ]);

        return redirect()->to('/admin/dashboard');
    }

    // ---------------------------------------------------------------
    // LOGOUT
    // ---------------------------------------------------------------

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
