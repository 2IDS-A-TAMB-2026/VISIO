<?= view('sistema/layout/header_adm') ?>

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }

/* Tema claro é o padrão (alinhado com header_adm.php) */
/* body.dark é adicionado pelo footer_adm.php ao alternar */
body.dark {
    --bg:#0b1120;
    --card:#111827;
    --text:#f8fafc;
    --text2:#94a3b8;
    --border:#1e293b;
    --sidebar:#020617;
    --sidebar2:#0f172a;
    --input-bg:#1e293b;
    --input-readonly:#0f172a;
    --shadow:0 10px 25px rgba(0,0,0,.3);
}

/* Flexbox garantindo que o footer seja empurrado para o final */
body { 
    background:var(--bg); 
    color:var(--text); 
    min-height:100vh; 
    display:flex; 
    flex-direction:column; 
    transition:.3s; 
}
.layout { 
    display:flex; 
    flex: 1 0 auto; /* Ocupa todo o espaço vertical disponível acima do footer */
}

.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0; top: 0;
    padding: 25px;
    background: linear-gradient(180deg, var(--sidebar), var(--sidebar2));
    overflow-y: auto;
    z-index: 1000;
}

.logo-area { display: flex; align-items: center; gap: 15px; margin-bottom: 40px; }
.logo-area h2 { color: white; font-size: 28px; font-weight: 700; }
.menu-title { color: #64748b; text-transform: uppercase; font-size: 12px; margin-bottom: 15px; letter-spacing: 1px; }
.menu { list-style: none; }
.menu li { margin-bottom: 10px; }
.menu a { display: flex; align-items: center; gap: 14px; padding: 15px; border-radius: 14px; text-decoration: none; color: #e2e8f0; font-weight: 500; transition: .3s; }
.menu a:hover, .menu a.active { background: rgba(37,99,235,.2); }

.main { 
    width:calc(100% - 280px); 
    margin-left:280px; 
    flex:1; 
    display: flex;
    flex-direction: column;
    padding-bottom:20px; 
}
.content { 
    padding:30px; 
    max-width:1400px; 
    margin:0 auto;
    width: 100%;
    flex: 1 0 auto; /* Mantém o conteúdo expandido dentro de .main */
}
.page-title { font-size:34px; margin-bottom:8px; }
.page-sub { color:var(--text2); margin-bottom:25px; }

/* BARRA DE PESQUISA EXPANDIDA */
.search-container {
    position: relative;
    margin-bottom: 25px;
    width: 100%; /* Ocupa toda a largura do container */
}
.search-container i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text2);
    font-size: 16px;
}
.search-input {
    width: 100%;
    padding: 15px 20px 15px 50px; /* Mais alta e com espaço extra à esquerda */
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--card);
    color: var(--text);
    font-size: 15px;
    transition: border-color .2s, box-shadow .2s;
    box-shadow: var(--shadow);
}
.search-input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}

table { width:100%; border-collapse:collapse; background:var(--card); border-radius:20px; box-shadow:var(--shadow); }
th, td { padding:15px; border-bottom:1px solid var(--border); }
th { text-align:left; color:var(--text2); font-size:13px; text-transform:uppercase; letter-spacing:.5px; }
.input-table { width:100%; padding:8px 10px; border:1px solid var(--border); border-radius:8px; background:var(--input-bg); color:var(--text); font-size:14px; }
.input-table:focus { outline:none; border-color:var(--primary); }
.input-table.readonly { background:var(--input-readonly); cursor:not-allowed; opacity:0.7; }

/* BOTÕES */
.actions { display:flex; gap:8px; align-items:center; justify-content:center; }
.btn-salvar, .btn-delete {
    width: 95px !important;
    height: 38px !important;
    padding: 0 12px !important;
    margin: 0 !important;
    border: none !important;
    border-radius: 8px !important;
    cursor: pointer;
    font-size: 13px !important;
    font-weight: 600 !important;
    font-family: 'Segoe UI', sans-serif !important;
    transition: all 0.3s !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 5px !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    box-sizing: border-box !important;
    line-height: 1 !important;
}
.btn-salvar { background: var(--primary) !important; color: white !important; }
.btn-salvar:hover { background: #1d4ed8 !important; }
.btn-delete { background: #ef4444 !important; color: white !important; }
.btn-delete:hover { background: #dc2626 !important; }

.btn-salvar i, .btn-delete i { font-size: 13px !important; }

/* Hover nas linhas */
tbody tr:hover { background: rgba(37, 99, 235, 0.06); transition: background .15s; }
body.dark tbody tr:hover { background: rgba(38, 98, 217, 0.1); }

.alert { padding:12px 20px; border-radius:10px; margin-bottom:20px; }
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.alert-danger { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

/* Garante o rodapé preso na parte inferior da tela */
.footer { 
    text-align:center; 
    padding:20px; 
    color:var(--text2); 
    font-size:14px; 
    margin-top:auto; 
    width: 100%;
}
</style>

<div class="layout">

  <?= view('sistema/admin/_sidebar', ['ativo' => 'usuarios']) ?>

  <div class="main">
    <div class="content">

      <h1 class="page-title">Usuários cadastrados</h1>
      <p class="page-sub">Gerencie usuários e cartões IoT vinculados ao sistema.</p>

      <?php if (session()->getFlashdata('sucesso')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('sucesso') ?></div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('erro')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('erro') ?></div>
      <?php endif; ?>

      <!-- CAMPO DE PESQUISA EXPANDIDO E COM MAXLENGTH ELEVADO -->
      <div class="search-container">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" 
               id="searchInput" 
               placeholder="Pesquisar por nome..." 
               class="search-input" 
               maxlength="100">
      </div>

      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Data Nasc.</th>
            <th>Telefone</th>
            <th>Cartão IoT</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody id="usersTableBody">

        <?php if (empty($usuarios)): ?>
          <tr class="no-data">
            <td colspan="7" style="text-align:center; padding:30px; color:var(--text2);">
              Nenhum usuário cadastrado.
            </td>
          </tr>
        <?php else: ?>

          <?php foreach ($usuarios as $i => $usuario): ?>
            <?php
              $nome  = $usuario['NOME']            ?? $usuario['nome']            ?? '';
              $cpf   = $usuario['CPF']             ?? $usuario['cpf']             ?? '';
              $email = $usuario['EMAIL']           ?? $usuario['email']           ?? '';
              $data  = $usuario['DATA_NASCIMENTO'] ?? $usuario['data_nascimento'] ?? '';
              $tel   = $usuario['TELEFONE']        ?? $usuario['telefone']        ?? '';
              $cart  = $usuario['CARTAO']          ?? $usuario['cartao']          ?? '';
              $formId = 'f_usuario_' . $i;
            ?>
            <tr class="user-row">

              <td>
                <input form="<?= $formId ?>" type="text" name="nome"
                       value="<?= htmlspecialchars($nome) ?>"
                       class="input-table">
              </td>

              <td>
                <input form="<?= $formId ?>" type="text" name="cpf"
                       value="<?= htmlspecialchars($cpf) ?>"
                       class="input-table readonly" readonly>
              </td>

              <td>
                <input form="<?= $formId ?>" type="email" name="email"
                       value="<?= htmlspecialchars($email) ?>"
                       class="input-table">
              </td>

              <td>
                <?php 
                  $data_formatada = '';
                  if (!empty($data)) {
                      $data_formatada = date('Y-m-d', strtotime($data));
                  }
                ?>
                <input form="<?= $formId ?>" type="date" name="data_nascimento"
                      value="<?= htmlspecialchars($data_formatada) ?>"
                      max="<?= date('Y-m-d') ?>"
                      class="input-table">
              </td>

              <td>
                <input form="<?= $formId ?>" type="tel" name="telefone"
                       value="<?= htmlspecialchars($tel) ?>"
                       class="input-table">
              </td>

              <td>
                <input form="<?= $formId ?>" type="text" name="cartao"
                       value="<?= htmlspecialchars($cart) ?>"
                       class="input-table">
              </td>

              <td>
                <div class="actions">
                  <form id="<?= $formId ?>"
                        action="<?= base_url('/admin/usuario/atualizar/' . urlencode($cpf)) ?>"
                        method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-salvar">
                      <i class="fa-solid fa-floppy-disk"></i>Salvar
                    </button>
                  </form>

                  <form action="<?= base_url('/admin/usuario/excluir/' . urlencode($cpf)) ?>"
                        method="POST"
                        onsubmit="return confirm('Excluir o usuário <?= htmlspecialchars($cpf) ?>?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-delete">
                      <i class="fa-solid fa-trash"></i>Excluir
                    </button>
                  </form>
                </div>
              </td>

            </tr>
          <?php endforeach; ?>

          <tr id="noResultsRow" style="display: none;">
            <td colspan="7" style="text-align:center; padding:30px; color:var(--text2);">
              Nenhum usuário encontrado para a pesquisa.
            </td>
          </tr>

        <?php endif; ?>

        </tbody>
      </table>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('.user-row');
    const noResultsRow = document.getElementById('noResultsRow');

    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const inputs = row.querySelectorAll('input');
            let match = false;

            inputs.forEach(input => {
                if (input.value.toLowerCase().includes(query)) {
                    match = true;
                }
            });

            if (match) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (noResultsRow) {
            noResultsRow.style.display = (visibleCount === 0 && query !== '') ? '' : 'none';
        }
    });
});
</script>

<?= view('sistema/layout/footer_adm') ?>