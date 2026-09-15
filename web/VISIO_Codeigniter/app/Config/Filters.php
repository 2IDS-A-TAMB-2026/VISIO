<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use App\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AdminAuth;
use App\Filters\UserAuth;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'         => CSRF::class,
        'adminAuth'    => AdminAuth::class,
        'userAuth'     => UserAuth::class,  
        'toolbar'      => DebugToolbar::class,
        'honeypot'     => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders'=> SecureHeaders::class,
        'cors'         => Cors::class, // App\Filters\Cors (customizado) — não o nativo do CI4. Trata o preflight OPTIONS diretamente, antes de userAuth/adminAuth, que sempre redirecionam sem sessão (e um preflight nunca tem sessão).
        'forcehttps'   => ForceHTTPS::class,
        'pagecache'    => PageCache::class,
        'performance'  => PerformanceMetrics::class,
    ];

    public array $required = [
        'before' => [
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [
            'cors', // Necessário para o app Flutter Web (requisições cross-origin com credentials)
            // 'csrf', // Ativar em produção
        ],
        'after' => [
            'cors',
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}
