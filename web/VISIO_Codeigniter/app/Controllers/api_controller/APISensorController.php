<?php

namespace App\Controllers\api_controller;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SensorModel;

class APISensorController extends ResourceController
{
    protected $modelName = SensorModel::class;
    protected $format    = 'json';

    // GET /api/sensores
    public function index()
    {
        $sensores = $this->model->findAll();

      
        foreach ($sensores as &$s) {
            $s['ID_SENSOR'] = (int) $s['ID_SENSOR'];
        }
        unset($s);

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'mensagem' => 'Lista de sensores recuperada com sucesso.',
            'total'    => count($sensores),
            'dados'    => $sensores
        ], 200);
    }

    // GET /api/sensores/(:num)
    public function show($id = null)
    {
        $sensor = $this->model->find($id);

        if (!$sensor) {
            return $this->failNotFound("Sensor com ID {$id} não foi encontrado.");
        }

        $sensor['ID_SENSOR'] = (int) $sensor['ID_SENSOR'];

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'mensagem' => 'Sensor localizado com sucesso.',
            'dados'    => $sensor
        ], 200);
    }

    // POST /api/sensores
    public function create()
    {
        $json = $this->request->getJSON(true);

        $dados = [
            'NOME'      => $json['NOME'] ?? $this->request->getVar('NOME'),
            'DESCRICAO' => $json['DESCRICAO'] ?? $this->request->getVar('DESCRICAO'),
            'CIRCUITO'  => $json['CIRCUITO'] ?? $this->request->getVar('CIRCUITO') ?? '',
            'FOTO'      => $json['FOTO'] ?? $this->request->getVar('FOTO') ?? ''
        ];

        $regras = [
            'NOME'      => 'required|min_length[2]|max_length[100]',
            'DESCRICAO' => 'required|max_length[255]',
            'CIRCUITO'  => 'permit_empty|max_length[255]',
            'FOTO'      => 'permit_empty|max_length[255]'
        ];

        if (!$this->validateData($dados, $regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $idInserido = $this->model->insert($dados);

        if ($idInserido === false) {
            return $this->failServerError('Ocorreu um erro ao cadastrar o sensor.');
        }

        $sensorCriado = $this->model->find($idInserido);

        return $this->respondCreated([
            'status'   => 201,
            'erro'     => false,
            'mensagem' => 'Sensor cadastrado com sucesso!',
            'dados'    => $sensorCriado
        ]);
    }

    // PUT /api/sensores/(:num)
    public function update($id = null)
    {
        $sensor = $this->model->find($id);

        if (!$sensor) {
            return $this->failNotFound("Sensor com ID {$id} não encontrado para atualização.");
        }

        $dadosEntrada = $this->request->getJSON(true) ?? $this->request->getRawInput();

        $dadosAtualizacao = [
            'NOME'      => $dadosEntrada['NOME'] ?? $sensor['NOME'],
            'DESCRICAO' => $dadosEntrada['DESCRICAO'] ?? $sensor['DESCRICAO'],
            'CIRCUITO'  => $dadosEntrada['CIRCUITO'] ?? $sensor['CIRCUITO'],
            'FOTO'      => $dadosEntrada['FOTO'] ?? $sensor['FOTO'],
        ];

        if (!$this->model->update($id, $dadosAtualizacao)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'mensagem' => 'Sensor atualizado com sucesso!',
            'dados'    => $this->model->find($id)
        ], 200);
    }

    // DELETE /api/sensores/(:num)
    public function delete($id = null)
    {
        $sensor = $this->model->find($id);

        if (!$sensor) {
            return $this->failNotFound("Sensor com ID {$id} não foi encontrado.");
        }

        if ($this->model->delete($id)) {
            return $this->respondDeleted([
                'status'   => 200,
                'erro'     => false,
                'mensagem' => "Sensor ID {$id} excluído com sucesso."
            ]);
        }

        return $this->failServerError("Não foi possível excluir o sensor ID {$id}.");
    }
}