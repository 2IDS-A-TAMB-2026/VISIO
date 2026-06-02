<?= view('sistema/layout/header') ?>


    <main class="questoes-page" style="display: grid; place-items: center; min-height: 60vh;">
        <div class="questao-box" style="text-align: center;">
            <h1 class="questoes-titulo">Resultado do Quiz</h1>
            <p style="font-size: 1.5rem; margin: 1rem 0;">
                Você acertou <strong><?= $acertos ?></strong> de <strong><?= $total ?></strong> perguntas.
            </p>
            <?php
                $percentual = $total > 0 ? round(($acertos / $total) * 100) : 0;
            ?>
            <p style="font-size: 1.2rem; color: var(--color-accent);"><?= $percentual ?>% de aproveitamento</p>
            <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
                <a href="<?= base_url('/quiz') ?>" class="animated-button azul">Jogar novamente</a>
                <a href="<?= base_url('/') ?>" class="animated-button">Voltar ao início</a>
            </div>
        </div>
    </main>


<?= view('sistema/layout/footer') ?>
