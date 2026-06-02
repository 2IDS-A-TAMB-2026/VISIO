

<?= view('sistema/layout/header_adm') ?>

<style>
body{
      background:var(--bg);
      color:var(--text);
      min-height:100vh;
      transition:.3s;
    }

    .layout{
      display:flex;
    }

    /* SIDEBAR */

    .sidebar{
      width:280px;
      height:100vh;
      background:linear-gradient(180deg,var(--sidebar),var(--sidebar2));
      position:fixed;
      left:0;
      top:0;
      padding:25px;
      overflow-y:auto;
      z-index:1000;
    }

    .logo-area{
      display:flex;
      align-items:center;
      gap:15px;
      margin-bottom:40px;
    }

    .logo-area h2{
      color:white;
      font-size:28px;
    }

    .menu-title{
      color:#64748b;
      text-transform:uppercase;
      font-size:12px;
      margin-bottom:15px;
      letter-spacing:1px;
    }

    .menu{
      list-style:none;
    }

    .menu li{
      margin-bottom:10px;
    }

    .menu a{
      display:flex;
      align-items:center;
      gap:14px;
      padding:15px;
      border-radius:14px;
      text-decoration:none;
      color:#e2e8f0;
      transition:.3s;
      font-weight:500;
      font-size:16px;
    }

    .menu a:hover,
    .menu a.active{
      background:rgba(37,99,235,.2);
    }

    /* MAIN */

    .main{
      width:calc(100% - 280px);
      margin-left:280px;
    }

    /* HEADER */

    header{
      height:90px;
      background:var(--card);
      border-bottom:1px solid var(--border);
      display:flex;
      align-items:center;
      justify-content:center;
      padding:0 30px;
      position:sticky;
      top:0;
      z-index:999;
      box-shadow:0 4px 20px rgba(0,0,0,.04);
    }

    .top-nav{
      width:100%;
      max-width:1600px;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }

    .nav-left h1{
      font-size:24px;
    }

    .nav-links{
      display:flex;
      align-items:center;
      gap:20px;
      list-style:none;
    }

    .perfil-btn{
      background:#2563eb;
      color:white;
      padding:12px 18px;
      border-radius:10px;
      display:flex;
      align-items:center;
      gap:8px;
      text-decoration:none;
      font-weight:600;
    }

    #theme-toggle{
      width:42px;
      height:42px;
      border:none;
      border-radius:12px;
      background:var(--bg);
      color:var(--text);
      cursor:pointer;
      font-size:16px;
    }

    /* CONTENT */

    .content{
      padding:30px;
      max-width:1200px;
      margin:auto;
    }

    .page-title{
      font-size:34px;
      margin-bottom:8px;
    }

    .page-sub{
      color:var(--text2);
      margin-bottom:25px;
    }

    .page-sub a{
      color:var(--primary);
      text-decoration:none;
      font-weight:600;
    }

    /* CARD */

    .card{
      background:var(--card);
      border-radius:22px;
      padding:30px;
      box-shadow:var(--shadow);
    }

    /* FORM */

    .form-grid{
      display:grid;
      gap:20px;
    }

    label{
      font-weight:600;
      margin-bottom:8px;
      display:block;
    }

    textarea,
    input,
    select{
      width:100%;
      padding:15px;
      border-radius:14px;
      border:1px solid var(--border);
      background:var(--card);
      color:var(--text);
      outline:none;
      font-size:15px;
    }

    textarea{
      resize:none;
    }

    .form-section{
      margin-top:10px;
      margin-bottom:5px;
      font-weight:700;
      font-size:18px;
    }

    .alt-row{
      display:flex;
      align-items:center;
      gap:12px;
    }

    .alt-row input[type="radio"]{
      width:auto;
    }

    .alt-letra{
      font-weight:700;
      min-width:30px;
    }

    .btn-salvar{
      background:var(--primary);
      color:white;
      border:none;
      padding:15px;
      border-radius:14px;
      font-size:16px;
      font-weight:600;
      cursor:pointer;
      margin-top:10px;
      transition:.3s;
    }

    .btn-salvar:hover{
      transform:translateY(-2px);
    }

    footer{
      text-align:center;
      padding:30px;
      color:var(--text2);
    }

    @media(max-width:900px){

      .sidebar{
        width:100%;
        height:auto;
        position:relative;
      }

      .main{
        width:100%;
        margin-left:0;
      }

      .layout{
        flex-direction:column;
      }

    }

  </style>

</head>

<body>

<div class="layout">



  <aside class="sidebar">

    <div class="logo-area">
      <h2>VISIO</h2>
    </div>

    <p class="menu-title">
      Menu principal
    </p>

    <ul class="menu">

      <li>
        <a href="inicio_adm.html">
          <i class="fa-solid fa-chart-line"></i>
          Dashboard
        </a>
      </li>

      <li>
        <a href="lista.php">
          <i class="fa-solid fa-users"></i>
          Usuários
        </a>
      </li>

      <li>
        <a href="cadastrar_questoes_adm.html" class="active">
          <i class="fa-solid fa-clipboard-list"></i>
          Questões
        </a>
      </li>

      <li>
        <a href="cadastrar_sensor_ADM.html">
          <i class="fa-solid fa-microchip"></i>
          Sensores
        </a>
      </li>

    </ul>

  </aside>



  <div class="main">



    <header>

      <nav class="top-nav">

        <div class="nav-left">
          <h1>Nova questão</h1>
        </div>

        <ul class="nav-links">

          <li>
            <a href="perfil_adm.html" class="perfil-btn">
              <i class="fa-solid fa-user"></i>
              Perfil
            </a>
          </li>

          <li>
            <button id="theme-toggle">
              <i class="fa-solid fa-moon"></i>
            </button>
          </li>

        </ul>

      </nav>

    </header>


    <div class="content">

      <h1 class="page-title">
        Cadastrar nova questão
      </h1>

      <p class="page-sub">
        <a href="cadastrar_questoes_adm.html">
          <i class="fa-solid fa-arrow-left"></i>
          Voltar à lista
        </a>
      </p>

      <section class="card">

        <form id="form-questao" class="form-grid">

          <div>

            <label for="descricao">
              Enunciado da pergunta
            </label>

            <textarea
              id="descricao"
              name="descricao"
              rows="4"
              placeholder="Digite a pergunta"></textarea>

          </div>

          <div>

            <label for="nivel">
              Nível de dificuldade
            </label>

            <select id="nivel" name="nivel">

              <option value="Fácil">Fácil</option>
              <option value="Médio">Médio</option>
              <option value="Difícil">Difícil</option>

            </select>

          </div>

          <p class="form-section">
            Alternativas
          </p>

          <div class="alt-row">

            <input type="radio" name="correta" value="a" checked>

            <span class="alt-letra">A)</span>

            <input
              type="text"
              id="alt_a"
              name="alt_a"
              placeholder="Alternativa A">

          </div>

          <div class="alt-row">

            <input type="radio" name="correta" value="b">

            <span class="alt-letra">B)</span>

            <input
              type="text"
              id="alt_b"
              name="alt_b"
              placeholder="Alternativa B">

          </div>

          <div class="alt-row">

            <input type="radio" name="correta" value="c">

            <span class="alt-letra">C)</span>

            <input
              type="text"
              id="alt_c"
              name="alt_c"
              placeholder="Alternativa C">

          </div>

          <div class="alt-row">

            <input type="radio" name="correta" value="d">

            <span class="alt-letra">D)</span>

            <input
              type="text"
              id="alt_d"
              name="alt_d"
              placeholder="Alternativa D">

          </div>

          <button type="submit" class="btn-salvar">

            <i class="fa-solid fa-floppy-disk"></i>
            Salvar questão

          </button>

        </form>

      </section>

      <footer>
        © 2026 VISIO — Plataforma Inteligente para Gestão de Sensores IoT.
      </footer>

    </div>

  </div>

</div>

<script>

/* DARK MODE */

const toggle = document.getElementById("theme-toggle");

if(localStorage.getItem("tema") === "dark"){

  document.body.classList.add("dark");

  toggle.innerHTML = '<i class="fa-solid fa-sun"></i>';

}

toggle.addEventListener("click", () => {

  document.body.classList.toggle("dark");

  const icon = toggle.querySelector("i");

  if(document.body.classList.contains("dark")){

    localStorage.setItem("tema","dark");

    icon.classList.remove("fa-moon");
    icon.classList.add("fa-sun");

  }else{

    localStorage.setItem("tema","light");

    icon.classList.remove("fa-sun");
    icon.classList.add("fa-moon");

  }

});

/* FORM */

document.getElementById("form-questao")

.addEventListener("submit", function (e) {

  e.preventDefault();

  const descricao =
  document.getElementById("descricao").value.trim();

  const altA =
  document.getElementById("alt_a").value.trim();

  const altB =
  document.getElementById("alt_b").value.trim();

  const altC =
  document.getElementById("alt_c").value.trim();

  const altD =
  document.getElementById("alt_d").value.trim();

  if(descricao.length < 5){

    Swal.fire({

      icon:"warning",
      title:"Pergunta inválida",
      text:"Digite uma pergunta maior.",
      confirmButtonColor:"#2563eb"

    });

    return;

  }

  if(
    !altA ||
    !altB ||
    !altC ||
    !altD
  ){

    Swal.fire({

      icon:"warning",
      title:"Alternativas incompletas",
      text:"Preencha todas as alternativas.",
      confirmButtonColor:"#2563eb"

    });

    return;

  }

  var fd = new FormData(this);

  fetch("api/questoes.php", {

    method: "POST",
    body: fd

  })

  .then(function (r) {

    return r.json();

  })

  .then(function (data) {

    Swal.fire({

      icon:"success",
      title:"Questão cadastrada!",
      text:"A questão foi salva com sucesso.",
      confirmButtonColor:"#2563eb"

    }).then(() => {

      window.location.href =
      "cadastrar_questoes_adm.html";

    });

  })

  .catch(function () {

    Swal.fire({

      icon:"error",
      title:"Erro",
      text:"Erro ao salvar questão.",
      confirmButtonColor:"#2563eb"

    });

  });

});

</script>

</body>

</html>

<?= view('sistema/layout/footer') ?>