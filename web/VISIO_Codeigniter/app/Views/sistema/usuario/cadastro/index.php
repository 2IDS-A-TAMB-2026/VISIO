<?= view('sistema/layout/header') ?>
<br><br>

<?php
// Se o formulário for enviado (clicou no botão de cadastrar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Pega os dados digitados
    $cpf = $_POST['cpf'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = password_hash($_POST['senha'] ?? '', PASSWORD_BCRYPT);
    $cartao = $_POST['cartao'] ?? ''; // <--- PEGA O NÚMERO GERADO NA TELA
    $dataNascimento = $_POST['data_nascimento'] ?? '';
    $telefone = $_POST['telefone'] ?? '';

    // Salva no banco de dados BD_VISIO
    $sql = "INSERT INTO USUARIO (CPF, NOME, EMAIL, SENHA, CARTAO, DATA_NASCIMENTO, TELEFONE) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$cpf, $nome, $email, $senha, $cartao, $dataNascimento, $telefone])) {
        echo "<script>alert('Usuário e Cartão cadastrados com sucesso!');</script>";
    }
}
?>

<!-- SweetAlert & FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  .signup-page {
    display: flex; 
    justify-content: center; 
    align-items: center; 
    padding: 30px 20px; 
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  }

  /* Main Container */
  .signup-container {
    position: relative;
    display: flex; 
    width: 980px; 
    max-width: 100%; 
    min-height: 620px; 
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
  .signup-container::before {
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

  .signup-container::after {
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
  .signup-card-glass {
    width: 480px;
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

  .signup-card-title {
    font-size: 1.85rem; 
    font-weight: 700; 
    color: #ffffff; 
    margin-bottom: 6px;
    text-align: left;
    position: relative;
    display: inline-block;
    letter-spacing: -0.5px;
  }

  .signup-card-title::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 32px;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #38bdf8);
    border-radius: 2px;
  }

  .signup-subtitle {
    font-size: 0.88rem;
    color: #94a3b8;
    margin-bottom: 22px;
  }

  /* Grid de Inputs */
  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 14px;
  }

  .form-group-full {
    grid-column: span 2;
  }

  .input-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
    text-align: left;
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

  .signup-input {
    width: 100%; 
    padding: 10px 14px; 
    border: 1px solid rgba(255, 255, 255, 0.12); 
    border-radius: 12px; 
    background-color: rgba(15, 23, 42, 0.6); 
    color: #f8fafc; 
    font-size: 0.88rem; 
    outline: none; 
    box-sizing: border-box; 
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .signup-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: rgba(15, 23, 42, 0.3);
  }

  .signup-input::placeholder {
    color: #64748b;
  }

  .signup-input:focus:not(:disabled) {
    border-color: #38bdf8;
    background-color: rgba(15, 23, 42, 0.85);
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.18);
  }

  .signup-input.input-error {
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

  .signup-btn {
    width: 100%; 
    padding: 12px; 
    background: linear-gradient(135deg, #2563eb 0%, #0284c7 100%); 
    color: #ffffff; 
    border: none; 
    border-radius: 12px; 
    font-weight: 600; 
    font-size: 0.95rem; 
    cursor: pointer; 
    margin-top: 14px; 
    transition: all 0.25s ease;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .signup-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 189, 248, 0.45);
    background: linear-gradient(135deg, #1d4ed8 0%, #0369a1 100%);
  }

  .signup-footer {
    margin-top: 18px; 
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
  .signup-branding {
    flex: 1;
    z-index: 2;
    padding-left: 30px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .signup-branding .logo {
    max-width: 280px; 
    width: 100%;
    height: auto; 
    object-fit: contain; 
    margin-bottom: 28px;
    filter: drop-shadow(0 14px 28px rgba(0,0,0,0.45));
  }

  .signup-branding h2 {
    font-size: 2.2rem !important; 
    font-weight: 700 !important; 
    margin-bottom: 12px; 
    color: #ffffff;
    letter-spacing: -0.5px;
  }

  .signup-branding p {
    font-size: 0.95rem; 
    color: #94a3b8; 
    line-height: 1.6;
    max-width: 320px;
  }

  /* --- MODO CLARO --- */
  [data-theme="light"] .signup-container,
  body.light-theme .signup-container,
  body.light .signup-container {
    background: linear-gradient(135deg, rgba(241, 245, 249, 0.8) 0%, rgba(224, 242, 254, 0.7) 100%);
    border-color: rgba(56, 189, 248, 0.4);
    box-shadow: 0 25px 50px -12px rgba(2, 132, 199, 0.15);
  }

  [data-theme="light"] .signup-branding h2,
  body.light-theme .signup-branding h2,
  body.light .signup-branding h2 {
    color: #0f172a;
  }

  [data-theme="light"] .signup-branding p,
  body.light-theme .signup-branding p,
  body.light .signup-branding p {
    color: #475569;
  }

  [data-theme="light"] .signup-card-glass,
  body.light-theme .signup-card-glass,
  body.light .signup-card-glass {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 20px 40px rgba(14, 165, 233, 0.12);
  }

  [data-theme="light"] .signup-card-title,
  body.light-theme .signup-card-title,
  body.light .signup-card-title {
    color: #0f172a;
  }

  [data-theme="light"] .signup-subtitle,
  body.light-theme .signup-subtitle,
  body.light .signup-subtitle {
    color: #64748b;
  }

  [data-theme="light"] .input-group label,
  body.light-theme .input-group label,
  body.light .input-group label {
    color: #334155;
  }

  [data-theme="light"] .signup-input,
  body.light-theme .signup-input,
  body.light .signup-input {
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    color: #0f172a;
  }

  /* Mantém o fundo branco ao focar no modo claro */
  [data-theme="light"] .signup-input:focus:not(:disabled),
  body.light-theme .signup-input:focus:not(:disabled),
  body.light .signup-input:focus:not(:disabled) {
    background-color: #ffffff !important;
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
  }

  [data-theme="light"] .signup-input:disabled {
    background-color: #e2e8f0;
  }

  [data-theme="light"] .signup-input::placeholder,
  body.light-theme .signup-input::placeholder,
  body.light .signup-input::placeholder {
    color: #94a3b8;
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
  .high-contrast .signup-page,
  [data-theme="light"].high-contrast .signup-container,
  body.light-theme.high-contrast .signup-container,
  body.light.high-contrast .signup-container {
    background-color: #000000 !important;
    background: #000000 !important;
    color: #ffffff !important;
  }

  .high-contrast .signup-container,
  .high-contrast .signup-card-glass,
  .high-contrast .signup-branding,
  [data-theme="light"].high-contrast .signup-card-glass,
  body.light-theme.high-contrast .signup-card-glass {
    background-color: #000000 !important;
    background: #000000 !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    box-shadow: none !important;
  }

  .high-contrast .signup-container::before,
  .high-contrast .signup-container::after {
    display: none !important;
  }

  .high-contrast .signup-container {
    border: 3px solid #ffff00 !important;
  }

  .high-contrast .signup-card-glass {
    border: 2px solid #ffffff !important;
  }

  .high-contrast .signup-card-title,
  .high-contrast .signup-branding h2,
  [data-theme="light"].high-contrast .signup-card-title,
  [data-theme="light"].high-contrast .signup-branding h2,
  body.light-theme.high-contrast .signup-card-title,
  body.light-theme.high-contrast .signup-branding h2 {
    color: #ffff00 !important;
    background: transparent !important;
  }

  .high-contrast .signup-card-title::after {
    background: #ffff00 !important;
  }

  .high-contrast .signup-subtitle,
  .high-contrast .signup-branding p,
  .high-contrast .signup-footer *,
  [data-theme="light"].high-contrast .signup-subtitle,
  [data-theme="light"].high-contrast .signup-branding p {
    background-color: transparent !important;
    box-shadow: none !important;
    color: #ffffff !important;
  }

  .high-contrast .input-group label,
  .high-contrast .input-group label i {
    color: #ffff00 !important;
  }

  .high-contrast .signup-input,
  [data-theme="light"].high-contrast .signup-input {
    background-color: #000000 !important;
    border: 2px solid #ffffff !important;
    color: #ffffff !important;
  }

  .high-contrast .signup-input:disabled {
    border-color: #666666 !important;
    color: #888888 !important;
  }

  .high-contrast .signup-input:focus:not(:disabled) {
    border-color: #ffff00 !important;
    box-shadow: 0 0 0 2px #ffff00 !important;
  }

  .high-contrast .signup-input::placeholder {
    color: #aaaaaa !important;
  }

  .high-contrast .signup-btn {
    background: #ffff00 !important;
    color: #000000 !important;
    border: 2px solid #ffffff !important;
    font-weight: 900 !important;
  }

  .high-contrast .signup-btn * {
    color: #000000 !important;
  }

  .high-contrast .link-voltar-login,
  [data-theme="light"].high-contrast .link-voltar-login {
    color: #ffff00 !important;
    text-decoration: underline !important;
  }

  /* LOGO CORRIGIDA (SEM BLOCO BRANCO) */
  .high-contrast .signup-branding .logo {
    background: transparent !important;
    filter: invert(1) grayscale(100%) !important;
    -webkit-filter: invert(1) grayscale(100%) !important;
  }

  @media (max-width: 850px) {
    .signup-container {
      flex-direction: column-reverse;
      padding: 32px 20px;
    }
    .signup-branding {
      padding-left: 0;
      margin-bottom: 24px;
    }
    .signup-branding .logo {
      max-width: 220px;
    }
    .signup-card-glass {
      width: 100%;
    }
    .form-grid {
      grid-template-columns: 1fr;
    }
    .form-group-full {
      grid-column: span 1;
    }
  }
</style>

<main class="signup-page">

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

  <div class="signup-container">
    
    <!-- LADO ESQUERDO: Form Card Glass -->
    <div class="signup-card-glass">
      <h1 class="signup-card-title">Criar Conta</h1>
      <p class="signup-subtitle">Cadastre-se para acessar o ecossistema VISIO.</p>

      <form action="<?= base_url('/usuario/cadastro') ?>" method="post" id="form" novalidate>
        <?= csrf_field() ?>
        
        <div class="form-grid">
          
          <div class="input-group form-group-full">
            <label for="nome"><i class="fa-regular fa-user"></i> Nome Completo</label>
            <input type="text" name="nome" id="nome" placeholder="Ex: João Silva" class="signup-input">
            <span class="erro" id="erroNome"></span>
          </div>

          <div class="input-group">
            <label for="cpf"><i class="fa-regular fa-address-card"></i> CPF</label>
            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14" class="signup-input">
            <span class="erro" id="erroCpf"></span>
          </div>

          <div class="input-group">
            <label for="telefone"><i class="fa-solid fa-mobile-screen-button"></i> Telefone</label>
            <input type="tel" name="telefone" id="telefone" placeholder="(00) 00000-0000" maxlength="15" class="signup-input">
            <span class="erro" id="erroTelefone"></span>
          </div>

          <div class="input-group form-group-full">
            <label for="email"><i class="fa-regular fa-envelope"></i> E-mail</label>
            <input type="email" name="email" id="email" placeholder="nome@empresa.com" class="signup-input">
            <span class="erro" id="erroEmail"></span>
          </div>

          <div class="input-group">
            <label for="data_nascimento"><i class="fa-regular fa-calendar"></i> Nascimento</label>
            <input type="date" name="data_nascimento" id="data_nascimento" class="signup-input">
            <span class="erro" id="erroDataNascimento"></span>
          </div>

          <div class="input-group">
            <label for="cartao"><i class="fa-regular fa-credit-card"></i> Nº do Cartão</label>
            <input type="text" name="cartao" id="cartao" placeholder="0000 0000 0000 0000" maxlength="25" class="signup-input" readonly>
            <span class="erro" id="erroCartao"></span>
          </div>

          <script>
            window.addEventListener('DOMContentLoaded', function() {
              const b = () => Math.floor(1000 + Math.random() * 9000);
              document.getElementById('cartao').value = `${b()} ${b()} ${b()} ${b()}`;
            });
          </script>

          <div class="input-group form-group-full">
            <label for="senha"><i class="fa-solid fa-lock"></i> Senha</label>
            <input type="password" name="senha" id="senha" placeholder="••••••••" class="signup-input">
            <span class="erro" id="erroSenha"></span>
          </div>

        </div>

        <button type="submit" class="signup-btn">
          <i class="fa-solid fa-user-plus"></i> Finalizar Cadastro
        </button>
      </form>

      <div class="signup-footer">
        <a href="<?= base_url('/login') ?>" class="link-voltar-login">
          <i class="fa-solid fa-arrow-left"></i> Voltar para o login
        </a>
      </div>
    </div>

    <!-- LADO DIREITO: Branding -->
    <div class="signup-branding">
      <img class="theme-img logo"
           src="<?= base_url('assets/images/logos/Icone/IconeDark.png') ?>"
           data-light="<?= base_url('assets/images/logos/Icone/IconeLight.png') ?>"
           data-dark="<?= base_url('assets/images/logos/Icone/IconeDark.png') ?>"
           alt="Logo VISIO">
      
      <h2>Plataforma VISIO</h2>
      <p>Gerenciamento de dispositivos e inteligência de dados centralizados em tempo real.</p>
    </div>

  </div>
</main>

<script>
  const form = document.getElementById('form');
  const cpfInput = document.getElementById('cpf');
  const telefoneInput = document.getElementById('telefone');
  const cartaoInput = document.getElementById('cartao');
  
  const erroTelefone = document.getElementById('erroTelefone');
  const erroCpf = document.getElementById('erroCpf');

  // Clear errors on input
  document.querySelectorAll('.signup-input').forEach(input => {
    input.addEventListener('input', function() {
      this.classList.remove('input-error');
      const erroSpan = document.getElementById('erro' + this.id.charAt(0).toUpperCase() + this.id.slice(1));
      if (erroSpan) erroSpan.textContent = '';
    });
  });

  // CPF Mask
  cpfInput.addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '');
    if (v.length > 11) v = v.slice(0, 11);
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    e.target.value = v;
  });

  // Phone Mask
  telefoneInput.addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '');
    if (v.length > 11) v = v.slice(0, 11);
    
    if (v.length > 10) {
      v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else if (v.length > 6) {
      v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
    } else if (v.length > 2) {
      v = v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
    } else if (v.length > 0) {
      v = v.replace(/^(\d*)$/, '($1');
    }
    e.target.value = v;
  });

  // Credit Card Mask
  if (cartaoInput && !cartaoInput.disabled) {
    cartaoInput.addEventListener('input', function(e) {
      let v = e.target.value.replace(/\D/g, '');
      v = v.replace(/(.{4})/g, '$1 ').trim();
      e.target.value = v;
    });
  }

  // Validation
  form.addEventListener('submit', function(e) {
    const apenasNumerosTel = telefoneInput.value.replace(/\D/g, '');
    const apenasNumerosCpf = cpfInput.value.replace(/\D/g, '');

    let temErro = false;

    if (apenasNumerosTel.length < 10) {
      temErro = true;
      telefoneInput.classList.add('input-error');
      if (erroTelefone) erroTelefone.textContent = 'Mínimo de 10 dígitos (com DDD).';
    }

    if (apenasNumerosCpf.length !== 11) {
      temErro = true;
      cpfInput.classList.add('input-error');
      if (erroCpf) erroCpf.textContent = 'O CPF deve ter 11 dígitos.';
    }

    if (temErro) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Verifique os Campos',
        text: 'Por favor, corrija as informações destacadas antes de continuar.',
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Entendido'
      });
    }
  });
</script>

<?= view('sistema/layout/footer') ?>