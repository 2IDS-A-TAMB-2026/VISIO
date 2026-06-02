<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="icon" href="<?= base_url('assets/images/logos/Simbolo/SimboloDark.png') ?>">
  <title>VISIO</title>

  <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet" crossorigin="anonymous">

  <style>
    /* VARIÁVEIS DE TEMA (PADRÃO: ESCURO) */
    :root {
      --bg-body: #000000;
      --text-body: #ffffff;
      --bg-header: #17182c;
      --bg-nav: #1a253b;
      --color-heading: #3b82f6;
      --bg-panel: #ffffff;
      --text-panel: #111827;
      --btn-primary: #2563eb;
      --btn-hover: #1249c0;
    }

    /* TEMA CLARO */
    body.light-mode {
      --bg-body: #ffffff;
      --text-body: #000000;
      --bg-header: #f3f4f6;
      --bg-nav: #e5e7eb;
      --color-heading: #2f6cef;
      --bg-panel: #111827;
      --text-panel: #ffffff;
    }

    /* ALTO CONTRASTE (SOBREPÕE OS OUTROS TEMAS) */
    body.high-contrast {
      --bg-body: #000000 !important;
      --text-body: #ffff00 !important;
      --bg-header: #000000 !important;
      --bg-nav: #000000 !important;
      --color-heading: #ffff00 !important;
      --bg-panel: #000000 !important;
      --text-panel: #ffff00 !important;
    }

    /* ESTILOS GERAIS */
    body {
      background-color: var(--bg-body);
      color: var(--text-body);
      font-size: 16px;
      transition: background 0.3s, color 0.3s;
      margin: 0;
      font-family: sans-serif;
    }

    header {
      width: 100%;
      background-color: var(--bg-header);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    nav {
      background-color: var(--bg-nav);
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
    }

    .nav-links {
      display: flex;
      list-style: none;
      gap: 15px;
      align-items: center;
    }

    .nav-links a {
      color: var(--text-body);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    h1, h2, h3, h4, h5, h6 {
      color: var(--color-heading);
    }

    /* BOTÃO ENGRENAGEM */
    #accessibility-btn {
      width: 40px;
      height: 40px;
      border: none;
      border-radius: 12px;
      background: var(--btn-primary);
      color: white;
      font-size: 20px;
      cursor: pointer;
      transition: 0.3s;
    }

    #accessibility-btn:hover {
      background: var(--btn-hover);
      transform: rotate(90deg);
    }

    /* PAINEL DE ACESSIBILIDADE */
    #accessibility-panel {
      position: absolute;
      top: 70px;
      right: 20px;
      width: 260px;
      background: var(--bg-panel);
      color: var(--text-panel);
      border-radius: 16px;
      padding: 18px;
      display: none;
      z-index: 9999;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    #accessibility-panel button {
      width: 100%;
      padding: 12px;
      margin-bottom: 10px;
      border: 1px solid transparent;
      border-radius: 10px;
      background: var(--text-body);
      color: var(--bg-body);
      font-size: 14px;
      cursor: pointer;
      transition: 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    #accessibility-panel button:hover {
      background: var(--btn-primary);
      color: white;
    }

    /* Borda amarela para itens em Alto Contraste */
    body.high-contrast * {
      border-color: #ffff00 !important;
    }
  </style>
</head>

<body>
  <header>
    <nav>
      <img src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>" class="logo" alt="Logo plataforma VISIO" id="logo">
      
      <ul class="nav-links">
        <li><a href="<?= base_url('/') ?>"><i class="fa-solid fa-house"></i>Início</a></li>
        <li><a href="<?= base_url('/sobre') ?>"><i class="fa-solid fa-info-circle"></i> Sobre Nós</a></li>
        <li><a href="<?= base_url('/quiz') ?>"><i class="fa-solid fa-circle-question"></i> Questões</a></li>
        <li><a href="<?= base_url('/sensor') ?>"><i class="fa-solid fa-microchip"></i> Sensores</a></li>
        <li><a href="<?= base_url('/login') ?>"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
        
        <li>
          <button id="accessibility-btn" aria-label="Abrir menu de acessibilidade" aria-expanded="false" aria-controls="accessibility-panel">
            <i class="fa-solid fa-gear"></i>
          </button>
        </li>
      </ul>

      <div id="accessibility-panel" aria-hidden="true">
        <button onclick="changeFontSize(2)">
          <i class="fa-solid fa-magnifying-glass-plus"></i> Aumentar Fonte
        </button>
        <button onclick="changeFontSize(-2)">
          <i class="fa-solid fa-magnifying-glass-minus"></i> Diminuir Fonte
        </button>
        <button id="theme-toggle">
          <i id="theme-icon" class="fa-solid fa-sun"></i> <span id="theme-text">Modo Claro</span>
        </button>
        <button onclick="toggleContrast()">
          <i class="fa-solid fa-adjust"></i> Alto Contraste
        </button>
        <button onclick="readPage()">
          <i class="fa-solid fa-volume-high"></i> Ler Site
        </button>
      </div>
    </nav>
  </header>

  <script>
    const accessibilityBtn = document.getElementById('accessibility-btn');
    const accessibilityPanel = document.getElementById('accessibility-panel');
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');

    /* ABRIR/FECHAR PAINEL (COM ARIA IMPLEMENTADO) */
    accessibilityBtn.addEventListener('click', () => {
      const isVisible = accessibilityPanel.style.display === 'block';
      accessibilityPanel.style.display = isVisible ? 'none' : 'block';
      
      // Atualiza os estados de acessibilidade para leitores de tela
      accessibilityBtn.setAttribute('aria-expanded', !isVisible);
      accessibilityPanel.setAttribute('aria-hidden', isVisible);
    });

    /* ALTERAR TAMANHO DA FONTE (Otimizado via Body) */
    let currentFontSize = 16;
    function changeFontSize(action) {
      if ((action === 2 && currentFontSize < 24) || (action === -2 && currentFontSize > 12)) {
        currentFontSize += action;
        document.body.style.fontSize = currentFontSize + 'px';
      }
    }

    /* ALTERAR TEMA (Corrigido e Semântico) */
    function toggleTheme() {
      const