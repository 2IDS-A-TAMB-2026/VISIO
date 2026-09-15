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
            // CORRIGIDO: este filtro agora também protege rotas de API
            // (api/sensores POST/PUT/DELETE — ver Routes.php). Um redirect
            // HTML (302 para /login/admin) não faz sentido para uma
            // chamada JSON: o cliente receberia a página de login como
            // corpo da resposta em vez de um erro. Mesmo padrão querJson()
            // já usado em AuthController/UsuarioController/QuizController/
            // SensorController.
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
        // Não precisa fazer nada aqui
    }
}