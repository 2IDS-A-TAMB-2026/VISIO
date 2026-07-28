<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * SensorModel
 * Responsável por todas as operações da tabela SENSOR.
 * O SQL corrigido possui: ID_SENSOR, NOME, FOTO, DESCRICAO, CIRCUITO
 */
class SensorModel extends Model
{
    protected $table = 'SENSOR';
    protected $primaryKey = 'ID_SENSOR';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'NOME',
        'FOTO',
        'DESCRICAO',
        'CIRCUITO',
    ];

    public function listar(): array
    {
        return $this->findAll();
    }

    public function buscarPorId(int $id): array|null
    {
        return $this->find($id);
    }

    public function buscarPorNome(string $nome): array|null
    {
        return $this->where('LOWER(NOME)', strtolower(trim($nome)))->first();
    }
}