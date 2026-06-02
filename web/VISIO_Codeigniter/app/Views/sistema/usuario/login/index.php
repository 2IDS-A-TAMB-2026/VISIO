<?= view('sistema/layout/header') ?>

<main class="login-page" style="display: grid; place-items: center; height: 100vh;">

  <?php if (session()->getFlashdata('erro')): ?>
    <p class="contato-feedback contato-feedback--erro"><?= session()->getFlashdata('erro') ?></p>
  <?php endif; ?>
  <?php if (session()->getFlashdata('sucesso')): ?>
    <p class="contato-feedback contato-feedback--ok"><?= session()->getFlashdata('sucesso') ?></p>
  <?php endif; ?>

  <div class="login-container">
    <div class="login-form">
      <h1>LOGIN</h1>
      <p>Entre com suas credenciais para acessar a plataforma.</p>
      <form action="<?= base_url('/login') ?>" method="post">
        <?= csrf_field() ?>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="senha" id="senha" placeholder="Senha" required>
        <br>
        <button type="submit" style="color:#fff">Entrar</button>
      </form>
      <div class="login-footer">
        <p>Não tem conta? <a href="<?= base_url('/usuario/cadastro') ?>">Cadastre-se</a></p>
      </div>
    </div>
    <div class="login-image">
      <img class="theme-img" src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                        data-light="<?= base_url('assets/images/logos/Logo/LogoLight.png') ?>"
                        data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>" alt="Logo" style="width: 100%;  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
    </div>
  </div>
</main>
<?= view('sistema/layout/footer') ?>