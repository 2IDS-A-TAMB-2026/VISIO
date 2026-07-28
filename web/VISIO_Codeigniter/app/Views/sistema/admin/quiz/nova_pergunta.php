<?= view('sistema/layout/header_adm') ?>

<style>
*{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
:root{ --bg:#f1f5f9; --card:#fff; --text:#0f172a; --text2:#64748b; --border:#e2e8f0; --primary:#2563eb; --sidebar:#0f172a; --sidebar2:#111827; --shadow:0 10px 25px rgba(0,0,0,.08); }
body.dark{ --bg:#0b1120; --card:#111827; --text:#f8fafc; --text2:#94a3b8; --border:#1e293b; --sidebar:#020617; --sidebar2:#0f172a; }
body{ background:var(--bg); color:var(--text); min-height:100vh; transition:.3s; }
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

.main{ width:calc(100% - 280px); margin-left:280px; }
header{ height:90px; background:var(--card); border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; padding:0 30px; position:sticky; top:0; z-index:999; box-shadow:0 4px 20px rgba(0,0,0,.04); }
.nav-right{ display:flex; align-items:center; gap:20px; }
#theme-toggle{ width:42px; height:42px; border:none; border-radius:12px; background:var(--bg); color:var(--text); cursor:pointer; font-size:16px; }
.profile-btn{ background:#2563eb; color:white; padding:10px 16px; border-radius:10px; text-decoration:none; display:flex; align-items:center; gap:8px; font-weight:600; }
.content{ padding:30px; max-width:1200px; margin:auto; }
.page-title{ font-size:34px; margin-bottom:8px; }
.page-sub{ color:var(--text2); margin-bottom:25px; }
.page-sub a{ color:var(--primary); text-decoration:none; font-weight:600; }
.card{ background:var(--card); border-radius:22px; padding:30px; box-shadow:var(--shadow); }
.form-grid{ display:grid; gap:20px; }
label{ font-weight:600; margin-bottom:8px; display:block; }
textarea, input, select{ width:100%; padding:15px; border-radius:14px; border:1px solid var(--border); background:var(--bg); color:var(--text); outline:none; font-size:15px; transition:border-color .2s; }
textarea:focus, input:focus, select:focus{ border-color:var(--primary); }
textarea{ resize:none; }
.form-section{ margin-top:10px; margin-bottom:5px; font-weight:700; font-size:18px; }
.alt-row{ display:flex; align-items:center; gap:12px; }
.alt-row input[type="radio"]{ width:auto; }
.alt-letra{ font-weight:700; min-width:30px; }
.btn-salvar{ background:var(--primary); color:white; border:none; padding:15px; border-radius:14px; font-size:16px; font-weight:600; cursor:pointer; margin-top:10px; width:100%; transition:.3s; }
.btn-salvar:hover{ opacity:.9; transform:translateY(-2px); }
</style>

<div class="layout">

<!-- SIDEBAR -->
<?= view('sistema/admin/_sidebar', ['ativo' => 'perguntas']) ?>

    <div class="main">

        <div class="content">

            <h1 class="page-title">Cadastrar nova questão</h1>
            <p class="page-sub">
                <a href="<?= base_url('/admin/perguntas') ?>">
                    <i class="fa-solid fa-arrow-left"></i> Voltar à lista
                </a>
            </p>

            <?php if (session()->getFlashdata('erro')): ?>
                <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:12px;padding:14px 18px;margin-bottom:20px;color:#991b1b;">
                    <?= session()->getFlashdata('erro') ?>
                </div>
            <?php endif; ?>

            <section class="card">
                <form action="<?= base_url('/admin/pergunta/inserir') ?>" method="post" class="form-grid">
                    <?= csrf_field() ?>

                    <div>
                        <label for="descricao">Enunciado da pergunta</label>
                        <textarea id="descricao" name="descricao" rows="4" placeholder="Digite a pergunta" required></textarea>
                    </div>

                    <div>
                        <label for="nivel">Nível de dificuldade</label>
                        <select id="nivel" name="nivel">
                            <option value="Fácil">Fácil</option>
                            <option value="Médio">Médio</option>
                            <option value="Difícil">Difícil</option>
                        </select>
                    </div>

                    <p class="form-section">Alternativas</p>

                    <div class="alt-row">
                        <input type="radio" name="correta" value="a" checked>
                        <span class="alt-letra">A)</span>
                        <input type="text" name="alt_a" placeholder="Alternativa A" required>
                    </div>

                    <div class="alt-row">
                        <input type="radio" name="correta" value="b">
                        <span class="alt-letra">B)</span>
                        <input type="text" name="alt_b" placeholder="Alternativa B" required>
                    </div>

                    <div class="alt-row">
                        <input type="radio" name="correta" value="c">
                        <span class="alt-letra">C)</span>
                        <input type="text" name="alt_c" placeholder="Alternativa C" required>
                    </div>

                    <div class="alt-row">
                        <input type="radio" name="correta" value="d">
                        <span class="alt-letra">D)</span>
                        <input type="text" name="alt_d" placeholder="Alternativa D" required>
                    </div>

                    <button type="submit" class="btn-salvar">
                        <i class="fa-solid fa-floppy-disk"></i> Salvar questão
                    </button>

                </form>
            </section>

        </div>
    </div>
</div>

<?= view('sistema/layout/footer_adm') ?>
