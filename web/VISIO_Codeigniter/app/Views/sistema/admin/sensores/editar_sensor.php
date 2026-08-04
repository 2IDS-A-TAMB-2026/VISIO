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

    textarea, input[type="text"] {
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

    textarea:focus, input[type="text"]:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30, 107, 231, 0.15);
    }

    textarea {
        resize: vertical;
        min-height: 110px;
    }

    /* === EXIBIÇÃO E UPLOAD DE FOTO === */
    .foto-container {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .current-foto-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .current-foto-box img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid var(--border-color);
    }

    .current-foto-box span {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .file-upload-wrapper {
        flex: 1;
        min-width: 260px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px;
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        background: var(--bg-color);
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .file-upload-wrapper:hover {
        border-color: var(--primary);
        background: rgba(30, 107, 231, 0.02);
    }

    .file-upload-icon {
        font-size: 28px;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .file-upload-text {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-color);
    }

    .file-upload-info {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .file-upload-wrapper input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* === BOTÃO SUBMIT === */
    .btn-primary {
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
        margin-top: 10px;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 107, 231, 0.3);
    }

    /* === MENSAGEM DE ERRO/ALERTA === */
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
    }
</style>

<div class="layout">

    <!-- SIDEBAR -->
    <?= view('sistema/admin/_sidebar', ['ativo' => 'sensores']) ?>

    <div class="main">
        <div class="content">

            <div class="page-header">
                <div class="page-title-group">
                    <h1>Editar Sensor</h1>
                    <p>Atualize as informações técnicas ou altere a imagem do sensor selecionado.</p>
                </div>
                <a href="<?= base_url('/admin/sensores') ?>" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Voltar à lista
                </a>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <?php if (!$sensor): ?>
                <div class="alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i> Sensor não encontrado no sistema.
                </div>
            <?php else: ?>

                <section class="card">
                    <form id="formSensor" action="<?= base_url('/admin/sensor/atualizar/' . $sensor['ID_SENSOR']) ?>" method="post" enctype="multipart/form-data" class="form-grid">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="nome">Nome do sensor</label>
                            <input type="text" id="nome" name="nome" value="<?= esc($sensor['NOME']) ?>" placeholder="Ex.: Sensor de Temperatura LM35" maxlength="150" required>
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" id="descricao" name="descricao" value="<?= esc($sensor['DESCRICAO']) ?>" placeholder="Resumo do funcionamento ou especificação técnica" maxlength="255" required>
                        </div>

                        <div class="form-group">
                            <label for="circuito">Circuito / Montagem</label>
                            <textarea id="circuito" name="circuito" rows="4" placeholder="Ex.: Conecte o pino VCC em 5V, GND no terra e VOUT no pino A0..."><?= esc($sensor['CIRCUITO'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Foto do sensor</label>
                            <div class="foto-container">
                                
                                <?php if (!empty($sensor['FOTO'])): ?>
                                    <div class="current-foto-box">
                                        <img id="img-atual" src="<?= base_url($sensor['FOTO']) ?>" alt="Foto atual do sensor">
                                        <span>Foto Atual</span>
                                    </div>
                                <?php endif; ?>

                                <div class="file-upload-wrapper">
                                    <i class="fa-solid fa-cloud-arrow-up file-upload-icon"></i>
                                    <span class="file-upload-text" id="file-label">
                                        <?= !empty($sensor['FOTO']) ? 'Substituir imagem...' : 'Clique ou arraste a imagem aqui' ?>
                                    </span>
                                    <span class="file-upload-info">JPG, PNG ou WEBP (máx. 2 MB)</span>
                                    <input type="file" name="foto" accept="image/*" id="foto_input_editar">
                                </div>

                            </div>
                        </div>

                        <button type="submit" id="btnSubmit" class="btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Salvar alterações
                        </button>
                    </form>
                </section>

            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    const inputFoto = document.getElementById('foto_input_editar');
    const labelFoto = document.getElementById('file-label');
    const imgAtual = document.getElementById('img-atual');
    const formSensor = document.getElementById('formSensor');
    const btnSubmit = document.getElementById('btnSubmit');

    if (inputFoto) {
        inputFoto.addEventListener('change', function () {
            const maxBytes = 2 * 1024 * 1024; // 2 MB
            const file = this.files[0];

            if (file) {
                if (file.size > maxBytes) {
                    alert('A imagem selecionada excede o limite de 2 MB. Por favor, escolha uma imagem menor.');
                    this.value = '';
                    labelFoto.textContent = 'Substituir imagem...';
                } else {
                    labelFoto.textContent = 'Nova foto: ' + file.name;
                    
                    // Se houver prévia da imagem atual, substitui na hora
                    if (imgAtual) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            imgAtual.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    }

    if (formSensor) {
        formSensor.addEventListener('submit', function() {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Salvando...';
        });
    }
</script>

<?= view('sistema/layout/footer_adm') ?>