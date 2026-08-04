<?= view('sistema/layout/header_adm') ?>

<style>
    /* === RESET GLOBAL DE ROLAGEM === */
    html, body {
        height: auto !important;
        min-height: 100% !important;
        overflow-x: hidden !important;
        overflow-y: auto !important; /* Libera a barra de rolagem da página */
    }

    /* === PALETA DE CORES === */
    :root {
        --color-surface-dark: #17182c;
        --color-surface-card: #131b4f;
        --color-surface-btn: #2a3472;
        --color-primary: #1e6be7;
        --color-primary-hover: #47cdfd;
        --color-primary-dark: #1557c0;
        --color-primary-accent: #2662d9;
        --color-btn-grad-text: #d4e0f7;
        --color-btn-grad-before: #8592ad;
        --radius-std: 12px;
    }

    /* === LAYOUT SEM LIMITES DE ALTURA TRAVADOS === */
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
        padding: 35px 40px 100px 40px; /* Margem inferior generosa para você rolar até o fim sem cortar nada */
        min-height: 100vh;
        height: auto !important; /* Garante que cresce conforme o número de linhas */
        background: #f8fafc;
    }

    body.dark .main {
        background: linear-gradient(135deg, #17182c 0%, #131b4f 100%);
    }

    .content {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* === TOPBAR === */
    .topbar {
        margin-bottom: 25px;
    }

    .topbar h1 {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    body.dark .topbar h1 {
        color: #d4e0f7;
    }

    .topbar p {
        color: #64748b;
        font-size: 14px;
    }

    body.dark .topbar p {
        color: #8592ad;
    }

    /* === BARRA DE AÇÕES (BOTÃO + PESQUISA) === */
    .top-actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-accent));
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(30, 107, 231, 0.2);
    }

    .btn-add:hover {
        background: var(--color-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 107, 231, 0.35);
    }

    /* Input de Pesquisa */
    .search-wrapper {
        position: relative;
        flex: 1;
        max-width: 380px;
        min-width: 250px;
    }

    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 40px;
        font-size: 14px;
        color: #0f172a;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        outline: none;
        transition: all 0.2s ease;
    }

    body.dark .search-input {
        background-color: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }

    .search-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(30, 107, 231, 0.15);
    }

    /* === CARD DA TABELA (SEM OVERFLOW HIDDEN) === */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        /* Removido o overflow: hidden que impedia o scroll */
    }

    body.dark .card {
        background: #131b4f;
        border-color: #2662d9;
        box-shadow: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: var(--color-primary);
    }

    body.dark thead {
        background: linear-gradient(135deg, #0b1b3d, #08142b);
    }

    th {
        padding: 16px 14px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        color: white;
        font-weight: 600;
        border: none;
    }

    /* Bordas arredondadas no topo da tabela sem usar overflow hidden */
    thead tr th:first-child {
        border-top-left-radius: 16px;
    }
    thead tr th:last-child {
        border-top-right-radius: 16px;
    }

    td {
        padding: 16px;
        color: #0f172a;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    body.dark td {
        color: #d4e0f7;
        border-bottom-color: #2662d9;
    }

    td strong {
        color: #0f172a;
    }

    body.dark td strong {
        color: #d4e0f7;
    }

    /* Células e Fotos */
    .cell-img {
        width: 120px;
        text-align: center;
    }

    .sensor-img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s ease;
    }

    .sensor-img:hover {
        transform: scale(1.05);
    }

    body.dark .sensor-img {
        border-color: #2662d9;
    }

    .cell-nome {
        min-width: 150px;
    }

    .cell-descricao {
        min-width: 200px;
        max-width: 320px;
        white-space: normal;
        line-height: 1.4;
    }

    .cell-circuito {
        min-width: 120px;
    }

    .cell-acoes {
        width: 200px;
        text-align: center;
    }

    /* Botões de Ação */
    .actions-btns {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .btn-edit, .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        color: white;
    }

    .btn-edit {
        background-color: #f59e0b;
    }

    .btn-edit:hover {
        background-color: #d97706;
    }

    .btn-delete {
        background-color: #ef4444;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    tbody tr:hover {
        background: rgba(37, 99, 235, 0.04);
    }

    body.dark tbody tr:hover {
        background: rgba(38, 98, 217, 0.1);
    }

    /* Responsivo */
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
            overflow-x: auto;
        }
        
        table {
            min-width: 800px;
        }

        .top-actions-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrapper {
            max-width: 100%;
        }
    }
</style>

<div class="layout">
    <?= view('sistema/admin/_sidebar', ['ativo' => 'sensores']) ?>

    <div class="main">
        <div class="content">

            <div class="topbar">
                <h1>Sensores</h1>
                <p>Gerencie os sensores cadastrados no sistema.</p>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <!-- BARRA DE AÇÕES (BOTÃO E PESQUISA LADO A LADO) -->
            <div class="top-actions-bar">
                <a href="<?= base_url('/admin/sensor/novo') ?>" class="btn-add">
                    <i class="fa-solid fa-plus"></i> Novo sensor
                </a>

                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Pesquisar por nome, ID..." 
                           class="search-input" 
                           maxlength="100">
                </div>
            </div>

            <section class="card">
                <table id="tableSensores">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th class="cell-img">Foto</th>
                            <th class="cell-nome">Nome</th>
                            <th class="cell-descricao">Descrição</th>
                            <th class="cell-circuito">Circuito</th>
                            <th class="cell-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sensores)): ?>
                            <tr class="no-data">
                                <td colspan="6" style="text-align:center; padding:30px; color:#64748b;">
                                    Nenhum sensor cadastrado. <a href="<?= base_url('/admin/sensor/novo') ?>">Cadastre o primeiro!</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sensores as $s): ?>
                                <tr class="sensor-row">
                                    <td><strong>#<?= esc($s['ID_SENSOR']) ?></strong></td>
                                    <td class="cell-img">
                                        <?php if (!empty($s['FOTO'])): ?>
                                            <img src="<?= base_url($s['FOTO']) ?>" alt="Foto" class="sensor-img" onerror="this.src='<?= base_url('assets/images/placeholder.png') ?>'">
                                        <?php else: ?>
                                            <span style="color: #94a3b8;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="cell-nome">
                                        <strong><?= esc($s['NOME']) ?></strong>
                                    </td>
                                    <td class="cell-descricao">
                                        <?= esc($s['DESCRICAO']) ?>
                                    </td>
                                    <td class="cell-circuito">
                                        <?= esc($s['CIRCUITO'] ?: '—') ?>
                                    </td>
                                    <td class="cell-acoes">
                                        <div class="actions-btns">
                                            <a href="<?= base_url('/admin/sensor/editar/' . $s['ID_SENSOR']) ?>" class="btn-edit" title="Editar">
                                                <i class="fa-solid fa-pen"></i> Editar
                                            </a>

                                            <form action="<?= base_url('/admin/sensor/excluir/' . $s['ID_SENSOR']) ?>"
                                                  method="POST"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir o sensor <?= esc($s['NOME']) ?>?')"
                                                  style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-delete" title="Excluir">
                                                    <i class="fa-solid fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="6" style="text-align:center; padding: 25px; color:#64748b;">
                                <i class="fa-solid fa-magnifying-glass" style="margin-right: 8px;"></i> Nenhum sensor encontrado para a sua pesquisa.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </div><!-- /.content -->
    </div><!-- /.main -->
</div><!-- /.layout -->

<script>
    // === SCRIPT DE PESQUISA EM TEMPO REAL ===
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('.sensor-row');
        const noResultsRow = document.getElementById('noResultsRow');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                let hasMatch = false;

                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    if (text.includes(term)) {
                        row.style.display = '';
                        hasMatch = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (noResultsRow) {
                    noResultsRow.style.display = (hasMatch || rows.length === 0) ? 'none' : '';
                }
            });
        }
    });
</script>

<?= view('sistema/layout/footer_adm') ?>