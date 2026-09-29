<?php

namespace App\Controllers\api_controller;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SensorModel;
use App\Models\UsuarioModel;
use App\Models\RespondeModel;

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
        $sensorModel   = new SensorModel();
        $usuarioModel  = new UsuarioModel();
        $respondeModel = new RespondeModel();

        $sensoresCadastrados = $sensorModel->countAllResults();
        $usuariosCadastrados = $usuarioModel->countAllResults();

        // ATUALIZADO — o terceiro card era "tipos de sensores" (não existe
        // coluna de "tipo" em SENSOR, então era só uma contagem de nomes
        // distintos usada como proxy). Trocado para a taxa média de
        // acertos do quiz, calculada em RespondeModel::taxaMediaAcertos().
        $taxaMediaAcertos = $respondeModel->taxaMediaAcertos();

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'mensagem' => 'Estatísticas recuperadas com sucesso.',
            'dados'    => [
                'SENSORES_CADASTRADOS' => $sensoresCadastrados,
                'TAXA_MEDIA_ACERTOS'   => $taxaMediaAcertos,
                'USUARIOS_CADASTRADOS' => $usuariosCadastrados,
            ],
        ], 200);
    }
}
