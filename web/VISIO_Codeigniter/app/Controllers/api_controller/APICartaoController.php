<?php

namespace App\Controllers\api_controller;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsuarioModel;

class APICartaoController extends ResourceController
{
    protected $modelName = 'App\Models\UsuarioModel';
    protected $format = 'json';

    /**
     * Verifica se um cartão RFID está cadastrado.
     *
     * POST /api/cartao/verificar
     *
     * JSON recebido:
     * {
     *     "uid": "A37F219C"
     * }
     */
    public function verificar()
    {
        // Recebe o JSON enviado pelo ESP32
        $dados = $this->request->getJSON(true);

        // Verifica se o UID foi enviado
        if (!isset($dados['uid']) || empty($dados['uid'])) {
            return $this->fail([
                'sucesso' => false,
                'autorizado' => false,
                'mensagem' => 'UID do cartão não informado.'
            ], 400);
        }

        // Remove espaços extras
        $uid = trim($dados['uid']);

        // Procura o cartão no banco
        $model = new UsuarioModel();

        $usuario = $model
            ->where('CARTAO', $uid)
            ->first();

        // Cartão não encontrado
        if (!$usuario) {
            return $this->respond([
                'sucesso' => true,
                'autorizado' => false,
                'uid' => $uid,
                'mensagem' => 'Cartão não cadastrado.'
            ], 200);
        }

        // Remove informações que não precisam ser enviadas para o ESP32
        unset($usuario['SENHA']);

        // Cartão encontrado
        return $this->respond([
            'sucesso' => true,
            'autorizado' => true,
            'uid' => $uid,
            'usuario' => [
                'cpf' => $usuario['CPF'],
                'nome' => $usuario['NOME'],
                'email' => $usuario['EMAIL'],
                'cartao' => $usuario['CARTAO']
            ],
            'mensagem' => 'Acesso autorizado.'
        ], 200);
    }
}