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

    /* === CAMPO DE UPLOAD DE FOTO === */
    .file-upload-wrapper {
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

    /* Elemento de Preview da Imagem */
    .img-preview {
        max-width: 150px;
        max-height: 150px;
        margin-top: 12px;
        border-radius: 8px;
        object-fit: cover;
        display: none;
        border: 2px solid var(--border-color);
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
                    <h1>Cadastrar Novo Sensor</h1>
                    <p>Adicione um novo componente de hardware e suas especificações técnicas ao sistema.</p>
                </div>
                <a href="<?= base_url('/admin/sensores') ?>" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Voltar à lista
                </a>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <section class="card">
                <form id="formSensor" action="<?= base_url('/admin/sensor/inserir') ?>" method="post" enctype="multipart/form-data" class="form-grid">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="nome">Nome do sensor</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex.: Sensor de Temperatura LM35" maxlength="150" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <input type="text" id="descricao" name="descricao" placeholder="Resumo do funcionamento ou especificação técnica" maxlength="255" required>
                    </div>

                    <div class="form-group">
                        <label for="circuito">Circuito / Montagem</label>
                        <textarea id="circuito" name="circuito" rows="4" placeholder="Ex.: Conecte o pino VCC em 5V, GND no terra e VOUT no pino A0 do Arduino..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Foto do sensor (opcional)</label>
                        <div class="file-upload-wrapper">
                            <i class="fa-solid fa-cloud-arrow-up file-upload-icon"></i>
                            <span class="file-upload-text" id="file-label">Clique ou arraste a imagem aqui</span>
                            <span class="file-upload-info">JPG, PNG ou WEBP (máx. 2 MB)</span>
                            <input type="file" name="foto" accept="image/*" id="foto_input_novo">
                            <img id="preview" class="img-preview" alt="Pré-visualização da Imagem">
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit" class="btn-primary">
                        <i class="fa-solid fa-plus"></i> Cadastrar sensor
                    </button>
                </form>
            </section>

        </div>
    </div>
</div>

<script>
    const inputFoto = document.getElementById('foto_input_novo');
    const labelFoto = document.getElementById('file-label');
    const previewFoto = document.getElementById('preview');
    const formSensor = document.getElementById('formSensor');
    const btnSubmit = document.getElementById('btnSubmit');

    // Validação de arquivo + Preview visual
    inputFoto.addEventListener('change', function () {
        const maxBytes = 2 * 1024 * 1024; // 2 MB
        const file = this.files[0];

        if (file) {
            if (file.size > maxBytes) {
                alert('A imagem selecionada excede o limite de 2 MB. Por favor, escolha uma imagem menor.');
                this.value = '';
                labelFoto.textContent = 'Clique ou arraste a imagem aqui';
                previewFoto.style.display = 'none';
            } else {
                labelFoto.textContent = 'Imagem: ' + file.name;
                
                // Exibe o preview da imagem
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewFoto.src = e.target.result;
                    previewFoto.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Estado de carregamento no envio para evitar cliques duplos
    formSensor.addEventListener('submit', function() {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cadastrando...';
    });
</script>

<?= view('sistema/layout/footer_adm') ?>