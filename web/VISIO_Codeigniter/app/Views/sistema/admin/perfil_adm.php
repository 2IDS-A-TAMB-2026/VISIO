<?= view('sistema/layout/header_adm') ?>

<style>
/* Estilo Base - Gradiente azul mantido para todos os temas */
body {
    background-image: 
        radial-gradient(circle at top right, #0055ff6f 0%, transparent 40%),
        radial-gradient(circle at bottom left, #0055ff6f 0%, transparent 40%) !important;
    background-attachment: fixed !important;
    color: #ffffff;
    font-family: sans-serif;
    min-height: 100vh;
    margin: 0;
}

/* Tema Claro - Fundo branco mantendo o degradê azul */
body.light {
    background-color: #ffffff !important;
    color: #0f172a;
}

/* ── LAYOUT E ESTRUTURA ── */
.layout { 
    display: flex; 
}

.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0; 
    top: 0;
    padding: 25px;
    background: linear-gradient(180deg, var(--sidebar, #0d1117), var(--sidebar2, #161b22));
    overflow-y: auto;
    z-index: 1000;
}

.logo-area { 
    display: flex; 
    align-items: center; 
    gap: 15px; 
    margin-bottom: 40px; 
}

.logo-area h2 { 
    color: #ffffff; 
    font-size: 28px; 
    font-weight: 700; 
}

.menu-title { 
    color: #64748b; 
    text-transform: uppercase; 
    font-size: 12px; 
    margin-bottom: 15px; 
    letter-spacing: 1px; 
}

.menu { 
    list-style: none; 
    padding: 0; 
}

.menu li { 
    margin-bottom: 10px; 
}

.menu a { 
    display: flex; 
    align-items: center; 
    gap: 14px; 
    padding: 15px; 
    border-radius: 14px; 
    text-decoration: none; 
    color: #e2e8f0; 
    font-weight: 500; 
    transition: .3s; 
}

.menu a:hover, .menu a.active { 
    background: rgba(37,99,235,.2); 
}

.main { 
    width: calc(100% - 280px); 
    margin-left: 280px; 
    min-height: 100vh; 
    transition: background 0.3s; 
}

.content { 
    padding: 30px; 
}

.perfil-header { 
    margin-bottom: 30px; 
}

.perfil-header h1 { 
    font-size: 30px; 
    color: var(--text, #ffffff); 
}

.perfil-sub { 
    color: var(--text2, #94a3b8); 
    margin-top: 8px; 
}

.container-perfil { 
    display: flex; 
    gap: 30px; 
    flex-wrap: wrap; 
    align-items: flex-start; 
}

/* Lateral (Avatar) */
.perfil-lateral {
    width: 280px;
    background: var(--card, #1a1a24);
    border-radius: 24px;
    padding: 30px 20px;
    box-shadow: var(--shadow, 0 10px 30px rgba(0,0,0,0.2));
    border: 1px solid var(--border, rgba(255,255,255,.08));
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex-shrink: 0;
    transition: background 0.3s, border-color 0.3s;
}

.perfil-lateral img {
    width: 160px; 
    height: 160px;
    border-radius: 50%; 
    object-fit: cover;
    border: 5px solid var(--primary, #2563eb);
    box-shadow: 0 10px 30px rgba(37,99,235,.2);
    margin-bottom: 15px;
}

.perfil-lateral h3 { 
    font-size: 18px; 
    margin-bottom: 4px; 
    color: var(--text, #ffffff); 
}

.perfil-lateral span { 
    color: var(--text2, #94a3b8); 
    font-size: 13px; 
}

.btn-foto {
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 8px;
    margin-top: 18px;
    background: var(--primary, #2563eb); 
    padding: 10px 16px; 
    border-radius: 12px;
    font-weight: 600; 
    color: #ffffff !important;
    cursor: pointer; 
    width: 100%; 
    box-sizing: border-box;
    transition: background .2s;
}

.btn-foto:hover { 
    background: #1d4ed8; 
}

/* Formulário */
.card-form {
    flex: 1; 
    min-width: 360px;
    background: var(--card, #1a1a24);
    padding: 35px;
    border-radius: 24px;
    box-shadow: var(--shadow, 0 10px 30px rgba(0,0,0,0.2));
    border: 1px solid var(--border, rgba(255,255,255,.08));
    transition: background 0.3s, border-color 0.3s;
}

.card-form h2 { 
    font-size: 20px; 
    margin-bottom: 24px; 
    color: var(--text, #ffffff); 
}

.campo { 
    margin-bottom: 18px; 
}

.campo label { 
    display: block; 
    margin-bottom: 7px; 
    font-weight: 600; 
    color: var(--text, #ffffff); 
}

.campo input {
    width: 100%; 
    height: 52px;
    padding: 0 16px;
    border: 2px solid var(--border, rgba(255,255,255,.12));
    border-radius: 14px;
    background: var(--bg, #0e0e16);
    color: var(--text, #ffffff);
    box-sizing: border-box;
    transition: border-color .2s, background 0.3s, color 0.3s;
}

.campo input:focus { 
    outline: none; 
    border-color: var(--primary, #2563eb); 
}

.campo input[readonly] { 
    opacity: .55; 
    cursor: not-allowed; 
}

.senha-wrap { 
    position: relative; 
}

.senha-wrap input { 
    padding-right: 48px; 
}

.btn-ver {
    position: absolute; 
    right: 14px; 
    top: 50%;
    transform: translateY(-50%);
    background: none; 
    border: none; 
    cursor: pointer;
    color: var(--text2, #94a3b8); 
    padding: 0;
}

.btn-ver:hover { 
    color: var(--text, #ffffff); 
}

.campo small { 
    display: block; 
    margin-top: 5px; 
    font-size: 12px; 
    color: var(--text2, #94a3b8); 
}

.btn-salvar {
    width: 100%; 
    height: 52px;
    border: none; 
    border-radius: 14px;
    background: var(--primary, #2563eb); 
    color: #ffffff;
    font-weight: 600;
    cursor: pointer; 
    margin-top: 8px;
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 10px;
    transition: background .2s;
}

.btn-salvar:hover { 
    background: #1d4ed8; 
}

/* Ajustes Tema Claro */
body.light .perfil-lateral,
body.light .card-form {
    background: #ffffff;
    border-color: #e2e8f0;
}

body.light .campo input {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}

body.light .perfil-header h1,
body.light .perfil-lateral h3,
body.light .card-form h2,
body.light .campo label {
    color: #0f172a;
}

/* ── ALTO CONTRASTE ── */
body.high-contrast .perfil-lateral,
body.high-contrast .card-form      { background: #000000 !important; border-color: #ffff00 !important; }
body.high-contrast .campo input    { background: #000000 !important; color: #ffff00 !important; border-color: #ffff00 !important; }
body.high-contrast .campo label,
body.high-contrast .card-form h2,
body.high-contrast .perfil-header h1 { color: #ffff00 !important; }
body.high-contrast .btn-salvar     { background: #ffff00 !important; color: #000000 !important; }
body.high-contrast .btn-foto       { background: #ffff00 !important; color: #000000 !important; }
</style>

<div class="layout">
    <?= view('sistema/admin/_sidebar', ['ativo' => 'perfil']) ?>

    <div class="main">
        <div class="content">
            <div class="perfil-header">
                <h1><i class="fa-solid fa-user-shield"></i> Perfil do Administrador</h1>
                <p class="perfil-sub">Gerencie suas informações e configurações da conta.</p>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <div class="container-perfil">
                <div class="perfil-lateral">
                    <img id="preview_foto"
                         src="<?= !empty($admin['FOTO']) ? base_url($admin['FOTO']) : 'https://ui-avatars.com/api/?name=ADM&background=2563eb&color=fff&size=160' ?>"
                         alt="Foto de perfil"
                         onerror="this.src='https://ui-avatars.com/api/?name=ADM&background=2563eb&color=fff&size=160'">
                    <h3><?= esc($admin['NOME'] ?? '') !== '' ? esc($admin['NOME']) : 'Administrador' ?></h3>
                    <span><?= esc($admin['EMAIL'] ?? '') ?></span>
                    <label for="foto_adm_input" class="btn-foto">
                        <i class="fa-solid fa-camera"></i> Alterar Foto
                    </label>
                    <small style="margin-top:10px;font-size:11px;color:var(--text2, #94a3b8);">
                        PNG, JPG ou WEBP, até 2MB.
                    </small>
                </div>

                <div class="card-form">
                    <h2><i class="fa-solid fa-id-card"></i> Informações da conta</h2>

                    <form action="<?= base_url('/admin/perfil') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <input type="file" id="foto_adm_input" name="foto" accept="image/*" style="display:none;">

                        <div class="campo">
                            <label>CNPJ</label>
                            <input type="text" value="<?= esc($admin['CNPJ'] ?? '') ?>" readonly>
                            <small>O CNPJ não pode ser alterado.</small>
                        </div>

                        <div class="campo">
                            <label>Nome</label>
                            <input type="text" name="nome" value="<?= esc($admin['NOME'] ?? '') ?>" required>
                        </div>

                        <div class="campo">
                            <label>E-mail</label>
                            <input type="email" name="email" value="<?= esc($admin['EMAIL'] ?? '') ?>" required>
                        </div>

                        <div class="campo">
                            <label>Telefone</label>
                            <input type="tel" name="telefone" id="campo_tel"
                                   value="<?= esc($admin['TELEFONE'] ?? '') ?>"
                                   placeholder="(00) 00000-0000">
                        </div>

                        <div class="campo">
                            <label>Nova senha <small style="display:inline;opacity:.6;">(deixe em branco para não alterar)</small></label>
                            <div class="senha-wrap">
                                <input type="password" name="senha" id="campo_senha" placeholder="Digite a nova senha">
                                <button type="button" class="btn-ver" onclick="toggleSenha()">
                                    <i class="fa-solid fa-eye" id="icon_senha"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-salvar">
                            <i class="fa-solid fa-floppy-disk"></i> Salvar alterações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/* Máscara e Validação de telefone */
document.getElementById('campo_tel').addEventListener('input', function () {
    let raw = this.value.replace(/\D/g, '').slice(0, 11);
    let v = raw;

    if      (v.length > 6) v = v.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    else if (v.length > 2) v = v.replace(/^(\d{2})(\d{0,5})/,        '($1) $2');
    else if (v.length > 0) v = v.replace(/^(\d{0,2})/,               '($1');
    
    this.value = v;

    if (raw.length > 0 && raw.length < 10) {
        this.setCustomValidity('Informe o DDD e o telefone completo (mínimo 10 dígitos).');
    } else {
        this.setCustomValidity(''); 
    }
});

/* Mostrar/ocultar senha */
function toggleSenha() {
    const c = document.getElementById('campo_senha');
    const i = document.getElementById('icon_senha');
    c.type = c.type === 'password' ? 'text' : 'password';
    i.className = c.type === 'password' ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
}

/* Preview de foto */
const fotoInput   = document.getElementById('foto_adm_input');
const fotoPreview = document.getElementById('preview_foto');

fotoInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Imagem muito grande. Máximo: 2 MB.');
        this.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        fotoPreview.src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>

<?= view('sistema/layout/footer_adm') ?>