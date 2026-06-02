<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class UsuariosController extends ResourceController
{
    protected $modelName = 'App\\Models\\UsuariosModel';
    protected $format = 'json';

    public function index()
    {
        $usuarios = array_map(
            fn($usuario) => $this->removerSenha($usuario),
            $this->model->findAll()
        );

        return $this->respond($usuarios);
    }

    public function show($id = null)
    {
        $usuario = $this->model->find($id);

        if ($usuario === null) {
            return $this->failNotFound('Usuário não encontrado');
        }

        return $this->respond($this->removerSenha($usuario));
    }

    private function removerSenha(array $usuario): array
    {
        unset($usuario['senha']);

        return $usuario;
    }
}