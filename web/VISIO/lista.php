<?php
$url = "http://localhost/Aulas_PBE_2/VISIO/api/usuarios.php";
$resposta = file_get_contents($url);
if ($resposta === false) {
    $usuarios = [];
} else {
    $usuarios = json_decode($resposta, true) ?? [];
}
if ($resposta === false) {
    $usuarios = [];
} else {
    $usuarios = json_decode($resposta, true);
}
if ($usuarios == null) {
    $usuarios = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cadastros</title>

    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <header>
        <nav>
            <img src="images/LogoDark.png" class="logo" alt="Logo VISIO">
            <ul class="nav-links">
                <li><a href="index.html"><i class="fa-solid fa-house"></i> Início</a></li>
                <li><a href="sobre.html"><i class="fa-solid fa-info-circle"></i> Sobre Nós</a></li>
                <li><a href="contato.html"><i class="fa-solid fa-address-book"></i> Contato</a></li>
                <li><a href="questoes.html"><i class="fa-solid fa-circle-question"></i> Questões</a></li>
                <li><a href="login.html"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
            </ul>
        </nav>
    </header>
    <main class="lista">
        <h1>Usuários Cadastrados</h1>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Data de Nascimento</th>
                        <th>Senha</th>
                        <th>Telefone</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= ($usuario["id"]) ?></td>
                                <td><?= ($usuario["cpf"]) ?></td>
                                <td><?= ($usuario["email"]) ?></td>
                                <td><?= ($usuario["data_nascimento"]) ?></td>
                                <td><?= ($usuario["senha"]) ?></td>
                                <td><?= ($usuario["telefone"]) ?></td>
                                <td class="coluna-acao">
                                    <form action="api/usuarios.php" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                        <button type="submit" class="btn-delete">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Nenhum usuário cadastrado</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        &copy; 2026 VISIO – Visão Inteligente para Sensores IoT.
    </footer>
</body>

</html>