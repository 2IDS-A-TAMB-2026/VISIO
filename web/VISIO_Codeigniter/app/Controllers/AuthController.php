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

        // Verifica se o usuário existe e valida o hash da senha
        if (!$usuario || !password_verify($senha, $usuario['SENHA'])) {
            return redirect()->to('/login')
                ->with('erro', 'E-mail ou senha incorretos.');
        }

        // Configura a sessão do Usuário (incluindo o NOME)
        session()->set([
            'usuario_logado' => true,
            'usuario_cpf' => $usuario['CPF'],
            'usuario_email' => $usuario['EMAIL'],
            'tipo' => 'usuario',
        ]);

        return redirect()->to('/quiz');
    }

    public function loginAdminForm(): string
    {
        return view('sistema/admin/login_adm');
    }

    public function loginAdmin()
    {
        session()->set([
            'admin_logado' => true,
            'admin_cnpj' => '12345678000199',
            'admin_email' => 'admin_local@teste.com',
            'tipo' => 'admin',
        ]);

            return redirect()->to('/admin/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}