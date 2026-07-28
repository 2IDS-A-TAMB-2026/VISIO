<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ResetSenhaModel
 * Gerencia os tokens de redefinição de senha da tabela RESET_SENHA.
 * Cada token é válido por 30 minutos e pode ser usado uma única vez.
 */
class ResetSenhaModel extends Model
{
    protected $table      = 'RESET_SENHA';
    protected $primaryKey = 'ID_RESET';
    protected $returnType = 'array';

    protected $allowedFields = [
        'FK_EMAIL',
        'TOKEN',
        'USADO',
        'EXPIRA_EM',
    ];

    // ---------------------------------------------------------------
    // Gera e persiste um novo token para o e-mail informado.
    // Invalida todos os tokens anteriores desse e-mail antes de criar.
    // Retorna o token gerado (string hex de 64 chars).
    // ---------------------------------------------------------------
    public function gerarToken(string $email): string
    {
        // Invalida tokens anteriores não usados
        $this->where('FK_EMAIL', $email)
             ->where('USADO', 0)
             ->set(['USADO' => 1])
             ->update();

        $token    = bin2hex(random_bytes(32)); // 64 chars hex
        $expiraEm = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $this->insert([
            'FK_EMAIL'  => $email,
            'TOKEN'     => $token,
            'EXPIRA_EM' => $expiraEm,
        ]);

        return $token;
    }

    // ---------------------------------------------------------------
    // Busca um token válido (não usado e não expirado).
    // Retorna o registro ou null.
    // ---------------------------------------------------------------
    public function buscarValido(string $token): array|null
    {
        return $this->where('TOKEN', $token)
                    ->where('USADO', 0)
                    ->where('EXPIRA_EM >=', date('Y-m-d H:i:s'))
                    ->first();
    }

    // ---------------------------------------------------------------
    // Marca o token como usado após a redefinição.
    // ---------------------------------------------------------------
    public function marcarUsado(string $token): void
    {
        $this->where('TOKEN', $token)->set(['USADO' => 1])->update();
    }

    // ---------------------------------------------------------------
    // Limpa tokens expirados (manutenção opcional, pode ser chamada
    // em um scheduled task ou junto com o fluxo de geração).
    // ---------------------------------------------------------------
    public function limparExpirados(): void
    {
        $this->where('EXPIRA_EM <', date('Y-m-d H:i:s'))->delete();
    }
}
