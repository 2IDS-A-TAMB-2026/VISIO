const form = document.getElementById("form");

const cpf = document.getElementById("cpf");
const email = document.getElementById("email");
const data = document.getElementById("data_nascimento");
const telefone = document.getElementById("telefone");
const senha = document.getElementById("senha");
const confirmarSenha = document.getElementById("confirmar_senha");

const erroCpf = document.getElementById("erroCpf");
const erroEmail = document.getElementById("erroEmail");
const erroData = document.getElementById("erroDataNascimento");
const erroTelefone = document.getElementById("erroTelefone");
const erroSenha = document.getElementById("erroSenha");
const erroConfirmarSenha = document.getElementById("erroConfirmarSenha");


// VALIDAÇÃO
form.addEventListener("submit", function (e) {

    e.preventDefault();

    let valido = true;


    // CPF
    if (cpf.value.trim() === "") {
        erroCpf.innerText = "CPF obrigatório";
        cpf.style.border = "2px solid red";
        valido = false;
    } else {
        erroCpf.innerText = "";
        cpf.style.border = "2px solid #42b2de";
    }


    // EMAIL
    if (email.value.trim() === "") {
        erroEmail.innerText = "Email obrigatório";
        email.style.border = "2px solid red";
        valido = false;
    } else {
        erroEmail.innerText = "";
        email.style.border = "2px solid #42b2de";
    }


    // DATA
    if (data.value.trim() === "") {
        erroData.innerText = "Data obrigatória";
        data.style.border = "2px solid red";
        valido = false;
    } else {
        erroData.innerText = "";
        data.style.border = "2px solid #42b2de";
    }


    // TELEFONE
    if (telefone.value.trim() === "") {
        erroTelefone.innerText = "Telefone obrigatório";
        telefone.style.border = "2px solid red";
        valido = false;
    } else {
        erroTelefone.innerText = "";
        telefone.style.border = "2px solid #42b2de";
    }


    // SENHA
    if (senha.value.trim() === "") {
        erroSenha.innerText = "Senha obrigatória";
        senha.style.border = "2px solid red";
        valido = false;
    } else {
        erroSenha.innerText = "";
        senha.style.border = "2px solid #42b2de";
    }


    // CONFIRMAR SENHA
    if (confirmarSenha.value !== senha.value) {
        erroConfirmarSenha.innerText = "Senhas não coincidem";
        confirmarSenha.style.border = "2px solid red";
        valido = false;
    } else {
        erroConfirmarSenha.innerText = "";
        confirmarSenha.style.border = "2px solid #42b2de";
    }


    if (valido) {
        form.submit();
    }

});

/* CPF */
cpf.addEventListener("input", function () {
    let valor = cpf.value;
    valor = valor.replace(/\D/g, "");
    if (valor.length > 11) {
        valor = valor.slice(0, 11);
    }

    if (valor.length > 9) {
        valor = valor.slice(0, 3) + "." + valor.slice(3, 6) + "." + valor.slice(6, 9) + "-" + valor.slice(9);
    }
    else if (valor.length > 6) {
        valor = valor.slice(0, 3) + "." + valor.slice(3, 6) + "." + valor.slice(6);
    }
    else if (valor.length > 3) {
        valor = valor.slice(0, 3) + "." + valor.slice(3);
    }

    cpf.value = valor;

});

/* TELEFONE */
telefone.addEventListener("input", function () {
    let valor = telefone.value;
    valor = valor.replace(/\D/g, "");
    if (valor.length > 11) {
        valor = valor.slice(0, 11);
    }
    if (valor.length > 10) {
        valor = "(" + valor.slice(0, 2) + ") " + valor.slice(2, 7) + "-" + valor.slice(7);
    }
    else if (valor.length > 6) {
        valor = "(" + valor.slice(0, 2) + ") " + valor.slice(2, 6) + "-" + valor.slice(6);
    }
    else if (valor.length > 2) {
        valor = "(" + valor.slice(0, 2) + ") " + valor.slice(2);
    }
    else if (valor.length > 0) {
        valor = "(" + valor;
    }
    telefone.value = valor;
});

/* DATA DE NASCIMENTO */
data.addEventListener("input", function () {
    let valor = data.value;
    valor = valor.replace(/\D/g, "");
    if (valor.length > 8) {
        valor = valor.slice(0, 8);
    }
    if (valor.length > 4) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2, 4) + "/" + valor.slice(4);
    }
    else if (valor.length > 2) {
        valor = valor.slice(0, 2) + "/" + valor.slice(2);
    }
    data.value = valor;
});