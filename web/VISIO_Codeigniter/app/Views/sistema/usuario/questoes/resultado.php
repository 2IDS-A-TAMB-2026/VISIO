<?= view('sistema/layout/header') ?>

<style>
/* Botões do resultado visíveis no tema claro */
body.light .animated-button,
body.light .animated-button.azul {
    background: var(--primary, #2563eb) !important;
    color: #fff !important;
    border: 2px solid var(--primary, #2563eb) !important;
}
body.light .animated-button:not(.azul) {
    background: transparent !important;
    color: var(--primary, #2563eb) !important;
    border: 2px solid var(--primary, #2563eb) !important;
}
body.light .animated-button:hover,
body.light .animated-button.azul:hover {
    opacity: 0.85;
}
</style>

    <main class="questoes-page" style="display: flex; align-items: center; justify-content: center; min-height: 80vh; width: 100%;">
        
        <div class="questao-box" style="display: flex; flex-direction: column; align-items: center; text-align: center; max-width: 600px; width: 90%; margin: 0 auto;">
            
            <h1 class="questoes-titulo">Resultado do Quiz</h1>
            
            <p style="margin: 1rem 0;">
                Você acertou <strong><?= $acertos ?></strong> de <strong><?= $total ?></strong> perguntas.
            </p>
            
            <?php
                $percentual = $total > 0 ? round(($acertos / $total) * 100) : 0;
            ?>
            <p style="color: var(--color-accent); font-weight: bold;"><?= $percentual ?>% de aproveitamento</p>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; width: 100%;">
                <a href="<?= base_url('/quiz') ?>" class="animated-button azul">Jogar novamente</a>
                <a href="<?= base_url('/') ?>" class="animated-button">Voltar ao início</a>
            </div>
            
        </div>
    </main>
    
<script src="<?= base_url('assets/js/theme.js') ?>"></script>

<?= view('sistema/layout/footer') ?>