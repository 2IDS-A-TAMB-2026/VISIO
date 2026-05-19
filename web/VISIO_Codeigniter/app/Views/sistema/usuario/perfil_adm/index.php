<?= view('sistema/layout/header') ?>


<!-- NAO MEXER NADA DE ADM -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Perfil — VISIO</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/* ===== RESET ===== */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

/* ===== VARIÁVEIS IGUAL DASHBOARD ===== */
:root{
    --bg:#f1f5f9;
    --card:#ffffff;
    --text:#0f172a;
    --text2:#64748b;
    --border:#e2e8f0;
    --primary:#2563eb;
    --sidebar:#0f172a;
    --sidebar2:#111827;
    --shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* DARK MODE */
body.dark{
    --bg:#0b1120;
    --card:#111827;
    --text:#f8fafc;
    --text2:#94a3b8;
    --border:#1e293b;
    --sidebar:#020617;
    --sidebar2:#0f172a;
}

body{
    background:var(--bg);
    color:var(--text);
}

/* ===== LAYOUT ===== */
.layout{
    display:flex;
}

/* ===== SIDEBAR IGUAL DASHBOARD ORIGINAL ===== */
.sidebar{
    width:280px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    padding:25px;
    background:linear-gradient(180deg,var(--sidebar),var(--sidebar2));
}

/* LOGO IGUAL DASHBOARD */
.logo-area{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:40px;
}

.logo-area h2{
    color:white;
    font-size:28px;
    font-weight:700;
}

/* MENU PRINCIPAL IGUAL ORIGINAL */
.menu-title{
    color:#64748b;
    text-transform:uppercase;
    font-size:12px;
    margin-bottom:15px;
    letter-spacing:1px;
}

.menu a{
    display:flex;
    align-items:center;
    gap:14px;
    padding:15px;
    border-radius:14px;
    text-decoration:none;
    color:#e2e8f0;
    font-weight:500;
    margin-bottom:10px;
}

.menu a:hover,
.menu a.active{
    background:rgba(37,99,235,0.2);
}

/* ===== MAIN ===== */
.main{
    width:calc(100% - 280px);
    margin-left:280px;
}

/* ===== HEADER IGUAL DASHBOARD (IMPORTANTE) ===== */
header{
    height:90px;
    background:var(--card);
    border-bottom:1px solid var(--border);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
    position:sticky;
    top:0;
    z-index:999;
    box-shadow:var(--shadow);
}

.header-right{
    display:flex;
    align-items:center;
    gap:15px;
}

/* PERFIL BTN IGUAL DASHBOARD */
.profile-btn{
    background:var(--primary);
    color:white;
    padding:10px 15px;
    border-radius:10px;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:8px;
}

/* THEME BTN */
#theme-toggle{
    width:42px;
    height:42px;
    border:none;
    border-radius:12px;
    background:var(--bg);
    cursor:pointer;
}

/* ===== CONTENT ===== */
.content{
    padding:30px;
}

.page-title{
    font-size:30px;
    margin-bottom:20px;
}

/* PERFIL */
.container{
    display:flex;
    gap:40px;
    flex-wrap:wrap;
}

/* FOTO */
.foto{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:15px;
}

.foto img{
    width:160px;
    height:160px;
    border-radius:50%;
    object-fit:cover;
    box-shadow:var(--shadow);
}

.btn-foto{
    background:var(--primary);
    color:white;
    padding:8px 14px;
    border-radius:8px;
    cursor:pointer;
}

/* FORM */
.card{
    flex:1;
    background:var(--card);
    padding:25px;
    border-radius:20px;
    box-shadow:var(--shadow);
}

input{
    padding:10px;
    border:1px solid var(--border);
    border-radius:8px;
    margin-bottom:10px;
    width:100%;
}

.btn-salvar{
    background:var(--primary);
    color:white;
    padding:10px;
    border:none;
    border-radius:10px;
    cursor:pointer;
}

</style>

</head>

<body>

<div class="layout">

<!-- SIDEBAR -->
<aside class="sidebar">

<div class="logo-area">
<h2>VISIO</h2>
</div>

<!-- 🔥 MENU PRINCIPAL IGUAL DASHBOARD -->
<p class="menu-title">Menu principal</p>

<nav class="menu">

<a href="inicio_adm.html"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
<a href="lista.php"><i class="fa-solid fa-users"></i> Usuários</a>
<a href="cadastrar_questoes_adm.html"><i class="fa-solid fa-clipboard-list"></i> Questões</a>
<a href="cadastrar_sensor_ADM.html"><i class="fa-solid fa-microchip"></i> Sensores</a>

</nav>

</aside>

<!-- MAIN -->
<div class="main">

<header>

<h2>Perfil do administrador</h2>

<div class="header-right">

<a href="perfil_adm.html" class="profile-btn">
<i class="fa-solid fa-user"></i> Perfil
</a>

<button id="theme-toggle">
<i class="fa-solid fa-moon"></i>
</button>

</div>

</header>

<div class="content">

<h1 class="page-title">Perfil do administrador</h1>

<div class="container">

<!-- FOTO -->
<div class="foto">

<img id="preview" src="images/foto_adm_perfil.jpeg">

<input type="file" id="file" hidden>

<label for="file" class="btn-foto">
<i class="fa-solid fa-camera"></i> Mudar foto
</label>

</div>

<!-- FORM -->
<div class="card">

<input id="nome" placeholder="Nome">
<input id="cpf" placeholder="CPF">
<input id="telefone" placeholder="Telefone">
<input id="data" type="date">
<input id="email" placeholder="E-mail">
<input id="cartao" placeholder="Cartão IoT">
<input id="senha" type="password" placeholder="Senha">

<button class="btn-salvar" id="salvar">
<i class="fa-solid fa-floppy-disk"></i> Salvar
</button>

</div>

</div>

</div>

</div>

<script>
/* THEME */
document.getElementById("theme-toggle").onclick = () => {
    document.body.classList.toggle("dark");
};

/* FOTO */
const file = document.getElementById("file");
const preview = document.getElementById("preview");

file.onchange = e => {
    const reader = new FileReader();
    reader.onload = () => {
        preview.src = reader.result;
        localStorage.setItem("foto", reader.result);
    };
    reader.readAsDataURL(e.target.files[0]);
};

if(localStorage.getItem("foto")){
    preview.src = localStorage.getItem("foto");
}

/* SALVAR */
document.getElementById("salvar").onclick = () => {

    ["nome","cpf","telefone","data","email","cartao","senha"].forEach(id=>{
        localStorage.setItem(id, document.getElementById(id).value);
    });

    alert("Dados salvos!");
};

</script>
</body>


<?= view('sistema/layout/footer') ?>