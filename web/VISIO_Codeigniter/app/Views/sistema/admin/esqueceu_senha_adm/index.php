<?= view('sistema/layout/header') ?>

<main class="forgot-page">
    <p id="senha-feedback" class="contato-feedback" hidden></p>

    <div class="forgot-container">

        <section class="forgot-form">
            <h1>Recuperar Senha</h1>
            <p>Digite seu e-mail cadastrado. Você receberá um link para redefinir sua senha.</p>

            <?php if (session()->getFlashdata('erro')): ?>
                <p class="contato-feedback contato-feedback--erro" style="margin-bottom:14px;">
                    <?= session()->getFlashdata('erro') ?>
                </p>
            <?php endif; ?>

            <form action="<?= base_url('/usuario/esqueceu_senha') ?>" method="post" id="form">
                <?= csrf_field() ?>
                <input type="email" name="email" id="email" placeholder="Seu e-mail">
                <span class="erro" id="erroEmail"></span>
                <button type="submit">
                    <i class="fa-solid fa-envelope"></i> Enviar link de recuperação
                </button>
            </form>

            <div class="forgot-footer" style="margin-top:20px;">
                <p>Lembrou a senha? <a href="<?= base_url('/login') ?>" style="color:#0084f7;">Entrar</a></p>
            </div>
        </section>

        <div class="login-image">
            <img class="theme-img"
                src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                data-light="<?= base_url('assets/images/logos/Logo/LogoLight.png') ?>"
                data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                alt="Logo VISIO"
                style="width:100%; box-shadow:0 4px 15px rgba(0,0,0,.2);">
        </div>

    </div>
</main>

<script src="<?= base_url('assets/js/validacaorecuperar.js') ?>"></script>
<?= view('sistema/layout/footer') ?>
