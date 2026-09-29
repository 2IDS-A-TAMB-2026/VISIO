<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ResetSenhaAdminModel
 *
 * ADICIONADO — recuperação de senha do administrador.
 *
 * Gerencia os tokens da tabela RESET_SENHA_ADMIN. Mesmo padrão de
 * ResetSenhaModel (usuário comum), mas ligado a ADMIN por CNPJ, não por
 * e-mail: ADMIN.EMAIL não é UNIQUE no schema atual, então não pode ser
 * referenciado por uma FOREIGN KEY (RESET_SENHA, do usuário, só
 * consegue referenciar USUARIO.EMAIL porque lá o e-mail É único).
 * CNPJ, a chave primária de ADMIN, resolve isso sem precisar alterar a
 * tabela ADMIN existente.
 *
 * Cada token é válido por 30 minutos e pode ser usado uma única vez —
 * mesma regra do fluxo do usuário.
 *
 * REQUER a tabela RESET_SENHA_ADMIN, que ainda não existe no banco.
 * SQL enviado junto com esta correção (ver bd_visio.sql atualizado).
 */
class ResetSenhaAdminModel extends Model
{
    protected $table      = 'RESET_SENHA_ADMIN';
    protected $primaryKey = 'ID_RESET';
    protected $returnType = 'array';

    protected $allowedFields = [
        'FK_CNPJ',
        'TOKEN',
        'USADO',
        'EXPIRA_EM',
    ];

    // ---------------------------------------------------------------
    // Gera e persiste um novo token para o CNPJ informado.
    // Invalida todos os tokens anteriores desse admin antes de criar.
    // Retorna o token gerado (string hex de 64 chars).
    // ---------------------------------------------------------------
    public function gerarToken(string $cnpj): string
    {
        // Invalida tokens anteriores não usados
        $this->where('FK_CNPJ', $cnpj)
             ->where('USADO', 0)
             ->set(['USADO' => 1])
             ->update();

        $token    = bin2hex(random_bytes(32)); // 64 chars hex
        $expiraEm = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $this->insert([
            'FK_CNPJ'   => $cnpj,
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
    // Limpa tokens expirados (mesma função de manutenção opcional do
    // ResetSenhaModel).
    // ---------------------------------------------------------------
    public function limparExpirados(): void
    {
        $this->where('EXPIRA_EM <', date('Y-m-d H:i:s'))->delete();
    }
}
