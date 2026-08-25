<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ----------------------------------------------------
// PÁGINAS PÚBLICAS / INSTITUCIONAIS
// ----------------------------------------------------
$routes->get('/',              'InicioController::index');
$routes->get('/sobre',          'SobreController::index');

// Catálogo de Sensores (público — apenas leitura)
$routes->get('/sensor',        'SensorController::index');
$routes->get('/sensor/(:num)', 'SensorController::detalhe/$1');

// IdentificadorController (público)
$routes->get('/identificador',               'IdentificadorController::index');
$routes->post('/identificador/buscar-sensor', 'IdentificadorController::buscarSensor');

// ----------------------------------------------------
// AUTENTICAÇÃO (LOGIN / LOGOUT / CADASTRO)
// ----------------------------------------------------
$routes->get('/login',        'AuthController::index');
$routes->post('/login',       'AuthController::loginUsuario');
$routes->get('/login/admin',  'AuthController::loginAdminForm');
$routes->post('/login/admin', 'AuthController::loginAdmin');
$routes->get('/logout',       'AuthController::logout');

// Cadastros
$routes->get('/usuario/cadastro',  'UsuarioController::cadastroForm');
$routes->post('/usuario/cadastro', 'UsuarioController::cadastrar');

// Recuperação de senha — Usuário
$routes->get('/usuario/esqueceu_senha',   'RecuperacaoSenhaController::form');
$routes->post('/usuario/esqueceu_senha',  'RecuperacaoSenhaController::solicitar');
$routes->get('/usuario/redefinir_senha',  'RecuperacaoSenhaController::redefinirForm');
$routes->post('/usuario/redefinir_senha', 'RecuperacaoSenhaController::redefinir');

// Recuperação de senha — Admin
$routes->get('/admin/esqueceu_senha', 'AuthController::esqueceuSenhaAdmForm');

// ----------------------------------------------------
// ENDPOINTS DE API (RETORNO JSON)
// ----------------------------------------------------
$routes->group('api', ['namespace' => 'App\Controllers\api_controller'], function ($routes) {
    // API de Usuários
    $routes->get('usuarios', 'APIUsuarioController::index');
    $routes->get('usuarios/(:any)', 'APIUsuarioController::show/$1');

    // API de Sensores
    $routes->get('sensores', 'APISensorController::index');
    $routes->get('sensores/(:num)', 'APISensorController::show/$1');
    $routes->post('sensores', 'APISensorController::create');
    $routes->put('sensores/(:num)', 'APISensorController::update/$1');
    $routes->delete('sensores/(:num)', 'APISensorController::delete/$1');

    // API de Quiz (Perguntas e Respostas)
    $routes->get('quiz/perguntas', 'APIQuizController::perguntas');
    $routes->get('quiz/perguntas/(:num)', 'APIQuizController::showPergunta/$1');
    $routes->post('quiz/responder', 'APIQuizController::responder');
});

// ----------------------------------------------------
// ÁREA RESTRITA: USUÁRIO LOGADO
// Filtro 'userAuth' protege todas as rotas deste grupo
// ----------------------------------------------------
$routes->group('', ['filter' => 'userAuth'], function ($routes) {

    // Área inicial do usuário
    $routes->get('inicio', 'UsuarioController::inicio');

    // Perfil
    $routes->get('perfil',  'UsuarioController::perfil');
    $routes->post('perfil', 'UsuarioController::atualizarPerfil');

    // Histórico de respostas
    $routes->get('historico',                'RespostaController::historico');
    $routes->post('resposta/excluir/(:num)', 'RespostaController::excluir/$1');

    // Quiz Web
    $routes->get('quiz',            'QuizController::index');
    $routes->get('quiz/pergunta',   'QuizController::pergunta');
    $routes->post('quiz/responder', 'QuizController::responder');
    $routes->post('quiz/avancar',   'QuizController::avancar');
    $routes->get('quiz/resultado',  'QuizController::resultado');
});

// ----------------------------------------------------
// ÁREA RESTRITA: PAINEL ADMINISTRATIVO
// Filtro 'adminAuth' garante acesso apenas a administradores
// ----------------------------------------------------
$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) { 

    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('perfil',    'AdminController::perfil');
    $routes->post('perfil',   'AdminController::atualizarPerfilAdmin');

    // Gestão de Usuários
    $routes->get('usuarios',                  'AdminController::usuarios');
    $routes->post('usuario/atualizar/(:any)', 'AdminController::atualizarUsuario/$1');
    $routes->post('usuario/excluir/(:any)',   'AdminController::excluirUsuario/$1');

    // Gestão de Sensores
    $routes->get('sensores',                  'AdminController::sensores');
    $routes->get('sensor/novo',               'AdminController::novoSensorForm');
    $routes->post('sensor/inserir',           'AdminController::inserirSensor');
    $routes->get('sensor/editar/(:num)',      'AdminController::editarSensorForm/$1');
    $routes->post('sensor/atualizar/(:num)',  'AdminController::atualizarSensor/$1');
    $routes->post('sensor/excluir/(:num)',    'AdminController::excluirSensor/$1');

    // Gestão de Perguntas e Alternativas
    $routes->get('perguntas',                  'AdminController::perguntas');
    $routes->get('pergunta/nova',              'AdminController::novaPerguntaForm');
    $routes->post('pergunta/inserir',          'AdminController::inserirPergunta');
    $routes->get('pergunta/editar/(:num)',     'AdminController::editarPerguntaForm/$1');
    $routes->post('pergunta/atualizar/(:num)', 'AdminController::atualizarPergunta/$1');
    $routes->post('pergunta/excluir/(:num)',   'AdminController::excluirPergunta/$1');
});
    // ----------------------------------------------------
    // ROTAS OPTIONS — CORS / PREFLIGHT
    // ----------------------------------------------------
    $routes->options('usuarios', static function () {
        return response()->setStatusCode(204);
    });

    $routes->options('usuarios/(:any)', static function () {
        return response()->setStatusCode(204);
    });

    $routes->options('sensores', static function () {
        return response()->setStatusCode(204);
    });

    $routes->options('sensores/(:num)', static function () {
        return response()->setStatusCode(204);
    });

    $routes->options('quiz/perguntas', static function () {
        return response()->setStatusCode(204);
    });

    $routes->options('quiz/perguntas/(:num)', static function () {
        return response()->setStatusCode(204);
    });

    $routes->options('quiz/responder', static function () {
        return response()->setStatusCode(204);
    });

    $routes->post(
    'api/cartao/verificar',
    'App\Controllers\api_controller\APICartaoController::verificar'
);

$routes->post('/login/cartao', 'UsuarioController::loginPorCartao');