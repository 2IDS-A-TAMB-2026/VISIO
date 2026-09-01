<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Cross-Origin Resource Sharing (CORS) Configuration
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
 */
class Cors extends BaseConfig
{
    /**
     * The default CORS configuration.
     *
     * @var array{
     *      allowedOrigins: list<string>,
     *      allowedOriginsPatterns: list<string>,
     *      supportsCredentials: bool,
     *      allowedHeaders: list<string>,
     *      exposedHeaders: list<string>,
     *      allowedMethods: list<string>,
     *      maxAge: int,
     *  }
     */
    public array $default = [
        /**
         * Origins for the `Access-Control-Allow-Origin` header.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
         *
         * E.g.:
         *   - ['http://localhost:8080']
         *   - ['https://www.example.com']
         */
        // Origem real do Flutter Web, confirmada pelo comando usado para
        // rodar o app: `flutter run -d web-server --web-hostname 192.168.0.6
        // --web-port 5000`. Mesmo host do backend, porta diferente — ainda
        // conta como cross-origin para o navegador (origem = esquema+host+porta).
        'allowedOrigins' => ['http://desktop-un212s1/VISIO_Codeigniter/public/'],

        /**
         * Origin regex patterns for the `Access-Control-Allow-Origin` header.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
         *
         * NOTE: A pattern specified here is part of a regular expression. It will
         *       be actually `#\A<pattern>\z#`.
         *
         * E.g.:
         *   - ['https://\w+\.example\.com']
         */
        'allowedOriginsPatterns' => [],

        /**
         * Weather to send the `Access-Control-Allow-Credentials` header.
         *
         * The Access-Control-Allow-Credentials response header tells browsers whether
         * the server allows cross-origin HTTP requests to include credentials.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Credentials
         */
        // Obrigatório: o Flutter Web usa withCredentials=true (BrowserClient,
        // ver http_client_provider_web.dart) pra receber/reenviar o cookie de
        // sessão PHPSESSID. Com false aqui, o navegador descarta o cookie de
        // qualquer resposta cross-origin mesmo com allowedOrigins correto.
        'supportsCredentials' => true,

        /**
         * Set headers to allow.
         *
         * The Access-Control-Allow-Headers response header is used in response to
         * a preflight request which includes the Access-Control-Request-Headers to
         * indicate which HTTP headers can be used during the actual request.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Headers
         */
        // Únicos headers customizados que o app manda hoje (ver api_client.dart
        // e auth_service.dart: Accept sempre; Content-Type em postJson/postForm
        // e nos dois logins). Se o app passar a mandar mais headers custom no
        // futuro, precisam ser adicionados aqui também.
        'allowedHeaders' => ['Content-Type', 'Accept'],

        /**
         * Set headers to expose.
         *
         * The Access-Control-Expose-Headers response header allows a server to
         * indicate which response headers should be made available to scripts running
         * in the browser, in response to a cross-origin request.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Expose-Headers
         */
        'exposedHeaders' => [],

        /**
         * Set methods to allow.
         *
         * The Access-Control-Allow-Methods response header specifies one or more
         * methods allowed when accessing a resource in response to a preflight
         * request.
         *
         * E.g.:
         *   - ['GET', 'POST', 'PUT', 'DELETE']
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Methods
         */
        // ApiClient só implementa GET e POST (get/postJson/postForm/postMultipart);
        // OPTIONS é sempre necessário para o preflight. Sem PUT/DELETE porque
        // nada no app os usa hoje — adicionar aqui se algo passar a precisar.
        'allowedMethods' => ['GET', 'POST', 'OPTIONS'],

        /**
         * Set how many seconds the results of a preflight request can be cached.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Max-Age
         */
        'maxAge' => 7200,
    ];
}
