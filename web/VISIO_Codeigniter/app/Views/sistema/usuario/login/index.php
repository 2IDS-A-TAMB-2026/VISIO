<?= view('sistema/layout/header') ?>
<br><br>

<!-- SweetAlert CDN & FontAwesome Icons -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Google Identity Services (login com Google) -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<style>
  .login-page {
    display: flex; 
    justify-content: center; 
    align-items: center; 
    padding: 20px; 
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  }

  /* Main Container - Mantido o padrão escuro original */
  .login-container {
    position: relative;
    display: flex; 
    width: 900px; 
    max-width: 100%; 
    min-height: 520px; 
    border-radius: 20px; 
    overflow: hidden; 
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4); 
    align-items: center;
    justify-content: space-between;
    padding: 40px;
    background: linear-gradient(135deg, #0b1120 0%, #1e293b 50%, #080d1a 100%);
    border: 1px solid rgba(37, 99, 235, 0.25);
    transition: all 0.3s ease;
  }

  /* Efeitos de brilho no fundo */
  .login-container::before {
    content: '';
    position: absolute;
    top: -100px;
    right: -100px;
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.45) 0%, rgba(37, 99, 235, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  .login-container::after {
    content: '';
    position: absolute;
    bottom: -80px;
    left: 15%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.30) 0%, rgba(37, 99, 235, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  /* Lado Esquerdo: Branding */
  .login-branding {
    flex: 1;
    z-index: 2;
    padding-right: 40px;
    color: #ffffff;
  }

  .login-branding .logo-img {
    width: auto; 
    max-width: 250px; 
    height: auto; 
    max-height: 700px; 
    object-fit: contain; 
    margin-bottom: 24px;
  }

  .login-branding h2 {
    font-size: 2.5rem !important; 
    font-weight: 700 !important; 
    margin-bottom: 12px; 
    color: #ffffff;
  }

  .login-branding p {
    font-size: 0.95rem; 
    color: rgba(255, 255, 255, 0.8); 
    line-height: 1.6;
    max-width: 320px;
  }

  /* Lado Direito: Form Card */
  .login-card-glass {
    width: 380px;
    max-width: 100%;
    z-index: 2;
    padding: 36px 32px;
    border-radius: 20px;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(37, 99, 235, 0.25);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
  }

  .login-card-title {
    font-size: 1.75rem; 
    font-weight: 700; 
    color: #ffffff; 
    margin-bottom: 24px;
    text-align: left;
    position: relative;
    display: inline-block;
  }

  .login-card-title::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 28px;
    height: 3px;
    background: #2563eb;
    border-radius: 2px;
  }

  .input-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 16px;
    text-align: left;
  }

  .input-group label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #cbd5e1;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .input-group label i {
    color: #38bdf8;
    font-size: 0.8rem;
  }

  .login-input {
    width: 100%; 
    padding: 12px 16px; 
    border: 1px solid rgba(37, 99, 235, 0.3); 
    border-radius: 30px; 
    background-color: rgba(15, 23, 42, 0.7); 
    color: #ffffff; 
    font-size: 0.9rem; 
    outline: none; 
    box-sizing: border-box; 
    transition: all 0.25s ease;
  }

  .login-input::placeholder {
    color: #64748b;
  }

  .login-input:focus {
    border-color: #2563eb;
    background-color: rgba(15, 23, 42, 0.9);
    box-shadow: 0 0 12px rgba(37, 99, 235, 0.4);
  }

  .login-input.input-error {
    border-color: #ef4444 !important;
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.4) !important;
  }

  .login-btn {
    width: 100%; 
    padding: 13px; 
    background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%); 
    color: #ffffff; 
    border: none; 
    border-radius: 30px; 
    font-weight: 700; 
    font-size: 0.95rem; 
    cursor: pointer; 
    margin-top: 10px; 
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(56, 189, 248, 0.45);
  }

  .login-footer-links {
    margin-top: 20px; 
    text-align: center;
    font-size: 0.85rem;
  }

  .text-no-account {
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
  }

  .login-link {
    color: #38bdf8;
    text-decoration: none;
    font-weight: 600;
  }

  .login-link:hover {
    text-decoration: underline;
  }

  .login-link-secondary {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    display: inline-block;
    margin-top: 8px;
  }

  .login-link-secondary:hover {
    color: #ffffff;
  }

  /* --- MODO CLARO (Fundo Azul Transparente) --- */
  [data-theme="light"] .login-container,
  body.light-theme .login-container,
  body.light .login-container {
    background: linear-gradient(135deg, rgba(224, 242, 254, 0.65) 0%, rgba(186, 230, 253, 0.55) 50%, rgba(125, 211, 252, 0.45) 100%);
    border-color: rgba(56, 189, 248, 0.5);
    box-shadow: 0 20px 40px rgba(2, 132, 199, 0.12);
  }

  [data-theme="light"] .login-branding h2,
  body.light-theme .login-branding h2,
  body.light .login-branding h2 {
    color: #0369a1;
  }

  [data-theme="light"] .login-branding p,
  body.light-theme .login-branding p,
  body.light .login-branding p {
    color: #0c4a6e;
  }

  [data-theme="light"] .login-card-glass,
  body.light-theme .login-card-glass,
  body.light .login-card-glass {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 15px 30px rgba(3, 105, 161, 0.15);
  }

  [data-theme="light"] .login-card-title,
  body.light-theme .login-card-title,
  body.light .login-card-title {
    color: #0369a1;
  }

  [data-theme="light"] .input-group label,
  body.light-theme .input-group label,
  body.light .input-group label {
    color: #0284c7;
  }

  [data-theme="light"] .login-input,
  body.light-theme .login-input,
  body.light .login-input {
    background-color: rgba(255, 255, 255, 0.9);
    border: 1px solid #7dd3fc;
    color: #0c4a6e;
  }

  [data-theme="light"] .login-input::placeholder,
  body.light-theme .login-input::placeholder,
  body.light .login-input::placeholder {
    color: #0284c7;
    opacity: 0.6;
  }

  [data-theme="light"] .text-no-account,
  body.light-theme .text-no-account,
  body.light .text-no-account {
    color: #0369a1;
  }

  [data-theme="light"] .login-link,
  body.light-theme .login-link,
  body.light .login-link {
    color: #0284c7;
  }

  [data-theme="light"] .login-link-secondary,
  body.light-theme .login-link-secondary,
  body.light .login-link-secondary {
    color: #0369a1;
  }

  /* REGRAS DE ALTO CONTRASTE */
  html.high-contrast,
  body.high-contrast,
  .high-contrast .login-page,
  [data-theme="light"].high-contrast .login-container,
  body.light-theme.high-contrast .login-container,
  body.light.high-contrast .login-container {
    background-color: #000000 !important;
    background: #000000 !important;
    color: #ffffff !important;
  }

  .high-contrast .login-container,
  .high-contrast .login-card-glass,
  .high-contrast .login-branding,
  [data-theme="light"].high-contrast .login-card-glass,
  body.light-theme.high-contrast .login-card-glass {
    background-color: #000000 !important;
    background: #000000 !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    box-shadow: none !important;
  }

  .high-contrast .login-container::before,
  .high-contrast .login-container::after {
    display: none !important;
  }

  .high-contrast .login-container {
    border: 3px solid #ffff00 !important;
  }

  .high-contrast .login-card-glass {
    border: 2px solid #ffffff !important;
  }

  .high-contrast .login-card-title,
  .high-contrast .login-branding h2,
  [data-theme="light"].high-contrast .login-card-title,
  [data-theme="light"].high-contrast .login-branding h2,
  body.light-theme.high-contrast .login-card-title,
  body.light-theme.high-contrast .login-branding h2 {
    color: #ffff00 !important;
    background: transparent !important;
  }

  .high-contrast .login-card-title::after {
    background: #ffff00 !important;
  }

  .high-contrast .login-branding p,
  .high-contrast .text-no-account,
  .high-contrast .login-footer-links *,
  [data-theme="light"].high-contrast .login-branding p,
  [data-theme="light"].high-contrast .text-no-account {
    background-color: transparent !important;
    box-shadow: none !important;
    color: #ffffff !important;
  }

  .high-contrast .input-group label,
  .high-contrast .input-group label i {
    color: #ffff00 !important;
  }

  .high-contrast .login-input,
  [data-theme="light"].high-contrast .login-input {
    background-color: #000000 !important;
    border: 2px solid #ffffff !important;
    color: #ffffff !important;
  }

  .high-contrast .login-input:focus {
    border-color: #ffff00 !important;
    box-shadow: 0 0 0 2px #ffff00 !important;
  }

  .high-contrast .login-input::placeholder {
    color: #aaaaaa !important;
  }

  .high-contrast .login-btn {
    background: #ffff00 !important;
    color: #000000 !important;
    border: 2px solid #ffffff !important;
    font-weight: 900 !important;
  }

  .high-contrast .login-btn * {
    color: #000000 !important;
  }

  .high-contrast .login-link,
  .high-contrast .login-link-secondary,
  [data-theme="light"].high-contrast .login-link,
  [data-theme="light"].high-contrast .login-link-secondary {
    color: #ffff00 !important;
    text-decoration: underline !important;
  }

  .high-contrast .login-branding .logo-img {
    background: transparent !important;
    filter: invert(1) grayscale(100%) !important;
    -webkit-filter: invert(1) grayscale(100%) !important;
  }

  @media (max-width: 768px) {
    .login-container {
      flex-direction: column;
      padding: 30px 20px;
    }
    .login-branding {
      padding-right: 0;
      text-align: center;
      margin-bottom: 24px;
    }
    .login-branding p {
      margin: 0 auto;
    }
    .login-card-glass {
      width: 100%;
    }
  }
</style>

<main class="login-page">

  <br><br><br>

  <?php if (session()->getFlashdata('erro')): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Erro de Autenticação',
        text: '<?= session()->getFlashdata('erro') ?>',
        confirmButtonColor: '#2563eb'
      });
    </script>
  <?php endif; ?>

  <?php if (session()->getFlashdata('sucesso')): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '<?= session()->getFlashdata('sucesso') ?>',
        confirmButtonColor: '#2563eb'
      });
    </script>
  <?php endif; ?>

  <div class="login-container">
    
    <!-- LADO ESQUERDO: Marca VISIO (Logo Dinâmica) -->
    <div class="login-branding">
      <img class="theme-img logo-img" 
           src="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>"
           data-light="<?= base_url('assets/images/logos/Icone/IconeLight2.png') ?>"
           data-dark="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>" 
           alt="Logo VISIO">
      
      <h2>Bem-vindo!</h2>
      <p>Acesse a plataforma VISIO para gerenciar seus sensores IoT e dashboards em tempo real.</p>
    </div>

    <!-- LADO DIREITO: Form Card -->
    <div class="login-card-glass">
      <h1 class="login-card-title">Entrar</h1>

      <form id="loginForm" action="<?= base_url('/login') ?>" method="post" style="margin-top: 24px;" novalidate>
        <?= csrf_field() ?>
        
        <div class="input-group">
          <label for="email"><i class="fa-regular fa-envelope"></i> E-mail</label>
          <input type="email" name="email" id="email" placeholder="seu@email.com" class="login-input">
        </div>
        
        <div class="input-group">
          <label for="senha"><i class="fa-solid fa-lock"></i> Senha</label>
          <input type="password" name="senha" id="senha" placeholder="••••••••" class="login-input">
        </div>

        <button type="submit" class="login-btn">
          <i class="fa-solid fa-right-to-bracket"></i> Entrar
        </button>

        <button type="button" id="btnCartao" class="login-btn">
          <i class="fa-solid fa-right-to-bracket"></i> Entrar com cartão
        </button>

       <?php $googleClientId = env('GOOGLE_CLIENT_ID'); ?>

<?php if (!empty($googleClientId)): ?>
    <div class="google-login">
        <div id="g_id_onload"
             data-client_id="<?= esc($googleClientId) ?>"
             data-login_uri="<?= base_url('/login/google') ?>"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-shape="pill"
             data-theme="outline"
             data-text="continue_with"
             data-size="large"
             data-width="300"
             data-locale="pt-BR">
        </div>
    </div>
        <?php endif; ?>

      </form>

      <div class="login-footer-links">
        <p class="text-no-account">
          Não tem conta? <a href="<?= base_url('/usuario/cadastro') ?>" class="login-link"><i class="fa-solid fa-user-plus"></i> Cadastre-se</a>
        </p>
        <a href="<?= base_url('/usuario/esqueceu_senha') ?>" class="login-link-secondary">
          <i class="fa-solid fa-key"></i> Esqueceu a senha?
        </a>
      </div>
    </div>

  </div>
</main>

<script>
  const loginForm = document.getElementById('loginForm');
  const emailInput = document.getElementById('email');
  const senhaInput = document.getElementById('senha');
  const btnCartao = document.getElementById('btnCartao');

  [emailInput, senhaInput].forEach(input => {
    input.addEventListener('input', function() {
      if (this.value.trim() !== '') {
        this.classList.remove('input-error');
      }
    });
  });

  // btnCartao.addEventListener('click', function() {
  //   const DURACAO_MAXIMA_MS = 30000;
  //   const INTERVALO_POLLING_MS = 1500;

  //   let intervaloId = null;

  //   const pararPolling = () => {
  //     if (intervaloId !== null) {
  //       clearInterval(intervaloId);
  //       intervaloId = null;
  //     }
  //   };

  //   Swal.fire({
  //     title: "Aguardando aproximação",
  //     html: "Aproxime o cartão do leitor.",
  //     timer: DURACAO_MAXIMA_MS,
  //     timerProgressBar: true,
  //     allowOutsideClick: true,
  //     didOpen: () => {
  //       Swal.showLoading();

  //       intervaloId = setInterval(async () => {
  //         try {
  //           const resposta = await fetch("<?= base_url('login/cartao') ?>", {
  //             method: 'POST',
  //             headers: { 'Accept': 'application/json' },
  //           });
  //           const dados = await resposta.json();

  //           if (dados.autorizado) {
  //             pararPolling();
  //             Swal.close();
  //             window.location.href = dados.redirect || "<?= base_url('perfil') ?>";
  //           }
  //         } catch (erro) {
  //           // Falha de rede pontual durante o polling não deve
  //           // interromper a espera — só registra e tenta de novo no
  //           // próximo intervalo.
  //           console.error('Erro ao consultar login por cartão:', erro);
  //         }
  //       }, INTERVALO_POLLING_MS);
  //     },
  //     willClose: () => {
  //       pararPolling();
  //     }
  //   }).then((resultado) => {
  //     // Só mostra "tempo esgotado" se o motivo do fechamento foi
  //     // realmente o timer acabar — se fechamos programaticamente após
  //     // um login bem-sucedido (Swal.close() acima), o dismiss não é
  //     // "timer" e o redirecionamento já está em andamento.
  //     if (resultado.dismiss === Swal.DismissReason.timer) {
  //       Swal.fire({
  //         title: "Nenhum cartão identificado",
  //         text: "Não conseguimos identificar um cartão autorizado a tempo. Tente novamente.",
  //         icon: "error",
  //         confirmButtonColor: '#2563eb'
  //       });
  //     }
  //   });
  // });

btnCartao.addEventListener('click', function() {

  const DURACAO_MAXIMA_MS = 4000;
  const INTERVALO_POLLING_MS = 500;

  let intervaloId = null;

  const pararPolling = () => {
    if (intervaloId !== null) {
      clearInterval(intervaloId);
      intervaloId = null;
    }
  };

  Swal.fire({
    title: "Aguardando aproximação",
    text: "Aproxime o cartão do leitor.",
    timer: DURACAO_MAXIMA_MS,
    timerProgressBar: true,
    allowOutsideClick: true,
    didOpen: () => {

      Swal.showLoading();

      intervaloId = setInterval(async () => {

        try {

          const resposta = await fetch("<?= base_url('login/cartao') ?>", {
            method: "GET",
            headers: {
              "Accept": "application/json"
            },
            cache: "no-store"
          });

          const dados = await resposta.json();

          console.log("Login cartão:", dados);

          if (dados.cartao_lido === true) {

            pararPolling();

            if (dados.autorizado === true) {

              Swal.close();

              window.location.href = dados.redirect || "<?= base_url('perfil') ?>";

            } else {

              Swal.fire({
                title: "Não autorizado",
                text: "Este cartão não está cadastrado.",
                icon: "error",
                confirmButtonText: "OK",
                confirmButtonColor: "#2563eb"
              });

            }

          }

        } catch (erro) {

          console.error("Erro:", erro);

        }

      }, INTERVALO_POLLING_MS);
    },

    willClose: () => {
      pararPolling();
    }

  }).then((resultado) => {

    if (resultado.dismiss === Swal.DismissReason.timer) {

      Swal.fire({
        title: "Erro ao identificar cartão",
        text: "Tente novamente.",
        icon: "error",
        confirmButtonText: "OK",
        confirmButtonColor: "#2563eb"
      });

    }

  });

});

  // Validação apenas do envio do formulário padrão
  loginForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const email = emailInput.value.trim();
    const senha = senhaInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    emailInput.classList.remove('input-error');
    senhaInput.classList.remove('input-error');

    if (!email || !senha) {
      if (!email) emailInput.classList.add('input-error');
      if (!senha) senhaInput.classList.add('input-error');

      Swal.fire({
        icon: 'warning',
        title: 'Campos Incompletos',
        text: 'Por favor, preencha os campos destacados para continuar.',
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Entendido'
      });
      return;
    }

    if (!emailRegex.test(email)) {
      emailInput.classList.add('input-error');

      Swal.fire({
        icon: 'error',
        title: 'E-mail Inválido',
        text: 'Por favor, digite um endereço de e-mail válido.',
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Corrigir'
      });
      return;
    }

    e.target.submit();
  });
</script>

<?= view('sistema/layout/footer') ?>