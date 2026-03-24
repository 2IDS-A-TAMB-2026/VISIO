const form = document.getElementById('formAdm');
const inputEmail = document.getElementById('email');
const inputSenha = document.getElementById('senha');
const erroEmail = document.getElementById('erroEmail');
const erroSenha = document.getElementById('erroSenha');

form.addEventListener('submit', function (event) {
    event.preventDefault();
    let formValido = true;

    // Email
    if (inputEmail.value.trim() === '') {
        erroEmail.innerText = 'O email é obrigatório';
        inputEmail.style.border = '2px solid red';
        formValido = false;
    } else {
        erroEmail.innerText = '';
        inputEmail.style.border = '2px solid #42b2de';
    }

    // Senha
    if (inputSenha.value.trim() === '') {
        erroSenha.innerText = 'A senha é obrigatória';
        inputSenha.style.border = '2px solid red';
        formValido = false;
    } else {
        erroSenha.innerText = '';
        inputSenha.style.border = '2px solid #42b2de';
    }

    if (formValido === true) {
        form.submit();
    } else {
        alert('Por favor, corrija os erros no formulário antes de enviar.');
    }
}); 