<?= view('sistema/layout/header') ?>

<style>
/* ===== Página de Resultado do Quiz ===== */
.resultado-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 80vh;
    width: 100%;
}

.resultado-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 600px;
    width: 90%;
    margin: 0 auto;
    padding: 2rem;
    border-radius: 16px;
    background: var(--card-bg, #fff);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.resultado-placar {
    margin: 1rem 0;
    font-size: 1.05rem;
}

.resultado-percentual {
    font-size: 2rem;
    font-weight: 800;
    color: var(--color-accent);
    margin: 0.5rem 0;
}

.resultado-mensagem {
    font-size: 1rem;
    color: var(--text-muted, #64748b);
    margin-bottom: 1.5rem;
}

/* Barra de progresso */
.resultado-barra {
    width: 100%;
    height: 12px;
    border-radius: 999px;
    background: var(--track-bg, #e2e8f0);
    overflow: hidden;
    margin: 0.75rem 0 1.5rem;
}

.resultado-barra-preenchimento {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, var(--primary, #2563eb), var(--color-accent, #2232c5));
    transition: width 0.6s ease;
}

/* Ações */
.resultado-acoes {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
    width: 100%;
    flex-wrap: wrap;
}

/* Botões — visíveis em ambos os temas */
.animated-button.azul {
    background: var(--primary, #2563eb);
    color: #fff;
    border: 2px solid var(--primary, #2563eb);
}

.animated-button:not(.azul) {
    background: transparent;
    color: var(--primary, #2563eb);
    border: 2px solid var(--primary, #2563eb);
}

.animated-button {
    padding: 0.7rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: opacity 0.2s ease, transform 0.15s ease;
    display: inline-block;
}

.animated-button:hover {
    opacity: 0.85;
    transform: translateY(-1px);
}
</style>

<?php
    $percentual = $total > 0 ? round(($acertos / $total) * 100) : 0;

    // Feedback educacional de acordo com o desempenho
    if ($percentual >= 80) {
        $mensagem = 'Excelente! Você domina bem o conteúdo. 🎉';
    } elseif ($percentual >= 50) {
        $mensagem = 'Bom trabalho! Revise os pontos que errou para fixar ainda mais. 📘';
    } else {
        $mensagem = 'Continue estudando — a prática leva à perfeição. 💪';
    }
?>

<main class="resultado-container">
    <div class="resultado-box">
        <h1 class="questoes-titulo" style="color: #000">Resultado do Quiz</h1>
        <p class="resultado-placar"  style="color: #000">
            Você acertou <strong><?= $acertos ?></strong> de <strong><?= $total ?></strong> perguntas.
        </p>
        <p class="resultado-percentual"  style="color: #000"><?= $percentual ?>%</p>
        <div class="resultado-barra" role="progressbar" aria-valuenow="<?= $percentual ?>" aria-valuemin="0" aria-valuemax="100">
            <div class="resultado-barra-preenchimento" style="width: <?= $percentual ?>%;"></div>
        </div>
        <p class="resultado-mensagem"><?= $mensagem ?></p>
        <div class="resultado-acoes">
            <a href="<?= base_url('/quiz') ?>" class="animated-button azul">Jogar novamente</a>
            <a href="<?= base_url('/') ?>" class="animated-button azul">Voltar ao início</a>
        </div>
    </div>
</main>


<script src="<?= base_url('assets/js/theme.js') ?>"></script>

<?= view('sistema/layout/footer') ?>