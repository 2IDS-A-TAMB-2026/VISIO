<?php
$arquivo = __DIR__ . "/dados/usuarios.json";
$usuarios = [];

if (file_exists($arquivo)) {
    $usuarios = json_decode(file_get_contents($arquivo), true);
}

if (!is_array($usuarios)) {
    $usuarios = [];
}
?>

<style>

/* ===== BASE ===== */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

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

/* ===== SIDEBAR ===== */
.sidebar{
    width:280px;
    height:100vh;
    background:linear-gradient(180deg,var(--sidebar),var(--sidebar2));
    position:fixed;
    left:0;
    top:0;
    padding:25px;
}

.logo-area h2{
    color:white;
    font-size:28px;
    margin-bottom:40px;
}

.menu-title{
    color:#64748b;
    text-transform:uppercase;
    font-size:12px;
    margin-bottom:15px;
    letter-spacing:1px;
}

.menu a{
    display:flex;
    gap:14px;
    padding:15px;
    border-radius:14px;
    text-decoration:none;
    color:#e2e8f0;
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

/* ===== HEADER IGUAL DASHBOARD ===== */
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
    box-shadow:0 4px 20px rgba(0,0,0,0.04);
}

.nav-right{
    display:flex;
    align-items:center;
    gap:20px;
}

/* BOTÃO TEMA */
#theme-toggle{
    width:42px;
    height:42px;
    border:none;
    border-radius:12px;
    background:var(--bg);
    cursor:pointer;
}

/* PERFIL */
.profile-btn{
    background:#2563eb;
    color:white;
    padding:10px 16px;
    border-radius:10px;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:8px;
}

/* CONTENT */
.content{
    padding:30px;
}

.page-title{
    font-size:32px;
    margin-bottom:6px;
}

.page-sub{
    color:var(--text2);
    margin-bottom:25px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:var(--card);
    border-radius:20px;
    overflow:hidden;
    box-shadow:var(--shadow);
}

th,td{
    padding:15px;
    border-bottom:1px solid var(--border);
}

th{
    text-align:left;
    color:var(--text2);
}

.input-table{
    width:100%;
    padding:8px;
    border:1px solid var(--border);
    border-radius:8px;
}

/* BOTÕES */
.btn-salvar{
    background:var(--primary);
    color:white;
    border:none;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
}

.btn-delete{
    background:#ef4444;
    color:white;
    border:none;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
}

.actions{
    display:flex;
    gap:8px;
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

    <p class="menu-title">Menu principal</p>

    <nav class="menu">

    <a href="inicio_adm.html">
    <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>

    <a href="lista.php" class="active">
    <i class="fa-solid fa-users"></i> Usuários
    </a>

    <a href="cadastrar_questoes_adm.html">
    <i class="fa-solid fa-clipboard-list"></i> Questões
    </a>

    <a href="cadastrar_sensor_ADM.html">
    <i class="fa-solid fa-microchip"></i> Sensores
    </a>

    </nav>

    </aside>

    <!-- MAIN -->
    <div class="main">

    <header>

    <h2>Usuários cadastrados</h2>

    <div class="nav-right">

    <a href="perfil_adm.html" class="profile-btn">
    <i class="fa-solid fa-user"></i>
    Perfil
    </a>

    <button id="theme-toggle">
    <i class="fa-solid fa-moon"></i>
    </button>

    </div>

    </header>

    <div class="content">

    <h1 class="page-title">Usuários cadastrados</h1>
    <p class="page-sub">Gerencie usuários e cartões IoT vinculados ao sistema.</p>

    <table>

    <thead>
        <tr>
            <th>ID</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Data Nasc.</th>
            <th>Telefone</th>
            <th>Cartão IoT</th>
            <th>Ações</th>
        </tr>
    </thead>

        <tbody>

        <?php foreach($usuarios as $usuario): ?>

        <?php
            $id = (int)($usuario["id"] ?? 0);
            $form = "f".$id;
        ?>

        <tr>
        <td><?= $id ?></td>

        <td>
            <input form="<?= $form ?>" name="cpf"
            value="<?= htmlspecialchars($usuario["cpf"] ?? "") ?>"
            class="input-table">
        </td>

        <td>
            <input form="<?= $form ?>" name="email"
            value="<?= htmlspecialchars($usuario["email"] ?? "") ?>"
            class="input-table">
        </td>

        <td>
            <input form="<?= $form ?>" type="date" name="data_nascimento"
            value="<?= htmlspecialchars($usuario["data_nascimento"] ?? "") ?>"
            class="input-table">
        </td>

        <td>
            <input form="<?= $form ?>" name="telefone"
            value="<?= htmlspecialchars($usuario["telefone"] ?? "") ?>"
            class="input-table">
        </td>

        <td>
            <input form="<?= $form ?>" name="cartao"
            value="<?= htmlspecialchars($usuario["cartao"] ?? "") ?>"
            class="input-table">
        </td>

        <td>

        <div class="actions">
            <form id="<?= $form ?>" action="api/usuarios.php" method="POST">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button class="btn-salvar">Salvar</button>
            </form>

            <form action="api/usuarios.php" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button class="btn-delete">Excluir</button>
            </form>
        </div>
        </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
    </div>

        <script>
        const toggle = document.getElementById("theme-toggle");

        toggle.addEventListener("click", () => {
            document.body.classList.toggle("dark");

            const icon = toggle.querySelector("i");

            if(document.body.classList.contains("dark")){
                icon.classList.remove("fa-moon");
                icon.classList.add("fa-sun");
            } else {
                icon.classList.remove("fa-sun");
                icon.classList.add("fa-moon");
            }
        });
        </script>

    </body>
</html>