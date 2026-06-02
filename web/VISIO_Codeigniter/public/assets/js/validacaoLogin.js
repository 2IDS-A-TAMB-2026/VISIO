
(function () {
    'use strict';

    const form = document.getElementById('form-login');
    if (!form) return;

    const inputEmail = document.getElementById('email');
    const inputSenha = document.getElementById('senha');
    const erroEmail  = document.getElementById('erroEmail');
    const erroSenha  = document.getElementById('erroSenha');

    /* Limpa mensagem ao digitar */
    [inputEmail, inputSenha].forEach(function (input) {
        if (!input) return;
        input.addEventListener('input', function () {
            const spanId = 'erro' + this.id.charAt(0).toUpperCase() + this.id.slice(1);
            const span   = document.getElementById(spanId);
            if (span) span.innerText = '';
            this.classList.remove('input-error');
        });
    });

    form.addEventListener('submit', function (event) {
        let formValido = true;

        /* E-mail */
        if (!inputEmail || inputEmail.value.trim() === '') {
            if (erroEmail) erroEmail.innerText = 'E-mail obrigatório.';
            if (inputEmail) inputEmail.classList.add('input-error');
            formValido = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(inputEmail.value)) {
            if (erroEmail) erroEmail.innerText = 'Formato de e-mail inválido.';
            if (inputEmail) inputEmail.classList.add('input-error');
            formValido = false;
        } else {
            if (erroEmail) erroEmail.innerText = '';
            if (inputEmail) {
                inputEmail.classList.remove('input-error');
                inputEmail.classList.add('input-valid');
            }
        }

        /* Senha */
        if (!inputSenha || inputSenha.value.trim() === '') {
            if (erroSenha) erroSenha.innerText = 'Senha obrigatória.';
            if (inputSenha) inputSenha.classList.add('input-error');
            formValido = false;
        } else {
            if (erroSenha) erroSenha.innerText = '';
            if (inputSenha) {
                inputSenha.classList.remove('input-error');
                inputSenha.classList.add('input-valid');
            }
        }

        if (!formValido) {
            event.preventDefault();

            if (window.Swal) {
                Swal.fire({
                    toast:             true,
                    position:          'top-end',
                    icon:              'warning',
                    title:             'Preencha e-mail e senha para continuar.',
                    showConfirmButton:  false,
                    timer:             3000,
                    timerProgressBar:  true,
                });
            }
        }
        /* Se válido: submit normal → AuthController::loginUsuario() */
    });

})();
