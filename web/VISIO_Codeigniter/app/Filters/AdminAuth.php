<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
       if (!session()->get('admin_logado')) {
            $querJson = $request->isAJAX()
                || str_contains($request->getHeaderLine('Accept'), 'json')
                || str_contains($request->getHeaderLine('Content-Type'), 'json');

            if ($querJson) {
                return service('response')->setStatusCode(401)->setJSON([
                    'message' => 'Acesso restrito! Faça login como administrador.',
                ]);
            }

            return redirect()->to('/login/admin')
                ->with('erro', 'Acesso restrito! Por favor, faça login.');
        } 
           return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}