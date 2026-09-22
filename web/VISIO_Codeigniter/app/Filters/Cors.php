<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Cors
 *
 * Permite que o Flutter Web (rodando em uma origem diferente da API — ex.:
 * http://10.141.129.9:PORTA durante `flutter run -d chrome`, ou onde o build
 * web final for hospedado) consulte este backend mantendo a sessão PHP
 * (cookie), que é como AuthController/QuizController/etc. autenticam hoje.
 *
 * IMPORTANTE: como a autenticação é por cookie de sessão (não token), o
 * CORS precisa de 'Access-Control-Allow-Credentials: true' + uma origem
 * EXATA em 'Access-Control-Allow-Origin' — o navegador recusa a combinação
 * de credentials com Access-Control-Allow-Origin: '*'. É por isso que este
 * filtro reflete a origem da requisição em vez de usar um curinga.
 *
 * Este filtro sozinho resolve o CORS. Ele NÃO resolve os dois problemas
 * abaixo, que são do lado do app Flutter (ver services/api_client.dart e
 * services/auth_service.dart):
 *   - o navegador nunca expõe o header Set-Cookie da resposta para o
 *     JavaScript (nem lê, nem grava um header Cookie manual);
 *   - por isso, o Flutter Web precisa deixar o PRÓPRIO NAVEGADOR guardar e
 *     reenviar o cookie de sessão sozinho (credentials automáticas), em vez
 *     de tentar capturar/reenviar o cookie manualmente como hoje.
 *
 * AMBIENTE DE DESENVOLVIMENTO: como descrito no prompt original, este
 * projeto roda em rede local fechada, então o filtro abaixo reflete
 * qualquer Origin recebida. Antes de expor a API além da rede local,
 * troque $origensPermitidas por uma lista fixa dos domínios reais do
 * Flutter Web em produção — ex.:
 *   private ?array $origensPermitidas = ['http://10.141.129.9:8080'];
 */
class Cors implements FilterInterface
{
    /** Deixe null para refletir qualquer Origin (uso local/dev). */
    private ?array $origensPermitidas = null;

    public function before(RequestInterface $request, $arguments = null)
    {
        $origem = $request->getHeaderLine('Origin');

    
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            $response = service('response');
            $this->aplicarHeaders($response, $origem);

            return $response->setStatusCode(204);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $origem = $request->getHeaderLine('Origin');
        $this->aplicarHeaders($response, $origem);
    }

    private function aplicarHeaders(ResponseInterface $response, string $origem): void
    {
        if ($origem === '') {
            return;
        }

        if ($this->origensPermitidas !== null && !in_array($origem, $this->origensPermitidas, true)) {
            return;
        }

        $response->setHeader('Access-Control-Allow-Origin', $origem);
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');
      
        $response->setHeader('Vary', 'Origin');
    }
}
