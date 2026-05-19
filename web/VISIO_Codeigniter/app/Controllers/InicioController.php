<?php

namespace App\Controllers;

class InicioController extends BaseController
{
    public function index(): string
    {
        return view('sistema/usuario/inicio/index');
    }
}