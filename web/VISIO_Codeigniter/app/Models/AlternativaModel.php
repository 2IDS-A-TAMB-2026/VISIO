<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * AlternativaModel
 * Responsável por todas as operações da tabela ALTERNATIVA.
 * Toda alternativa pertence a uma PERGUNTA via FK_ID_PERGUNTA.
 */
class AlternativaModel extends Model
{
    protected $table = 'ALTERNATIVA';
    protected $primaryKey = 'ID_ALTERNATIVA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'DESCRICAO',
        'IS_CORRETA', // Substituiu o CORRETA_ERRADA
        'FK_ID_PERGUNTA',
    ];
}