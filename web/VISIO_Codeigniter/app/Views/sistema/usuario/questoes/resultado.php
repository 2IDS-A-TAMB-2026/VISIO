<?= view('sistema/layout/header') ?>

<style>
/* Estilo Base - Gradiente azul mantido para todos os temas */
body {
    background-color: #000000 !important;
    background-image: 
        radial-gradient(circle at top right, #0055ff6f 0%, transparent 40%),
        radial-gradient(circle at bottom left, #0055ff6f 0%, transparent 40%) !important;
    background-attachment: fixed !important;
    color: #ffffff;
    font-family: sans-serif;
    min-height: 100vh;
    margin: 0;
}

/* Tema Claro */
body.light {
    background-color: #ffffff !important;
    color: #0f172a;
}

/* Caixa de conteúdo (Modo Escuro / Padrão) */
.questao-box {
    background-color: #1a1a1a;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    transition: background-color 0.3s, border-color 0.3s;
}

/* Caixa de conteúdo (Modo Claro) */
body.light .questao-box {
    background-color: #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    color: #0f172a;
}

/* ── Padronização dos Botões (Escuro e Claro) ── */
.animated-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.2s ease-in-out;
    box-sizing: border-box;
}

/* Botão Azul (Preenchido) - Escuro */
.animated-button.azul {
    background-color: #2563eb !important;
    color: #ffffff !important;
    border: 2px solid #2563eb !important;
}

/* Botão Secundário (Contorno) - Escuro */
.animated-button:not(.azul) {
    background-color: transparent !important;
    color: #ffffff !important;
    border: 2px solid #ffffff !important;
}

/* Botão Azul (Preenchido) - Claro */
body.light .animated-button.azul {
    background-color: #2563eb !important;
    color: #ffffff !important;
    border: 2px solid #2563eb !important;
}

/* Botão Secundário (Contorno) - Claro */
body.light .animated-button:not(.azul) {
    background-color: transparent !important;
    color: #2563eb !important;
    border: 2px solid #2563eb !important;
}

/* Efeito Hover */
.animated-button:hover {
    opacity: 0.85;
    transform: translateY(-1px);
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
        <p style="color: var(--color-accent, #2563eb); font-weight: bold;"><?= $percentual ?>% de aproveitamento</p>
        
        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; width: 100%; flex-wrap: wrap;">
            <a href="<?= base_url('/quiz') ?>" class="animated-button azul">Jogar novamente</a>
            <a href="<?= base_url('/') ?>" class="animated-button">Voltar ao início</a>
        </div>
        
    </div>
</main>
 
<script src="<?= base_url('assets/js/theme.js') ?>"></script>

<?= view('sistema/layout/footer') ?>