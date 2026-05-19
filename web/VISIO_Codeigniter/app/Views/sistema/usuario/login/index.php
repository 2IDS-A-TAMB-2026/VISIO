<?= view('sistema/layout/header') ?>

<body>
    <main class="login-page"  style="display: grid; place-items: center; height: 100vh;">
        <p id="login-feedback" class="contato-feedback" hidden></p>
        <div class="login-container">
            <div class="login-form">
                <h1>LOGIN</h1>
                <p>Entre com suas credenciais para acessar a plataforma.</p>
                <form action="#" method="post" id="form">
                    <input type="email" name="email" id="email" placeholder="Email">
                    <span class="erro" id="erroEmail"></span>
                    <input type="password" name="senha" id="senha" placeholder="Senha">
                    <span class="erro" id="erroSenha"></span>
                    <br>
                    <button type="submit" style="color:#fff"><a href="index_logado.html" style="color:#fff">Entrar</a></button>
                </form>
                <div class="login-footer">
                    <p>Não tem conta? <a href="cadastro.html">Cadastre-se</a></p>
                    <p><a href="senha.html">Esqueceu a senha?</a></p>
                    <p><a href="loginAdm.html" class="admin-link">Login de Administrador</a></p>
                </div>
            </div>
            <div class="login-image">
                <img src="assets/Marca/Logo/logo_padrao.png" alt="Ilustração representando login na plataforma VISIO" style="width: 180%;">
            </div>
        </div>
    </main>
    
    <script>
        (function () {
            var p = new URLSearchParams(window.location.search);
            var el = document.getElementById("login-feedback");
            if (p.get("cadastro") === "ok") {
                el.hidden = false;
                el.textContent = "Cadastro concluído. Faça login com seu e-mail e senha.";
                el.classList.add("contato-feedback--ok");
            }
        })();
    </script>
    <script src="JS/validacaologin.js"></script>
    <script src="JS/theme.js"></script>
</body>

<?= view('sistema/layout/footer') ?>