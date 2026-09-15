<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * UserAuth
 * Filtro de autenticação para as rotas da área do usuário logado.
 * Impede acesso de visitantes não autenticados às rotas protegidas.
 */
class UserAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('usuario_logado')) {
            return redirect()->to('/login')
                ->with('erro', 'Acesso restrito. Por favor, faça login para continuar.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
