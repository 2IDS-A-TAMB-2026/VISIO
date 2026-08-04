<?= view('sistema/layout/header_adm') ?>

<style>
    /* === RESETS & SCROLL FIX === */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    html, body {
        height: auto !important;
        min-height: 100% !important;
        overflow-y: auto !important;
    }

    :root {
        --bg-color: #f8fafc;
        --card-bg: #ffffff;
        --text-color: #0f172a;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --primary: #1e6be7;
        --primary-dark: #1557c0;
        --primary-accent: #2662d9;
        --radius: 12px;
        --shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    body.dark {
        --bg-color: #0d121f;
        --card-bg: #131b4f;
        --text-color: #d4e0f7;
        --text-muted: #8592ad;
        --border-color: #2662d9;
        --shadow: none;
    }

    body {
        background: var(--bg-color);
        color: var(--text-color);
        transition: background-color 0.3s ease;
    }

    /* === LAYOUT E ESTRUTURA === */
    .layout {
        display: flex;
        min-height: 100vh;
        width: 100%;
        position: relative;
    }

    .sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: linear-gradient(180deg, #0f172a, #111827);
        padding: 25px;
        z-index: 1000;
    }

    .main {
        margin-left: 280px;
        flex: 1;
        padding: 35px 40px;
        padding-bottom: 80px;
        min-height: 100vh;
    }

    .content {
        max-width: 900px;
        margin: 0 auto;
    }

    /* === CABEÇALHO DA PÁGINA === */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title-group h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-color);
    }

    .page-title-group p {
        font-size: 14px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* Botão Voltar Padronizado (Azul) */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(30, 107, 231, 0.2);
    }

    .btn-back:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 107, 231, 0.35);
    }

    /* === CARD E FORMULÁRIO === */
    .card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 35px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }

    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    label {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-color);
    }

    textarea, input[type="text"], select {
        width: 100%;
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-color);
        color: var(--text-color);
        outline: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    textarea:focus, input[type="text"]:focus, select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30, 107, 231, 0.15);
    }

    textarea {
        resize: vertical;
        min-height: 110px;
    }

    /* === SEÇÃO DE ALTERNATIVAS === */
    .form-section-header {
        border-top: 1px dashed var(--border-color);
        padding-top: 20px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .form-section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-color);
    }

    .form-section-subtitle {
        font-size: 13px;
        color: var(--primary);
        font-weight: 500;
        background: rgba(30, 107, 231, 0.08);
        padding: 4px 12px;
        border-radius: 20px;
    }

    .alternatives-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .alt-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 16px;
        background: var(--bg-color);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .alt-row:hover {
        border-color: #cbd5e1;
    }

    body.dark .alt-row:hover {
        border-color: #3b82f6;
    }

    /* Estilo ativado ao selecionar a alternativa correta */
    .alt-row:has(input[type="radio"]:checked) {
        border-color: var(--primary);
        background: rgba(30, 107, 231, 0.03);
    }

    .alt-row input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    .alt-letra {
        font-weight: 700;
        min-width: 24px;
        color: var(--primary);
        font-size: 15px;
    }

    .alt-row input[type="text"] {
        border: none;
        background: transparent;
        padding: 10px 0;
    }

    .alt-row input[type="text"]:focus {
        box-shadow: none;
    }

    /* === BOTÃO SUBMIT === */
    .btn-salvar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-accent));
        color: white;
        border: none;
        padding: 16px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 15px;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-salvar:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 107, 231, 0.3);
    }

    /* === ALERTAS === */
    .alert-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        border-radius: 12px;
        padding: 16px;
        color: #991b1b;
        font-weight: 500;
        font-size: 14px;
    }

    /* === RESPONSIVIDADE === */
    @media (max-width: 900px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .main {
            margin-left: 0;
            padding: 20px;
        }

        .card {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="layout">

    <!-- SIDEBAR -->
    <?= view('sistema/admin/_sidebar', ['ativo' => 'perguntas']) ?>

    <div class="main">
        <div class="content">

            <div class="page-header">
                <div class="page-title-group">
                    <h1>Editar Questão</h1>
                    <p>Altere o enunciado, o nível de dificuldade ou o texto das alternativas.</p>
                </div>
                <a href="<?= base_url('/admin/perguntas') ?>" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Voltar à lista
                </a>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <?php if (!$pergunta): ?>
                <div class="alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i> Questão não encontrada no sistema.
                </div>
            <?php else: ?>

                <section class="card">
                    <form id="formPergunta" action="<?= base_url('/admin/pergunta/atualizar/' . $pergunta['ID_PERGUNTA']) ?>" method="post" class="form-grid">
                        <?= csrf_field() ?>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="descricao">Enunciado da pergunta</label>
                                <textarea id="descricao" name="descricao" rows="4" placeholder="Escreva o enunciado completo da questão..." required><?= esc($pergunta['DESCRICAO']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="nivel">Nível de dificuldade</label>
                                <select id="nivel" name="nivel" required>
                                    <option value="Fácil" <?= $pergunta['NIVEL_DIFICULDADE'] === 'Fácil' ? 'selected' : '' ?>>Fácil</option>
                                    <option value="Médio" <?= $pergunta['NIVEL_DIFICULDADE'] === 'Médio' ? 'selected' : '' ?>>Médio</option>
                                    <option value="Difícil" <?= $pergunta['NIVEL_DIFICULDADE'] === 'Difícil' ? 'selected' : '' ?>>Difícil</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-section-header">
                            <span class="form-section-title">Alternativas de Resposta</span>
                            <span class="form-section-subtitle"><i class="fa-solid fa-circle-check"></i> Marque qual alternativa é a correta</span>
                        </div>

                        <div class="alternatives-container">
                            <?php
                            $letras = ['A', 'B', 'C', 'D'];
                            foreach ($pergunta['alternativas'] as $i => $alt):
                                $letra = $letras[$i] ?? ($i + 1);
                            ?>
                                <label class="alt-row">
                                    <input type="radio" name="correta" value="<?= esc($alt['ID_ALTERNATIVA']) ?>" <?= $alt['IS_CORRETA'] ? 'checked' : '' ?> required>
                                    <input type="hidden" name="id_alternativa[]" value="<?= esc($alt['ID_ALTERNATIVA']) ?>">
                                    <span class="alt-letra"><?= $letra ?>)</span>
                                    <input type="text" name="alternativa[]" value="<?= esc($alt['DESCRICAO']) ?>" placeholder="Digite o texto da Alternativa <?= $letra ?>" required>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" id="btnSubmit" class="btn-salvar">
                            <i class="fa-solid fa-floppy-disk"></i> Salvar alterações
                        </button>
                    </form>
                </section>

            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    const formPergunta = document.getElementById('formPergunta');
    const btnSubmit = document.getElementById('btnSubmit');

    if (formPergunta) {
        formPergunta.addEventListener('submit', function() {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Salvando...';
        });
    }
</script>

<?= view('sistema/layout/footer_adm') ?>