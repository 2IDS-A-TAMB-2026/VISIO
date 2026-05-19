<?php
namespace App\Models;

use CodeIgniter\Model;
/**
 * PerguntaModel
 * Responsável por todas as operações da tabela PERGUNTA.
 * Inclui joins com ALTERNATIVA quando necessário.
 */
class PerguntaModel extends Model
{
    protected $table = 'PERGUNTA';
    protected $primaryKey = 'ID_PERGUNTA';
    protected $allowedFields = [
        'DESCRICAO',
        'NIVEL_DIFICULDADE',
    ];
}
