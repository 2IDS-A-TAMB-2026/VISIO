
(function () {
    'use strict';

    const form = document.getElementById('form');
    if (!form) return;

    /* Referências aos campos */
    const nome      = document.getElementById('nome');
    const cpf       = document.getElementById('cpf');
    const email     = document.getElementById('email');
    const data      = document.getElementById('data_nascimento');
    const telefone  = document.getElementById('telefone');
    const senha     = document.getElementById('senha');

    /* Referências às mensagens de erro */
    const erroNome     = document.getElementById('erroNome');
    const erroCpf      = document.getElementById('erroCpf');
    const erroEmail    = document.getElementById('erroEmail');
    const erroData     = document.getElementById('erroDataNascimento');
    const erroTelefone = document.getElementById('erroTelefone');
    const erroSenha    = document.getElementById('erroSenha');

    /* ── Limpa erros ao digitar ── */
    const pares = [
        [nome, erroNome], [cpf, erroCpf], [email, erroEmail],
        [data, erroData], [telefone, erroTelefone], [senha, erroSenha], 
    ];

    pares.forEach(function ([input, span]) {
        if (!input) return;
        input.addEventListener('input', function () {
            if (span) span.innerText = '';
            this.classList.remove('input-error');
        });
    });

    /* ── Validação no submit ── */
    form.addEventListener('submit', function (event) {
        let valido = true;

        function validarCampo(campo, span, msg) {
            if (!campo) return;
            if (campo.value.trim() === '') {
                if (span) span.innerText = msg;
                campo.classList.add('input-error');
                campo.classList.remove('input-valid');
                valido = false;
            } else {
                if (span) span.innerText = '';
                campo.classList.remove('input-error');
                campo.classList.add('input-valid');
            }
        }

        validarCampo(nome,     erroNome,     'Nome obrigatório.');
        validarCampo(cpf,      erroCpf,      'CPF obrigatório.');
        validarCampo(email,    erroEmail,    'E-mail obrigatório.');
        validarCampo(data,     erroData,     'Data de nascimento obrigatória.');
        validarCampo(telefone, erroTelefone, 'Telefone obrigatório.');
        validarCampo(senha,    erroSenha,    'Senha obrigatória.');

        /* Validação extra: formato de e-mail */
        if (email && email.value.trim() !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            if (erroEmail) erroEmail.innerText = 'Formato de e-mail inválido.';
            email.classList.add('input-error');
            valido = false;
        }

        /* Validação extra: comprimento mínimo da senha */
        if (senha && senha.value.trim() !== '' && senha.value.length < 6) {
            if (erroSenha) erroSenha.innerText = 'Senha deve ter pelo menos 6 caracteres.';
            senha.classList.add('input-error');
            valido = false;
        }

        if (!valido) {
            event.preventDefault();

            if (window.Swal) {
                Swal.fire({
                    toast:             true,
                    position:          'top-end',
                    icon:              'warning',
                    title:             'Corrija os campos em vermelho antes de continuar.',
                    showConfirmButton:  false,
                    timer:             3500,
                    timerProgressBar:  true,
                });
            }
        }
        /* Se válido: submit normal → UsuarioController::cadastrar() */
    });

    /* ─────────────────────────────────────────────────
       MÁSCARA DE CPF (type="text") — mantida
       ───────────────────────────────────────────────── */
    if (cpf) {
        cpf.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 9)      v = v.slice(0,3) + '.' + v.slice(3,6) + '.' + v.slice(6,9) + '-' + v.slice(9);
            else if (v.length > 6) v = v.slice(0,3) + '.' + v.slice(3,6) + '.' + v.slice(6);
            else if (v.length > 3) v = v.slice(0,3) + '.' + v.slice(3);
            this.value = v;
        });
    }

    /* ─────────────────────────────────────────────────
       MÁSCARA DE TELEFONE (type="tel") — mantida
       ───────────────────────────────────────────────── */
    if (telefone) {
        telefone.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 10)     v = '(' + v.slice(0,2) + ') ' + v.slice(2,7) + '-' + v.slice(7);
            else if (v.length > 6) v = '(' + v.slice(0,2) + ') ' + v.slice(2,6) + '-' + v.slice(6);
            else if (v.length > 2) v = '(' + v.slice(0,2) + ') ' + v.slice(2);
            else if (v.length > 0) v = '(' + v;
            this.value = v;
        });
    }


})();
