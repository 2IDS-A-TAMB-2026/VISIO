<?php

use CodeIgniter\Router\RouteCollection;

/**
 * =====================================================================
 * VISIO — Rotas CodeIgniter 4 (Integradas com Banco de Dados)
 * =====================================================================
 *
 * @var RouteCollection $routes
 */

// ----------------------------------------------------
// PÁGINAS PÚBLICAS / INSTITUCIONAIS
// ----------------------------------------------------
$routes->get('/', 'InicioController::index');       // Catálogo de sensores (Página Inicial)
$routes->get('/sobre', 'SobreController::index');   // Quem somos, informações do projeto
$routes->get('/identificador', 'IdentificadorController::index');

// Catálogo de Sensores (Aberto ao público ler)
$routes->get('/sensor', 'SensorController::index');
$routes->get('/sensor/(:num)', 'SensorController::detalhe/$1');

// ----------------------------------------------------
// AUTENTICAÇÃO (LOGIN / LOGOUT / CADASTRO)
// ----------------------------------------------------
$routes->get('/login', 'AuthController::index');           // Form de login do usuário comum
$routes->post('/login', 'AuthController::loginUsuario');    // Processa login do usuário comum
$routes->get('/login/admin', 'AuthController::loginAdminForm'); // Form de login do Admin
$routes->post('/login/admin', 'AuthController::loginAdmin');    // Processa login do Admin
$routes->get('/logout', 'AuthController::logout');          // Encerra qualquer sessão ativa

$routes->get('/usuario/cadastro', 'UsuarioController::cadastroForm'); // Form de cadastro público
$routes->post('/usuario/cadastro', 'UsuarioController::cadastrar');    // Processa o cadastro (Salva no BD)

// ----------------------------------------------------
// ÁREA RESTRITA: USUÁRIO LOGADO
// Aplica o filtro 'auth' para impedir acessos de deslogados
// ----------------------------------------------------
$routes->group('usuario', ['filter' => 'auth'], function ($routes) {
    $routes->get('inicio', 'UsuarioController::inicio');
    $routes->get('perfil', 'UsuarioController::perfil');
    $routes->post('perfil', 'UsuarioController::atualizarPerfil');
    $routes->get('historico', 'UsuarioController::historico');
});

// Quiz e Respostas (Apenas usuários logados)
$routes->group('quiz', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'QuizController::index');
    $routes->get('pergunta', 'QuizController::pergunta');
    $routes->post('responder', 'QuizController::responder'); // Insere na tabela 'RESPONDE'
    $routes->get('resultado', 'QuizController::resultado');
});

$routes->group('resposta', ['filter' => 'auth'], function ($routes) {
    $routes->get('historico', 'RespostaController::historico');
    $routes->get('excluir/(:num)', 'RespostaController::excluir/$1');
});

// ----------------------------------------------------
// ÁREA RESTRITA: PAINEL ADMINISTRATIVO (ADMIN)
// Aplica o filtro 'adminAuth' para que apenas administradores entrem
// ----------------------------------------------------
$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {

    // Dashboard Geral
    $routes->get('dashboard', 'AdminController::dashboard');

    // Gestão de Usuários (Chave primária é o CPF, por isso (:any))
    $routes->get('usuarios', 'AdminController::usuarios');
    $routes->get('usuario/excluir/(:any)', 'AdminController::excluirUsuario/$1');

    // Gestão de Sensores (Inserção/Alteração no BD)
    $routes->get('sensores', 'AdminController::sensores');
    $routes->get('sensor/novo', 'AdminController::novoSensorForm');
    $routes->post('sensor/inserir', 'AdminController::inserirSensor');
    $routes->get('sensor/editar/(:num)', 'AdminController::editarSensorForm/$1');
    $routes->post('sensor/atualizar/(:num)', 'AdminController::atualizarSensor/$1');
    $routes->get('sensor/excluir/(:num)', 'AdminController::excluirSensor/$1');

    // Gestão de Perguntas e Alternativas do Quiz
    $routes->get('perguntas', 'AdminController::perguntas');
    $routes->get('pergunta/nova', 'AdminController::novaPerguntaForm');
    $routes->post('pergunta/inserir', 'AdminController::inserirPergunta');
    $routes->get('pergunta/editar/(:num)', 'AdminController::editarPerguntaForm/$1');
    $routes->post('pergunta/atualizar/(:num)', 'AdminController::atualizarPergunta/$1');
    $routes->get('pergunta/excluir/(:num)', 'AdminController::excluirPergunta/$1');

    // Relatórios / Histórico Geral de Respostas de todos os Alunos
    $routes->get('respostas', 'AdminController::respostas');
});