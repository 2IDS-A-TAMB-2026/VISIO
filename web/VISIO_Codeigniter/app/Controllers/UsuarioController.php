<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RespondeModel;
use App\Models\LoginCartaoModel;

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
        $nome = $this->request->getPost('nome');
        $cpf = $this->request->getPost('cpf');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $data = $this->request->getPost('data_nascimento');
        $tel = $this->request->getPost('telefone');
        
        $querJson = $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');

        if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
            if ($querJson) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Nome, CPF, e-mail e senha são obrigatórios.',
                ]);
            }
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'Nome, CPF, e-mail e senha são obrigatórios.');
        }

        $model = new UsuarioModel();

        if ($model->where('CPF', $cpf)->first()) {
            if ($querJson) {
                return $this->response->setStatusCode(409)->setJSON([
                    'message' => 'CPF já cadastrado.',
                ]);
            }
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'CPF já cadastrado.');
        }

        if ($model->where('EMAIL', $email)->first()) {
            if ($querJson) {
                return $this->response->setStatusCode(409)->setJSON([
                    'message' => 'Este e-mail já está em uso.',
                ]);
            }
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'Este e-mail já está em uso.');
        }

        do {
            $cartao = '';

            for ($i = 0; $i < 16; $i++) {
                $cartao .= random_int(0, 9);
            }

        } while ($model->where('CARTAO', $cartao)->first());

        $model->insert([
            'CPF' => $cpf,
            'NOME' => $nome,
            'EMAIL' => $email,
            'SENHA' => password_hash($senha, PASSWORD_BCRYPT),
    
            'CARTAO' => $cartao,
            'DATA_NASCIMENTO' => $data,
            'TELEFONE' => $tel,
        ]);

        if ($querJson) {
            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Cadastro realizado com sucesso!',
            ]);
        }

        return redirect()->to('/login')
            ->with('sucesso', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    // ---------------------------------------------------------------
    // INÍCIO DO USUÁRIO LOGADO
    // ---------------------------------------------------------------

    public function inicio(): string
    {
        return view('sistema/usuario/inicio/index');
    }

    // ---------------------------------------------------------------
    // PERFIL DO USUÁRIO LOGADO
    // ---------------------------------------------------------------

    public function perfil()
    {
        $cpf = session()->get('usuario_cpf');

        $querJson = $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');

        if (empty($cpf)) {
            if ($querJson) {
                return $this->response->setStatusCode(401)->setJSON([
                    'message' => 'Usuário não autenticado.'
                ]);
            }
            return redirect()->to('/login')->with('erro', 'Faça login para continuar.');
        }

        $usuarioModel = new UsuarioModel();
        $respondeModel = new RespondeModel();

        // Dados do usuário
        $usuario = $usuarioModel->where('CPF', $cpf)->first();

        if (!$usuario && $querJson) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Usuário não encontrado.'
            ]);
        }

        // Estatísticas do quiz
        $total = $respondeModel->totalPorUsuario($cpf);
        $acertos = $respondeModel->totalAcertosPorUsuario($cpf);

        $percentual = $total > 0
            ? round(($acertos / $total) * 100)
            : 0;

        if ($querJson) {
            unset($usuario['SENHA']);
            $usuario['TOTAL'] = $total;
            $usuario['ACERTOS'] = $acertos;
            $usuario['PERCENTUAL'] = $percentual;

            return $this->response->setJSON($usuario);
        }

        return view('sistema/usuario_logado/perfil/index', [
            'usuario' => $usuario,
            'total' => $total,
            'acertos' => $acertos,
            'percentual' => $percentual,
        ]);
    }

    public function atualizarPerfil()
    {
        $cpf = session()->get('usuario_cpf');

        $querJson = $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');

        if (empty($cpf)) {
            if ($querJson) {
                return $this->response->setStatusCode(401)->setJSON([
                    'message' => 'Usuário não autenticado.'
                ]);
            }
            return redirect()->to('/login')->with('erro', 'Faça login para continuar.');
        }

        $model = new UsuarioModel();
        $email = $this->request->getPost('email');

        // 1. Validação de Email Existente
        $emailExistente = $model->where('EMAIL', $email)->where('CPF !=', $cpf)->first();
        if ($emailExistente) {
            if ($querJson) {
                return $this->response->setStatusCode(409)->setJSON([
                    'message' => 'Este e-mail já está em uso por outra conta.'
                ]);
            }
            return redirect()->to('/perfil')
                ->with('erro', 'Este e-mail já está em uso por outra conta.');
        }

        // 2. Montagem dos dados básicos
        $dados = [
            'NOME' => $this->request->getPost('nome'),
            'EMAIL' => $email,
            'DATA_NASCIMENTO' => $this->request->getPost('data_nascimento'),
            'TELEFONE' => $this->request->getPost('telefone'),
        ];

        // 3. Upload da Foto de Perfil
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $validacao = $this->validate([
                'foto' => [
                    'uploaded[foto]',
                    'mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]',
                    'max_size[foto,2048]',
                ],
            ]);

            if (!$validacao) {
                if ($querJson) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'message' => 'Arquivo inválido. Escolha uma imagem PNG, JPG ou WEBP de até 2MB.'
                    ]);
                }
                return redirect()->to('/perfil')->with('erro', 'Arquivo inválido. Escolha uma imagem PNG, JPG ou WEBP de até 2MB.');
            }

            $novoNome = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/perfil/', $novoNome);

            $usuarioAtual = $model->find($cpf);
            if (!empty($usuarioAtual['FOTO']) && file_exists(ROOTPATH . 'public/' . $usuarioAtual['FOTO'])) {
                if (!unlink(ROOTPATH . 'public/' . $usuarioAtual['FOTO'])) {
                    log_message('warning', 'Não foi possível remover a foto antiga do usuário ' . $cpf . ': ' . $usuarioAtual['FOTO']);
                }
            }

            $dados['FOTO'] = 'uploads/perfil/' . $novoNome;
        }

        // 4. Verificação de Nova Senha
        $novaSenha = $this->request->getPost('senha');
        if (!empty($novaSenha)) {
            $dados['SENHA'] = password_hash($novaSenha, PASSWORD_BCRYPT);
        }

        // 5. Atualização no Banco de Dados
        $model->update($cpf, $dados);

        if ($querJson) {
            $usuarioAtualizado = $model->find($cpf);
            unset($usuarioAtualizado['SENHA']);
            return $this->response->setJSON($usuarioAtualizado);
        }

        return redirect()->to('/perfil')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    // ---------------------------------------------------------------
    // LOGIN VIA CARTÃO RFID (polling da tela de login)
    // ---------------------------------------------------------------

    /**
     * Chamado repetidamente (polling) pelo JavaScript da tela de login
     * enquanto o botão "Entrar com cartão" está ativo. Não é a
     * requisição do ESP32 — é a do PRÓPRIO NAVEGADOR verificando se
     * algum cartão foi autorizado recentemente. É por isso que
     * session()->set() aqui funciona: quem está fazendo esta
     * requisição é o navegador que deve ficar logado, não o ESP32.
     *
     * Explicação completa do porquê esse modelo de polling existe (em
     * vez do ESP32 "logar" o navegador diretamente, o que não é
     * tecnicamente possível — são dois clientes HTTP sem cookie em
     * comum) em WEB/app/Models/LoginCartaoModel.php.
     *
     * Sempre responde JSON — usado exclusivamente via JavaScript
     * (fetch) na tela de login, nunca como submissão de formulário.
     */
    public function loginPorCartao()
    {
        $loginCartaoModel = new LoginCartaoModel();
        $cpf = $loginCartaoModel->consumirMaisRecente();

        if (!$cpf) {
            // Ainda esperando alguém aproximar um cartão autorizado —
            // não é um erro, é o estado normal enquanto aguarda.
            return $this->response->setJSON([
                'autorizado' => false,
            ]);
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($cpf);

        if (!$usuario) {
            // Situação rara: o usuário foi removido entre o ESP32
            // registrar a autorização e o navegador consumi-la.
            return $this->response->setJSON([
                'autorizado' => false,
            ]);
        }

        // Mesmo mecanismo de sessão do login tradicional (ver
        // AuthController::loginUsuario) — login por cartão não cria
        // um segundo tipo de sessão nem um novo padrão de autenticação.
        session()->set([
            'usuario_logado' => true,
            'usuario_cpf'    => $usuario['CPF'],
            'usuario_email'  => $usuario['EMAIL'],
            'tipo'           => 'usuario',
        ]);

        return $this->response->setJSON([
            'autorizado' => true,
            'redirect'   => '/perfil',
        ]);
    }
}