<?php
namespace App\Models;

use CodeIgniter\Model;
/**
 * RespondeModel
 * Responsável por todas as operações da tabela RESPONDE.
 * Registra o histórico de respostas dos usuários no quiz.
 *
 * Relacionamentos:
 *   RESPONDE.FK_CPF_USUARIO  → USUARIO.CPF
 *   RESPONDE.FK_ID_ALTERNATIVA → ALTERNATIVA.ID_ALTERNATIVA
 */
class RespondeModel extends Model
{
    protected $table = 'RESPONDE';
    protected $primaryKey = 'ID_RESPONDE';

    protected $allowedFields = [
        'FK_CPF_USUARIO',
        'FK_ID_ALTERNATIVA',];
}
