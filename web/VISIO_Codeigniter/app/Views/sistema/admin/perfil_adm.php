<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil — VISIO Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/style_adm.css') ?>" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{--bg:#f1f5f9;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--primary:#2563eb;--sidebar:#0f172a;--sidebar2:#111827;}
    body.dark{--bg:#0b1120;--card:#111827;--text:#f8fafc;--muted:#94a3b8;--border:#1e293b;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{background:var(--bg);color:var(--text);font-family:'Segoe UI',sans-serif;}
    .layout{display:flex;}
    .sidebar{width:260px;height:100vh;background:linear-gradient(180deg,var(--sidebar),var(--sidebar2));position:fixed;left:0;top:0;padding:28px 20px;display:flex;flex-direction:column;gap:4px;overflow-y:auto;}
    .sidebar .brand{color:#fff;font-size:1.4rem;font-weight:700;margin-bottom:32px;padding-left:8px;}
    .sidebar .menu-title{color:#475569;font-size:.72rem;text-transform:uppercase;letter-spacing:1px;margin:16px 0 8px 8px;}
    .sidebar a{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:10px;color:#94a3b8;text-decoration:none;font-size:.95rem;transition:.2s;}
    .sidebar a:hover,.sidebar a.active{background:rgba(37,99,235,.2);color:#fff;}
    .sidebar .logout{margin-top:auto;color:#ef4444!important;}
    .main{margin-left:260px;flex:1;}
    .topbar{height:72px;background:var(--card);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:10;box-shadow:0 2px 8px rgba(0,0,0,.05);}
    .topbar h2{font-size:1.1rem;}
    .topbar-right{display:flex;align-items:center;gap:14px;}
    .admin-badge{background:var(--primary);color:#fff;padding:8px 14px;border-radius:8px;font-size:.85rem;font-weight:600;}
    #theme-toggle-adm{width:38px;height:38px;border:none;border-radius:10px;background:var(--bg);color:var(--text);cursor:pointer;font-size:15px;}
    .content{padding:32px;max-width:900px;}
    .aviso-local{background:#fef3c7;border:1px solid #fcd34d;border-radius:12px;padding:14px 18px;margin-bottom:24px;font-size:.88rem;color:#92400e;display:flex;gap:10px;align-items:flex-start;}
    .perfil-wrapper{display:flex;gap:28px;flex-wrap:wrap;}
    .perfil-foto-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:28px;display:flex;flex-direction:column;align-items:center;gap:16px;width:260px;}
    .perfil-img{width:140px;height:140px;border-radius:50%;object-fit:cover;border:4px solid var(--primary);}
    .btn-trocar-foto{padding:10px 18px;background:var(--primary);color:#fff;border-radius:8px;cursor:pointer;font-size:.88rem;display:inline-flex;align-items:center;gap:7px;}
    .form-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:28px;flex:1;min-width:320px;}
    .form-card h2{font-size:1.1rem;margin-bottom:20px;}
    .input-group{margin-bottom:16px;}
    .input-group label{display:block;font-size:.82rem;color:var(--muted);margin-bottom:6px;font-weight:600;}
    .input-group input{width:100%;padding:12px 14px;border-radius:10px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:.93rem;}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    .btn-salvar{width:100%;padding:14px;background:var(--primary);color:#fff;border:none;border-radius:10px;cursor:pointer;font-size:1rem;font-weight:700;margin-top:8px;transition:opacity .2s;}
    .btn-salvar:hover{opacity:.85;}
    @media(max-width:768px){.sidebar{width:100%;height:auto;position:relative;}.main{margin-left:0;}.layout{flex-direction:column;}.form-row{grid-template-columns:1fr;}.perfil-wrapper{flex-direction:column;}}
  </style>
</head>
<body>

<div class="layout">


  <aside class="sidebar">
    <div class="brand"><i class="fa-solid fa-eye"></i> VISIO</div>
    <p class="menu-title">Menu Principal</p>
    <a href="<?= base_url('/admin/dashboard') ?>"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="<?= base_url('/admin/usuarios') ?>"><i class="fa-solid fa-users"></i> Usuários</a>
    <a href="<?= base_url('/admin/perguntas') ?>"><i class="fa-solid fa-clipboard-list"></i> Questões</a>
    <a href="<?= base_url('/admin/sensores') ?>"><i class="fa-solid fa-microchip"></i> Sensores</a>
    <a href="<?= base_url('/admin/respostas') ?>"><i class="fa-solid fa-chart-bar"></i> Respostas</a>
    <a href="<?= base_url('/logout') ?>" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
  </aside>

  <div class="main">
    <header class="topbar">
      <h2>Perfil do Administrador</h2>
      <div class="topbar-right">
        <div class="admin-badge">
          <i class="fa-solid fa-shield-halved"></i>
          <?= esc(session()->get('admin_email') ?? 'Admin') ?>
        </div>
        <button id="theme-toggle-adm"><i class="fa-solid fa-moon"></i></button>
      </div>
    </header>

    <div class="content">

      <!--
        AVISO: este perfil ainda salva os dados apenas no
        localStorage do navegador — não persiste no banco de dados.
        Os dados somem ao trocar de navegador ou limpar o cache.
        Para uma versão futura conectar ao AdminController com
        um método de atualização de perfil na tabela ADMIN.
      -->
      <div class="aviso-local">
        <i class="fa-solid fa-triangle-exclamation" style="margin-top:2px;"></i>
        <div>
          <strong>Atenção:</strong> os dados desta tela são salvos apenas no
          seu navegador (localStorage) e <strong>não vão para o banco de dados</strong>.
          Isso é suficiente para o TCC, mas em produção conectaria ao servidor.
        </div>
      </div>

      <div class="perfil-wrapper">


        <div class="perfil-foto-card">
          <img src="<?= base_url('assets/images/logos/Icone/IconeDark.png') ?>"
               alt="Foto de perfil do administrador"
               class="perfil-img" id="preview">
          <input type="file" id="file-foto" accept="image/*" hidden>
          <label for="file-foto" class="btn-trocar-foto">
            <i class="fa-solid fa-camera"></i> Alterar Foto
          </label>
          <p style="font-size:.8rem;color:var(--muted);text-align:center;">
            A foto fica salva localmente no navegador.
          </p>
        </div>


        <div class="form-card">
          <h2><i class="fa-solid fa-user-pen"></i> Informações pessoais</h2>

          <!--
            O form original enviava para api/login_adm.php (antigo).
            Como os dados ainda ficam no localStorage (limitação desta tela),
            o submit é interceptado pelo JS e exibe SweetAlert2 em vez de alert().
          -->
          <form id="form-perfil-adm" onsubmit="return false;">

            <div class="form-row">
              <div class="input-group">
                <label for="pnome">Nome</label>
                <input type="text" id="pnome" placeholder="Seu nome">
              </div>
              <div class="input-group">
                <label for="pemail">E-mail</label>
                <input type="email" id="pemail" placeholder="admin@visio.com">
              </div>
            </div>

            <div class="form-row">
              <div class="input-group">
                <label for="pcpf">CPF</label>
                <input type="text" id="pcpf" placeholder="000.000.000-00" maxlength="14">
              </div>
              <div class="input-group">
                <label for="ptelefone">Telefone</label>
                <input type="tel" id="ptelefone" placeholder="(19) 99999-9999">
              </div>
            </div>

            <div class="form-row">
              <div class="input-group">
                <label for="pdata">Data de nascimento</label>
                <input type="date" id="pdata">
              </div>
              <div class="input-group">
                <label for="pcartao">Cartão IoT</label>
                <input type="text" id="pcartao" placeholder="ID do cartão">
              </div>
            </div>

            <button type="submit" class="btn-salvar" id="btn-salvar-perfil">
              <i class="fa-solid fa-floppy-disk"></i> Salvar localmente
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
/* ── Tema ── */
const btnT = document.getElementById('theme-toggle-adm');
const iconT = btnT.querySelector('i');
if (localStorage.getItem('visio_adm_tema') === 'dark') {
  document.body.classList.add('dark');
  iconT.className = 'fa-solid fa-sun';
}
btnT.addEventListener('click', () => {
  document.body.classList.toggle('dark');
  const d = document.body.classList.contains('dark');
  localStorage.setItem('visio_adm_tema', d ? 'dark' : 'light');
  iconT.className = d ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
});

/* ── Foto: preview + salva em localStorage ── */
const fileFoto = document.getElementById('file-foto');
const preview  = document.getElementById('preview');

const fotoSalva = localStorage.getItem('visio_adm_foto');
if (fotoSalva) preview.src = fotoSalva;

fileFoto.addEventListener('change', function () {
  if (!this.files[0]) return;
  const reader = new FileReader();
  reader.onload = function (e) {
    preview.src = e.target.result;
    try {
      localStorage.setItem('visio_adm_foto', e.target.result);
    } catch (err) {
      /* localStorage cheio (imagem muito grande) */
      Swal.fire({
        icon: 'warning',
        title: 'Foto muito grande',
        text:  'Escolha uma imagem menor para salvar localmente.',
        confirmButtonColor: '#2563eb',
      });
    }
  };
  reader.readAsDataURL(this.files[0]);
});

/* ── Campos: restaura valores do localStorage ── */
const campos = ['pnome', 'pemail', 'pcpf', 'ptelefone', 'pdata', 'pcartao'];

campos.forEach(function (id) {
  const val = localStorage.getItem('visio_adm_' + id);
  if (val) {
    const el = document.getElementById(id);
    if (el) el.value = val;
  }
});

/* ── Salvar: localStorage + SweetAlert2 (substitui alert()) ── */
document.getElementById('btn-salvar-perfil').addEventListener('click', function () {
  campos.forEach(function (id) {
    const el = document.getElementById(id);
    if (el) localStorage.setItem('visio_adm_' + id, el.value);
  });

  Swal.fire({
    toast:             true,
    position:          'top-end',
    icon:              'success',
    title:             'Dados salvos localmente!',
    showConfirmButton:  false,
    timer:             3000,
    timerProgressBar:  true,
  });
});
</script>

</body>
</html>
