<?php

namespace App\Controllers;

class SobreController extends BaseController
{
    public function index(): string
    {
        return view('sistema/usuario/sobre/index');
    }
}
