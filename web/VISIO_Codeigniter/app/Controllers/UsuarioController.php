<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RespondeModel;

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
        $nome   = $this->request->getPost('nome');
        $cpf    = $this->request->getPost('cpf');
        $email  = $this->request->getPost('email');
        $senha  = $this->request->getPost('senha');
        $cartao = $this->request->getPost('cartao') ?? ''; // <--- PEGA O CARTÃO GERADO NO HTML
        $data   = $this->request->getPost('data_nascimento');
        $tel    = $this->request->getPost('telefone');

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

        // Salva todos os dados incluindo o cartão gerado
        $model->insert([
            'CPF'             => $cpf,
            'NOME'            => $nome,
            'EMAIL'           => $email,
            'SENHA'           => password_hash($senha, PASSWORD_BCRYPT),
            'CARTAO'          => $cartao, // <--- SALVA NO BANCO DE DADOS
            'DATA_NASCIMENTO' => $data,
            'TELEFONE'        => $tel,
        ]);

        if ($querJson) {
            return $this->response->setJSON([
                'status'  => 200,
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

    public function perfil(): string
    {
        $cpf = session()->get('usuario_cpf');

        $usuarioModel = new UsuarioModel();
        $respondeModel = new RespondeModel();

        $usuario = $usuarioModel->where('CPF', $cpf)->first();
        $total = $respondeModel->totalPorUsuario($cpf);
        $acertos = $respondeModel->totalAcertosPorUsuario($cpf);

        $percentual = $total > 0
            ? round(($acertos / $total) * 100)
            : 0;

        return view('sistema/usuario_logado/perfil/index', [
            'usuario'    => $usuario,
            'total'      => $total,
            'acertos'    => $acertos,
            'percentual' => $percentual,
        ]);
    }

    public function atualizarPerfil()
    {
        $cpf = session()->get('usuario_cpf');
        $model = new UsuarioModel();
        $email = $this->request->getPost('email');

        // 1. Validação de Email Existente
        $emailExistente = $model->where('EMAIL', $email)->where('CPF !=', $cpf)->first();
        if ($emailExistente) {
            return redirect()->to('/perfil')
                ->with('erro', 'Este e-mail já está em uso por outra conta.');
        }

        // 2. Montagem dos dados básicos
        $dados = [
            'NOME'            => $this->request->getPost('nome'),
            'EMAIL'           => $email,
            'CARTAO'          => $this->request->getPost('cartao') ?? '',
            'DATA_NASCIMENTO' => $this->request->getPost('data_nascimento'),
            'TELEFONE'        => $this->request->getPost('telefone'),
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
        return redirect()->to('/perfil')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    public function loginPorCartao()
{
    $cartao = trim($this->request->getPost('cartao') ?? '');

    if (empty($cartao)) {
        return $this->response->setStatusCode(400)->setJSON([
            'status'  => 'erro',
            'message' => 'Por favor, informe ou aproxime o cartão.'
        ]);
    }

    $model = new UsuarioModel();
    $usuario = $model->where('CARTAO', $cartao)->first();

    if ($usuario) {
        // Cria a sessão de login
        session()->set([
            'usuario_cpf'  => $usuario['CPF'],
            'usuario_nome' => $usuario['NOME'],
            'logado'       => true
        ]);

        return $this->response->setJSON([
            'status'   => 'sucesso',
            'redirect' => base_url('/inicio') // Altere para a rota inicial do seu sistema
        ]);
    }

    // Se não encontrar o cartão no banco
    return $this->response->setStatusCode(401)->setJSON([
        'status'  => 'erro',
        'message' => 'Cartão não cadastrado ou inválido!'
    ]);
}
}