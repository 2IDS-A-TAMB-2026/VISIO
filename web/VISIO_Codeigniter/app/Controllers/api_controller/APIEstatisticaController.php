<?php

namespace App\Controllers\api_controller;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SensorModel;
use App\Models\UsuarioModel;

/**
 * APIEstatisticaController
 * Fornece números reais (contados no banco) para os cards de estatística
 * da tela inicial do app Mobile — substitui os valores fixos que existiam
 * antes ("128+", "92%", "9").
 */
class APIEstatisticaController extends ResourceController
{
    protected $format = 'json';

    // GET /api/estatisticas
    public function index()
    {
        $sensorModel  = new SensorModel();
        $usuarioModel = new UsuarioModel();

        $sensoresCadastrados = $sensorModel->countAllResults();

        // IMPORTANTE: esta contagem precisa vir DEPOIS da anterior — select()
        // e distinct() alteram o builder interno do model, então se a ordem
        // for invertida a contagem de $sensoresCadastrados sairia errada.
        //
        // Não existe coluna de "tipo" em SENSOR — cada NOME já é único na
        // prática (não há dois sensores com o mesmo nome cadastrados), então
        // contamos nomes distintos como proxy para "tipos de sensores".
        $tiposDeSensores = $sensorModel
            ->select('NOME')
            ->distinct()
            ->countAllResults();

        $usuariosCadastrados = $usuarioModel->countAllResults();

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'mensagem' => 'Estatísticas recuperadas com sucesso.',
            'dados'    => [
                'SENSORES_CADASTRADOS' => $sensoresCadastrados,
                'TIPOS_DE_SENSORES'    => $tiposDeSensores,
                'USUARIOS_CADASTRADOS' => $usuariosCadastrados,
            ],
        ], 200);
    }
}
