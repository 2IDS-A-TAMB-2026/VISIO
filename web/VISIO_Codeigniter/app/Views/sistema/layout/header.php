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

<body>

  <a href="#conteudo-principal" class="skip-link">Pular para o conteúdo</a>

  <header>
    <nav>
      <img src="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>" class="logo" alt="Logo plataforma VISIO"
        id="logo" data-dark="<?= base_url('assets/images/logos/Logo/LogoDark2.png') ?>"
        data-light="<?= base_url('assets/images/logos/Logo/LogoLight2.png') ?>">

      <button class="hamburger" id="hamburger" aria-label="Abrir menu de navegação" aria-expanded="false"
        aria-controls="nav-links">
        <span></span><span></span><span></span>
      </button>

      <ul class="nav-links" id="nav-links" role="navigation" aria-label="Menu principal">

        <li><a href="<?= base_url('/') ?>"><i class="fa-solid fa-house"></i> Início</a></li>
        <li><a href="<?= base_url('/sobre') ?>"><i class="fa-solid fa-info-circle"></i> Sobre Nós</a></li>
        <li><a href="<?= base_url('/sensor') ?>"><i class="fa-solid fa-microchip"></i> Sensores</a></li>

        <?php if (session()->get('usuario_logado')): ?>
          <li><a href="<?= base_url('/quiz') ?>"><i class="fa-solid fa-circle-question"></i> Questões</a></li>
          <li><a href="<?= base_url('/usuario/perfil') ?>">
              <i class="fa-solid fa-user"></i> <?= esc(session()->get('usuario_nome') ?: 'Perfil') ?>
            </a></li>
          <li><a href="<?= base_url('/logout') ?>"><i class="fa-solid fa-right-from-bracket"></i> Sair</a></li>
        <?php else: ?>
          <li><a href="<?= base_url('/quiz') ?>"><i class="fa-solid fa-circle-question"></i> Questões</a></li>
          <li><a href="<?= base_url('/login') ?>"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
        <?php endif; ?>

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

    /* ── Fonte ── */
    let currentFontSize = parseInt(localStorage.getItem('visio_fontsize') || '16');
    document.body.style.fontSize = currentFontSize + 'px';

    function changeFontSize(delta) {
      const novo = currentFontSize + delta;
      if (novo < 12 || novo > 26) return;
      currentFontSize = novo;
      document.body.style.fontSize = currentFontSize + 'px';
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
</body>
</html>