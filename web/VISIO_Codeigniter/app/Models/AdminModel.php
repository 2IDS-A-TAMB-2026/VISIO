<?php
namespace App\Models;

use CodeIgniter\Model;

/**
 * AdminModel
 * Responsável por todas as operações da tabela ADMIN.
 * Chave primária: CNPJ (string, sem auto-increment).
 */
class AdminModel extends Model
{
    protected $table = 'ADMIN'; // nome da tabela
    protected $primaryKey = 'CNPJ'; // chave primária
    protected $useAutoIncrement = false;

    /**
     * Campos permitidos para INSERT/UPDATE
     * Segurança contra inserção indevida
     */
    protected $allowedFields = [
        // colunas na tabela ALUNOS do banco
        'CNPJ',
        'NOME',
        'EMAIL',
        'TELEFONE',
        'SENHA',
    ];
}

