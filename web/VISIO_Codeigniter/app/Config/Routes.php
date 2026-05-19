<?php

use CodeIgniter\Router\RouteCollection;

/**
 * =====================================================================
 * VISIO — Rotas CodeIgniter 4
 * =====================================================================
 *
 * @var RouteCollection $routes
 */

// -----------------------------------------------------------------
// HOME / PÁGINA INICIAL
// -----------------------------------------------------------------
$routes->get('/', 'InicioController::index');   // catálogo de sensores como página inicial

// -----------------------------------------------------------------
// AUTENTICAÇÃO
// -----------------------------------------------------------------
$routes->get('/login', 'Auth::index');           // form login usuário
$routes->post('/login', 'Auth::loginUsuario');    // processa login usuário

$routes->get('/login/admin', 'Auth::loginAdminForm');  // form login admin
$routes->post('/login/admin', 'Auth::loginAdmin');      // processa login admin

$routes->get('/logout', 'Auth::logout');          // encerra sessão

// -----------------------------------------------------------------
// CADASTRO DE USUÁRIO (público)
// -----------------------------------------------------------------
$routes->get('/usuario/cadastro', 'Usuario::cadastroForm');  // formulário
$routes->post('/usuario/cadastro', 'Usuario::cadastrar');     // processa

// -----------------------------------------------------------------
// ÁREA DO USUÁRIO LOGADO
// -----------------------------------------------------------------
$routes->get('/usuario/perfil', 'Usuario::perfil');         // exibe perfil
$routes->post('/usuario/perfil', 'Usuario::atualizarPerfil'); // salva perfil

$routes->get('/usuario/historico', 'Usuario::historico');    // histórico de respostas

// -----------------------------------------------------------------
// CATÁLOGO DE SENSORES (público)
// -----------------------------------------------------------------
$routes->get('/sensor', 'SensorController::index');        // listagem
$routes->get('/sensor/(:num)', 'SensorController::detalhe/$1');   // detalhe por ID

// -----------------------------------------------------------------
// QUIZ (usuário logado)
// -----------------------------------------------------------------
$routes->get('/quiz', 'Quiz::index');          // inicia quiz
$routes->get('/quiz/pergunta', 'Quiz::pergunta');       // exibe pergunta atual
$routes->post('/quiz/responder', 'Quiz::responder');      // registra resposta
$routes->get('/quiz/resultado', 'Quiz::resultado');      // exibe resultado final

// -----------------------------------------------------------------
// HISTÓRICO DE RESPOSTAS (usuário logado)
// -----------------------------------------------------------------
$routes->get('/resposta/historico', 'Resposta::historico');     // histórico
$routes->get('/resposta/excluir/(:num)', 'Resposta::excluir/$1');    // remove registro

// -----------------------------------------------------------------
// PAINEL ADMINISTRATIVO
// -----------------------------------------------------------------

// Dashboard
$routes->get('/admin/dashboard', 'Admin::dashboard');

// Gestão de usuários
$routes->get('/admin/usuarios', 'Admin::usuarios');
$routes->get('/admin/usuario/excluir/(:any)', 'Admin::excluirUsuario/$1');

// Gestão de sensores
$routes->get('/admin/sensores', 'Admin::sensores');
$routes->get('/admin/sensor/novo', 'Admin::novoSensorForm');
$routes->post('/admin/sensor/inserir', 'Admin::inserirSensor');
$routes->get('/admin/sensor/editar/(:num)', 'Admin::editarSensorForm/$1');
$routes->post('/admin/sensor/atualizar/(:num)', 'Admin::atualizarSensor/$1');
$routes->get('/admin/sensor/excluir/(:num)', 'Admin::excluirSensor/$1');

// Gestão de perguntas e alternativas
$routes->get('/admin/perguntas', 'Admin::perguntas');
$routes->get('/admin/pergunta/nova', 'Admin::novaPerguntaForm');
$routes->post('/admin/pergunta/inserir', 'Admin::inserirPergunta');
$routes->get('/admin/pergunta/editar/(:num)', 'Admin::editarPerguntaForm/$1');
$routes->post('/admin/pergunta/atualizar/(:num)', 'Admin::atualizarPergunta/$1');
$routes->get('/admin/pergunta/excluir/(:num)', 'Admin::excluirPergunta/$1');

// Histórico geral de respostas
$routes->get('/admin/respostas', 'Admin::respostas');
