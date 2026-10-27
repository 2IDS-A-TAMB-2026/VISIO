<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VISIO</title>

  <link rel="icon" href="<?= base_url('assets/images/logos/Simbolo/SimboloDark2.png') ?>">
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/botao_style.css') ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
  }

  :root {
    --bg: #f1f5f9;
    --card: #fff;
    --text: #0f172a;
    --text2: #64748b;
    --border: #e2e8f0;
    --primary: #2563eb;
    --danger: #ef4444;
    --warning: #f59e0b;
    --sidebar: #0f172a;
    --sidebar2: #111827;
    --shadow: 0 10px 25px rgba(0, 0, 0, .08);
  }

  body.dark {
    --bg: #0b1120;
    --card: #111827;
    --text: #f8fafc;
    --text2: #94a3b8;
    --border: #1e293b;
    --sidebar: #020617;
    --sidebar2: #0f172a;
  }

  body {
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    transition: background-color .3s, color .3s;
  }

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
    background: linear-gradient(180deg, var(--sidebar), var(--sidebar2));
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
    color: white;
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
    font-size: 15px;
  }

  .menu a:hover,
  .menu a.active {
    background: rgba(37, 99, 235, .2);
  }

  .main {
    width: calc(100% - 280px);
    margin-left: 280px;
  }

  /* HEADER FIXO - Não altera o tamanho com o controle de fonte */
  header {
    height: 90px;
    background: var(--card);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    position: sticky;
    top: 0;
    z-index: 999;
    box-shadow: 0 4px 20px rgba(0, 0, 0, .04);
    font-size: 16px !important;
  }

  header * {
    font-size: initial;
  }

  .nav-right {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 20px;
    list-style: none;
    position: relative;
  }

  .nav-links a {
    text-decoration: none;
    color: var(--text);
    font-weight: 600;
    font-size: 15px !important;
    transition: 0.3s;
  }

  .nav-links a:hover {
    color: var(--primary);
  }

  /* Rotação do ícone de engrenagem */
  #accessibility-btn i {
    transition: transform 0.4s ease-in-out;
    display: inline-block;
  }

  #accessibility-btn.ativo i {
    transform: rotate(180deg);
  }

  /* Botões do painel de acessibilidade */
  #accessibility-panel .btn-panel-access {
    width: 100% !important;
    height: 48px !important;
    min-height: 48px !important;
    box-sizing: border-box !important;
    padding: 0 16px !important;
    margin: 0 0 10px 0 !important;
    border: none !important;
    border-radius: 12px !important;
    background: #111827 !important;
    color: #ffffff !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 12px !important;
    white-space: nowrap !important;
    transition: background 0.2s ease, transform 0.1s ease !important;
  }

  #accessibility-panel .btn-panel-access span {
    font-size: 14px !important;
  }

  #accessibility-panel .btn-panel-access:last-child {
    margin-bottom: 0 !important;
  }

  #accessibility-panel .btn-panel-access:hover {
    background: #1f2937 !important;
  }

  #accessibility-panel .btn-panel-access i {
    width: 20px !important;
    text-align: center !important;
    font-size: 16px !important;
    color: #ffffff !important;
    flex-shrink: 0 !important;
  }

  .profile-btn {
    background: #2563eb;
    color: white;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 14px !important;
  }

  .content {
    padding: 30px;
    max-width: 1600px;
    margin: auto;
  }

  .topbar {
    margin-bottom: 30px;
  }

  .topbar h1 {
    font-size: 2.125rem;
    margin-bottom: 8px;
  }

  .topbar p {
    color: var(--text2);
    font-size: 1rem;
  }

  .top-actions {
    margin-bottom: 25px;
  }

  .btn-add {
    background: var(--primary);
    color: white;
    padding: 14px 20px;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: var(--shadow);
    font-size: 1rem;
  }

  .card {
    background: var(--card);
    border-radius: 22px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: var(--shadow);
  }

  .form-grid {
    display: grid;
    gap: 18px;
  }

  label {
    font-weight: 600;
    font-size: 1rem;
  }

  input,
  textarea {
    width: 100%;
    padding: 15px;
    border-radius: 14px;
    border: 1px solid var(--border);
    outline: none;
    font-size: 0.9375rem;
    background: var(--bg);
    color: var(--text);
  }

  textarea {
    resize: vertical;
  }

  .btn-primary {
    background: var(--primary);
    color: white;
    border: none;
    padding: 14px 20px;
    border-radius: 14px;
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
  }

  .table-wrapper {
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1rem;
  }

  thead {
    background: #0f172a;
  }

  thead th {
    padding: 18px;
    color: white;
    text-align: left;
    font-size: 1rem;
  }

  tbody td {
    padding: 18px;
    border-bottom: 1px solid var(--border);
    font-size: 1rem;
  }

  .sensor-img {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    object-fit: cover;
  }

  .btn-edit {
    background: var(--warning);
    color: white;
    border: none;
    padding: 10px 14px;
    border-radius: 10px;
    cursor: pointer;
    margin-right: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: 0.875rem;
  }

  .btn-delete {
    background: var(--danger);
    color: white;
    border: none;
    padding: 10px 14px;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: 0.875rem;
  }
</style>

<body>

  <a href="#conteudo-principal" class="skip-link">Pular para o conteúdo</a>

  <header>
    <nav style="width: 100%; display: flex; justify-content: space-between; align-items: center; position: relative;">
      <img src="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>" class="logo" alt="Logo plataforma VISIO" id="logo" data-dark="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>" data-light="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>">

      <button class="hamburger" id="hamburger" aria-label="Abrir menu de navegação" aria-expanded="false" aria-controls="nav-links">
        <span></span><span></span><span></span>
      </button>

      <ul class="nav-links" id="nav-links" role="navigation" aria-label="Menu principal">
        <li>
          <a href="<?= base_url('/admin/perfil') ?>" style="background:#2563eb; color:white; padding:12px 18px; border-radius:10px; display:flex; align-items:center; gap:8px; text-decoration:none; font-weight:600; font-size:14px !important;">
            <i class="fa-solid fa-user-shield" style="color:white; font-size:14px !important;"></i>
            Perfil
          </a>
        </li>
        <li style="position: relative;">
          <button id="accessibility-btn" aria-label="Abrir menu de acessibilidade" aria-expanded="false" aria-controls="accessibility-panel" style="width:40px; height:40px; border:none; border-radius:12px; background:#2563eb; color:white; font-size:18px; cursor:pointer;">
            <i class="fa-solid fa-gear" style="font-size:18px !important;"></i>
          </button>

          <!-- PAINEL DE ACESSIBILIDADE -->
          <div id="accessibility-panel" aria-hidden="true" style="position:absolute; top:50px; right:0; width:260px; background:var(--card, #fff); border-radius:16px; padding:16px; display:none; z-index:9999; box-shadow:0 10px 25px rgba(0,0,0,.25); border: 1px solid var(--border);">

            <!-- AUMENTAR FONTE -->
            <button type="button" onclick="changeFontSize(1)" aria-label="Aumentar tamanho da fonte" class="btn-panel-access">
              <i class="fa-solid fa-magnifying-glass-plus" aria-hidden="true"></i>
              <span>Aumentar Fonte</span>
            </button>

            <!-- DIMINUIR FONTE -->
            <button type="button" onclick="changeFontSize(-1)" aria-label="Diminuir tamanho da fonte" class="btn-panel-access">
              <i class="fa-solid fa-magnifying-glass-minus" aria-hidden="true"></i>
              <span>Diminuir Fonte</span>
            </button>

            <!-- ALTERAR TEMA -->
            <button type="button" id="theme-toggle-btn" class="btn-panel-access">
              <i class="fa-solid fa-moon" id="icon-moon"></i>
              <i class="fa-solid fa-sun" id="icon-sun" style="display:none; color:#fff"></i>
              <span>Alterar Tema</span>
            </button>

            <!-- ALTO CONTRASTE -->
            <button type="button" onclick="toggleContrast()" id="btn-contraste" class="btn-panel-access">
              <i class="fa-solid fa-adjust"></i>
              <span>Alto Contraste</span>
            </button>

            <!-- LER PAGINA -->
            <button type="button" onclick="readPage()" class="btn-panel-access">
              <i class="fa-solid fa-volume-high"></i>
              <span>Ler Página</span>
            </button>

          </div>
        </li>
      </ul>
    </nav>
  </header>

  <div id="conteudo-principal" tabindex="-1" style="outline:none;"></div>

  <?= view('sistema/layout/_flash') ?>

  <script>
    /* ── Hamburger ── */
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    hamburger.addEventListener('click', () => {
      const aberto = navLinks.classList.toggle('aberta');
      hamburger.classList.toggle('aberto', aberto);
      hamburger.setAttribute('aria-expanded', aberto);
      if (aberto) { 
        accessibilityPanel.style.display = 'none'; 
        accessibilityBtn.classList.remove('ativo');
      }
    });

    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('aberta');
        hamburger.classList.remove('aberto');
        hamburger.setAttribute('aria-expanded', 'false');
      });
    });

    document.addEventListener('click', (e) => {
      if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
        navLinks.classList.remove('aberta');
        hamburger.classList.remove('aberto');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > 768) {
        navLinks.classList.remove('aberta');
        hamburger.classList.remove('aberto');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });

    /* ── Painel de acessibilidade com rotação da engrenagem (180 deg) ── */
    const accessibilityBtn = document.getElementById('accessibility-btn');
    const accessibilityPanel = document.getElementById('accessibility-panel');

    accessibilityBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const visivel = accessibilityPanel.style.display === 'block';
      
      accessibilityPanel.style.display = visivel ? 'none' : 'block';
      accessibilityBtn.setAttribute('aria-expanded', !visivel);
      accessibilityBtn.classList.toggle('ativo', !visivel);

      if (!visivel) {
        navLinks.classList.remove('aberta');
        hamburger.classList.remove('aberto');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('click', (e) => {
      if (!accessibilityBtn.contains(e.target) && !accessibilityPanel.contains(e.target)) {
        accessibilityPanel.style.display = 'none';
        accessibilityBtn.setAttribute('aria-expanded', 'false');
        accessibilityBtn.classList.remove('ativo');
      }
    });

    /* ── Alternar Tema Claro/Escuro ── */
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const iconMoon = document.getElementById('icon-moon');
    const iconSun = document.getElementById('icon-sun');

    function updateThemeIcons(isDark) {
      if (iconMoon && iconSun) {
        iconMoon.style.display = isDark ? 'none' : 'inline-block';
        iconSun.style.display = isDark ? 'inline-block' : 'none';
      }
    }

    if (localStorage.getItem('visio_theme') === 'dark') {
      document.body.classList.add('dark');
      updateThemeIcons(true);
    }

    if (themeToggleBtn) {
      themeToggleBtn.addEventListener('click', () => {
        const isDark = document.body.classList.toggle('dark');
        localStorage.setItem('visio_theme', isDark ? 'dark' : 'light');
        updateThemeIcons(isDark);
      });
    }

    /* ── Fonte (Altera font-size do <html>; elementos com px ficam intactos) ── */
    let currentFontSize = parseInt(localStorage.getItem('visio_fontsize') || '16');

    function applyFontSize(size) {
      document.documentElement.style.fontSize = size + 'px';
    }

    applyFontSize(currentFontSize);

    function changeFontSize(delta) {
      const novo = currentFontSize + delta;
      if (novo < 12 || novo > 24) return;
      currentFontSize = novo;
      applyFontSize(currentFontSize);
      localStorage.setItem('visio_fontsize', currentFontSize);
    }

    /* ── Alto contraste ── */
    if (localStorage.getItem('visio_contraste') === '1') {
      document.body.classList.add('high-contrast');
    }

    function toggleContrast() {
      const ativo = document.body.classList.toggle('high-contrast');
      localStorage.setItem('visio_contraste', ativo ? '1' : '0');
      if (themeToggleBtn) {
        themeToggleBtn.disabled = ativo;
        themeToggleBtn.style.opacity = ativo ? '0.4' : '1';
        themeToggleBtn.style.cursor = ativo ? 'not-allowed' : 'pointer';
        themeToggleBtn.title = ativo
          ? 'Desative o alto contraste para trocar o tema'
          : 'Alternar tema claro/escuro';
      }
    }

    (function () {
      if (document.body.classList.contains('high-contrast')) {
        if (themeToggleBtn) { 
          themeToggleBtn.disabled = true; 
          themeToggleBtn.style.opacity = '0.4'; 
          themeToggleBtn.style.cursor = 'not-allowed'; 
        }
      }
    })();

    /* ── Leitor ── */
    let leituraEmAndamento = false;

    function pararLeitura() {
      window.speechSynthesis.cancel();
      leituraEmAndamento = false;
    }

    function readPage() {
      if (leituraEmAndamento) {
        pararLeitura();
        return;
      }

      window.speechSynthesis.cancel();

      let text = '';
      document.querySelectorAll('h1,h2,h3,p,label').forEach(el => {
        const t = el.innerText?.trim();
        if (t) text += t + '. ';
      });
      if (!text) text = 'Nenhum conteúdo encontrado para leitura.';

      const fala = new SpeechSynthesisUtterance(text);
      fala.lang = 'pt-BR';

      fala.onend = () => { leituraEmAndamento = false; };
      fala.onerror = () => { leituraEmAndamento = false; };

      leituraEmAndamento = true;
      window.speechSynthesis.speak(fala);
    }
  </script>

  <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
</body>

</html>