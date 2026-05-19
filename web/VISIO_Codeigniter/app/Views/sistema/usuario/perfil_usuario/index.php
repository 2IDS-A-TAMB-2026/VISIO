<?= view('sistema/layout/header') ?>


  <title>Perfil Usuario</title>

  <style>
    .foto_adm_wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
    }

    .btn-mudar-foto {
      display: inline-block;
      padding: 8px 16px;
      background-color: var(--color-accent-adm, #3a86ff);
      /* Usa a sua cor primária */
      color: #ffffff;
      border-radius: 8px;
      cursor: pointer;
      font-size: 13px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-mudar-foto:hover {
      background-color: #2668d1;
    }

    .foto_adm {
      object-fit: cover;
      width: 150px;
      height: 150px;
      border-radius: 50%;
    }
  </style>
</head>
<body>
  <div class="app-container">
    
    <div class="adm-main-area">
      <h1 class="adm-page-title">Perfil do Usuario</h1>
      <div class="container_perfil_adm adm-perfil-layout">

        <div class="foto_adm_wrap">
          <img class="foto_adm" id="preview_foto" src="images/foto_adm_perfil.jpeg" alt="Foto do administrador">

          <input type="file" id="foto_upload" accept="image/*" style="display: none;">

          <label for="foto_upload" class="btn-mudar-foto">
            <i class="fa-solid fa-camera"></i> Mudar foto
          </label>
        </div>
        <section class="adm-form-card adm-perfil-form">
          <h2>Dados de contato</h2>
          <form id="perfil-form">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Matheus Neri">

            <label for="tel">CPF</label>
            <input type="text" id="cpf" name="cpf" placeholder="xxx.xxx.xxx-xx">

            <label for="tel">Telefone</label>
            <input type="tel" id="tel" name="telefone" placeholder="(19) 99315-1477">

            <label for="tel">Data de nascimento</label>
            <input type="date" id="data" name="data" >

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="matheusferreiracom15@gmail.com">

            <label for="tel">Número do cartão</label>
            <input type="text" id="cartao" name="cartao" placeholder="1234-56">
            
            <label for="senha">Senha</label>
            <input type="text" id="senha" name="senha">
            <br>
            
            <button type="submit" class="btn-salvar" style="color: #fff;"><i class="fa-solid fa-floppy-disk"></i> Salvar
              alterações</button>
          </form>
          <p class="perfil-aviso" id="perfil-msg" hidden>Alterações guardadas neste dispositivo (demonstração).</p>
        </section>
      </div>
    </div>
  </div>
   
  <script>
    (function () {
      var form = document.getElementById("perfil-form");
      var msg = document.getElementById("perfil-msg");
      var keys = ["nome_adm", "tel_adm", "email_adm", "cnpj_adm"];

      keys.forEach(function (id) {
        var el = document.getElementById(id);
        var k = "visio_perfil_" + id;
        var v = localStorage.getItem(k);
        if (v) el.value = v;
      });

      form.addEventListener("submit", function (e) {
        e.preventDefault();
        keys.forEach(function (id) {
          var el = document.getElementById(id);
          localStorage.setItem("visio_perfil_" + id, el.value);
        });
        msg.hidden = false;
        setTimeout(function () { msg.hidden = true; }, 4000);
      });
      var fotoInput = document.getElementById("foto_upload");
      var fotoPreview = document.getElementById("preview_foto");
      var fotoSalva = localStorage.getItem("visio_perfil_foto_dados");
      if (fotoSalva) {
        fotoPreview.src = fotoSalva;
      }
      fotoInput.addEventListener("change", function (event) {
        var file = event.target.files[0];
        if (file) {
          var reader = new FileReader();
          reader.onload = function (e) {
            var fotoBase64 = e.target.result;
            fotoPreview.src = fotoBase64;
            try {
              localStorage.setItem("visio_perfil_foto_dados", fotoBase64);
            } catch (erro) {
              console.warn("A imagem é muito grande para ser salva no navegador localmente.");
            }
          };
          reader.readAsDataURL(file);
        }
      });
    })();
  </script>
  <script src="JS/theme.js"></script>
</body>
<?= view('sistema/layout/footer') ?>