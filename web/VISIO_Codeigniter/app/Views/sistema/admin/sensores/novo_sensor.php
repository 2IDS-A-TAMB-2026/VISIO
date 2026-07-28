<?= view('sistema/layout/header_adm') ?>

<div class="layout">
    <?= view('sistema/admin/_sidebar', ['ativo' => 'sensores']) ?>

    <div class="main">
        <div class="content">

            <div class="topbar">
                <h1>Cadastrar Novo Sensor</h1>
                <p><a href="<?= base_url('/admin/sensores') ?>" style="color:var(--primary);">
                    <i class="fa-solid fa-arrow-left"></i> Voltar à lista
                </a></p>
            </div>
<style>
    .layout{ display:flex; }

.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0; top: 0;
    padding: 25px;
    background: linear-gradient(180deg, var(--sidebar), var(--sidebar2));
    overflow-y: auto;
    z-index: 1000;
}

.logo-area { display: flex; align-items: center; gap: 15px; margin-bottom: 40px; }
.logo-area h2 { color: white; font-size: 28px; font-weight: 700; }
.menu-title { color: #64748b; text-transform: uppercase; font-size: 12px; margin-bottom: 15px; letter-spacing: 1px; }
.menu { list-style: none; }
.menu li { margin-bottom: 10px; }
.menu a { display: flex; align-items: center; gap: 14px; padding: 15px; border-radius: 14px; text-decoration: none; color: #e2e8f0; font-weight: 500; transition: .3s; }
.menu a:hover, .menu a.active { background: rgba(37,99,235,.2); }

.card{ background:var(--card); border-radius:22px; padding:30px; box-shadow:var(--shadow); }
.form-grid{ display:grid; gap:20px; }
.form-grid label{ font-weight:600; margin-bottom:8px; display:block; color:var(--text); }
.form-grid input,
.form-grid textarea,
.form-grid select{ width:100%; padding:15px; border-radius:14px; border:1px solid var(--border); background:var(--bg); color:var(--text); outline:none; font-size:15px; transition:border-color .2s; box-sizing:border-box; }
.form-grid input:focus,
.form-grid textarea:focus,
.form-grid select:focus{ border-color:var(--primary); }
.form-grid textarea{ resize:vertical; }
.btn-primary{ background:var(--primary); color:#fff; border:none; padding:15px 24px; border-radius:14px; font-size:15px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:10px; transition:.2s; }
.btn-primary:hover{ opacity:.9; }
</style>
            <?= view('sistema/layout/_flash') ?>

            <section class="card">
                <form action="<?= base_url('/admin/sensor/inserir') ?>" method="post" enctype="multipart/form-data" class="form-grid">
                    <?= csrf_field() ?>

                    <div>
                        <label>Nome do sensor</label>
                        <input type="text" name="nome" placeholder="Ex.: Sensor de Temperatura" required>
                    </div>

                    <div>
                        <label>Descrição</label>
                        <input type="text" name="descricao" placeholder="Descrição técnica do sensor" required>
                    </div>

                    <div>
                        <label>Circuito / Montagem</label>
                        <textarea name="circuito" rows="4" placeholder="Ex.: Arduino + LM35 + resistor 10k"></textarea>
                    </div>

                    <div>
                        <label>Foto do sensor (opcional)</label>
                        <input type="file" name="foto" accept="image/*" id="foto_input_novo">
                        <small style="color:var(--text2,#64748b);font-size:12px;margin-top:4px;display:block;">
                            <i class="fa-solid fa-circle-info"></i> Tamanho máximo: 2 MB. Formatos: JPG, PNG, WEBP.
                        </small>
                    </div>

                    <div>
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-plus"></i> Cadastrar sensor
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</div>


<script>
document.getElementById('foto_input_novo').addEventListener('change', function () {
    var maxBytes = 2 * 1024 * 1024; // 2 MB
    if (this.files[0] && this.files[0].size > maxBytes) {
        alert('A imagem selecionada excede o limite de 2 MB. Por favor, escolha uma imagem menor.');
        this.value = '';
    }
});
</script>
<?= view('sistema/layout/footer_adm') ?>
