<?= view('sistema/layout/header_adm') ?>

<style>
*{ margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
:root{ --bg:#f1f5f9; --card:#fff; --text:#0f172a; --text2:#64748b; --border:#e2e8f0; --primary:#2563eb; --danger:#ef4444; --sidebar:#0f172a; --sidebar2:#111827; --shadow:0 10px 25px rgba(0,0,0,.08); }
body.dark{ --bg:#0b1120;  --text:#f8fafc; --text2:#94a3b8; --border:#1e293b; --sidebar:#020617; --sidebar2:#0f172a; }
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
.card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
    }

    body.dark .card {
    background: #131b4f;
    border-color: #2662d9;
    box-shadow: none;
}
.logo-area { display: flex; align-items: center; gap: 15px; margin-bottom: 40px; }
.logo-area h2 { color: white; font-size: 28px; font-weight: 700; }
.menu-title { color: #64748b; text-transform: uppercase; font-size: 12px; margin-bottom: 15px; letter-spacing: 1px; }
.menu { list-style: none; }
.menu li { margin-bottom: 10px; }
.menu a { display: flex; align-items: center; gap: 14px; padding: 15px; border-radius: 14px; text-decoration: none; color: #e2e8f0; font-weight: 500; transition: .3s; }
.menu a:hover, .menu a.active { background: rgba(37,99,235,.2); }

.main { width: calc(100% - 280px); margin-left: 280px; }
.main{ width:calc(100% - 280px); margin-left:280px; }
header{ height:90px; background:var(--card); border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; padding:0 30px; position:sticky; top:0; z-index:999; box-shadow:0 4px 20px rgba(0,0,0,.04); }
.nav-right{ display:flex; align-items:center; gap:20px; }
#theme-toggle{ width:42px; height:42px; border:none; border-radius:12px; background:var(--bg); color:var(--text); cursor:pointer; font-size:16px; }
.profile-btn{ background:#2563eb; color:white; padding:10px 16px; border-radius:10px; text-decoration:none; display:flex; align-items:center; gap:8px; font-weight:600; }
.content{ padding:30px; max-width:1600px; margin:auto; }
.page-title{ font-size:34px; margin-bottom:8px; }
.page-sub{ color:var(--text2); margin-bottom:25px; }
.top-actions{ margin-bottom:25px; }
.btn-add{ background:var(--primary); color:white; padding:14px 20px; border-radius:14px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:10px; box-shadow:var(--shadow); }
.card{ background:var(--card); border-radius:22px; padding:25px; box-shadow:var(--shadow); }
table{ width:100%; border-collapse:collapse; }
thead{ background:var(--bg); }
th{ padding:14px 16px; text-align:left; font-size:12px; text-transform:uppercase; color:var(--text2); font-weight:600; border-bottom:2px solid var(--border); }
td{ padding:14px 16px; color:var(--text); border-bottom:1px solid var(--border); font-size:14px; }
tbody tr:hover{ background: rgba(37, 99, 235, 0.06); transition: background .15s; }
body.dark tbody tr:hover{ background: rgba(38, 98, 217, 0.1); }
body.high-contrast th{ color:#ff0 !important; background:#000 !important; border-color:#ff0 !important; }
body.high-contrast td{ color:#ff0 !important; background:#000 !important; border-color:#ff0 !important; }
body.high-contrast .card{ background:#000 !important; border:1px solid #ff0 !important; }
table{ width:100%; border-collapse:collapse; }



.input-table, select{ width:100%; padding:12px; border-radius:10px; border:1px solid var(--border); background:var(--bg); color:var(--text); outline:none; }
.td-alts{ min-width:350px; }
.alt-edit-row{ display:flex; align-items:center; gap:10px; margin-bottom:10px; }
.alt-label{ font-weight:700; min-width:25px; }
.btn-salvar{ background:#22c55e; color:white; border:none; padding:12px 16px; border-radius:10px; cursor:pointer; font-weight:600; margin-bottom:8px; width:100%; }
.btn-delete{ background:var(--danger); color:white; border:none; padding:12px 16px; border-radius:10px; cursor:pointer; font-weight:600; width:100%; }
</style>

<div class="layout">

<!-- SIDEBAR -->
<?= view('sistema/admin/_sidebar', ['ativo' => 'perguntas']) ?>

    <div class="main">

        <div class="content">

            <h1 class="page-title">Questões cadastradas</h1>
            <p class="page-sub">Gerencie as perguntas do quiz.</p>

            <div class="top-actions">
                <a href="<?= base_url('/admin/pergunta/nova') ?>" class="btn-add">
                    <i class="fa-solid fa-plus"></i> Nova questão
                </a>
            </div>

            <section class="card">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pergunta</th>
                            <th>Nível</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($perguntas as $p): ?>
                            <tr>
                                <td><?= esc($p['ID_PERGUNTA']) ?></td>
                                <td><?= esc($p['DESCRICAO']) ?></td>
                                <td><?= esc($p['NIVEL_DIFICULDADE'] ?? '—') ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <a href="<?= base_url('/admin/pergunta/editar/' . $p['ID_PERGUNTA']) ?>"
                                        style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; border-radius: 10px; font-weight: 600; color: white; background-color: #F6A316; text-decoration: none; text-align: center;">
                                            <i class="fa-solid fa-pen"></i> Editar
                                        </a>

                                        <form action="<?= base_url('/admin/pergunta/excluir/' . $p['ID_PERGUNTA']) ?>"
                                            method="POST"
                                            onsubmit="return confirm('Excluir esta questão?')"
                                            style="flex: 1; margin: 0;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-delete"
                                                    style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; border: none; cursor: pointer; padding: 10px; border-radius: 10px; font-weight: 600; color: white; background-color: #E84C4C;">
                                                <i class="fa-solid fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

        </div>
    </div>
</div>

<?= view('sistema/layout/footer_adm') ?>
