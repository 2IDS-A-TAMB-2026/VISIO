<?= view('sistema/layout/header') ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha — VISIO</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="assets/Marca/Simbolo/SimboloDark.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
</head>


    <main class="forgot-page"  style="display: grid; place-items: center; height: 100vh;">
        <p id="senha-feedback" class="contato-feedback" hidden></p>
        <div class="forgot-container">
            <section class="forgot-form">
                <h1>Recuperar Senha</h1>
                <p>Digite seu e-mail. O pedido será registrado (demonstração — sem envio real de link).</p>
                <form action="api/recuperar_senha.php" method="post" id="form">
                    <input type="email" name="email" id="email" placeholder="Seu e-mail">
                    <span class="erro" id="erroEmail"></span>
                    <button type="submit" style="color: #fff"><i class="fa-solid fa-envelope" style="color: #fff"></i> Registrar</button>
                </form>
                <div class="forgot-footer">
                    <p>Voltar para o Login? <a href="login.html">Entrar</a></p>
                    <p>Não tem conta? <a href="cadastro.html">Cadastre-se</a></p>
                </div>
            </section>
            <section class="forgot-image">
                <img src="assets/Marca/Logo/logo_padrao.png" alt="Ilustração representando login na plataforma VISIO" style="width: 180%;">
            </section>
        </div>
    </main>

</html>

<?= view('sistema/layout/footer') ?>