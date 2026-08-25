<?= view('sistema/layout/header') ?>
<br><br>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  .forgot-page {
    display: flex; 
    justify-content: center; 
    align-items: center; 
    padding: 30px 20px; 
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  }

  /* Main Container */
  .forgot-container {
    position: relative;
    display: flex; 
    width: 980px; 
    max-width: 100%; 
    min-height: 580px; 
    border-radius: 24px; 
    overflow: hidden; 
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
    align-items: center;
    justify-content: space-between;
    padding: 48px;
    background: linear-gradient(135deg, #0b1120 0%, #1e293b 50%, #080d1a 100%);
    border: 1px solid rgba(56, 189, 248, 0.2);
    transition: all 0.3s ease;
  }

  /* Efeitos de iluminação ambiente */
  .forgot-container::before {
    content: '';
    position: absolute;
    top: -120px;
    right: -120px;
    width: 420px;
    height: 420px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.35) 0%, rgba(37, 99, 235, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  .forgot-container::after {
    content: '';
    position: absolute;
    bottom: -100px;
    left: 10%;
    width: 360px;
    height: 360px;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(56, 189, 248, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  /* ESQUERDA: Card Glassmorphism */
  .forgot-card-glass {
    width: 460px;
    max-width: 100%;
    z-index: 2;
    padding: 36px 32px;
    border-radius: 20px;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  }

  .forgot-card-title {
    font-size: 1.85rem; 
    font-weight: 700; 
    color: #ffffff; 
    margin-bottom: 6px;
    text-align: left;
    position: relative;
    display: inline-block;
    letter-spacing: -0.5px;
  }

  .forgot-card-title::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 32px;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #38bdf8);
    border-radius: 2px;
  }

  .forgot-subtitle {
    font-size: 0.88rem;
    color: #94a3b8;
    margin-bottom: 20px;
    line-height: 1.5;
  }

  /* Box do Link de Demonstração */
  .demo-link-box {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(56, 189, 248, 0.3);
    border-radius: 12px;
    padding: 12px 16px;
    word-break: break-all;
    font-family: monospace;
    font-size: 0.82rem;
    margin: 16px 0;
    color: #38bdf8;
  }

  .btn-forgot {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%; 
    padding: 12px; 
    background: linear-gradient(135deg, #2563eb 0%, #0284c7 100%); 
    color: #ffffff; 
    border: none; 
    border-radius: 12px; 
    font-weight: 600; 
    font-size: 0.95rem; 
    text-decoration: none;
    cursor: pointer; 
    margin-bottom: 12px; 
    transition: all 0.25s ease;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
    box-sizing: border-box;
  }

  .btn-forgot:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 189, 248, 0.45);
    background: linear-gradient(135deg, #1d4ed8 0%, #0369a1 100%);
    color: #ffffff;
  }

  .time-warning {
    font-size: 0.82rem;
    color: #94a3b8;
    text-align: center;
    margin-top: 8px;
  }

  .time-warning i {
    color: #38bdf8;
  }

  .forgot-footer {
    margin-top: 24px; 
    text-align: center;
    font-size: 0.85rem;
  }

  .link-voltar-login {
    color: #94a3b8;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
  }

  .link-voltar-login:hover {
    color: #38bdf8;
    transform: translateX(-3px);
  }

  /* DIREITA: Branding */
  .forgot-branding {
    flex: 1;
    z-index: 2;
    padding-left: 30px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .forgot-branding .logo {
    max-width: 300px; 
    width: 100%;
    height: auto; 
    object-fit: contain; 
    margin-bottom: 28px;
    filter: drop-shadow(0 14px 28px rgba(0,0,0,0.45));
  }

  .forgot-branding h2 {
    font-size: 2.2rem !important; 
    font-weight: 700 !important; 
    margin-bottom: 12px; 
    color: #ffffff;
    letter-spacing: -0.5px;
  }

  .forgot-branding p {
    font-size: 0.95rem; 
    color: #94a3b8; 
    line-height: 1.6;
    max-width: 320px;
  }

  /* --- MODO CLARO --- */
  [data-theme="light"] .forgot-container,
  body.light-theme .forgot-container,
  body.light .forgot-container {
    background: linear-gradient(135deg, rgba(241, 245, 249, 0.8) 0%, rgba(224, 242, 254, 0.7) 100%);
    border-color: rgba(56, 189, 248, 0.4);
    box-shadow: 0 25px 50px -12px rgba(2, 132, 199, 0.15);
  }

  [data-theme="light"] .forgot-branding h2,
  body.light-theme .forgot-branding h2,
  body.light .forgot-branding h2 {
    color: #0f172a;
  }

  [data-theme="light"] .forgot-branding p,
  body.light-theme .forgot-branding p,
  body.light .forgot-branding p {
    color: #475569;
  }

  [data-theme="light"] .forgot-card-glass,
  body.light-theme .forgot-card-glass,
  body.light .forgot-card-glass {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 20px 40px rgba(14, 165, 233, 0.12);
  }

  [data-theme="light"] .forgot-card-title,
  body.light-theme .forgot-card-title,
  body.light .forgot-card-title {
    color: #0f172a;
  }

  [data-theme="light"] .forgot-subtitle,
  body.light-theme .forgot-subtitle,
  body.light .forgot-subtitle {
    color: #64748b;
  }

  [data-theme="light"] .demo-link-box,
  body.light-theme .demo-link-box {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #0284c7;
  }

  [data-theme="light"] .link-voltar-login,
  body.light-theme .link-voltar-login,
  body.light .link-voltar-login {
    color: #475569;
  }

  /* ============================================================
     REGRAS DE ALTO CONTRASTE (PRIORIDADE ABSOLUTA)
     ============================================================ */

  html.high-contrast,
  body.high-contrast,
  .high-contrast .forgot-page,
  [data-theme="light"].high-contrast .forgot-container,
  body.light-theme.high-contrast .forgot-container,
  body.light.high-contrast .forgot-container {
    background-color: #000000 !important;
    background: #000000 !important;
    color: #ffffff !important;
  }

  .high-contrast .forgot-container,
  .high-contrast .forgot-card-glass,
  .high-contrast .forgot-branding,
  [data-theme="light"].high-contrast .forgot-card-glass,
  body.light-theme.high-contrast .forgot-card-glass {
    background-color: #000000 !important;
    background: #000000 !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    box-shadow: none !important;
  }

  .high-contrast .forgot-container::before,
  .high-contrast .forgot-container::after {
    display: none !important;
  }

  .high-contrast .forgot-container {
    border: 3px solid #ffff00 !important;
  }

  .high-contrast .forgot-card-glass {
    border: 2px solid #ffffff !important;
  }

  .high-contrast .forgot-card-title,
  .high-contrast .forgot-branding h2,
  [data-theme="light"].high-contrast .forgot-card-title,
  [data-theme="light"].high-contrast .forgot-branding h2,
  body.light-theme.high-contrast .forgot-card-title,
  body.light-theme.high-contrast .forgot-branding h2 {
    color: #ffff00 !important;
    background: transparent !important;
  }

  .high-contrast .forgot-card-title::after {
    background: #ffff00 !important;
  }

  .high-contrast .forgot-subtitle,
  .high-contrast .forgot-branding p,
  .high-contrast .forgot-footer *,
  .high-contrast .time-warning,
  [data-theme="light"].high-contrast .forgot-subtitle,
  [data-theme="light"].high-contrast .forgot-branding p {
    background-color: transparent !important;
    box-shadow: none !important;
    color: #ffffff !important;
  }

  .high-contrast .demo-link-box {
    background-color: #000000 !important;
    border: 2px solid #ffff00 !important;
    color: #ffff00 !important;
  }

  .high-contrast .btn-forgot {
    background: #ffff00 !important;
    color: #000000 !important;
    border: 2px solid #ffffff !important;
    font-weight: 900 !important;
  }

  .high-contrast .btn-forgot * {
    color: #000000 !important;
  }

  .high-contrast .link-voltar-login,
  [data-theme="light"].high-contrast .link-voltar-login {
    color: #ffff00 !important;
    text-decoration: underline !important;
  }

  /* LOGO CORRIGIDA (SEM BLOCO BRANCO) */
  .high-contrast .forgot-branding .logo {
    background: transparent !important;
    filter: invert(1) grayscale(100%) !important;
    -webkit-filter: invert(1) grayscale(100%) !important;
  }

  @media (max-width: 850px) {
    .forgot-container {
      flex-direction: column-reverse;
      padding: 32px 20px;
    }
    .forgot-branding {
      padding-left: 0;
      margin-bottom: 24px;
    }
    .forgot-branding .logo {
      max-width: 220px;
    }
    .forgot-card-glass {
      width: 100%;
    }
  }
</style>

<main class="forgot-page">

  <div class="forgot-container">
    
    <!-- LADO ESQUERDO: Card Glass -->
    <div class="forgot-card-glass">

      <?php if ($link): ?>

        <h1 class="forgot-card-title">Link Gerado!</h1>
        <p class="forgot-subtitle">
          Em produção, este link seria enviado para o seu e-mail.<br>
          Como estamos em modo de demonstração, clique abaixo para redefinir sua senha:
        </p>

        <div class="demo-link-box">
          <?= esc($link) ?>
        </div>

        <a href="<?= esc($link) ?>" class="btn-forgot">
          <i class="fa-solid fa-key"></i> Redefinir minha senha agora
        </a>

        <p class="time-warning">
          <i class="fa-solid fa-clock"></i> Este link expira em <strong>30 minutos</strong>.
        </p>

      <?php else: ?>

        <h1 class="forgot-card-title">Solicitação Recebida</h1>
        <p class="forgot-subtitle">
          Se o e-mail informado estiver cadastrado em nosso sistema, você receberá as instruções de recuperação em breve.
        </p>

      <?php endif; ?>

      <div class="forgot-footer">
        <a href="<?= base_url('/login') ?>" class="link-voltar-login">
          <i class="fa-solid fa-arrow-left"></i> Voltar ao login
        </a>
      </div>

    </div>

    <!-- LADO DIREITO: Branding -->
    <div class="forgot-branding">
      <img class="theme-img logo"
           src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
           data-light="<?= base_url('assets/images/logos/Logo/LogoLight.png') ?>"
           data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
           alt="Logo VISIO">
      
      <h2>Plataforma VISIO</h2>
      <p>Recuperação de acesso e segurança da sua conta centralizada.</p>
    </div>

  </div>
</main>

<?= view('sistema/layout/footer') ?>