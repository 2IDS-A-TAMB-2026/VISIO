<?php
namespace App\Models;

use CodeIgniter\Model;
/*
 * SensorModel
 * Responsável por todas as operações da tabela SENSOR.
 */
class SensorModel extends Model
{
    protected $table = 'SENSOR';
    protected $primaryKey = 'ID_SENSOR';

    protected $allowedFields = [
        'FOTO',
        'NOME',
        'DESCRICAO',
    ];
    public function listar()
    {
        return $this->findAll();
    }

    public function buscarPorId($id)
    {
        return $this->find($id);
    }
}
