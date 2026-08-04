<?= view('sistema/layout/header_adm') ?>

<style>
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

    /* Layout */
    .layout {
        display: flex;
        min-height: 100vh;
        width: 100%;
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

    /* CORREÇÃO DO SCROLL AQUI */
    .main {
        margin-left: 280px;
        flex: 1;
        padding: 30px;
        background: #f8fafc;
        min-height: 100vh;
        overflow-y: auto; /* Permite rolar para baixo para ver todas as fotos */
    }

    body.dark .main {
        background: linear-gradient(135deg, #17182c 0%, #131b4f 100%);
    }

    /* Topbar */
    .topbar {
        margin-bottom: 25px;
    }

    .topbar h1 {
        font-size: 32px;
        color: #0f172a;
        margin-bottom: 8px;
    }

    body.dark .topbar h1 {
        color: #d4e0f7;
    }

    .topbar p {
        color: #64748b;
        font-size: 15px;
    }

    body.dark .topbar p {
        color: #8592ad;
    }

    /* === BARRA DE AÇÕES (BUSCA + FILTRO + BOTÃO ADICIONAR) === */
    .top-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 260px;
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0 16px;
        height: 45px;
        transition: all 0.3s ease;
    }

    body.dark .search-box {
        background: #131b4f;
        border-color: #2662d9;
    }

    .search-box:focus-within {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(30, 107, 231, 0.15);
    }

    .search-box i {
        color: #64748b;
        margin-right: 10px;
    }

    body.dark .search-box i {
        color: #8592ad;
    }

    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        color: #0f172a;
        width: 100%;
        font-size: 14px;
    }

    body.dark .search-box input {
        color: #d4e0f7;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Select de Filtro Estilizado */
    .filter-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-wrapper i.fa-filter {
        position: absolute;
        left: 14px;
        color: white;
        pointer-events: none;
        font-size: 13px;
        z-index: 1;
    }

    .btn-filter {
        height: 45px;
        padding: 0 32px 0 36px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        background-color: var(--color-primary);
        color: white;
        border: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background-color: var(--color-primary-dark);
    }

    .btn-filter option {
        background: white;
        color: #0f172a;
    }

    body.dark .btn-filter option {
        background: #131b4f;
        color: #d4e0f7;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 45px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-accent));
        color: white;
        padding: 0 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background: var(--color-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 107, 231, 0.3);
    }

    /* Card da tabela */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 40px; /* Margem para dar respiro na rolagem do final da página */
    }

    body.dark .card {
        background: #131b4f;
        border-color: #2662d9;
        box-shadow: none;
    }

    /* Tabela */
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
        vertical-align: middle;
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

    td span {
        color: #64748b;
    }

    body.dark td span {
        color: #8592ad;
    }

    /* CÉLULAS E FOTOS */
    .cell-img {
        width: 140px;
        text-align: center;
    }

    .sensor-img {
        width: 120px;
        height: 120px;
        border-radius: 16px;
        object-fit: cover;
        border: 3px solid #e2e8f0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
        max-width: 300px;
    }

    .cell-circuito {
        min-width: 120px;
    }

    .cell-acoes {
        width: 220px;
        text-align: center;
    }

    /* Botões de Ação */
    .actions-btns {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .actions-btns > div {
        width: 100%;
    }

    .btn-edit, .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 14px !important;
        font-size: 13px !important;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: opacity 0.2s;
    }

    .btn-edit:hover, .btn-delete:hover {
        opacity: 0.9;
    }

    tbody tr:hover {
        background: rgba(37, 99, 235, 0.06);
    }

    body.dark tbody tr:hover {
        background: rgba(38, 98, 217, 0.1);
    }

    .no-results {
        text-align: center;
        padding: 25px !important;
        color: #64748b;
    }

    body.dark .no-results {
        color: #8592ad;
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
            padding: 15px;
        }

        .card {
            overflow-x: auto;
        }

        table {
            min-width: 900px;
        }
    }
</style>

<div class="layout">
    <?= view('sistema/admin/_sidebar', ['ativo' => 'sensores']) ?>

    <div class="main">
        <div class="content">

            <div class="topbar">
                <h1>Sensores</h1>
                <p>Gerencie os sensores cadastrados no sistema. <strong id="totalCount">(<?= count($sensores) ?> encontrados)</strong></p>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <!-- BARRA DE AÇÕES COM LUPA E FILTRO -->
            <div class="top-actions">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" onkeyup="filtrarSensores()" placeholder="Buscar por nome ou descrição...">
                </div>

                <div class="action-buttons">
                    <div class="filter-wrapper">
                        <i class="fa-solid fa-filter"></i>
                        <select id="filterCircuito" onchange="filtrarSensores()" class="btn-filter">
                            <option value="">Todos os Circuitos</option>
                            <option value="com_circuito">Com Circuito</option>
                            <option value="sem_circuito">Sem Circuito</option>
                        </select>
                    </div>

                    <a href="<?= base_url('/admin/sensor/novo') ?>" class="btn-add">
                        <i class="fa-solid fa-plus"></i> Novo sensor
                    </a>
                </div>
            </div>

            <section class="card">
                <table id="tabelaSensores">
                    <thead>
                        <tr>
                            <th style="width: 70px; text-align: center;">ID</th>
                            <th class="cell-img">Foto</th>
                            <th class="cell-nome">Nome</th>
                            <th class="cell-descricao">Descrição</th>
                            <th class="cell-circuito">Circuito</th>
                            <th class="cell-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sensores)): ?>
                            <tr>
                                <td colspan="6" class="no-results">
                                    Nenhum sensor cadastrado. <a href="<?= base_url('/admin/sensor/novo') ?>">Cadastre o primeiro!</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sensores as $s): ?>
                                <tr>
                                    <td style="text-align: center; font-weight: 600;"><?= esc($s['ID_SENSOR']) ?></td>
                                    <td class="cell-img">
                                        <?php if (!empty($s['FOTO'])): ?>
                                            <img src="<?= base_url($s['FOTO']) ?>" alt="Foto" class="sensor-img" onerror="this.src='<?= base_url('assets/images/placeholder.png') ?>'">
                                        <?php else: ?>
                                            <span>—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="cell-nome"><strong><?= esc($s['NOME']) ?></strong></td>
                                    <td class="cell-descricao" style="white-space: normal; line-height: 1.4;"><?= esc($s['DESCRICAO']) ?></td>
                                    <td class="cell-circuito"><?= esc($s['CIRCUITO'] ?: '—') ?></td>
                                    <td class="cell-acoes">
                                        <div class="actions-btns">
                                            <div style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                                                <a href="<?= base_url('/admin/sensor/editar/' . $s['ID_SENSOR']) ?>" class="btn-edit"
                                                style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 14px; border-radius: 8px; font-weight: 600; color: white; background-color: #F6A316; text-decoration: none; text-align: center;">
                                                    <i class="fa-solid fa-pen"></i> Editar
                                                </a>

                                                <form action="<?= base_url('/admin/sensor/excluir/' . $s['ID_SENSOR']) ?>"
                                                    method="POST"
                                                    onsubmit="return confirm('Excluir o sensor <?= esc($s['NOME']) ?>?')"
                                                    style="flex: 1; margin: 0;">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-delete"
                                                            style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; border: none; cursor: pointer; padding: 10px 14px; border-radius: 8px; font-weight: 600; color: white; background-color: #E84C4C;">
                                                        <i class="fa-solid fa-trash"></i> Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Linha para quando a busca não encontrar resultados -->
                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="6" class="no-results">
                                <i class="fa-solid fa-circle-exclamation" style="font-size: 18px; margin-bottom: 6px; display: block;"></i>
                                Nenhum sensor encontrado para a pesquisa selecionada.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </div><!-- /.content -->
    </div><!-- /.main -->
</div><!-- /.layout -->

<script>
function filtrarSensores() {
    const searchInput = document.getElementById("searchInput").value.toLowerCase();
    const filterCircuito = document.getElementById("filterCircuito").value;
    const rows = document.querySelectorAll("#tabelaSensores tbody tr:not(#noResultsRow)");
    const noResultsRow = document.getElementById("noResultsRow");
    const totalCount = document.getElementById("totalCount");

    let visiveis = 0;

    rows.forEach(row => {
        const nome = row.children[2]?.textContent.toLowerCase() || '';
        const descricao = row.children[3]?.textContent.toLowerCase() || '';
        const circuito = row.children[4]?.textContent.trim() || '';

        const bateuBusca = nome.includes(searchInput) || descricao.includes(searchInput);
        
        let bateuCircuito = true;
        if (filterCircuito === 'com_circuito') {
            bateuCircuito = circuito !== '' && circuito !== '—';
        } else if (filterCircuito === 'sem_circuito') {
            bateuCircuito = circuito === '' || circuito === '—';
        }

        if (bateuBusca && bateuCircuito) {
            row.style.display = "";
            visiveis++;
        } else {
            row.style.display = "none";
        }
    });

    if (noResultsRow) {
        noResultsRow.style.display = (visiveis === 0 && rows.length > 0) ? "" : "none";
    }

    if (totalCount) {
        totalCount.textContent = `(${visiveis} encontrado${visiveis !== 1 ? 's' : ''})`;
    }
}
</script>

<?= view('sistema/layout/footer_adm') ?>