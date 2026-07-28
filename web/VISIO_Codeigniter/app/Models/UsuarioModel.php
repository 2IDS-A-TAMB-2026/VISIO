<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UsuarioModel
 * Responsável por todas as operações da tabela USUARIO.
 * Chave primária: CPF (string, sem auto-incremento).
 */
class UsuarioModel extends Model
{
    protected $table = 'USUARIO';
    protected $primaryKey = 'CPF';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';

    protected $allowedFields = [
        'CPF',
        'NOME',
        'EMAIL',
        'SENHA',
        'CARTAO',
        'DATA_NASCIMENTO',
        'TELEFONE',
        'FOTO',
    ];

    /**
     * Busca um usuário pelo e-mail para o sistema de login.
     */
    public function buscarPorEmail(string $email)
    {
        return $this->where('EMAIL', $email)->first();
    }
}