<?php

namespace App\Controllers;

use App\Models\SensorModel;

class IdentificadorController extends BaseController
{
    public function index()
    {
        return view('sistema/usuario/identificador/index');
    }

    public function buscarSensor()
    {
        $nome = $this->request->getPost('nome');
        $model = new SensorModel();

        $sensor = $model->buscarPorNome($nome);

        if (!$sensor) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sensor não encontrado no banco de dados.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'sensor' => $sensor
        ]);
    }
}