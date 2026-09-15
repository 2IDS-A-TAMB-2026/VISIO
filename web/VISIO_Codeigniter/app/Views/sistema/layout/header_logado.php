<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="icon" href="<?= base_url('assets/images/logos/Simbolo/SimboloDark.png') ?>">
  <title>VISIO</title>
  <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous"
    defer>
    </script>
  <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet"
    crossorigin="anonymous">

  <style>


    #accessibility-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      border: none;
      background: #111827;
      color: white;
      font-size: 24px;
      cursor: pointer;
      z-index: 9999;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
      transition: 0.3s;
    }

    #accessibility-btn:hover {
      transform: scale(1.1);
    }

    /* PAINEL */

    #accessibility-panel {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 260px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 20px;
      display: none;
      z-index: 9999;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    #accessibility-panel h3 {
      margin-bottom: 15px;
      font-size: 18px;
      color: #111827;
    }

    #accessibility-panel button {
      width: 100%;
      margin-bottom: 10px;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background: #111827;
      color: white;
      cursor: pointer;
      transition: 0.3s;
      font-size: 14px;
    }

    #accessibility-panel button:hover {
      background: #374151;
    }

    /* DARK MODE */

    .dark-mode {
      background: #111827 !important;
      color: white !important;
    }

    .dark-mode * {
      color: white !important;
    }

    /* ALTO CONTRASTE */

    .high-contrast {
      background: black !important;
      color: yellow !important;
    }

    .high-contrast * {
      background: black !important;
      color: yellow !important;
      border-color: yellow !important;
    }

    /* REDUZIR ANIMAÇÕES */

    .reduce-motion * {
      animation: none !important;
      transition: none !important;
      scroll-behavior: auto !important;
    }
  </style>

</head>

<body>

  <header>

    <nav>

      <img src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>" class="logo" alt="Logo plataforma VISIO"
        id="logo" data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
        data-light="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>">

      <ul class="nav-links">

        <li>
          <a href="<?= base_url('/') ?>">
            <i class="fa-solid fa-house"></i> Início
          </a>
        </li>

        <li>
          <a href="<?= base_url('/sobre') ?>">
            <i class="fa-solid fa-info-circle"></i> Sobre Nós
          </a>
        </li>

        <li>
          <a href="<?= base_url('/quiz') ?>">
            <i class="fa-solid fa-circle-question"></i> Questões
          </a>
        </li>

        <li>
          <a href="<?= base_url('/sensor') ?>">
            <i class="fa-solid fa-microchip"></i> Sensores
          </a>
        </li>

        <li>
          <a href="<?= base_url('/perfil') ?>">
            <i class="fa-solid fa-right-to-bracket"></i> Perfil
          </a>
        </li>



        <li>
          <button id="theme-toggle" aria-label="Alternar tema">

            <i id="icon-sun" class="fa-solid fa-sun"></i>

            <i id="icon-moon" class="fa-solid fa-moon"></i>

          </button>
        </li>

      </ul>

    </nav>

  </header>
  <button id="accessibility-btn" aria-label="Abrir painel de acessibilidade">

    <i class="fa-solid fa-gear"></i>

  </button>


  <div id="accessibility-panel">

    <h3>
      Acessibilidade
    </h3>

    <button onclick="changeFontSize(2)">
      A+ Aumentar Fonte
    </button>

    <button onclick="changeFontSize(-2)">
      A- Diminuir Fonte
    </button>

    <button onclick="toggleDarkMode()">
      🌙 Tema Escuro
    </button>

    <button onclick="toggleContrast()">
      ⚫⚪ Alto Contraste
    </button>

    <button onclick="readPage()">
      🔊 Ler Site
    </button>

    <button onclick="toggleAnimations()">
      ✨ Reduzir Animações
    </button>

  </div>


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


  <script>

    const accessibilityBtn =
      document.getElementById('accessibility-btn');

    const accessibilityPanel =
      document.getElementById('accessibility-panel');

    /* ABRIR PAINEL */

    accessibilityBtn.addEventListener('click', () => {

      accessibilityPanel.style.display =
        accessibilityPanel.style.display === 'block'
          ? 'none'
          : 'block';

    });

    /* FONTE (com transição suave, compatível com qualquer navegador) */
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

    /* TEMA ESCURO */

    function toggleDarkMode() {

      document.body.classList.toggle('dark-mode');

      localStorage.setItem(
        'darkMode',
        document.body.classList.contains('dark-mode')
      );
    }

    /* ALTO CONTRASTE */

    function toggleContrast() {

      document.body.classList.toggle('high-contrast');

      localStorage.setItem(
        'contrast',
        document.body.classList.contains('high-contrast')
      );
    }

    /* LER SITE */

    let leituraEmAndamento = false;

    function pararLeitura() {
      speechSynthesis.cancel();
      leituraEmAndamento = false;
    }

    function readPage() {

      // Segundo clique (ou clique enquanto já está lendo): apenas para.
      if (leituraEmAndamento) {
        pararLeitura();
        return;
      }

      // Primeiro clique (ou reinício): cancela qualquer fala residual
      // da fila do navegador antes de montar a nova leitura, garantindo
      // que nunca existam duas leituras tocando ao mesmo tempo.
      speechSynthesis.cancel();

      const text = document.body.innerText;

      const speech =
        new SpeechSynthesisUtterance(text);

      speech.lang = 'pt-BR';

      // Quando a leitura termina sozinha (chegou ao fim do texto),
      // o estado precisa voltar a "parado" para que o próximo clique
      // seja tratado como um novo início, e não como um "parar".
      speech.onend = () => { leituraEmAndamento = false; };
      speech.onerror = () => { leituraEmAndamento = false; };

      leituraEmAndamento = true;
      speechSynthesis.speak(speech);
    }

    /* REDUZIR ANIMAÇÕES */

    function toggleAnimations() {

      document.body.classList.toggle('reduce-motion');

      localStorage.setItem(
        'reduceMotion',
        document.body.classList.contains('reduce-motion')
      );
    }

    /* CARREGAR CONFIGURAÇÕES */

    window.onload = () => {

      if (
        localStorage.getItem('darkMode') === 'true'
      ) {
        document.body.classList.add('dark-mode');
      }

      if (
        localStorage.getItem('contrast') === 'true'
      ) {
        document.body.classList.add('high-contrast');
      }

      if (
        localStorage.getItem('reduceMotion') === 'true'
      ) {
        document.body.classList.add('reduce-motion');
      }

    };

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
