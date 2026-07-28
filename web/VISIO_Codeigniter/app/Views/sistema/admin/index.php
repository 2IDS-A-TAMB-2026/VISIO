<?= view('sistema/layout/header_adm') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
 
        :root {
            --bg: #f1f5f9;
            --card: #ffffff;
            --text: #0f172a;
            --text2: #64748b;
            --border: #e2e8f0;
 
            --primary: #2563eb;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
 
            --sidebar: #0f172a;
            --sidebar2: #111827;
 
            --shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
 
        body.dark {
 
            --bg: #0b1120;
            --card: #111827;
            --text: #f8fafc;
            --text2: #94a3b8;
            --border: #1e293b;
 
            --sidebar: #020617;
            --sidebar2: #0f172a;
 
            --shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
 
        body {
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            transition: 0.3s;
        }
 
        .layout {
            display: flex;
        }
 
        /* SIDEBAR */
 
        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar), var(--sidebar2));
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px;
            overflow-y: auto;
            z-index: 1000;
        }
 
        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
        }
 
        .logo-area img {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            object-fit: cover;
        }
 
        .logo-area h2 {
            color: white;
            font-size: 28px;
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
        }
 
        .menu li {
            margin-bottom: 10px;
        }
 
        .menu:hover {
            color: #fff;
             
        }
 
        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 15px;
            border-radius: 14px;
            text-decoration: none;
            color: #e2e8f0;
            transition: 0.3s;
            font-weight: 500;
        }
 
        .menu-item.active {
            background-color: #007bff; 
            color: #fff;
            border-left: 4px solid #0056b3;
            font-weight: bold; 
        }
 
        /* MAIN */
 
        .main {
            width: calc(100% - 280px);
            margin-left: 280px;
        }
 
        /* HEADER */
 
        header {
            height: 90px;
            background: var(--card);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
 
        .top-nav {
            width: 100%;
            max-width: 1600px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
 
        .nav-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
 
        .nav-left h1 {
            font-size: 24px;
        }
 
        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
            list-style: none;
        }
 
        .nav-links a {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            transition: 0.3s;
            
        }
 
        .nav-links a:hover {
            color: var(--primary);
        }
 
        #theme-toggle {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }
 
        #theme-toggle:hover {
            transform: scale(1.05);
        }
 
        /* CONTENT */
 
        .content {
            padding: 30px;
            max-width: 1600px;
            margin: auto;
        }
 
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
 
        .topbar h1 {
            font-size: 34px;
            margin-bottom: 8px;
        }
 
        .topbar p {
            color: var(--text2);
        }
 
        .profile-box {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--card);
            padding: 12px 18px;
            border-radius: 18px;
            box-shadow: var(--shadow);
        }
 
        .profile-box img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
 
        /* CARDS */
 
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
 
        .card {
            background: var(--card);
            border-radius: 22px;
            padding: 24px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
 
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            
        }
 
        .primary::before {
            background: var(--primary);
        }
 
        .success::before {
            background: var(--success);
        }
 
        .warning::before {
            background: var(--warning);
        }
 
        .danger::before {
            background: var(--danger);
        }
 
        .info::before {
            background: #06b6d4;
        }
 
        .card-primary {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
 
        .card-top i {
            width: 55px;
            height: 55px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
         
            font-size: 22px;
        }
 
        .primary i {
            background: var(--primary);
        }
 
        .success i {
            background: var(--success);
        }
 
        .warning i {
            background: var(--warning);
        }
 
        .danger i {
            background: var(--danger);
        }
 
        .info i {
            background: #06b6d4;
        }
 
        .card h3 {
            font-size: 14px;
            color: var(--text2);
            margin-bottom: 10px;
        }
 
        .card h2 {
            font-size: 35px;
            margin-bottom: 8px;
        }
 
        .trend {
            font-size: 14px;
            font-weight: 600;
        }
 
        .up {
            color: var(--success);
        }
 
        .down {
            color: var(--danger);
        }
 
        /* CHARTS */
 
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1.6fr;
            gap: 20px;
            margin-bottom: 30px;
        }
 
        .chart-card {
            background: var(--card);
            border-radius: 22px;
            padding: 25px;
            box-shadow: var(--shadow);
        }
 
        .chart-header {
            margin-bottom: 20px;
        }
 
        .chart-header h3 {
            margin-bottom: 5px;
        }
 
        .chart-header span {
            color: var(--text2);
        }
 
        canvas {
            width: 100% !important;
        }
 
        /* TABLE */
 
        .bottom-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
        }
 
        table {
            width: 100%;
            border-collapse: collapse;
        }
 
        table th {
            text-align: left;
            padding-bottom: 18px;
            color: #ffffff;
        }
 
        table td {
            padding: 15px 0;
            border-top: 1px solid var(--border);
        }
 
        .student {
            display: flex;
            align-items: center;
            gap: 12px;
        }
 
        .student img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
        }
 
        .badge {
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
 
        .gold {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
 
        .silver {
            background: rgba(148, 163, 184, 0.2);
            color: #94a3b8;
        }
 
        .bronze {
            background: rgba(180, 83, 9, 0.2);
            color: #b45309;
        }
 
        /* ── ALTO CONTRASTE: apenas preto, branco e amarelo ── */
        body.high-contrast .card::before               { background: #ffff00 !important; }
        body.high-contrast .card                       { background: #000 !important; border: 1px solid #ffff00 !important; }
        body.high-contrast .chart-card                 { background: #000 !important; border: 1px solid #ffff00 !important; }
        body.high-contrast .stats-grid .card h2,
        body.high-contrast .stats-grid .card h3,
        body.high-contrast .stats-grid .card span      { color: #ffff00 !important; }
        body.high-contrast .gold,
        body.high-contrast .silver,
        body.high-contrast .bronze                     { background: #000 !important; color: #ffff00 !important; border: 1px solid #ffff00 !important; }
        body.high-contrast table th                    { color: #ffff00 !important; background: #000 !important; border-bottom: 1px solid #ffff00 !important; }
        body.high-contrast table td                    { color: #fff !important; border-top: 1px solid rgba(255,255,0,0.3) !important; }
        body.high-contrast .trend.up                   { color: #ffff00 !important; }
        body.high-contrast .trend.down                 { color: #fff !important; }
        body.high-contrast .activities .activity       { border-bottom: 1px solid rgba(255,255,0,0.2) !important; }
 
        /* TOOLBAR (ações do admin) */
 
        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
 
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 10px 16px;
            box-shadow: var(--shadow);
        }
 
        .search-box i {
            color: var(--text2);
        }
 
        .search-box input {
            border: none;
            outline: none;
            background: transparent;
            color: var(--text);
            font-size: 14px;
            width: 220px;
        }
 
        .btn-action {
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            border-radius: 14px;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: 0.2s;
            color: #fff;
        }
 
        .btn-action:hover {
            transform: translateY(-2px);
        }
 
        .btn-pdf { background: var(--danger); }
        .btn-csv { background: var(--success); }
        .btn-refresh { background: var(--primary); }
 
        .last-update {
            font-size: 13px;
            color: var(--text2);
            display: flex;
            align-items: center;
            gap: 6px;
        }
 
        .no-results {
            text-align: center;
            color: var(--text2);
            padding: 20px 0;
        }
 
        /* IMPRESSÃO */
        @media print {
            .sidebar,
            .toolbar,
            #theme-toggle,
            .nav-links,
            .activities,
            .charts-grid:nth-of-type(2) {
                display: none !important;
            }
 
            .main {
                width: 100% !important;
                margin-left: 0 !important;
            }
 
            body, .card, .chart-card {
                box-shadow: none !important;
                background: #fff !important;
                color: #000 !important;
            }
 
            .content {
                padding: 0 !important;
            }
        }
 
        /* RESPONSIVO */
 
        @media(max-width: 1200px) {
 
            .charts-grid,
            .bottom-grid {
                grid-template-columns: 1fr;
            }
 
            .toolbar {
                width: 100%;
                justify-content: flex-start;
            }
        }
 
        @media(max-width: 900px) {
 
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
 
            .main {
                width: 100%;
                margin-left: 0;
            }
 
            .layout {
                flex-direction: column;
            }
 
            .top-nav {
                flex-direction: column;
                gap: 20px;
            }
 
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
 
            .content {
                padding: 20px;
            }
 
        }
 
    </style>
</head>
 
<body>
 
    <div class="layout">
 
        <!-- SIDEBAR -->
        <?= view('sistema/admin/_sidebar', ['ativo' => 'dashboard']) ?>
 
        <!-- MAIN -->
        <div class="main">
 
            <!-- CONTENT -->
            <div class="content" id="conteudo-pdf">
                <div class="topbar">
                    <div>
                        <h1>Dashboard Educacional</h1>
                        <p>Monitoramento completo da plataforma em tempo real.</p>
                    </div>
                    <div class="toolbar">
                        <span class="last-update" id="ultima-atualizacao">
                            <i class="fa-solid fa-clock"></i> Atualizado agora
                        </span>
                        <button type="button" class="btn-action btn-refresh" onclick="location.reload()">
                            <i class="fa-solid fa-rotate-right"></i> Atualizar
                        </button>
                        <button type="button" class="btn-action btn-csv" onclick="exportarRankingCSV()">
                            <i class="fa-solid fa-file-csv"></i> Exportar CSV
                        </button>
                        <button type="button" class="btn-action btn-pdf" onclick="gerarRelatorioPDF()">
                            <i class="fa-solid fa-file-pdf"></i> Imprimir / PDF
                        </button>
                    </div>
                    <div class="profile-box">
                        <img src="<?= !empty($admin['FOTO']) ? base_url($admin['FOTO']) : 'https://ui-avatars.com/api/?name=ADM&background=2563eb&color=fff&size=100' ?>"
                             onerror="this.src='https://ui-avatars.com/api/?name=ADM&background=2563eb&color=fff&size=100'" alt="">
                        <div>
                            <h4><?= esc($admin['NOME'] ?? '') !== '' ? esc($admin['NOME']) : 'Administrador VISIO' ?></h4>
                            <span>Gestão educacional</span>
                        </div>
                    </div>
                </div>
 
                <!-- CARDS -->
<section class="stats-grid">
 
    <div class="card primary">
        <div class="card-top">
            <div>
                <h3>Total de alunos</h3>
                <h2><?= esc($total_usuarios) ?></h2>
                <span class="trend">Usuários cadastrados</span>
                <br>
            </div>
            <i class="fa-solid fa-user-graduate"></i>
        </div>
    </div>
 
    <div class="card success">
        <div class="card-top">
            <div>
                <h3>Questões respondidas</h3>
                <h2><?= esc($total_respostas) ?></h2>
                <span class="trend">Total de respostas registradas</span>
                <br>
            </div>
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>
 
    <div class="card warning">
        <div class="card-top">
            <div>
                <h3>Taxa média de acertos</h3>
                <h2><?= esc($taxa_acerto) ?>%</h2>
                <span class="trend"><?= esc($total_acertos) ?> de <?= esc($total_respostas) ?> respostas corretas</span>
            
            </div>
            <br>
            <i class="fa-solid fa-chart-pie"></i>
        </div>
    </div>
 
    <div class="card danger">
        <div class="card-top">
            <div>
                <h3>Questões difíceis</h3>
                <h2><?= esc($total_perguntas_dificeis) ?></h2>
                <span class="trend down">Nível "Difícil" cadastradas</span>
            </div>
            <br>
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
    </div>
 
   
</section>
 
<style>
    /* Ícones brancos */
    .stats-grid i.fa-solid {
        color: #ffffff !important;
    }
</style>
 
                <!-- CHARTS -->
 
                <section class="charts-grid">
 
                    <div class="chart-card">
 
                        <div class="chart-header">
                            <h3>Desempenho dos alunos</h3>
                            <span>Evolução semanal</span>
                        </div>
 
                        <canvas id="performanceChart"></canvas>
 
                    </div>
 
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Acertos x Erros</h3>
                            <span>Questões respondidas</span>
                        </div>
                        <canvas id="questionsChart"></canvas>
                    </div>
                </section>
 
<section class="charts-grid">
 
    <!-- QUESTÕES MAIS ACERTADAS -->
 
    <div class="chart-card">
 
        <div class="chart-header">
            <h3>Perguntas mais acertadas</h3>
            <span>Maior taxa de acerto</span>
        </div>
 
        <canvas id="correctChart"></canvas>
 
    </div>
 
    <!-- QUESTÕES MAIS ERRADAS -->
 
    <div class="chart-card">
 
        <div class="chart-header">
            <h3>Perguntas mais erradas</h3>
            <span>Maior taxa de erro</span>
        </div>
 
        <canvas id="wrongChart"></canvas>
 
    </div>
 
</section>
 
<!-- RANKING + ATIVIDADES -->
 
<style>
    /* Usa variáveis do header_adm */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }
 
    .chart-card {
        background: var(--card);
        border-radius: 22px;
        padding: 25px;
        box-shadow: var(--shadow);
    }
 
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }
 
    .chart-header h3 {
        font-size: 1.125rem;
        color: var(--text);
        margin-bottom: 5px;
    }
 
    .chart-header span {
        font-size: 0.875rem;
        color: var(--text2);
    }
 
    /* TABELA */
    table {
        width: 100%;
        border-collapse: collapse;
    }
 
    table th {
        text-align: left;
        padding-bottom: 18px;
        color: var(--text2);
        border-bottom: 2px solid var(--border);
        font-size: 0.75rem;
        text-transform: uppercase;
    }
 
    table td {
        padding: 15px 0;
        border-top: 1px solid var(--border);
        color: var(--text);
    }
 
    .student {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: var(--text);
    }
 
    .student img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
    }
 
    .badge {
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }
 
    .gold {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }
 
    .silver {
        background: rgba(148, 163, 184, 0.2);
        color: #94a3b8;
    }
 
    .bronze {
        background: rgba(180, 83, 9, 0.2);
        color: #b45309;
    }
 
    /* Alto contraste */
    body.high-contrast .badge.gold,
    body.high-contrast .badge.silver,
    body.high-contrast .badge.bronze {
        background: #000 !important;
        color: #ffff00 !important;
        border: 1px solid #ffff00 !important;
    }
 
    @media(max-width: 1200px) {
        .bottom-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
 
<section class="bottom-grid">
 
    <!-- RANKING -->
    <div class="chart-card">
 
        <div class="chart-header">
            <div>
                <h3>Alunos destaque</h3>
                <span>Ranking de desempenho</span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="busca-aluno" placeholder="Pesquisar aluno..." onkeyup="filtrarRanking()">
            </div>
        </div>
 
        <table id="tabela-ranking">
 
            <thead>
 
                <tr>
                    <th style="color: #FFF;">Aluno</th>
                    <th style="color: #FFF;">Respostas</th>
                    <th style="color: #FFF;">Taxa de acerto</th>
                    <th style="color: #FFF;">Status</th>
                </tr>
 
            </thead>
 
            <tbody>
 
                <?php if (empty($ranking_usuarios)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; color: var(--text2);">
                        Nenhuma resposta registrada ainda.
                    </td>
                </tr>
                <?php else: ?>
                    <?php $medalhas = ['gold' => '1º Lugar', 'silver' => '2º Lugar', 'bronze' => '3º Lugar']; ?>
                    <?php $classes = array_keys($medalhas); ?>
                    <?php foreach ($ranking_usuarios as $i => $aluno): ?>
                    <tr data-nome="<?= esc(mb_strtolower($aluno['NOME'] ?: $aluno['EMAIL'])) ?>" data-email="<?= esc(mb_strtolower($aluno['EMAIL'])) ?>">
                        <td>
                            <div class="student">
                                <img src="<?= !empty($aluno['FOTO']) ? base_url($aluno['FOTO']) : 'https://ui-avatars.com/api/?name=' . esc(substr($aluno['NOME'] ?: $aluno['EMAIL'], 0, 1)) . '&background=3a86ff&color=fff&size=100' ?>"
                                     onerror="this.src='https://ui-avatars.com/api/?name=U&background=3a86ff&color=fff&size=100'">
                                <?php if (!empty($aluno['NOME'])): ?>
                                    <span><?= esc($aluno['NOME']) ?><br><small style="color:var(--text2);"><?= esc($aluno['EMAIL']) ?></small></span>
                                <?php else: ?>
                                    <?= esc($aluno['EMAIL']) ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?= esc($aluno['total']) ?></td>
                        <td><?= esc($aluno['taxa']) ?>%</td>
                        <td>
                            <?php if (isset($classes[$i])): ?>
                                <span class="badge <?= $classes[$i] ?>">
                                    <?= $medalhas[$classes[$i]] ?>
                                </span>
                            <?php else: ?>
                                &mdash;
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
 
            </tbody>
 
        </table>
 
        <p class="no-results" id="sem-resultados" style="display:none;">Nenhum aluno encontrado para essa busca.</p>
 
    </div>
 
    <!-- ATIVIDADES -->
    <div class="chart-card">
 
        <div class="chart-header">
            <h3>Atividades recentes</h3>
            <span>Últimas ações</span>
        </div>
 
        <div class="activities">
 
            <?php if (empty($atividades_recentes)): ?>
            <div class="activity">
                <i class="fa-solid fa-circle-info"></i>
                <div>
                    <h4>Sem atividades recentes</h4>
                    <p>Ainda não há respostas registradas no sistema.</p>
                </div>
            </div>
            <?php else: ?>
                <?php foreach ($atividades_recentes as $atividade): ?>
                <div class="activity">
 
                    <i class="fa-solid <?= $atividade['IS_CORRETA'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
 
                    <div>
 
                        <h4><?= $atividade['IS_CORRETA'] ? 'Resposta correta' : 'Resposta incorreta' ?></h4>
 
                        <p><?= esc($atividade['NOME'] ?: $atividade['EMAIL']) ?> respondeu: "<?= esc(mb_strimwidth($atividade['PERGUNTA_TEXTO'], 0, 60, '…')) ?>"</p>
 
                        <span><?= esc($atividade['TEMPO_RELATIVO']) ?></span>
 
                    </div>
 
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
 
        </div>
 
    </div>
 
</section>
 
            </div>
 
        </div>
 
    </div>
 
    
 
    <script>
 
        /* ── Script de gráficos ── */        /* ── Cores dos gráficos por modo ── */
        function getChartColors() {
            const hc = document.body.classList.contains('high-contrast');
            return {
                line:    hc ? '#ffff00' : '#2563eb',
                lineFill:hc ? 'rgba(255,255,0,0.15)' : 'rgba(37,99,235,0.1)',
                acerto:  hc ? '#ffffff' : '#22c55e',
                erro:    hc ? '#ffff00' : '#ef4444',
                barBlue: hc ? '#ffffff' : '#2563eb',
                barRed:  hc ? '#ffff00' : '#ef4444',
                tick:    hc ? '#ffff00' : '#64748b',
                grid:    hc ? 'rgba(255,255,0,0.2)' : 'rgba(0,0,0,0.05)',
            };
        }
 
        /* ── Instâncias dos gráficos ── */
        let perfChart, questChart, corrChart, wrongChart;
 
        function criarGraficos() {
            const c = getChartColors();
 
            perfChart = new Chart(document.getElementById('performanceChart'), {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($desempenho_semanal, 'label')) ?>,
                    datasets: [{
                        label: 'Acertos (%)',
                        data: <?= json_encode(array_column($desempenho_semanal, 'percentual')) ?>,
                        borderColor: c.line,
                        backgroundColor: c.lineFill,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: c.tick }, grid: { color: c.grid } },
                        y: { ticks: { color: c.tick }, grid: { color: c.grid }, suggestedMin: 0, suggestedMax: 100 }
                    }
                }
            });
 
            questChart = new Chart(document.getElementById('questionsChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Acertos', 'Erros'],
                    datasets: [{ data: [<?= (int) $total_acertos ?>, <?= (int) $total_erros ?>], backgroundColor: [c.acerto, c.erro] }]
                },
                options: { responsive: true }
            });
 
            corrChart = new Chart(document.getElementById('correctChart'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_map(
                        fn ($p) => mb_strimwidth($p['DESCRICAO'], 0, 25, '…'),
                        $perguntas_mais_acertadas
                    )) ?>,
                    datasets: [{
                        label: 'Taxa de acerto (%)',
                        data: <?= json_encode(array_column($perguntas_mais_acertadas, 'taxa')) ?>,
                        backgroundColor: c.barBlue,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: c.tick }, grid: { color: c.grid } },
                        y: { ticks: { color: c.tick }, grid: { color: c.grid }, suggestedMin: 0, suggestedMax: 100 }
                    }
                }
            });
 
            wrongChart = new Chart(document.getElementById('wrongChart'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_map(
                        fn ($p) => mb_strimwidth($p['DESCRICAO'], 0, 25, '…'),
                        $perguntas_mais_erradas
                    )) ?>,
                    datasets: [{
                        label: 'Taxa de erro (%)',
                        data: <?= json_encode(array_map(fn ($p) => 100 - $p['taxa'], $perguntas_mais_erradas)) ?>,
                        backgroundColor: c.barRed,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: c.tick }, grid: { color: c.grid } },
                        y: { ticks: { color: c.tick }, grid: { color: c.grid }, suggestedMin: 0, suggestedMax: 100 }
                    }
                }
            });
        }
 
        function atualizarCoresGraficos() {
            [perfChart, questChart, corrChart, wrongChart].forEach(ch => { if (ch) ch.destroy(); });
            criarGraficos();
        }
 
        /* Observa mudança de classe no body (tema e alto contraste vêm do footer_adm.php) */
        new MutationObserver(() => atualizarCoresGraficos())
            .observe(document.body, { attributes: true, attributeFilter: ['class'] });
 
        criarGraficos();
 
        /* ── Última atualização ── */
        function marcarUltimaAtualizacao() {
            const el = document.getElementById('ultima-atualizacao');
            if (!el) return;
            const agora = new Date();
            const hora = agora.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            el.innerHTML = `<i class="fa-solid fa-clock"></i> Atualizado às ${hora}`;
        }
        marcarUltimaAtualizacao();
 
        /* ── Pesquisa de alunos no ranking ── */
        function filtrarRanking() {
            const termo = document.getElementById('busca-aluno').value.trim().toLowerCase();
            const linhas = document.querySelectorAll('#tabela-ranking tbody tr');
            const semResultados = document.getElementById('sem-resultados');
            let visiveis = 0;
 
            linhas.forEach(linha => {
                const nome = linha.dataset.nome || '';
                const email = linha.dataset.email || '';
                const corresponde = !termo || nome.includes(termo) || email.includes(termo);
                linha.style.display = corresponde ? '' : 'none';
                if (corresponde) visiveis++;
            });
 
            if (semResultados) {
                semResultados.style.display = (visiveis === 0 && linhas.length > 0) ? 'block' : 'none';
            }
        }
 
        /* ── Exportar ranking em CSV ── */
        function exportarRankingCSV() {
            const linhas = document.querySelectorAll('#tabela-ranking tbody tr');
            let csv = 'Aluno;Email;Respostas;Taxa de acerto;Status\n';
 
            linhas.forEach(linha => {
                if (linha.style.display === 'none') return;
                const celulas = linha.querySelectorAll('td');
                if (celulas.length < 4) return;
 
                const nome = (linha.dataset.nome || '').replace(/;/g, ',');
                const email = (linha.dataset.email || '').replace(/;/g, ',');
                const respostas = celulas[1].innerText.trim();
                const taxa = celulas[2].innerText.trim();
                const status = celulas[3].innerText.trim().replace(/\n/g, ' ');
 
                csv += `${nome};${email};${respostas};${taxa};${status}\n`;
            });
 
            const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `ranking-alunos-${new Date().toISOString().slice(0, 10)}.csv`;
            link.click();
        }
 
        /* ── Gerar PDF do dashboard ── */
        function gerarRelatorioPDF() {
            const area = document.getElementById('conteudo-pdf');
            const botaoPdf = document.querySelector('.btn-pdf');
            const textoOriginal = botaoPdf.innerHTML;
            botaoPdf.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Gerando...';
            botaoPdf.disabled = true;
 
            html2canvas(area, { scale: 2, useCORS: true, backgroundColor: null }).then(canvas => {
                const { jsPDF } = window.jspdf;
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
 
                const larguraPagina = pdf.internal.pageSize.getWidth();
                const alturaPagina = pdf.internal.pageSize.getHeight();
                const alturaImagem = (canvas.height * larguraPagina) / canvas.width;
 
                let alturaRestante = alturaImagem;
                let posicaoY = 0;
 
                pdf.addImage(imgData, 'PNG', 0, posicaoY, larguraPagina, alturaImagem);
                alturaRestante -= alturaPagina;
 
                while (alturaRestante > 0) {
                    posicaoY = alturaRestante - alturaImagem;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, posicaoY, larguraPagina, alturaImagem);
                    alturaRestante -= alturaPagina;
                }
 
                pdf.save(`dashboard-educacional-${new Date().toISOString().slice(0, 10)}.pdf`);
                botaoPdf.innerHTML = textoOriginal;
                botaoPdf.disabled = false;
            }).catch(() => {
                botaoPdf.innerHTML = textoOriginal;
                botaoPdf.disabled = false;
                alert('Não foi possível gerar o PDF. Tente novamente.');
            });
        }
 
    </script>
 
<?= view('sistema/layout/footer_adm') ?>