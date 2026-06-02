<?php

/**
 * CodeIgniter 4.5+ — Front Controller
 *
 * CORREÇÃO: a versão 4.5 do CI4 removeu o arquivo system/bootstrap.php.
 * O novo formato usa CodeIgniter\Boot::bootWeb() em vez do bootstrap antigo.
 *
 * Se você estava usando o index.php antigo, este é o erro que aparecia:
 *   "This system/bootstrap.php is no longer used..."
 */

use CodeIgniter\Boot;

// Caminho absoluto desta pasta (public/)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Carrega o autoloader do Composer (vendor/autoload.php)
// O vendor/ fica um nível acima de public/ (na raiz do projeto)
require FCPATH . '../vendor/autoload.php';

// Carrega o arquivo de paths da aplicação
require FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

// CI4 4.5+: inicializa a aplicação web
Boot::bootWeb($paths);
