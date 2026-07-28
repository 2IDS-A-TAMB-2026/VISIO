<?= view('sistema/layout/header') ?>

<main class="forgot-page">
    <div class="forgot-container">

        <section class="forgot-form">

            <?php if ($link): ?>

                <h1>Link Gerado!</h1>
                <p>
                    Em produção, este link seria enviado para o seu e-mail.<br>
                    Como estamos em modo de demonstração, clique abaixo para redefinir sua senha:
                </p>

                <div style="background:var(--bg,#1e293b);border:1px solid var(--border,#334155);
                            border-radius:8px;padding:12px 16px;word-break:break-all;
                            font-family:monospace;font-size:.85rem;margin:16px 0;color:#ffffff;">
                    <?= esc($link) ?>
                </div>

                <a href="<?= esc($link) ?>"
                   style="display:block;text-align:center;padding:12px 20px;border-radius:8px;
                          background:var(--primary,#2563eb);color:#ffffff;font-weight:700;
                          text-decoration:none;margin-bottom:10px;">
                    <i class="fa-solid fa-key"></i> Redefinir minha senha agora
                </a>

                <p style="font-size:.85rem;color:var(--text2,#94a3b8);text-align:center;">
                    <i class="fa-solid fa-clock"></i> Este link expira em <strong>30 minutos</strong>.
                </p>

            <?php else: ?>

                <h1>Solicitação Recebida</h1>
                <p>
                    Se o e-mail informado estiver cadastrado, você receberá
                    as instruções de recuperação em breve.
                </p>

            <?php endif; ?>

            <div class="forgot-footer" style="margin-top:24px;">
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

<?= view('sistema/layout/footer') ?>
