<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
$metodo = $_SERVER["REQUEST_METHOD"];
// Permite simular PUT e DELETE via formulário HTML
if ($metodo == "POST" && isset($_POST["_method"])) {
    $metodo = $_POST["_method"];
}
$arquivo = "../dados/usuarios.json";
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([]));
}
$usuarios = json_decode(file_get_contents($arquivo), true);

if ($usuarios == null) {
    $usuarios = [];
}
// GET
if ($metodo == "GET") {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        foreach ($usuarios as $usuario) {
            if ($usuario["id"] == $id) {
                http_response_code(200);
                echo json_encode($usuario);
                exit;
            }
        }
        http_response_code(404);
        echo json_encode(["mensagem" => "Usuário não encontrado"]);
        exit;
    }

    http_response_code(200);
    echo json_encode($usuarios);
    exit;
}

// POST
if ($metodo == "POST") {

    $cpf = $_POST["cpf"] ?? null;
    $email = $_POST["email"] ?? null;
    $data_nascimento = $_POST["data_nascimento"] ?? null;
    $telefone = $_POST["telefone"] ?? null;
    $senha = $_POST["senha"] ?? null;
    $confirmar = $_POST["confirmar_senha"] ?? null;

    if (!$cpf || !$email || !$senha) {
        http_response_code(400);
        echo json_encode(["mensagem" => "CPF, email e senha são obrigatórios"]);
        exit;
    }

    if ($senha !== $confirmar) {
        http_response_code(400);
        echo json_encode(["mensagem" => "As senhas não coincidem"]);
        exit;
    }

    $novoId = count($usuarios) + 1;

    $novoUsuario = [
        "id" => $novoId,
        "cpf" => $cpf,
        "email" => $email,
        "data_nascimento" => $data_nascimento,
        "telefone" => $telefone,
        "senha" => password_hash($senha, PASSWORD_DEFAULT)
    ];

    $usuarios[] = $novoUsuario;

    file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));

    http_response_code(201);
    echo json_encode($novoUsuario);
    exit;
}
if ($metodo == "PATCH") {

    $id = $_POST["id"] ?? null;

    if (!$id) {
        http_response_code(400);
        exit;
    }

    foreach ($usuarios as &$usuario) {

        if ($usuario["id"] == $id) {

            if (isset($_POST["cpf"])) {
                $usuario["cpf"] = $_POST["cpf"];
            }

            if (isset($_POST["email"])) {
                $usuario["email"] = $_POST["email"];
            }

            if (isset($_POST["telefone"])) {
                $usuario["telefone"] = $_POST["telefone"];
            }

            file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));

            http_response_code(200);
            echo json_encode($usuario);
            exit;
        }
    }

    http_response_code(404);
    exit;
}

// DELETE
if ($metodo == "DELETE") {

    $id = $_POST["id"] ?? null;

    if (!$id) {
        http_response_code(400);
        exit;
    }

    foreach ($usuarios as $indice => $usuario) {

        if ($usuario["id"] == $id) {

            unset($usuarios[$indice]);

            file_put_contents($arquivo, json_encode(array_values($usuarios), JSON_PRETTY_PRINT));

            http_response_code(200);
            echo json_encode(["mensagem" => "Usuário removido"]);
            exit;
        }
    }

    http_response_code(404);
    exit;
}

http_response_code(405);
echo json_encode(["mensagem" => "Método não permitido"]);
