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
    }

    .sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: linear-gradient(180deg, #0f172a, #111827);
        padding: 25px;
    }

    .main {
        margin-left: 280px;
        flex: 1;
        padding: 30px;
        background: #f8fafc;
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

    /* Botão adicionar */
    .top-actions {
        margin-bottom: 20px;
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

    /* === CÉLULA & IMAGEM (MAIOR) === */
    .cell-img {
        width: 140px; /* Aumentado */
        text-align: center;
    }

    .sensor-img {
        width: 120px; /* Aumentado de 100px para 120px */
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

    /* Célula nome */
    .cell-nome {
        min-width: 150px;
    }

    /* Célula descrição */
    .cell-descricao {
        min-width: 200px;
        max-width: 300px;
    }

    /* Célula circuito */
    .cell-circuito {
        min-width: 120px;
    }

    /* Célula ações */
    .cell-acoes {
        width: 180px;
        text-align: center;
    }

    /* Botões de ação */
    .actions-btns {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .btn-edit, .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 12px;
    }

    .btn-edit {
        background: #f59e0b;
        color: white;
    }

    .btn-edit:hover {
        background: #d97706;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    /* Linha hover */
    tbody tr:hover {
        background: rgba(37, 99, 235, 0.06);
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
            padding: 15px;
        }
        
        .card {
            overflow-x: auto;
        }
        
        table {
            min-width: 900px;
        }
    }

    /* =========================================
       ESTILO PARA CAMPOS (INPUT/FORM)
       Adicione isso para melhorar inputs
       ========================================= */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    body.dark .form-label {
        color: #cbd5e1;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        font-size: 15px;
        color: #0f172a;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        outline: none;
        transition: all 0.3s ease;
    }

    body.dark .form-input {
        background-color: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }

    .form-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(30, 107, 231, 0.15);
    }

    .form-input::placeholder {
        color: #94a3b8;
    }

    body.dark .form-input::placeholder {
        color: #64748b;
    }

    textarea.form-input {
        min-height: 120px;
        resize: vertical;
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

            <div class="top-actions">
                <a href="<?= base_url('/admin/sensor/novo') ?>" class="btn-add">
                    <i class="fa-solid fa-plus"></i> Novo sensor
                </a>
            </div>

            <section class="card">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <!-- Header da coluna agora maior -->
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
                                <td colspan="6" style="text-align:center;padding:25px;color:#64748b;">
                                    Nenhum sensor cadastrado. <a href="<?= base_url('/admin/sensor/novo') ?>">Cadastre o primeiro!</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sensores as $s): ?>
                                <tr>
                                    <td><?= esc($s['ID_SENSOR']) ?></td>
                                    <td class="cell-img">
                                        <?php if (!empty($s['FOTO'])): ?>
                                            <img src="<?= base_url($s['FOTO']) ?>" alt="Foto" class="sensor-img" onerror="this.src='<?= base_url('assets/images/placeholder.png') ?>'">
                                        <?php else: ?>
                                            <span>—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="cell-nome"><strong><?= esc($s['NOME']) ?></strong></td>
                                    <!-- Adicionei style para quebrar texto se muito longo -->
                                    <td class="cell-descricao" style="white-space: normal; line-height: 1.4;"><?= esc($s['DESCRICAO']) ?></td>
                                    <td class="cell-circuito"><?= esc($s['CIRCUITO'] ?: '—') ?></td>
                                    <td class="cell-acoes">
                                        <div class="actions-btns">
                                            <div style="display: flex; gap: 8px; align-items: center;">
                                                <a href="<?= base_url('/admin/sensor/editar/' . $s['ID_SENSOR']) ?>" class="btn-edit"
                                                style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 18px; font-size: 15px; border-radius: 10px; font-weight: 600; color: white; background-color: #F6A316; text-decoration: none; text-align: center;">
                                                    <i class="fa-solid fa-pen"></i> Editar
                                                </a>

                                                <form action="<?= base_url('/admin/sensor/excluir/' . $s['ID_SENSOR']) ?>"
                                                    method="POST"
                                                    onsubmit="return confirm('Excluir o sensor <?= esc($s['NOME']) ?>?')"
                                                    style="flex: 1; margin: 0;">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-delete"
                                                            style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; border: none; cursor: pointer; padding: 12px 18px; font-size: 15px; border-radius: 10px; font-weight: 600; color: white; background-color: #E84C4C;">
                                                        <i class="fa-solid fa-trash"></i> Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

        </div><!-- /.content -->
    </div><!-- /.main -->
</div><!-- /.layout -->

<?= view('sistema/layout/footer_adm') ?>