(function () {
    'use strict';

    /* Procura o formulário pelo id — só executa se estiver na página */
    const form = document.getElementById("form");
    if (!form) return;

    const inputEmail = document.getElementById("email");
    const erroEmail = document.getElementById("erroEmail");

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        let formValido = true;

        if (!inputEmail || inputEmail.value.trim() === "") {
            if (erroEmail) erroEmail.innerText = "O email é obrigatório";
            if (inputEmail) {
                inputEmail.classList.add("input-error");
                inputEmail.classList.remove("input-valid");
            }
            formValido = false;
        } else {
            if (erroEmail) erroEmail.innerText = "";
            inputEmail.classList.remove("input-error");
            inputEmail.classList.add("input-valid");
        }

        if (!formValido) {
            alert("Por favor, corrija os erros no formulário antes de enviar.");
            return;
        }

        form.submit();
    });

})();
