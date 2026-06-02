<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * AdminModel
 * Responsável por todas as operações da tabela ADMIN no MySQL.
 * Chave primária: CNPJ (string, sem auto-incremento).
 */
class AdminModel extends Model
{
    protected $table = 'ADMIN';
    protected $primaryKey = 'CNPJ';
    protected $useAutoIncrement = false;
    protected $returnType = 'array'; // Garante o retorno em formato de array para o AuthController

    /**
     * Campos permitidos para INSERT/UPDATE
     */
    protected $allowedFields = [
        'CNPJ',
        'NOME', // O admin continua com nome
        'EMAIL',
        'TELEFONE',
        'SENHA',
    ];

    /**
     * Busca um administrador pelo e-mail para o sistema de login.
     */
    public function buscarPorEmail(string $email)
    {
        return $this->where('EMAIL', $email)->first();
    }
}