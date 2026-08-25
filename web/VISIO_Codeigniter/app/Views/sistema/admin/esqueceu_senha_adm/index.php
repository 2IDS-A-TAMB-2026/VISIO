<?= view('sistema/layout/header') ?>
<br><br>

<!-- SweetAlert & FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  .auth-page {
    display: flex; 
    justify-content: center; 
    align-items: center; 
    padding: 30px 20px; 
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  }

  /* Main Container */
  .auth-container {
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
  .auth-container::before {
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

  .auth-container::after {
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
  .auth-card-glass {
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

  .auth-card-title {
    font-size: 1.85rem; 
    font-weight: 700; 
    color: #ffffff; 
    margin-bottom: 6px;
    text-align: left;
    position: relative;
    display: inline-block;
    letter-spacing: -0.5px;
  }

  .auth-card-title::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 32px;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #38bdf8);
    border-radius: 2px;
  }

  .auth-subtitle {
    font-size: 0.88rem;
    color: #94a3b8;
    margin-bottom: 24px;
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

  .auth-input {
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

  .auth-input::placeholder {
    color: #64748b;
  }

  .auth-input:focus {
    border-color: #38bdf8;
    background-color: rgba(15, 23, 42, 0.85);
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.18);
  }

  .auth-input.input-error {
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

  .btn-auth {
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

  .btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 189, 248, 0.45);
    background: linear-gradient(135deg, #1d4ed8 0%, #0369a1 100%);
  }

  .auth-footer {
    margin-top: 20px; 
    text-align: center;
    font-size: 0.85rem;
  }

  .link-auth {
    color: #38bdf8;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
  }

  .link-auth:hover {
    text-decoration: underline;
    color: #60a5fa;
  }

  /* DIREITA: Branding */
  .auth-branding {
    flex: 1;
    z-index: 2;
    padding-left: 30px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .auth-branding .logo {
    max-width: 300px; 
    width: 100%;
    height: auto; 
    object-fit: contain; 
    margin-bottom: 28px;
    filter: drop-shadow(0 14px 28px rgba(0,0,0,0.45));
  }

  .auth-branding h2 {
    font-size: 2.2rem !important; 
    font-weight: 700 !important; 
    margin-bottom: 12px; 
    color: #ffffff;
    letter-spacing: -0.5px;
  }

  .auth-branding p {
    font-size: 0.95rem; 
    color: #94a3b8; 
    line-height: 1.6;
    max-width: 320px;
  }

  /* --- MODO CLARO --- */
  [data-theme="light"] .auth-container,
  body.light-theme .auth-container,
  body.light .auth-container {
    background: linear-gradient(135deg, rgba(241, 245, 249, 0.8) 0%, rgba(224, 242, 254, 0.7) 100%);
    border-color: rgba(56, 189, 248, 0.4);
    box-shadow: 0 25px 50px -12px rgba(2, 132, 199, 0.15);
  }

  [data-theme="light"] .auth-branding h2,
  body.light-theme .auth-branding h2,
  body.light .auth-branding h2 {
    color: #0f172a;
  }

  [data-theme="light"] .auth-branding p,
  body.light-theme .auth-branding p,
  body.light .auth-branding p {
    color: #475569;
  }

  [data-theme="light"] .auth-card-glass,
  body.light-theme .auth-card-glass,
  body.light .auth-card-glass {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 20px 40px rgba(14, 165, 233, 0.12);
  }

  [data-theme="light"] .auth-card-title,
  body.light-theme .auth-card-title,
  body.light .auth-card-title {
    color: #0f172a;
  }

  [data-theme="light"] .auth-subtitle,
  body.light-theme .auth-subtitle,
  body.light .auth-subtitle {
    color: #64748b;
  }

  [data-theme="light"] .input-group label,
  body.light-theme .input-group label,
  body.light .input-group label {
    color: #334155;
  }

  [data-theme="light"] .auth-input,
  body.light-theme .auth-input,
  body.light .auth-input {
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    color: #0f172a;
  }

  [data-theme="light"] .auth-input::placeholder,
  body.light-theme .auth-input::placeholder,
  body.light .auth-input::placeholder {
    color: #94a3b8;
  }

  [data-theme="light"] .link-auth,
  body.light-theme .link-auth,
  body.light .link-auth {
    color: #0284c7;
  }

  /* ============================================================
     REGRAS DE ALTO CONTRASTE (PRIORIDADE ABSOLUTA)
     ============================================================ */

  html.high-contrast,
  body.high-contrast,
  .high-contrast .auth-page,
  [data-theme="light"].high-contrast .auth-container,
  body.light-theme.high-contrast .auth-container,
  body.light.high-contrast .auth-container {
    background-color: #000000 !important;
    background: #000000 !important;
    color: #ffffff !important;
  }

  .high-contrast .auth-container,
  .high-contrast .auth-card-glass,
  .high-contrast .auth-branding,
  [data-theme="light"].high-contrast .auth-card-glass,
  body.light-theme.high-contrast .auth-card-glass {
    background-color: #000000 !important;
    background: #000000 !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    box-shadow: none !important;
  }

  .high-contrast .auth-container::before,
  .high-contrast .auth-container::after {
    display: none !important;
  }

  .high-contrast .auth-container {
    border: 3px solid #ffff00 !important;
  }

  .high-contrast .auth-card-glass {
    border: 2px solid #ffffff !important;
  }

  .high-contrast .auth-card-title,
  .high-contrast .auth-branding h2,
  [data-theme="light"].high-contrast .auth-card-title,
  [data-theme="light"].high-contrast .auth-branding h2,
  body.light-theme.high-contrast .auth-card-title,
  body.light-theme.high-contrast .auth-branding h2 {
    color: #ffff00 !important;
    background: transparent !important;
  }

  .high-contrast .auth-card-title::after {
    background: #ffff00 !important;
  }

  .high-contrast .auth-subtitle,
  .high-contrast .auth-branding p,
  .high-contrast .auth-footer *,
  [data-theme="light"].high-contrast .auth-subtitle,
  [data-theme="light"].high-contrast .auth-branding p {
    background-color: transparent !important;
    box-shadow: none !important;
    color: #ffffff !important;
  }

  .high-contrast .input-group label,
  .high-contrast .input-group label i {
    color: #ffff00 !important;
  }

  .high-contrast .auth-input,
  [data-theme="light"].high-contrast .auth-input {
    background-color: #000000 !important;
    border: 2px solid #ffffff !important;
    color: #ffffff !important;
  }

  .high-contrast .auth-input:focus {
    border-color: #ffff00 !important;
    box-shadow: 0 0 0 2px #ffff00 !important;
  }

  .high-contrast .auth-input::placeholder {
    color: #aaaaaa !important;
  }

  .high-contrast .btn-auth {
    background: #ffff00 !important;
    color: #000000 !important;
    border: 2px solid #ffffff !important;
    font-weight: 900 !important;
  }

  .high-contrast .btn-auth * {
    color: #000000 !important;
  }

  .high-contrast .link-auth,
  [data-theme="light"].high-contrast .link-auth {
    color: #ffff00 !important;
    text-decoration: underline !important;
  }

  /* LOGO CORRIGIDA (SEM BLOCO BRANCO) */
  .high-contrast .auth-branding .logo {
    background: transparent !important;
    filter: invert(1) grayscale(100%) !important;
    -webkit-filter: invert(1) grayscale(100%) !important;
  }

  @media (max-width: 850px) {
    .auth-container {
      flex-direction: column-reverse;
      padding: 32px 20px;
    }
    .auth-branding {
      padding-left: 0;
      margin-bottom: 24px;
    }
    .auth-branding .logo {
      max-width: 220px;
    }
    .auth-card-glass {
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

  <div class="auth-container">

    <!-- LADO ESQUERDO: Form Card Glass -->
    <div class="auth-card-glass">
      <h1 class="auth-card-title">Recuperar Senha</h1>
      <p class="auth-subtitle">
        Digite seu e-mail cadastrado. Você receberá um link para redefinir sua senha.
      </p>

      <form action="<?= base_url('/usuario/esqueceu_senha') ?>" method="post" id="form" novalidate>
        <?= csrf_field() ?>

        <div class="input-group">
          <label for="email">
            <i class="fa-regular fa-envelope"></i> E-mail
          </label>

          <input
            type="email"
            name="email"
            id="email"
            placeholder="seuemail@empresa.com"
            autocomplete="email"
            class="auth-input"
          >

          <span class="erro" id="erroEmail"></span>
        </div>

        <button type="submit" class="btn-auth">
          <i class="fa-solid fa-paper-plane"></i>
          Enviar link de recuperação
        </button>
      </form>

      <div class="auth-footer">
        <a href="<?= base_url('/login') ?>" class="link-auth">
          <i class="fa-solid fa-arrow-left"></i>
          Lembrou a senha? Entrar
        </a>
      </div>
    </div>

    <!-- LADO DIREITO: Branding -->
    <div class="auth-branding">
      <img
        class="theme-img logo"
        src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
        data-light="<?= base_url('assets/images/logos/Logo/LogoLight.png') ?>"
        data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
        alt="Logo VISIO"
      >

      <h2>Redefinição Segura</h2>

      <p>
        Enviaremos um link de acesso direto para você criar uma nova senha com segurança.
      </p>
    </div>

  </div>
</main>

<script>
  // Limpa mensagens de erro ao digitar
  document.querySelectorAll('.auth-input').forEach(input => {
    input.addEventListener('input', function() {
      this.classList.remove('input-error');

      const erroSpan = document.getElementById(
        'erro' + this.id.charAt(0).toUpperCase() + this.id.slice(1)
      );

      if (erroSpan) {
        erroSpan.textContent = '';
      }
    });
  });
</script>

<script src="<?= base_url('assets/js/validacaorecuperar.js') ?>"></script>

<?= view('sistema/layout/footer') ?>