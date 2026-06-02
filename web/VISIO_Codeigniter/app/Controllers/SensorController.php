<?php

namespace App\Controllers;

use App\Models\SensorModel;

/**
 * Sensor
 * Catálogo público de sensores (somente leitura).
 */
class SensorController extends BaseController
{
    public function index(): string
    {
        return view('sistema/usuario/sensores/index', [
            'sensores' => (new SensorModel())->listar(),
        ]);
    }

    public function detalhe(int $id): string
    {
        $sensor = (new SensorModel())->buscarPorId($id);

        if (!$sensor) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('sistema/usuario/sensores/detalhe', ['sensor' => $sensor]);
    }
}
