<?php

namespace App\Controllers;

use App\Models\SensorModel;

/*
 * Sensor
 * Catálogo público de sensores (somente leitura).
 *
 * Rotas esperadas:
 *   GET /sensor          → listagem de todos os sensores
 *   GET /sensor/(:id)    → detalhe de um sensor específico
 */
class SensorController extends BaseController
{
    /**
     * Lista todos os sensores disponíveis no catálogo.
     */
    public function index(): string
    {
        $model = new SensorModel();
        $dados['sensores'] = $model->listar();

        return view('sensor/index', $dados);
    }

    /**
     * Exibe o detalhe de um sensor específico.
     */
    public function detalhe(int $id): string
    {
        $model  = new SensorModel();
        $sensor = $model->buscarPorId($id);

        if (!$sensor) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('sensor/detalhe', ['sensor' => $sensor]);
    }
}
