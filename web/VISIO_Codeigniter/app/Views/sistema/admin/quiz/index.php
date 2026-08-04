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

.main { width: calc(100% - 280px); margin-left: 280px; }
.content{ padding:30px; max-width:1600px; margin:auto; }
.page-title{ font-size:34px; margin-bottom:8px; }
.page-sub{ color:var(--text2); margin-bottom:25px; }

/* BARRA DE AÇÕES SUPERIOR */
.top-actions{ 
    display: flex; 
    align-items: center; 
    gap: 15px; 
    margin-bottom: 25px; 
}

.search-box {
    flex: 1;
    display: flex;
    align-items: center;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 0 16px;
    height: 48px;
    box-shadow: 0 2px 8px rgba(0,0,0,.02);
    transition: all .2s ease;
}
.search-box:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.search-box i { color: var(--text2); margin-right: 10px; }
.search-box input {
    border: none;
    background: transparent;
    outline: none;
    color: var(--text);
    width: 100%;
    font-size: 14px;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}
.filter-wrapper i.fa-filter {
    position: absolute;
    left: 18px;
    color: white;
    pointer-events: none;
    font-size: 14px;
    z-index: 1;
}

.btn-filter, .btn-add {
    height: 48px;
    padding: 0 20px;
    border-radius: 14px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: var(--primary);
    color: white;
    border: none;
    box-shadow: var(--shadow);
    text-decoration: none;
    cursor: pointer;
    transition: transform .2s, opacity .2s;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.btn-filter {
    padding-left: 42px;
    padding-right: 32px;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
}

.btn-filter option { background: var(--card); color: var(--text); }
.btn-filter:hover, .btn-add:hover { transform: translateY(-2px); opacity: 0.92; }

/* ESTILOS DAS BADGES DE NÍVEL */
.badge-nivel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100px;
    padding: 8px 0;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    text-transform: capitalize;
    text-align: center;
}

.badge-facil { background: #dcfce7; color: #15803d; }
.badge-medio { background: #fef3c7; color: #b45309; }
.badge-dificil { background: #fee2e2; color: #b91c1c; }
.badge-padrao { background: #f1f5f9; color: #64748b; }

body.dark .badge-facil { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
body.dark .badge-medio { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
body.dark .badge-dificil { background: rgba(239, 68, 68, 0.2); color: #f87171; }

.badge-id {
    font-weight: 700;
    color: var(--text2);
    background: var(--bg);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    display: inline-block;
}

/* AJUSTES E ALINHAMENTO DA TABELA */
.card{ background:var(--card); border-radius:22px; padding:20px; box-shadow:var(--shadow); }
table{ width:100%; border-collapse:separate; border-spacing:0; }

/* CABEÇALHO AZUL MODERNO E ALINHADO */
thead tr { background: var(--primary); }
th { 
    padding: 16px 20px; 
    font-size: 13px; 
    text-transform: uppercase; 
    color: white; 
    font-weight: 700; 
    letter-spacing: 0.5px;
}

/* Cantos arredondados no cabeçalho azul */
th:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
th:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

/* ALINHAMENTOS DAS COLUNAS (TH E TD) */
.col-id { width: 90px; text-align: center; }
.col-pergunta { text-align: left; padding-left: 25px; }
.col-nivel { width: 160px; text-align: center; }
.col-acoes { width: 230px; text-align: center; }

/* LINHAS DA TABELA */
td { 
    padding: 18px 20px; 
    color: var(--text); 
    border-bottom: 1px solid var(--border); 
    font-size: 15px; 
    vertical-align: middle;
}

tbody tr:last-child td { border-bottom: none; }
tbody tr { transition: background .15s; }
tbody tr:hover { background: rgba(37, 99, 235, 0.03); }

.no-results { text-align: center; padding: 30px !important; color: var(--text2); font-weight: 500; }
.btn-delete{ background:var(--danger); color:white; border:none; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:600; width:100%; }
</style>

<div class="layout">

<!-- SIDEBAR -->
<?= view('sistema/admin/_sidebar', ['ativo' => 'perguntas']) ?>

    <div class="main">

        <div class="content">

            <h1 class="page-title">Questões cadastradas</h1>
            <p class="page-sub">Gerencie as perguntas do quiz. <strong id="totalCount">(<?= count($perguntas) ?> encontradas)</strong></p>

            <!-- BARRA DE AÇÕES -->
            <div class="top-actions">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" onkeyup="filtrarTabela()" placeholder="Buscar pergunta...">
                </div>

                <div class="action-buttons">
                    <div class="filter-wrapper">
                        <i class="fa-solid fa-filter"></i>
                        <select id="filterNivel" onchange="filtrarTabela()" class="btn-filter">
                            <option value="">Todos os Níveis</option>
                            <option value="fácil">Fácil</option>
                            <option value="médio">Médio</option>
                            <option value="difícil">Difícil</option>
                        </select>
                    </div>

                    <a href="<?= base_url('/admin/pergunta/nova') ?>" class="btn-add">
                        <i class="fa-solid fa-plus"></i> Nova questão
                    </a>
                </div>
            </div>

            <section class="card">
                <table id="tabelaQuestoes">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-pergunta">PERGUNTA</th>
                            <th class="col-nivel">NÍVEL</th>
                            <th class="col-acoes">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($perguntas as $p): 
                            $nivel = strtolower(trim($p['NIVEL_DIFICULDADE'] ?? ''));
                            $badgeClass = 'badge-padrao';
                            $icone = '<i class="fa-solid fa-circle-question"></i>';

                            if (strpos($nivel, 'fácil') !== false || strpos($nivel, 'facil') !== false) {
                                $badgeClass = 'badge-facil';
                                $icone = '<i class="fa-solid fa-check"></i>';
                            } elseif (strpos($nivel, 'médio') !== false || strpos($nivel, 'medio') !== false) {
                                $badgeClass = 'badge-medio';
                                $icone = '<i class="fa-solid fa-bolt"></i>';
                            } elseif (strpos($nivel, 'difícil') !== false || strpos($nivel, 'dificil') !== false) {
                                $badgeClass = 'badge-dificil';
                                $icone = '<i class="fa-solid fa-fire"></i>';
                            }
                        ?>
                            <tr>
                                <td class="col-id"><span class="badge-id">#<?= esc($p['ID_PERGUNTA']) ?></span></td>
                                <td class="col-pergunta"><strong><?= esc($p['DESCRICAO']) ?></strong></td>
                                <td class="col-nivel">
                                    <span class="badge-nivel <?= $badgeClass ?>">
                                        <?= $icone ?> <?= esc($p['NIVEL_DIFICULDADE'] ?? 'N/D') ?>
                                    </span>
                                </td>
                                <td class="col-acoes">
                                    <div style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                                        <a href="<?= base_url('/admin/pergunta/editar/' . $p['ID_PERGUNTA']) ?>"
                                        style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 14px; border-radius: 10px; font-weight: 600; color: white; background-color: #F6A316; text-decoration: none; text-align: center; font-size: 13px;">
                                            <i class="fa-solid fa-pen"></i> Editar
                                        </a>

                                        <form action="<?= base_url('/admin/pergunta/excluir/' . $p['ID_PERGUNTA']) ?>"
                                            method="POST"
                                            onsubmit="return confirm('Excluir esta questão?')"
                                            style="flex: 1; margin: 0;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-delete"
                                                    style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; border: none; cursor: pointer; padding: 10px 14px; border-radius: 10px; font-weight: 600; color: white; background-color: #E84C4C; font-size: 13px;">
                                                <i class="fa-solid fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="4" class="no-results">
                                <i class="fa-solid fa-circle-exclamation" style="font-size: 20px; margin-bottom: 8px; display: block;"></i>
                                Nenhuma pergunta encontrada para os filtros selecionados.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </div>
    </div>
</div>

<script>
function filtrarTabela() {
    const searchFilter = document.getElementById("searchInput").value.toLowerCase();
    const nivelFilter = document.getElementById("filterNivel").value.toLowerCase();
    const rows = document.querySelectorAll("#tabelaQuestoes tbody tr:not(#noResultsRow)");
    const noResultsRow = document.getElementById("noResultsRow");
    const totalCount = document.getElementById("totalCount");

    let visiveis = 0;

    rows.forEach(row => {
        const textoPergunta = row.children[1]?.textContent.toLowerCase() || '';
        const nivelPergunta = row.children[2]?.textContent.toLowerCase() || '';

        const bateuBusca = textoPergunta.includes(searchFilter);
        const bateuNivel = nivelFilter === '' || nivelPergunta.includes(nivelFilter);

        if (bateuBusca && bateuNivel) {
            row.style.display = "";
            visiveis++;
        } else {
            row.style.display = "none";
        }
    });

    if (visiveis === 0) {
        noResultsRow.style.display = "";
    } else {
        noResultsRow.style.display = "none";
    }

    totalCount.textContent = `(${visiveis} encontrada${visiveis !== 1 ? 's' : ''})`;
}
</script>

<?= view('sistema/layout/footer_adm') ?>