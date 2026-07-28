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
  <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous"
    defer></script>
  <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet"
    crossorigin="anonymous">
</head>

<style>
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
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
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
    background: var(--bg);
    color: var(--text);
    cursor: pointer;
    transition: 0.3s;
    font-size: 16px;
  }

  #theme-toggle:hover {
    transform: scale(1.05);
  }

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
    transition: .3s;
  }

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


 

  .main {
    width: calc(100% - 280px);
    margin-left: 280px;
  }

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
  }

  .nav-right {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  #theme-toggle {
    width: 42px;
    height: 42px;
    border: none;
    border-radius: 12px;
    background: var(--bg);
    color: var(--text);
    cursor: pointer;
    font-size: 16px;
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
    font-size: 34px;
    margin-bottom: 8px;
  }

  .topbar p {
    color: var(--text2);
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
  }

  input,
  textarea {
    width: 100%;
    padding: 15px;
    border-radius: 14px;
    border: 1px solid var(--border);
    outline: none;
    font-size: 15px;
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
  }

  .table-wrapper {
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead {
    background: #0f172a;
  }

  thead th {
    padding: 18px;
    color: white;
    text-align: left;
  }

  tbody td {
    padding: 18px;
    border-bottom: 1px solid var(--border);
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
  }
</style>

<body>

  <a href="#conteudo-principal" class="skip-link">Pular para o conteúdo</a>

  <header>
    <nav>
      <img src="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>" class="logo" alt="Logo plataforma VISIO"
        id="logo" data-dark="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>"
        data-light="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>">

      <button class="hamburger" id="hamburger" aria-label="Abrir menu de navegação" aria-expanded="false"
        aria-controls="nav-links">
        <span></span><span></span><span></span>
      </button>

      <ul class="nav-links" id="nav-links" role="navigation" aria-label="Menu principal">
        <li>
          <a href="<?= base_url('/admin/perfil') ?>" style="
    background:#2563eb;
    color:white;
    padding:12px 18px;
    border-radius:10px;
    display:flex;
    align-items:center;
    gap:8px;
    text-decoration:none;
    font-weight:600;
">
            <i class="fa-solid fa-user-shield" style="color:white;"></i>
            Perfil
          </a>
        </li>
        <li>
          <button id="accessibility-btn" aria-label="Abrir menu de acessibilidade" aria-expanded="false"
            aria-controls="accessibility-panel" style="width:40px;height:40px;border:none;border-radius:12px;
                       background:#2563eb;color:white;font-size:18px;cursor:pointer;">
            <i class="fa-solid fa-gear"></i>
          </button>
        </li>
      </ul>

      <div id="accessibility-panel" aria-hidden="true" style="position:absolute;top:70px;right:20px;width:260px;
                background:var(--color-bg-card,#fff);border-radius:16px;
                padding:18px;display:none;z-index:9999;
                box-shadow:0 10px 25px rgba(0,0,0,.25);">


        <!--AUMENTAR FONTE-->
        <button onclick="changeFontSize(2)"
          style="width:100%;height:45px;box-sizing:border-box;padding:12px;margin-bottom:10px;border:none;border-radius:10px;background:#111827;color:white;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;white-space:nowrap;overflow:hidden;">
          <i class="fa-solid fa-magnifying-glass-plus"></i> Aumentar Fonte
        </button>

        <!--DIMINUIR FONTE-->
        <button onclick="changeFontSize(-2)"
          style="width:100%;height:45px;box-sizing:border-box;padding:12px;margin-bottom:10px;border:none;border-radius:10px;background:#111827;color:white;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;white-space:nowrap;overflow:hidden;">
          <i class="fa-solid fa-magnifying-glass-minus"></i> Diminuir Fonte
        </button>


        <!--ALTERAR TEMA-->
        <button id="theme-toggle"
          style="width:100%;height:45px;box-sizing:border-box;padding:12px;margin-bottom:10px;border:none;border-radius:10px;background:#111827;color:white;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;white-space:nowrap;overflow:hidden;">
          <i class="fa-solid fa-moon" id="icon-moon"></i>
          <i class="fa-solid fa-sun" id="icon-sun" style="display:none; color:#fff"></i>
          Alterar Tema
        </button>


        <!--ALTO CONTRASTE-->
        <button onclick="toggleContrast()" id="btn-contraste"
          style="width:100%;height:45px;box-sizing:border-box;padding:12px;margin-bottom:10px;border:none;border-radius:10px;background:#111827;color:white;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;white-space:nowrap;overflow:hidden;">
          <i class="fa-solid fa-adjust"></i> Alto Contraste
        </button>


        <!--LER PAGINA-->
        <button onclick="readPage()"
          style="width:100%;height:45px;box-sizing:border-box;padding:12px;border:none;border-radius:10px;background:#111827;color:white;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;white-space:nowrap;overflow:hidden;">
          <i class="fa-solid fa-volume-high"></i> Ler Página
        </button>

      </div>
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
      if (aberto) { accessibilityPanel.style.display = 'none'; }
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

    /* ── Painel de acessibilidade ── */
    const accessibilityBtn = document.getElementById('accessibility-btn');
    const accessibilityPanel = document.getElementById('accessibility-panel');

    accessibilityBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const visivel = accessibilityPanel.style.display === 'block';
      accessibilityPanel.style.display = visivel ? 'none' : 'block';
      accessibilityBtn.setAttribute('aria-expanded', !visivel);
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
      }
    });

    /* ── Fonte (com transição suave, compatível com qualquer navegador) ── */
    let currentFontSize = parseInt(localStorage.getItem('visio_fontsize') || '16');
    document.body.style.zoom = currentFontSize / 16;

    function animarZoom(de, para) {
      const duracao = 250;
      const inicio = performance.now();
      function passo(agora) {
        const t = Math.min((agora - inicio) / duracao, 1);
        const ease = 1 - Math.pow(1 - t, 3); // ease-out cúbico
        document.body.style.zoom = de + (para - de) * ease;
        if (t < 1) requestAnimationFrame(passo);
      }
      requestAnimationFrame(passo);
    }

    function changeFontSize(delta) {
      const novo = currentFontSize + delta;
      if (novo < 12 || novo > 26) return;
      const zoomAntigo = currentFontSize / 16;
      currentFontSize = novo;
      const zoomNovo = currentFontSize / 16;
      animarZoom(zoomAntigo, zoomNovo);
      localStorage.setItem('visio_fontsize', currentFontSize);
    }

    /* ── Alto contraste ── */
    if (localStorage.getItem('visio_contraste') === '1') {
      document.body.classList.add('high-contrast');
    }

    function toggleContrast() {
      const ativo = document.body.classList.toggle('high-contrast');
      localStorage.setItem('visio_contraste', ativo ? '1' : '0');
      const btnTema = document.getElementById('theme-toggle');
      if (btnTema) {
        btnTema.disabled = ativo;
        btnTema.style.opacity = ativo ? '0.4' : '1';
        btnTema.style.cursor = ativo ? 'not-allowed' : 'pointer';
        btnTema.title = ativo
          ? 'Desative o alto contraste para trocar o tema'
          : 'Alternar tema claro/escuro';
      }
    }

    (function () {
      if (document.body.classList.contains('high-contrast')) {
        const b = document.getElementById('theme-toggle');
        if (b) { b.disabled = true; b.style.opacity = '0.4'; b.style.cursor = 'not-allowed'; }
      }
    })();

    /* ── Leitor ── */
    function readPage() {
      speechSynthesis.cancel();
      let text = '';
      document.querySelectorAll('h1,h2,h3,p,label').forEach(el => {
        const t = el.innerText?.trim();
        if (t) text += t + '. ';
      });
      if (!text) text = 'Nenhum conteúdo encontrado para leitura.';
      const fala = new SpeechSynthesisUtterance(text);
      fala.lang = 'pt-BR';
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
