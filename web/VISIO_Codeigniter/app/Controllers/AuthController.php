<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\UsuarioModel;

/**
 * AuthController
 * Gerencia login, logout e API de listagem de usuários.
 */
class AuthController extends BaseController
{
     //---------------------------------------------------------------
    // ENDPOINT API (RETORNO JSON)
    // ---------------------------------------------------------------
    public function listarUsuarios()
    {
        $model = new UsuarioModel();
        $usuarios = $model->findAll();

        foreach ($usuarios as &$usuario) {
            unset($usuario['SENHA']);
        }

        return $this->response->setJSON($usuarios);
    }

    // ---------------------------------------------------------------
    // LOGIN DO USUÁRIO COMUM (WEB)
    // ---------------------------------------------------------------

    public function index(): string
    {
        return view('sistema/usuario/login/index');
    }

    public function loginUsuario()
    {
        // Trata requisições enviadas por JSON/AJAX via Fetch
        if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Content-Type'), 'json')) {
            $json = $this->request->getJSON();
            $email = $json->email ?? null;
            $senha = $json->senha ?? null;

            if (empty($email) || empty($senha)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'E-mail e senha são obrigatórios.'
                ]);
            }

            $model = new UsuarioModel();
            $usuario = $model->buscarPorEmail($email);

            if (!$usuario || !password_verify($senha, $usuario['SENHA'])) {
                return $this->response->setStatusCode(401)->setJSON([
                    'message' => 'E-mail ou senha incorretos.'
                ]);
            }

            session()->set([
                'usuario_logado' => true,
                'usuario_cpf'    => $usuario['CPF'],
                'usuario_email'  => $usuario['EMAIL'],
                'tipo'           => 'usuario',
            ]);

            return $this->response->setJSON([
                'status'  => 200,
                'message' => 'Login realizado com sucesso!'
            ]);
        }

        // Fallback: requisição via FORM tradicional
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
            'usuario_cpf'    => $usuario['CPF'],
            'usuario_email'  => $usuario['EMAIL'],
            'tipo'           => 'usuario',
        ]);

        return redirect()->to('/perfil');
    }

    // ---------------------------------------------------------------
    // LOGIN DO ADMINISTRADOR
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
        if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Content-Type'), 'json')) {
            $json = $this->request->getJSON();
            $email = $json->email ?? null;
            $senha = $json->senha ?? null;

            if (empty($email) || empty($senha)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'E-mail e senha são obrigatórios.'
                ]);
            }

            $model = new AdminModel();
            $admin = $model->buscarPorEmail($email);

            if (!$admin || !password_verify($senha, $admin['SENHA'])) {
                return $this->response->setStatusCode(401)->setJSON([
                    'message' => 'E-mail ou senha incorretos.'
                ]);
            }

            session()->set([
                'admin_logado' => true,
                'admin_cnpj'   => $admin['CNPJ'],
                'admin_email'  => $admin['EMAIL'],
                'tipo'         => 'admin',
            ]);

            return $this->response->setJSON([
                'status'  => 200,
                'message' => 'Login realizado com sucesso!'
            ]);
        }

        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        if (empty($email) || empty($senha)) {
            return redirect()->to('/login/admin')
                ->with('erro', 'E-mail e senha são obrigatórios.');
        }

        $model = new AdminModel();
        $admin = $model->buscarPorEmail($email);

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

        $querJson = $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json');

        if ($querJson) {
            return $this->response->setJSON([
                'status'  => 200,
                'message' => 'Logout realizado com sucesso!',
            ]);
        }

        return redirect()->to('/login');
    }

    public function receberCartao()
    {
        $uid = $this->request->getPost('uid');

        if (empty($uid)) {
            return $this->response->setStatusCode(400)->setJSON([
                'sucesso' => false,
                'mensagem' => 'Cartão não informado.'
            ]);
        }

        $uid = strtoupper(trim($uid));

        $arquivo = WRITEPATH . 'cartao_lido.txt';

        file_put_contents($arquivo, $uid);

        return $this->response->setJSON([
            'sucesso' => true,
            'mensagem' => 'Cartão recebido.'
        ]);
    }

    // public function loginCartao()
    // {
    //     $arquivo = WRITEPATH . 'cartao_lido.txt';

    //     if (!file_exists($arquivo)) {
    //         return $this->response->setJSON([
    //             'autorizado' => false
    //         ]);
    //     }

    //     $uid = trim(file_get_contents($arquivo));

    //     if (empty($uid)) {
    //         return $this->response->setJSON([
    //             'autorizado' => false
    //         ]);
    //     }

    //     file_put_contents($arquivo, '');

    //     $model = new UsuarioModel();

    //     $usuario = $model->where('CARTAO', $uid)->first();

    //     if (!$usuario) {
    //         return $this->response->setJSON([
    //             'autorizado' => false,
    //             'mensagem' => 'Cartão não encontrado.'
    //         ]);
    //     }

    //     session()->set([
    //         'usuario_logado' => true,
    //         'usuario_cpf'    => $usuario['CPF'],
    //         'usuario_email'  => $usuario['EMAIL'],
    //         'tipo'           => 'usuario',
    //     ]);

    //     return $this->response->setJSON([
    //         'autorizado' => true,
    //         'nome'       => $usuario['NOME'],
    //         'cpf'        => $usuario['CPF'],
    //         'redirect'   => base_url('/perfil')
    //     ]);
    // }
    public function loginCartao()
{
    $arquivo = WRITEPATH . 'cartao_lido.txt';

    if (!file_exists($arquivo)) {
        return $this->response->setJSON([
            'autorizado' => false,
            'cartao_lido' => false
        ]);
    }

    $uid = trim(file_get_contents($arquivo));

    if (empty($uid)) {
        return $this->response->setJSON([
            'autorizado' => false,
            'cartao_lido' => false
        ]);
    }

    file_put_contents($arquivo, '');

    $model = new UsuarioModel();

    $usuario = $model->where('CARTAO', $uid)->first();

    if (!$usuario) {
        return $this->response->setJSON([
            'autorizado' => false,
            'cartao_lido' => true,
            'mensagem' => 'Cartão não autorizado.'
        ]);
    }

    session()->set([
        'usuario_logado' => true,
        'usuario_cpf'    => $usuario['CPF'],
        'usuario_email'  => $usuario['EMAIL'],
        'tipo'           => 'usuario',
    ]);

    return $this->response->setJSON([
        'autorizado' => true,
        'cartao_lido' => true,
        'nome'       => $usuario['NOME'],
        'cpf'        => $usuario['CPF'],
        'redirect'   => base_url('/perfil')
    ]);
}
}