<?php
namespace App\Models;

use CodeIgniter\Model;
/**
 * UsuarioModel
 * Responsável por todas as operações da tabela USUARIO.
 * Chave primária: CPF (string, sem auto-increment).
 */
class UsuarioModel extends Model
{
    protected $table = 'USUARIO';
    protected $primaryKey = 'CPF';
    protected $useAutoIncrement = false;

    protected $allowedFields = [
        'CPF',
        'NOME',
        'EMAIL',
        'SENHA',
        'CARTAO',
        'DATA_NASCIMENTO',
        'TELEFONE',
    ];
}
