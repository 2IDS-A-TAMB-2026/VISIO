<?= view('sistema/layout/header') ?>
<main class="signup-page" style="display: grid; place-items: center; height: 100vh;">
    <div class="signup-container">
        <div class="signup-form">
            <h1>Criar Conta</h1>
            <p>Cadastre-se para acessar todas as funcionalidades da plataforma VISIO.</p>
            <form action="api/usuarios.php" method="POST" id="form">
                <input type="hidden" name="redirect_html" value="1">
                <input type="text" name="nome" id="nome" placeholder="Nome">
                <span class="erro" id="erroNome"></span>
                <input type="text" name="cpf" id="cpf" placeholder="CPF">
                <span class="erro" id="erroCpf"></span>
                <input type="email" name="email" id="email" placeholder="Email">
                <span class="erro" id="erroEmail"></span>
                <input type="text" name="data_nascimento" id="data_nascimento" placeholder="Data de nascimento">
                <span class="erro" id="erroDataNascimento"></span>
                <input type="tel" name="telefone" id="telefone" placeholder="Telefone">
                <span class="erro" id="erroTelefone"></span>
                <input type="text" name="cartao" id="cartao" placeholder="Número do cartão IoT" maxlength="25">
                <span class="erro" id="erroCartao"></span>
                <input type="password" name="senha" id="senha" placeholder="Senha">
                <span class="erro" id="erroSenha"></span>
                <button type="submit" style="color: #fff;">
                    <i class="fa-solid fa-user-plus" style="color: #fff;"></i> &nbsp;&nbsp; Criar Conta
                </button>
            </form>
            <div class="signup-footer">
                <p><a href="<?= base_url('/login') ?>" class="link-voltar-login"><i class="fa-solid fa-arrow-left"></i>
                        Voltar ao
                        login</a></p>
            </div>
        </div>
        <div class="signup-image">
            <!-- <img src="<?= base_url('assets/images/logos/Icone/IconeDark.png') ?>"
            <img class="theme-img" src="<?= base_url('assets/images/logos/Icone/IconeDark.png') ?>"
                data-light="<?= base_url('assets/images/logos/Icone/IconeLight.png') ?>"
                data-dark="<?= base_url('assets/images/logos/Icone/IconeDark.png') ?>" alt="Logo"
                style="width: 100%;  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
        </div>
    </div>
</main>

<?= view('sistema/layout/footer') ?>