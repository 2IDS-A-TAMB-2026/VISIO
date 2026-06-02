<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database — Configuração de conexão com o banco de dados
 *
 * ╔═══════════════════════════════════════════════════════════════╗
 * ║  AJUSTE OS VALORES ABAIXO conforme seu ambiente:             ║
 * ║                                                               ║
 * ║  hostname : 'localhost' (XAMPP padrão)                        ║
 * ║  username : 'root'      (XAMPP padrão)                        ║
 * ║  password : ''          (XAMPP sem senha) ou sua senha        ║
 * ║  database : 'visio'     (nome do banco — rode o BD_VISIO.sql) ║
 * ╚═══════════════════════════════════════════════════════════════╝
 */
class Database extends Config
{
    /**
     * Grupo de conexão padrão — usado automaticamente pelos Models CI4.
     */
    public string $defaultGroup = 'default';

    /**
     * Conexão principal.
     *
     * ⚠️  Ajuste hostname, username, password e database conforme seu ambiente.
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',     // ← geralmente não precisa mudar
        'username'     => 'root',          // ← usuário do MySQL (XAMPP = root)
        'password'     => '',              // ← senha do MySQL (XAMPP = vazio)
        'database'     => 'bd_visio',         // ← nome do banco de dados
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,            // mude para false em produção
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,            // porta padrão MySQL
    ];

    /**
     * Conexão de testes (usada apenas para rodar PHPUnit).
     * Não precisa mexer aqui para o TCC.
     */
    public array $tests = [
        'DSN'      => '',
        'hostname' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'database' => 'visio_test',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => 'db_',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8mb4',
        'DBCollat' => 'utf8mb4_general_ci',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
