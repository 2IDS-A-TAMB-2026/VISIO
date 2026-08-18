<?= view('sistema/layout/header') ?>

<style>
    /* Estilo Base - Gradiente azul mantido para todos os temas */
    body {
        background-color: #000000 !important; /* Fundo escuro (padrão) */
        background-image: 
            radial-gradient(circle at top right, #0055ff6f 0%, transparent 40%),
            radial-gradient(circle at bottom left, #0055ff6f 0%, transparent 40%) !important;
        background-attachment: fixed !important;
        color: #ffffff;
        font-family: sans-serif;
        min-height: 100vh;
        margin: 0;
    }

    /* Tema Claro - Altera a cor base para branco mantendo o degradê azul por cima */
    body.light {
        background-color: #ffffff !important;
        color: #0f172a;
    }

    body.light .questao-box {
        background-color: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }

    body.light .alternativa-item {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        color: #0f172a;
    }

    body.light .alternativa-item:hover {
        background-color: #e2e8f0;
        border-color: #2563eb;
    }

    body.light .alternativa-item label {
        color: #0f172a;
    }

    body.light .alternativas-form button {
        background-color: #2563eb;
    }

    body.light .questoes-progresso {
        color: #64748b;
    }

    body.light .questoes-titulo {
        color: #0f172a;
    }

    /* Container principal */
    .questoes-page {
        margin-top: 5%;
    }

    /* Container das questões */
    .questoes-container {
        display: flex;
        justify-content: center;
    }

    /* Caixa da questão */
    .questao-box {
        background-color: #1a1a1a;
        padding: 2rem;
        border-radius: 12px;
        width: 80%;
        max-width: 800px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        transition: background-color 0.3s, border-color 0.3s;
    }

    /* Progresso da questão */
    .questoes-progresso {
        font-weight: bold;
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    /* Título da questão */
    .questoes-titulo {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        line-height: 1.4;
    }

    /* Formulário das alternativas */
    .alternativas-form {
        display: flex;
        flex-direction: column;
    }

    /* Item da alternativa */
    .alternativa-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        cursor: pointer;
        padding: 15px;
        border-radius: 8px;
        background-color: #2a2a2a;
        border: 2px solid transparent;
        transition: all 0.2s;
    }

    .alternativa-item:hover {
        background-color: #333333;
        border-color: #007bff;
    }

    /* Input radio */
    .alternativa-item input[type="radio"] {
        margin-right: 15px;
        width: 20px;
        height: 20px;
        accent-color: #007bff;
    }

    .alternativa-item label {
        cursor: pointer;
        font-size: 1.1rem;
        flex: 1;
    }

    /* Botão de resposta */
    .alternativas-form button {
        background-color: #007bff;
        color: #ffffff;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 1rem;
        font-weight: bold;
        font-size: 1rem;
        transition: all 0.2s;
        width: 100%;
    }

    .alternativas-form button:hover {
        opacity: 0.9;
    }

    /* ── Estados de feedback ── */
    .alternativa-item.correta {
        background-color: rgba(34, 197, 94, 0.18);
        border-color: #22c55e;
    }
    .alternativa-item.incorreta {
        background-color: rgba(239, 68, 68, 0.18);
        border-color: #ef4444;
    }
    .alternativa-item.desabilitada {
        cursor: default;
        pointer-events: none;
    }
    .alternativa-item .feedback-icon {
        margin-left: auto;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .alternativa-item.correta .feedback-icon { color: #22c55e; }
    .alternativa-item.incorreta .feedback-icon { color: #ef4444; }

    .feedback-banner {
        padding: 14px 18px;
        border-radius: 8px;
        font-weight: bold;
        margin-bottom: 1.5rem;
    }
    .feedback-banner.acertou {
        background-color: rgba(34, 197, 94, 0.18);
        color: #22c55e;
        border: 1px solid #22c55e;
    }
    .feedback-banner.errou {
        background-color: rgba(239, 68, 68, 0.18);
        color: #ef4444;
        border: 1px solid #ef4444;
    }

    body.light .feedback-banner.acertou { background-color: rgba(34, 197, 94, 0.12); }
    body.light .feedback-banner.errou   { background-color: rgba(239, 68, 68, 0.12); }

    /* Garantir que cores de feedback prevalecem no tema claro */
    body.light .alternativa-item.correta {
        background-color: rgba(34, 197, 94, 0.18) !important;
        border-color: #22c55e !important;
    }
    body.light .alternativa-item.incorreta {
        background-color: rgba(239, 68, 68, 0.18) !important;
        border-color: #ef4444 !important;
    }

    /* Responsivo */
    @media (max-width: 600px) {
        .questao-box {
            width: 95%;
            padding: 1.5rem;
        }
    }
</style>

<main class="questoes-page">
    <div class="questoes-container questoes-layout">
        <div class="questao-box">

            <?php if (session()->getFlashdata('erro')): ?>
                <p style="color: #ef4444; margin-bottom: 1rem; font-weight: bold;"><?= session()->getFlashdata('erro') ?></p>
            <?php endif; ?>

            <p class="questoes-progresso">Pergunta <?= $indice ?> de <?= $total ?></p>
            <h1 class="questoes-titulo"><?= esc($pergunta['DESCRICAO']) ?></h1>

            <?php if ($feedback): ?>

                <?php if ($feedback['acertou']): ?>
                    <div class="feedback-banner acertou">
                        <i class="fa-solid fa-circle-check"></i> Resposta correta!
                    </div>
                <?php else: ?>
                    <div class="feedback-banner errou">
                        <i class="fa-solid fa-circle-xmark"></i> Resposta incorreta.
                    </div>
                <?php endif; ?>

                <div class="alternativas-form">
                    <?php foreach ($pergunta['alternativas'] as $alt): ?>
                        <?php
                            $idAlt = (int) $alt['ID_ALTERNATIVA'];
                            $classe = '';
                            $icone  = '';
                            if ($idAlt === (int) $feedback['correta_id']) {
                                $classe = 'correta';
                                $icone  = '<i class="fa-solid fa-circle-check feedback-icon"></i>';
                            } elseif ($idAlt === (int) $feedback['escolhida']) {
                                $classe = 'incorreta';
                                $icone  = '<i class="fa-solid fa-circle-xmark feedback-icon"></i>';
                            }
                        ?>
                        <label class="alternativa-item desabilitada <?= $classe ?>">
                            <input type="radio" disabled
                                   <?= $idAlt === (int) $feedback['escolhida'] ? 'checked' : '' ?>>
                            <?= esc($alt['DESCRICAO']) ?>
                            <?= $icone ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <form action="<?= base_url('/quiz/avancar') ?>" method="post" class="alternativas-form">
                    <?= csrf_field() ?>
                    <button type="submit">
                        <?= $ultima ? 'Ver resultado' : 'Próxima pergunta' ?>
                    </button>
                </form>

            <?php else: ?>

                <form action="<?= base_url('/quiz/responder') ?>" method="post" class="alternativas-form">
                    <?= csrf_field() ?>
                    <?php foreach ($pergunta['alternativas'] as $alt): ?>
                        <label class="alternativa-item">
                            <input type="radio" name="id_alternativa" value="<?= $alt['ID_ALTERNATIVA'] ?>" required>
                            <?= esc($alt['DESCRICAO']) ?>
                        </label>
                    <?php endforeach; ?>
                    <button type="submit">Responder</button>
                </form>

            <?php endif; ?>

        </div>
    </div>
</main>

<?= view('sistema/layout/footer') ?>