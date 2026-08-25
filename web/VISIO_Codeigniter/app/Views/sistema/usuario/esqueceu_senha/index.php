<?= view('sistema/layout/header') ?>
<br><br>

<!-- SweetAlert & FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    min-height: 540px; 
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

  /* ESQUERDA: Card de Formulário Glassmorphism */
  .forgot-card-glass {
    width: 440px;
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
    margin-bottom: 24px;
    line-height: 1.5;
  }

  .input-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
    text-align: left;
    margin-bottom: 14px;
  }

  .input-group label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #cbd5e1;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .input-group label i {
    color: #38bdf8;
    font-size: 0.75rem;
  }

  .forgot-input {
    width: 100%; 
    padding: 11px 14px; 
    border: 1px solid rgba(255, 255, 255, 0.12); 
    border-radius: 12px; 
    background-color: rgba(15, 23, 42, 0.6); 
    color: #f8fafc; 
    font-size: 0.88rem; 
    outline: none; 
    box-sizing: border-box; 
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .forgot-input::placeholder {
    color: #64748b;
  }

  .forgot-input:focus {
    border-color: #38bdf8;
    background-color: rgba(15, 23, 42, 0.85);
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.18);
  }

  .forgot-input.input-error {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.2) !important;
  }

  .erro {
    color: #f87171;
    font-size: 0.72rem;
    padding-left: 4px;
    display: block;
    min-height: 14px;
  }

  .btn-forgot {
    width: 100%; 
    padding: 12px; 
    background: linear-gradient(135deg, #2563eb 0%, #0284c7 100%); 
    color: #ffffff; 
    border: none; 
    border-radius: 12px; 
    font-weight: 600; 
    font-size: 0.95rem; 
    cursor: pointer; 
    margin-top: 10px; 
    transition: all 0.25s ease;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn-forgot:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 189, 248, 0.45);
    background: linear-gradient(135deg, #1d4ed8 0%, #0369a1 100%);
  }

  .forgot-footer {
    margin-top: 20px; 
    text-align: center;
    font-size: 0.85rem;
    color: #94a3b8;
  }

  .link-voltar {
    color: #38bdf8;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .link-voltar:hover {
    color: #60a5fa;
    transform: translateX(-3px);
  }

  /* DIREITA: Branding com Logo sem Hover */
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

  [data-theme="light"] .input-group label,
  body.light-theme .input-group label,
  body.light .input-group label {
    color: #334155;
  }

  [data-theme="light"] .forgot-input,
  body.light-theme .forgot-input,
  body.light .forgot-input {
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    color: #0f172a;
  }

  [data-theme="light"] .forgot-input::placeholder,
  body.light-theme .forgot-input::placeholder,
  body.light .forgot-input::placeholder {
    color: #94a3b8;
  }

  [data-theme="light"] .link-voltar,
  body.light-theme .link-voltar,
  body.light .link-voltar {
    color: #0284c7;
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
  .high-contrast .forgot-footer,
  .high-contrast .forgot-footer p,
  .high-contrast .forgot-card-glass *,
  [data-theme="light"].high-contrast .forgot-subtitle,
  [data-theme="light"].high-contrast .forgot-branding p {
    background-color: transparent !important;
    box-shadow: none !important;
    color: #ffffff !important;
  }

  .high-contrast .input-group label,
  .high-contrast .input-group label i {
    color: #ffff00 !important;
  }

  .high-contrast .forgot-input,
  [data-theme="light"].high-contrast .forgot-input {
    background-color: #000000 !important;
    border: 2px solid #ffffff !important;
    color: #ffffff !important;
  }

  .high-contrast .forgot-input:focus {
    border-color: #ffff00 !important;
    box-shadow: 0 0 0 2px #ffff00 !important;
  }

  .high-contrast .forgot-input::placeholder {
    color: #aaaaaa !important;
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

  .high-contrast .link-voltar,
  [data-theme="light"].high-contrast .link-voltar {
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

  <?php if (session()->getFlashdata('erro')): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Atenção',
        text: '<?= session()->getFlashdata('erro') ?>',
        confirmButtonColor: '#2563eb'
      });
    </script>
  <?php endif; ?>

  <div class="forgot-container">
    
    <!-- LADO ESQUERDO: Form Card Glass -->
    <div class="forgot-card-glass">
      <h1 class="forgot-card-title">Recuperar Senha</h1>
      <p class="forgot-subtitle">Digite seu e-mail cadastrado para receber as instruções de redefinição.</p>

      <form action="<?= base_url('/usuario/esqueceu_senha') ?>" method="post" id="form" novalidate>
        <?= csrf_field() ?>
        
        <div class="input-group">
          <label for="email"><i class="fa-regular fa-envelope"></i> E-mail</label>
          <input type="email" name="email" id="email" placeholder="nome@empresa.com" class="forgot-input">
          <span class="erro" id="erroEmail"></span>
        </div>

        <button type="submit" class="btn-forgot">
          <i class="fa-solid fa-paper-plane"></i> Enviar link de recuperação
        </button>
      </form>

      <div class="forgot-footer">
        <p>Lembrou a senha? 
          <a href="<?= base_url('/login') ?>" class="link-voltar">
            <i class="fa-solid fa-arrow-left"></i> Entrar
          </a>
        </p>
      </div>
    </div>

    <!-- LADO DIREITO: Branding -->
    <div class="forgot-branding">
      <img class="theme-img logo"
           src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
           data-light="<?= base_url('assets/images/logos/Logo/LogoLight.png') ?>"
           data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
           alt="Logo VISIO">
      
      <h2>Redefinição Segura</h2>
      <p>Enviaremos um link de acesso direto para você criar uma nova senha com segurança.</p>
    </div>

  </div>
</main>

<script>
  // Limpa mensagens de erro ao digitar
  document.querySelectorAll('.forgot-input').forEach(input => {
    input.addEventListener('input', function() {
      this.classList.remove('input-error');
      const erroSpan = document.getElementById('erro' + this.id.charAt(0).toUpperCase() + this.id.slice(1));
      if (erroSpan) erroSpan.textContent = '';
    });
  });
</script>

<script src="<?= base_url('assets/js/validacaorecuperar.js') ?>"></script>
<?= view('sistema/layout/footer') ?>