<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ResetSenhaModel;

/**
 * RecuperacaoSenhaController
 *
 * Fluxo (Opção B — sem envio de e-mail):
 *   1. GET  /usuario/esqueceu_senha   → formulário de e-mail
 *   2. POST /usuario/esqueceu_senha   → gera token, exibe link de redefinição na tela
 *   3. GET  /usuario/redefinir_senha  → formulário de nova senha (via ?token=...)
 *   4. POST /usuario/redefinir_senha  → valida token, atualiza senha, invalida token
 */
class RecuperacaoSenhaController extends BaseController
{
    // ---------------------------------------------------------------
    // PASSO 1 — Exibir formulário de solicitação
    // ---------------------------------------------------------------
    public function form(): string
    {
        return view('sistema/usuario/esqueceu_senha/index');
    }

    // ---------------------------------------------------------------
    // PASSO 2 — Processar o e-mail, gerar token, exibir link
    // ---------------------------------------------------------------
    public function solicitar()
    {
        $email = trim($this->request->getPost('email') ?? '');

        if (empty($email)) {
            return redirect()->to('/usuario/esqueceu_senha')
                ->with('erro', 'Informe um e-mail válido.');
        }

        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->where('EMAIL', $email)->first();

        /*
         * Por segurança não revelamos se o e-mail existe ou não.
         * Mas como estamos em modo de demonstração (sem e-mail real),
         * exibimos a mensagem de "verifique" em qualquer caso —
         * o token só aparece se o e-mail existir de fato.
         */
        if ($usuario) {
            $resetModel = new ResetSenhaModel();
            $resetModel->limparExpirados(); // manutenção preventiva
            $token = $resetModel->gerarToken($email);

            // Monta o link de redefinição completo
            $link = base_url('/usuario/redefinir_senha?token=' . $token);

            return view('sistema/usuario/esqueceu_senha/token', [
                'link'  => $link,
                'token' => $token,
            ]);
        }

        return view('sistema/usuario/esqueceu_senha/token', [
            'link'  => null,
            'token' => null,
        ]);
    }

    // ---------------------------------------------------------------
    // PASSO 3 — Exibir formulário de nova senha
    // ---------------------------------------------------------------
    public function redefinirForm()
    {
        $token = $this->request->getGet('token') ?? '';

        if (empty($token)) {
            return redirect()->to('/usuario/esqueceu_senha')
                ->with('erro', 'Token inválido ou ausente.');
        }

        $resetModel = new ResetSenhaModel();
        $registro   = $resetModel->buscarValido($token);

        if (!$registro) {
            return redirect()->to('/usuario/esqueceu_senha')
                ->with('erro', 'Este link expirou ou já foi utilizado. Solicite um novo.');
        }

        return view('sistema/usuario/esqueceu_senha/redefinir', [
            'token' => $token,
            'email' => $registro['FK_EMAIL'],
        ]);
    }

    // ---------------------------------------------------------------
    // PASSO 4 — Salvar nova senha
    // ---------------------------------------------------------------
    public function redefinir()
    {
        $token       = trim($this->request->getPost('token') ?? '');
        $novaSenha   = $this->request->getPost('senha') ?? '';
        $confirma    = $this->request->getPost('confirma_senha') ?? '';

        if (empty($token) || empty($novaSenha)) {
            return redirect()->back()
                ->with('erro', 'Preencha todos os campos.');
        }

        if ($novaSenha !== $confirma) {
            return redirect()->back()
                ->with('erro', 'As senhas não coincidem.');
        }

        if (strlen($novaSenha) < 6) {
            return redirect()->back()
                ->with('erro', 'A senha deve ter pelo menos 6 caracteres.');
        }

        $resetModel = new ResetSenhaModel();
        $registro   = $resetModel->buscarValido($token);

        if (!$registro) {
            return redirect()->to('/usuario/esqueceu_senha')
                ->with('erro', 'Este link expirou ou já foi utilizado. Solicite um novo.');
        }

        // Atualiza a senha do usuário
        $usuarioModel = new UsuarioModel();
        $usuarioModel->where('EMAIL', $registro['FK_EMAIL'])
                     ->set(['SENHA' => password_hash($novaSenha, PASSWORD_BCRYPT)])
                     ->update();

        // Invalida o token
        $resetModel->marcarUsado($token);

        return redirect()->to('/login')
            ->with('sucesso', 'Senha redefinida com sucesso! Faça login com a nova senha.');
    }
}
