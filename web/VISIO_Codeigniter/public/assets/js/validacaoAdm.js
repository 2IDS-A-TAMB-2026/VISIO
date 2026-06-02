(function () {
    'use strict';

    /* Procura o formulário pelo id — só executa se estiver na página */
    const form = document.getElementById('formAdm');
    if (!form) return;

    const inputEmail = document.getElementById('email');
    const inputSenha = document.getElementById('senha');
    const erroEmail  = document.getElementById('erroEmail');
    const erroSenha  = document.getElementById('erroSenha');

    /* ── Limpa os erros ao digitar ── */
    if (inputEmail) {
        inputEmail.addEventListener('input', function () {
            erroEmail.innerText = '';
            this.classList.remove('input-error');
        });
    }

    if (inputSenha) {
        inputSenha.addEventListener('input', function () {
            erroSenha.innerText = '';
            this.classList.remove('input-error');
        });
    }

    /* ── Validação no submit ── */
    form.addEventListener('submit', function (event) {
        let formValido = true;

        /* Valida e-mail */
        if (!inputEmail || inputEmail.value.trim() === '') {
            if (erroEmail) erroEmail.innerText = 'O e-mail é obrigatório.';
            if (inputEmail) {
                inputEmail.classList.add('input-error');
                inputEmail.classList.remove('input-valid');
            }
            formValido = false;
        } else {
            if (erroEmail) erroEmail.innerText = '';
            if (inputEmail) {
                inputEmail.classList.remove('input-error');
                inputEmail.classList.add('input-valid');
            }
        }

        /* Valida senha */
        if (!inputSenha || inputSenha.value.trim() === '') {
            if (erroSenha) erroSenha.innerText = 'A senha é obrigatória.';
            if (inputSenha) {
                inputSenha.classList.add('input-error');
                inputSenha.classList.remove('input-valid');
            }
            formValido = false;
        } else {
            if (erroSenha) erroSenha.innerText = '';
            if (inputSenha) {
                inputSenha.classList.remove('input-error');
                inputSenha.classList.add('input-valid');
            }
        }

        /* Se inválido: exibe toast e cancela o submit */
        if (!formValido) {
            event.preventDefault();

            if (window.Swal) {
                Swal.fire({
                    toast:            true,
                    position:         'top-end',
                    icon:             'warning',
                    title:            'Preencha todos os campos antes de continuar.',
                    showConfirmButton: false,
                    timer:            3500,
                    timerProgressBar: true,
                });
            }
            return;
        }

        /*
         * Se válido: não fazemos NADA — o submit segue normalmente.
         * O AuthController::loginAdmin() (CI4) recebe os dados via POST,
         * verifica as credenciais e redireciona para /admin/dashboard.
         * Não há mais fetch() para api/login_adm.php.
         */
    });

})();
