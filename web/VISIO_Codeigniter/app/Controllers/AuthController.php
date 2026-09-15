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
    // ---------------------------------------------------------------
    // ENDPOINT API (RETORNO JSON)
    // ---------------------------------------------------------------

    // ENCONTRADO NA AUDITORIA: devolvia findAll() sem nenhum filtro, ou
    // seja, incluía SENHA (hash bcrypt) de TODOS os usuários no JSON.
    // Nenhuma rota em Routes.php aponta para este método hoje — não é
    // acessível por nenhuma URL enquanto o auto-routing do CI4 estiver
    // desligado (padrão do framework, e consistente com o resto do
    // projeto, que registra toda rota explicitamente). Ainda assim é
    // código morto perigoso: se um dia alguém apontar uma rota pra cá sem
    // notar isso, ou ligar o auto-routing, vaza hash de senha de todo
    // mundo. A listagem "oficial" da API já existe e já é seletiva em
    // APIUsuarioController::index() — mesmo padrão aplicado aqui.
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
    // LOGIN COM GOOGLE (WEB)
    // ---------------------------------------------------------------

    /**
     * Recebe o ID token que a biblioteca "Sign In With Google" do Google
     * envia via POST para este endpoint (configurado como
     * data-login_uri na view de login/index.php). O corpo chega como
     * JSON: {"credential": "...", "g_csrf_token": "...", "client_id": "..."}
     * — não é application/x-www-form-urlencoded como o restante deste
     * controller, por isso lemos via getJSON() em vez de getPost().
     * Documentação:
     * https://developers.google.com/identity/gsi/web/guides/verify-google-id-token
     *
     * REGRA COMBINADA COM O USUÁRIO: login com Google só funciona para
     * quem JÁ tem conta cadastrada (identificação pelo e-mail). Não
     * cria conta nova aqui — o cadastro (ver UsuarioController::
     * cadastrar) exige CPF, data de nascimento e telefone, e o Google
     * não fornece nenhum desses três dados. Inventar valores para eles
     * geraria registros inválidos no banco (CPF é a chave primária de
     * USUARIO e tem que ser um CPF de verdade).
     */
    public function loginGoogle()
    {
        $corpo = $this->request->getJSON(true) ?? [];

        $credential = $corpo['credential'] ?? null;
        $csrfCorpo  = $corpo['g_csrf_token'] ?? null;
        $csrfCookie = $this->request->getCookie('g_csrf_token');

        // Passo recomendado pelo Google antes de olhar o token em si:
        // confere o padrão "double-submit cookie". Só JavaScript
        // rodando no NOSSO domínio consegue ler o cookie g_csrf_token
        // que a biblioteca do Google define — se o valor do cookie não
        // bater com o valor enviado no corpo, a requisição não veio
        // realmente da nossa página de login.
        if (
            empty($csrfCookie)
            || empty($csrfCorpo)
            || !hash_equals((string) $csrfCookie, (string) $csrfCorpo)
        ) {
            return redirect()->to('/login')
                ->with('erro', 'Não foi possível confirmar sua conta Google.');
        }

        if (empty($credential)) {
            return redirect()->to('/login')
                ->with('erro', 'Não foi possível autenticar com o Google.');
        }

        $clientId = env('GOOGLE_CLIENT_ID');

        if (empty($clientId)) {
            // Configuração ausente no servidor (.env) — não é um erro
            // do usuário, então não expomos detalhe nenhum a ele.
            log_message('critical', 'GOOGLE_CLIENT_ID não configurado no .env — login com Google desabilitado.');

            return redirect()->to('/login')
                ->with('erro', 'Login com Google não está configurado no momento.');
        }

        // Valida o ID token contra o próprio Google usando o endpoint
        // tokeninfo, em vez de uma biblioteca de verificação de JWT —
        // evita depender de um pacote Composer que talvez não esteja
        // instalado no vendor/ deste projeto. O Google documenta este
        // endpoint como válido, ainda que recomende uma biblioteca
        // dedicada (ex.: google/apiclient) para produção de alto
        // volume; para este projeto o tokeninfo é suficiente.
        try {
            $cliente = service('curlrequest');
            $resposta = $cliente->get('https://oauth2.googleapis.com/tokeninfo', [
                'query'       => ['id_token' => $credential],
                'http_errors' => false,
                'timeout'     => 5,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Falha ao contatar o Google para validar o ID token: {msg}', ['msg' => $e->getMessage()]);

            return redirect()->to('/login')
                ->with('erro', 'Não foi possível confirmar sua conta Google agora. Tente novamente.');
        }

        if ($resposta->getStatusCode() !== 200) {
            // O próprio tokeninfo já rejeita assinatura, iss e exp
            // inválidos, devolvendo um status diferente de 200 nesses
            // casos — não precisamos reimplementar essa checagem.
            return redirect()->to('/login')
                ->with('erro', 'Não foi possível confirmar sua conta Google.');
        }

        $dadosToken = json_decode($resposta->getBody(), true);

        // Confirma que o token foi emitido para O NOSSO client_id —
        // sem isso, um ID token válido de OUTRO app Google poderia ser
        // reaproveitado contra este endpoint.
        if (($dadosToken['aud'] ?? null) !== $clientId) {
            return redirect()->to('/login')
                ->with('erro', 'Não foi possível confirmar sua conta Google.');
        }

        // email_verified vem como STRING "true"/"false" na resposta do
        // tokeninfo, não como boolean.
        if (($dadosToken['email_verified'] ?? 'false') !== 'true') {
            return redirect()->to('/login')
                ->with('erro', 'Sua conta Google precisa ter o e-mail verificado.');
        }

        $email = $dadosToken['email'] ?? null;

        if (empty($email)) {
            return redirect()->to('/login')
                ->with('erro', 'Não foi possível obter o e-mail da conta Google.');
        }

        $model   = new UsuarioModel();
        $usuario = $model->buscarPorEmail($email);

        if (!$usuario) {
            // Regra combinada com o usuário: não cria conta nova aqui
            // (ver bloco de comentário acima da assinatura do método).
            return redirect()->to('/login')
                ->with('erro', 'Nenhuma conta encontrada com este e-mail. Cadastre-se primeiro.');
        }

        // Mesmo mecanismo de sessão do login tradicional (ver
        // loginUsuario logo acima) — login com Google não cria um
        // segundo tipo de sessão nem um novo padrão de autenticação.
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
        // CORRIGIDO: faltava este branch — sem ele, uma requisição JSON (ex.:
        // do app Flutter) nunca preenche $_POST, então getPost() sempre
        // devolvia null e o login de admin falhava mesmo com credenciais
        // corretas. Mesmo padrão de loginUsuario() acima.
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

        // Fallback: requisição via FORM tradicional
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

        // ENCONTRADO NA AUDITORIA: este era o único método de autenticação
        // que sempre devolvia um redirect HTML (302), mesmo quando chamado
        // via /api/logout esperando JSON — diferente do padrão já usado em
        // loginUsuario/loginAdmin/cadastrar/perfil neste mesmo arquivo e em
        // UsuarioController. Uma chamada fetch/AJAX pro endpoint da API
        // receberia a página de login como corpo da resposta em vez de uma
        // confirmação em JSON.
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
}