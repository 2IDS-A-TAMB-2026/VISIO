const form = document.getElementById("form");
const inputEmail = document.getElementById("email");
const erroEmail = document.getElementById("erroEmail");

form.addEventListener("submit", function (event) {
    event.preventDefault();
    let formValido = true;

    if (inputEmail.value.trim() === "") {
        erroEmail.innerText = "O email é obrigatório";
        inputEmail.classList.add("input-error"); inputEmail.classList.remove("input-valid");
        formValido = false;
    } else {
        erroEmail.innerText = "";
        inputEmail.classList.remove("input-error"); inputEmail.classList.add("input-valid");
    }

    if (!formValido) {
        alert("Por favor, corrija os erros no formulário antes de enviar.");
        return;
    }

    form.submit();
});
