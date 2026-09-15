
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// PÁGINAS PÚBLICAS / INSTITUCIONAIS
// ============================================================

$routes->get('/', 'InicioController::index');
$routes->get('sobre', 'SobreController::index');

// Catálogo de Sensores
$routes->get('sensor', 'SensorController::index');
$routes->get('sensor/(:num)', 'SensorController::detalhe/$1');

// Identificador
$routes->get('identificador', 'IdentificadorController::index');
$routes->post(
    'identificador/buscar-sensor',
    'IdentificadorController::buscarSensor'
);


// ============================================================
// AUTENTICAÇÃO
// ============================================================

// Usuário
$routes->get('login', 'AuthController::index');
$routes->post('login', 'AuthController::loginUsuario');

// Login com Google (WEB) — a biblioteca "Sign In With Google" do
// Google envia o ID token diretamente para cá via POST (configurado
// como data-login_uri na view de login), documentação:
// https://developers.google.com/identity/gsi/web/guides/verify-google-id-token
$routes->post('login/google', 'AuthController::loginGoogle');

// Admin
$routes->get('login/admin', 'AuthController::loginAdminForm');
$routes->post('login/admin', 'AuthController::loginAdmin');

// Logout
$routes->get('logout', 'AuthController::logout');


// ============================================================
// CADASTRO
// ============================================================

$routes->get(
    'usuario/cadastro',
    'UsuarioController::cadastroForm'
);

$routes->post(
    'usuario/cadastro',
    'UsuarioController::cadastrar'
);


// ============================================================
// RECUPERAÇÃO DE SENHA — USUÁRIO
// ============================================================

$routes->get(
    'usuario/esqueceu_senha',
    'RecuperacaoSenhaController::form'
);

$routes->post(
    'usuario/esqueceu_senha',
    'RecuperacaoSenhaController::solicitar'
);

$routes->get(
    'usuario/redefinir_senha',
    'RecuperacaoSenhaController::redefinirForm'
);

$routes->post(
    'usuario/redefinir_senha',
    'RecuperacaoSenhaController::redefinir'
);


// ============================================================
// RECUPERAÇÃO DE SENHA — ADMIN
// ============================================================

$routes->get(
    'admin/esqueceu_senha',
    'AuthController::esqueceuSenhaAdmForm'
);


// ============================================================
// LOGIN POR CARTÃO
// ============================================================

$routes->post(
    'login/cartao',
    'UsuarioController::loginPorCartao'
);


// ============================================================
// API
// ============================================================

$routes->group(
    'api',
    [
        'filter' => 'cors',
        'namespace' => 'App\Controllers\api_controller'
    ],
    function ($routes) {

        // --------------------------------------------------------
        // AUTENTICAÇÃO API
        // --------------------------------------------------------

        $routes->post(
            'login',
            '\App\Controllers\AuthController::loginUsuario'
        );

        $routes->post(
            'login/admin',
            '\App\Controllers\AuthController::loginAdmin'
        );

        $routes->get(
            'logout',
            '\App\Controllers\AuthController::logout'
        );


        // --------------------------------------------------------
        // USUÁRIOS
        // --------------------------------------------------------

        // Apenas administrador
        $routes->get(
            'usuarios',
            'APIUsuarioController::index',
        );

        $routes->get(
            'usuarios/(:any)',
            'APIUsuarioController::show/$1',
        );

        $routes->post(
            'usuarios',
            '\App\Controllers\UsuarioController::cadastrar'
        );


        // --------------------------------------------------------
        // PERFIL DO USUÁRIO
        // --------------------------------------------------------

        $routes->get(
            'perfil',
            '\App\Controllers\UsuarioController::perfil'
        );

        $routes->post(
            'perfil',
            '\App\Controllers\UsuarioController::atualizarPerfil'
        );


        // --------------------------------------------------------
        // CARTÃO
        // --------------------------------------------------------

        $routes->post(
            'cartao/verificar',
            'APICartaoController::verificar'
        );


        // --------------------------------------------------------
        // SENSORES
        // --------------------------------------------------------

        // Público
        $routes->get(
            'sensores',
            'APISensorController::index'
        );

        $routes->get(
            'sensores/(:num)',
            'APISensorController::show/$1'
        );

        // Flutter usa sensor/{id}
        $routes->get(
            'sensor/(:num)',
            'APISensorController::show/$1'
        );

        // Apenas administrador
        $routes->post(
            'sensores',
            'APISensorController::create',
            ['filter' => 'adminAuth']
        );

        $routes->put(
            'sensores/(:num)',
            'APISensorController::update/$1',
            ['filter' => 'adminAuth']
        );

        $routes->delete(
            'sensores/(:num)',
            'APISensorController::delete/$1',
            ['filter' => 'adminAuth']
        );


        // --------------------------------------------------------
        // QUIZ — FLUTTER / API
        // --------------------------------------------------------

        // Fluxo baseado em sessão
        $routes->get(
            'quiz',
            '\App\Controllers\QuizController::index'
        );

        $routes->get(
            'quiz/pergunta',
            '\App\Controllers\QuizController::pergunta'
        );

        $routes->post(
            'quiz/responder',
            '\App\Controllers\QuizController::responder'
        );

        $routes->post(
            'quiz/avancar',
            '\App\Controllers\QuizController::avancar'
        );

        $routes->get(
            'quiz/resultado',
            '\App\Controllers\QuizController::resultado'
        );


        // API alternativa de perguntas
        $routes->get(
            'quiz/perguntas',
            'APIQuizController::perguntas'
        );

        $routes->get(
            'quiz/perguntas/(:num)',
            'APIQuizController::showPergunta/$1'
        );


        // --------------------------------------------------------
        // HISTÓRICO
        // --------------------------------------------------------

        $routes->get(
            'historico',
            '\App\Controllers\RespostaController::historico'
        );

        $routes->post(
            'resposta/excluir/(:num)',
            '\App\Controllers\RespostaController::excluir/$1'
        );


        // --------------------------------------------------------
        // ADMIN — DASHBOARD
        // --------------------------------------------------------

        $routes->get(
            'admin/dashboard',
            'AdminController::dashboard',
            ['filter' => 'adminAuth']
        );

        $routes->get(
            'admin/perfil',
            'AdminController::perfil',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/perfil',
            'AdminController::atualizarPerfilAdmin',
            ['filter' => 'adminAuth']
        );


        // --------------------------------------------------------
        // ADMIN — USUÁRIOS
        // --------------------------------------------------------

        $routes->get(
            'admin/usuarios',
            'AdminController::usuarios',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/usuario/atualizar/(:any)',
            'AdminController::atualizarUsuario/$1',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/usuario/excluir/(:any)',
            'AdminController::excluirUsuario/$1',
            ['filter' => 'adminAuth']
        );


        // --------------------------------------------------------
        // ADMIN — SENSORES
        // --------------------------------------------------------

        $routes->get(
            'admin/sensores',
            'AdminController::sensores',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/sensor/inserir',
            'AdminController::inserirSensor',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/sensor/atualizar/(:num)',
            'AdminController::atualizarSensor/$1',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/sensor/excluir/(:num)',
            'AdminController::excluirSensor/$1',
            ['filter' => 'adminAuth']
        );


        // --------------------------------------------------------
        // ADMIN — PERGUNTAS
        // --------------------------------------------------------

        $routes->get(
            'admin/perguntas',
            'AdminController::perguntas',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/pergunta/inserir',
            'AdminController::inserirPergunta',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/pergunta/atualizar/(:num)',
            'AdminController::atualizarPergunta/$1',
            ['filter' => 'adminAuth']
        );

        $routes->post(
            'admin/pergunta/excluir/(:num)',
            'AdminController::excluirPergunta/$1',
            ['filter' => 'adminAuth']
        );


        // --------------------------------------------------------
        // RECUPERAÇÃO DE SENHA — API
        // --------------------------------------------------------

        $routes->post(
            'usuario/esqueceu_senha',
            '\App\Controllers\RecuperacaoSenhaController::solicitar'
        );

        $routes->post(
            'usuario/redefinir_senha',
            '\App\Controllers\RecuperacaoSenhaController::redefinir'
        );


        // --------------------------------------------------------
        // IDENTIFICADOR — API
        // --------------------------------------------------------

        $routes->post(
            'identificador/buscar-sensor',
            '\App\Controllers\IdentificadorController::buscarSensor'
        );


        // --------------------------------------------------------
        // CONTATO — API
        // --------------------------------------------------------

        $routes->post(
            'contato/enviar',
            '\App\Controllers\ContatoController::enviar'
        );
    }
);


// ============================================================
// ÁREA RESTRITA — USUÁRIO LOGADO
// ============================================================

$routes->group(
    '',
    ['filter' => 'userAuth'],
    function ($routes) {

        // Início
        $routes->get(
            'inicio',
            'UsuarioController::inicio'
        );

        // Perfil
        $routes->get(
            'perfil',
            'UsuarioController::perfil'
        );

        $routes->post(
            'perfil',
            'UsuarioController::atualizarPerfil'
        );

        // Histórico
        $routes->get(
            'historico',
            'RespostaController::historico'
        );

        $routes->post(
            'resposta/excluir/(:num)',
            'RespostaController::excluir/$1'
        );

        // Quiz
        $routes->get(
            'quiz',
            'QuizController::index'
        );

        $routes->get(
            'quiz/pergunta',
            'QuizController::pergunta'
        );

        $routes->post(
            'quiz/responder',
            'QuizController::responder'
        );

        $routes->post(
            'quiz/avancar',
            'QuizController::avancar'
        );

        $routes->get(
            'quiz/resultado',
            'QuizController::resultado'
        );
    }
);


// ============================================================
// ÁREA RESTRITA — ADMINISTRADOR
// ============================================================

$routes->group(
    'admin',
    ['filter' => 'adminAuth'],
    function ($routes) {

        // --------------------------------------------------------
        // DASHBOARD
        // --------------------------------------------------------

        $routes->get(
            'dashboard',
            'AdminController::dashboard'
        );

        $routes->get(
            'perfil',
            'AdminController::perfil'
        );

        $routes->post(
            'perfil',
            'AdminController::atualizarPerfilAdmin'
        );


        // --------------------------------------------------------
        // USUÁRIOS
        // --------------------------------------------------------

        $routes->get(
            'usuarios',
            'AdminController::usuarios'
        );

        $routes->post(
            'usuario/atualizar/(:any)',
            'AdminController::atualizarUsuario/$1'
        );

        $routes->post(
            'usuario/excluir/(:any)',
            'AdminController::excluirUsuario/$1'
        );


        // --------------------------------------------------------
        // SENSORES
        // --------------------------------------------------------

        $routes->get(
            'sensores',
            'AdminController::sensores'
        );

        $routes->get(
            'sensor/novo',
            'AdminController::novoSensorForm'
        );

        $routes->post(
            'sensor/inserir',
            'AdminController::inserirSensor'
        );

        $routes->get(
            'sensor/editar/(:num)',
            'AdminController::editarSensorForm/$1'
        );

        $routes->post(
            'sensor/atualizar/(:num)',
            'AdminController::atualizarSensor/$1'
        );

        $routes->post(
            'sensor/excluir/(:num)',
            'AdminController::excluirSensor/$1'
        );


        // --------------------------------------------------------
        // PERGUNTAS
        // --------------------------------------------------------

        $routes->get(
            'perguntas',
            'AdminController::perguntas'
        );

        $routes->get(
            'pergunta/nova',
            'AdminController::novaPerguntaForm'
        );

        $routes->post(
            'pergunta/inserir',
            'AdminController::inserirPergunta'
        );

        $routes->get(
            'pergunta/editar/(:num)',
            'AdminController::editarPerguntaForm/$1'
        );

        $routes->post(
            'pergunta/atualizar/(:num)',
            'AdminController::atualizarPergunta/$1'
        );

        $routes->post(
            'pergunta/excluir/(:num)',
            'AdminController::excluirPergunta/$1'
        );
    }
);


// ============================================================
// CORS / OPTIONS / PREFLIGHT
// ============================================================

$routes->options(
    'api/(:any)',
    static function () {
        return service('response')
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader(
                'Access-Control-Allow-Headers',
                'Content-Type, Authorization, X-Requested-With, Accept'
            )
            ->setHeader(
                'Access-Control-Allow-Methods',
                'GET, POST, PUT, DELETE, OPTIONS'
            )
            ->setStatusCode(204);
    }
);
$routes->post('/login/cartao/enviar', 'AuthController::receberCartao');
$routes->get('/login/cartao', 'AuthController::loginCartao');