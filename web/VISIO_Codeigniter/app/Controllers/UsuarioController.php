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
        $nome = $this->request->getPost('nome');
        $cpf = $this->request->getPost('cpf');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $cartao = $this->request->getPost('cartao') ?? '';
        $data = $this->request->getPost('data_nascimento');
        $tel = $this->request->getPost('telefone');

        if (empty($nome) || empty($cpf) || empty($email) || empty($senha)) {
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'Nome, CPF, e-mail e senha são obrigatórios.');
        }

        $model = new UsuarioModel();

        if ($model->where('CPF', $cpf)->first()) {
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'CPF já cadastrado.');
        }

        if ($model->where('EMAIL', $email)->first()) {
            return redirect()->to('/usuario/cadastro')
                ->with('erro', 'Este e-mail já está em uso.');
        }

        $model->insert([
            'CPF' => $cpf,
            'NOME' => $nome,
            'EMAIL' => $email,
            'SENHA' => password_hash($senha, PASSWORD_BCRYPT),
            'CARTAO' => $cartao,
            'DATA_NASCIMENTO' => $data,
            'TELEFONE' => $tel,
        ]);

        return redirect()->to('/login')
            ->with('sucesso', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    // ---------------------------------------------------------------
    // INÍCIO DO USUÁRIO LOGADO — CORRIGIDO: caminho de view correto
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

        // Dados do usuário
        $usuario = $usuarioModel->where('CPF', $cpf)->first();

        // Estatísticas do quiz
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
    $foto = $this->request->getFile('foto'); // Nome que deve estar no 'name' do input HTML
    if ($foto && $foto->isValid() && !$foto->hasMoved()) {
        // Validação básica de extensão/tamanho para segurança
        $validacao = $this->validate([
            'foto' => [
                'uploaded[foto]',
                'mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]',
                'max_size[foto,2048]', // Máximo 2MB
            ],
        ]);

        if (!$validacao) {
            return redirect()->to('/perfil')->with('erro', 'Arquivo inválido. Escolha uma imagem PNG, JPG ou WEBP de até 2MB.');
        }
        // Nome aleatório e seguro para evitar conflitos de arquivos com o mesmo nome
        $novoNome = $foto->getRandomName();
        // Move para a pasta public/uploads/perfil/
        $foto->move(ROOTPATH . 'public/uploads/perfil/', $novoNome);
        // Busca o usuário atual para apagar a foto antiga do servidor (evita lixo no servidor)
        $usuarioAtual = $model->find($cpf);
        if (!empty($usuarioAtual['FOTO']) && file_exists(ROOTPATH . 'public/' . $usuarioAtual['FOTO'])) {
            if (!unlink(ROOTPATH . 'public/' . $usuarioAtual['FOTO'])) {
                log_message('warning', 'Não foi possível remover a foto antiga do usuário ' . $cpf . ': ' . $usuarioAtual['FOTO']);
            }
        }
        // Salva o caminho relativo no banco de dados
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

}