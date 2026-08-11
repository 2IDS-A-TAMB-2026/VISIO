<?= view('sistema/layout/header') ?>

<main class="login-page">
    <div class="login-container">
        <div class="login-form">
            <h1>Login como administrador</h1>
            <p>Entre com as credenciais de administrador cadastradas no sistema.</p>

            <?php if (session()->getFlashdata('erro')): ?>
                <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#991b1b;font-size:.9rem;">
                    <?= session()->getFlashdata('erro') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/login/admin') ?>" method="post" id="formAdm">
                <?= csrf_field() ?>
                <input type="email" name="email" id="email" placeholder="E-mail" autocomplete="username">
                <span class="erro" id="erroEmail"></span>
                <input type="password" name="senha" id="senha" placeholder="Senha" autocomplete="current-password">
                <span class="erro" id="erroSenha"></span>
                
                <button type="submit" class="btn-login-adm">
                    <i class="fa-solid fa-right-to-bracket"></i> Entrar como ADM
                </button>
                <br><br>
                <p> <a href="<?= base_url('/admin/esqueceu_senha') ?>" style="color: #0084f7;">Esqueceu senha?</a></p>
            </form>
        </div>
        <div class="login-image">
            <img class="theme-img"
                 src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                 data-light="<?= base_url('assets/images/logos/Logo/LogoLight.png') ?>"
                 data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                 alt="Logo"
                 style="width:100%; box-shadow:0 4px 15px rgba(0,0,0,.2);">
        </div>
    </div>
</main>

<?= view('sistema/layout/footer') ?>
