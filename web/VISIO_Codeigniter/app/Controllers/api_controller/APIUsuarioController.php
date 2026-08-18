<?php

namespace App\Controllers\api_controller;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsuarioModel;

class APIUsuarioController extends ResourceController
{
    protected $modelName = 'App\Models\UsuarioModel';
    protected $format    = 'json';

    /**
     * Retorna a lista de todos os usuários
     * GET /api/usuarios
     */
    public function index()
    {
        $model = new UsuarioModel();
        $usuarios = $model->findAll();

        // Oculta o hash de senha do retorno JSON por segurança
        foreach ($usuarios as &$usuario) {
            unset($usuario['SENHA']);
        }

        return $this->respond($usuarios, 200);
    }

    /**
     * Retorna os dados de um usuário específico pelo CPF
     * GET /api/usuarios/(:any)
     */
    public function show($id = null)
    {
        $model = new UsuarioModel();
        $usuario = $model->find($id);

        if (!$usuario) {
            return $this->failNotFound('Usuário não encontrado.');
        }

        // Oculta o hash de senha por segurança
        unset($usuario['SENHA']);

        return $this->respond($usuario, 200);
    }
}