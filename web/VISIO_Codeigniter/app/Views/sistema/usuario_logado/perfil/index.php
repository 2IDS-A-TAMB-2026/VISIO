<?= view('sistema/layout/header') ?>

<style>
  .perfil-page {
    min-height: 80vh;
    padding: 40px 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }

  .perfil-wrapper {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    width: 100%;
    max-width: 1000px;
    align-items: flex-start;
  }

  /* NOVA COLUNA PARA EMPILHAR AS LATERAIS */
  .perfil-coluna-lateral {
    display: flex;
    flex-direction: column;
    gap: 20px; /* Espaço entre o primeiro e o segundo card lateral */
    width: 260px;
    flex-shrink: 0;
  }

  /* LATERAL */
  .perfil-lateral {
    width: 100%; /* Agora ocupa 100% da largura da coluna pai (260px) */
    background: var(--color-bg-card, #1a1a24);
    border-radius: 24px;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    border: 1px solid var(--color-border, rgba(255,255,255,.08));
    box-sizing: border-box;
  }

  /* AJUSTADO: inline garante os números na mesma linha e removeu o break-all antigo */
  .perfil-lateral strong {
    font-size: 15px;
    font-weight: 700;
    display: inline; 
  }

  .perfil-foto-wrap {
    position: relative;
    width: 140px;
    height: 140px;
    margin-bottom: 15px;
  }

  .perfil-foto {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--color-accent, #3a86ff);
  }

  .perfil-foto-label {
    position: absolute;
    bottom: 4px;
    right: 4px;
    background: var(--color-accent, #3a86ff);
    color: #fff;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: background .2s;
  }

  .perfil-foto-label:hover { background: #2668d1; }

  .perfil-cpf-badge {
    opacity: .7;
    margin-top: 6px;
    word-break: break-all;
  }

  /* NOVO: Organização interna do card de desempenho */
  .card-desempenho {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .titulo-desempenho {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
  }

  .texto-desempenho {
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
    opacity: 0.9;
  }

  .bloco-percentual {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    margin-top: 5px;
  }

  .percentual-desempenho {
    font-size: 28px;
    font-weight: 800;
    color: var(--color-accent, #3a86ff);
    line-height: 1.1;
  }

  .legenda-desempenho {
    font-size: 13px;
    opacity: .7;
    display: block;
  }

  /* CARD FORMULÁRIO */
  .perfil-card {
    flex: 1;
    min-width: 320px;
    background: var(--color-bg-card, #1a1a24);
    border-radius: 24px;
    padding: 35px;
    border: 1px solid var(--color-border, rgba(255,255,255,.08));
  }

  .perfil-card h2 {
    margin-bottom: 24px;
  }

  .campo-grupo {
    margin-bottom: 18px;
  }

  .campo-grupo label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
    font-size: 14px;
    opacity: .85;
  }

  .campo-grupo input {
    width: 100%;
    height: 50px;
    padding: 0 16px;
    border: 1px solid var(--color-border, rgba(255,255,255,.12));
    border-radius: 12px;
    background: var(--color-bg, #0e0e16);
    color: var(--color-text, #fff);
    font-size: 15px;
    box-sizing: border-box;
    transition: border-color .2s;
  }

  .campo-grupo input:focus {
    outline: none;
    border-color: var(--color-accent, #3a86ff);
  }

  .campo-grupo input[readonly] {
    opacity: .5;
    cursor: not-allowed;
  }

  .campo-grupo input[type="date"] {
    color: var(--color-text, #fff);
  }

  .campo-grupo small {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    opacity: .6;
  }

  .senha-toggle-wrap {
    position: relative;
  }

  .senha-toggle-wrap input {
    padding-right: 48px;
  }

  .btn-ver-senha {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: inherit;
    opacity: .6;
    font-size: 16px;
    padding: 0;
  }

  .btn-ver-senha:hover { opacity: 1; }

  .btn-salvar-perfil {
    width: 100%;
    height: 52px;
    border: none;
    border-radius: 14px;
    background: var(--color-accent, #3a86ff);
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background .2s;
  }

  .btn-salvar-perfil:hover { background: #2668d1; }

  /* tema claro */
  body.light .perfil-lateral,
  body.light .perfil-card {
    background: #fff;
    border-color: #e2e8f0;
  }

  body.light .campo-grupo input {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
  }

</style>

<main class="perfil-page">
  <div class="perfil-wrapper" style="margin-top: 5%;">

    <div class="perfil-coluna-lateral">
      
      <div class="perfil-lateral">
        <div class="perfil-foto-wrap">
          <img id="preview_foto"
               src="<?= !empty($usuario['FOTO']) ? base_url($usuario['FOTO']) : base_url('assets/images/avatar_default.png') ?>"
               alt="Foto de perfil"
               class="perfil-foto"
               onerror="this.src='https://ui-avatars.com/api/?name=U&background=3a86ff&color=fff&size=140'">
          <label for="foto_input" class="perfil-foto-label" title="Alterar foto">
            <i class="fa-solid fa-camera"></i>
          </label>
        </div>
        <strong><?= esc($usuario['NOME'] ?? '') !== '' ? esc($usuario['NOME']) : esc($usuario['EMAIL'] ?? '') ?></strong>
        <span class="perfil-cpf-badge">CPF: <?= esc($usuario['CPF'] ?? '') ?></span>
      </div>


<!--CARD COM OS DADOS DE ACEETO E ERRO, EMBAIXO DA FOTO-->
      <div class="perfil-lateral">
    <div class="card-desempenho">
        <h3 class="titulo-desempenho">Seu desempenho</h3>
        
        <p class="texto-desempenho">
            Você acertou <strong><?= $acertos ?></strong> de <strong><?= $total ?></strong> questões.
        </p>
        
        <div class="bloco-percentual">
            <span class="percentual-desempenho"><?= $percentual ?>%</span>
            <span class="legenda-desempenho">de aproveitamento</span>
        </div>
    </div>
</div>



    </div> <div class="perfil-card">
      <h2><i class="fa-solid fa-user-pen"></i> Dados da conta</h2>

      <?php if (session()->getFlashdata('sucesso')): ?>
        <p class="contato-feedback contato-feedback--ok" style="margin-bottom:16px;">
          <?= session()->getFlashdata('sucesso') ?>
        </p>
      <?php endif; ?>

      <?php if (session()->getFlashdata('erro')): ?>
        <p class="contato-feedback contato-feedback--erro" style="margin-bottom:16px;">
          <?= session()->getFlashdata('erro') ?>
        </p>
      <?php endif; ?>

      <form action="<?= base_url('/perfil') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <input type="file" id="foto_input" name="foto" accept="image/*" style="display:none;">
        <div class="campo-grupo">
          <label>Nome</label>
          <input type="text" name="nome" value="<?= esc($usuario['NOME'] ?? '') ?>" required>
        </div>

        <div class="campo-grupo">
          <label>CPF</label>
          <input type="text" value="<?= esc($usuario['CPF'] ?? '') ?>" readonly>
          <small>O CPF não pode ser alterado.</small>
        </div>

        <div class="campo-grupo">
          <label>E-mail</label>
          <input type="email" name="email" value="<?= esc($usuario['EMAIL'] ?? '') ?>" required>
        </div>

        <div class="campo-grupo">
          <label>Telefone</label>
          <input type="tel" name="telefone"
                 value="<?= esc($usuario['TELEFONE'] ?? '') ?>"
                 placeholder="(00) 00000-0000"
                 id="campo_telefone">
        </div>


        <div class="campo-grupo">
          <label>Data de nascimento</label>
          <input type="date" id="dataLimite" name="data_nascimento" value="<?= esc($usuario['DATA_NASCIMENTO'] ?? '') ?>">
        </div>

      <script>
        // O seu script do JavaScript que bloqueia datas futuras continua aqui embaixo igualzinho
        const hoje = new Date();
        const ano = hoje.getFullYear();
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const dia = String(hoje.getDate()).padStart(2, '0');
        const dataFormatada = `${ano}-${mes}-${dia}`;
        document.getElementById('dataLimite').max = dataFormatada;
      </script>


        <div class="campo-grupo">
          <label>Número do cartão IoT</label>
          <input type="text" name="cartao"
                 value="<?= esc($usuario['CARTAO'] ?? '') ?>">
        </div>

        <div class="campo-grupo">
          <label>Nova senha <small style="display:inline;opacity:.6;">(deixe em branco para não alterar)</small></label>
          <div class="senha-toggle-wrap">
            <input type="password" name="senha" id="campo_senha" placeholder="Digite a nova senha">
            <button type="button" class="btn-ver-senha" onclick="toggleSenha()" title="Mostrar/ocultar senha">
              <i class="fa-solid fa-eye" id="icon_senha"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-salvar-perfil">
          <i class="fa-solid fa-floppy-disk"></i> Salvar alterações
        </button>

      </form>
    </div>

  </div>
</main>

<script>
  // Preview de foto antes do upload (Card 1)
  document.getElementById('foto_input').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('preview_foto').src = e.target.result;
    };
    reader.readAsDataURL(file);
  });

  // Mostrar/ocultar senha
  function toggleSenha() {
    const campo = document.getElementById('campo_senha');
    const icon  = document.getElementById('icon_senha');
    if (campo.type === 'password') {
      campo.type = 'text';
      icon.className = 'fa-solid fa-eye-slash';
    } else {
      campo.type = 'password';
      icon.className = 'fa-solid fa-eye';
    }
  }

  // Máscara telefone
  document.getElementById('campo_telefone').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').slice(0, 11);
    if (v.length > 6)      v = v.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    else if (v.length > 2) v = v.replace(/^(\d{2})(\d{0,5})/,        '($1) $2');
    else if (v.length > 0) v = v.replace(/^(\d{0,2})/,               '($1');
    this.value = v;
  });
</script>

<script src="<?= base_url('assets/js/validacaorecuperar.js')?>"></script>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<?= view('sistema/layout/footer') ?>