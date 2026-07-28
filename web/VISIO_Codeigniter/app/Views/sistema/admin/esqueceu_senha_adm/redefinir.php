<?= view('sistema/layout/header') ?>

<main class="forgot-page">
    <div class="forgot-container">

        <section class="forgot-form">
            <h1>Redefinir Senha</h1>
            <p>
                Conta: <strong><?= esc($email) ?></strong><br>
                Escolha uma nova senha com no mínimo 6 caracteres.
            </p>

            <?php if (session()->getFlashdata('erro')): ?>
                <p class="contato-feedback contato-feedback--erro" style="margin-bottom:14px;">
                    <?= session()->getFlashdata('erro') ?>
                </p>
            <?php endif; ?>

            <form action="<?= base_url('/usuario/redefinir_senha') ?>" method="post" id="form-redefinir">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc($token) ?>">

                <input type="password" name="senha" id="senha"
                       placeholder="Nova senha (mín. 6 caracteres)" minlength="6" required>
                <span class="erro" id="erroSenha"></span>

                <input type="password" name="confirma_senha" id="confirma_senha"
                       placeholder="Confirme a nova senha" minlength="6" required
                       style="margin-top:10px;">
                <span class="erro" id="erroConfirma"></span>

                <button type="submit" style="margin-top:16px;">
                    <i class="fa-solid fa-floppy-disk"></i> Salvar nova senha
                </button>
            </form>

            <div class="forgot-footer" style="margin-top:20px;">
                <p><a href="<?= base_url('/login') ?>" style="color:#0084f7;">
                    <i class="fa-solid fa-arrow-left"></i> Voltar ao login
                </a></p>
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

<script>
(function () {
    'use strict';
    var form     = document.getElementById('form-redefinir');
    var senha    = document.getElementById('senha');
    var confirma = document.getElementById('confirma_senha');
    var erroS    = document.getElementById('erroSenha');
    var erroC    = document.getElementById('erroConfirma');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        var valido = true;
        if (!senha.value || senha.value.length < 6) {
            erroS.innerText = 'A senha deve ter pelo menos 6 caracteres.';
            valido = false;
        } else { erroS.innerText = ''; }
        if (confirma.value !== senha.value) {
            erroC.innerText = 'As senhas não coincidem.';
            valido = false;
        } else { erroC.innerText = ''; }
        if (!valido) e.preventDefault();
    });
})();
</script>

<?= view('sistema/layout/footer') ?>
