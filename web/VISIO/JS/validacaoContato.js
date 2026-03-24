const form = document.getElementById('form');
const inputNome = document.getElementById('nome');
const inputEmail = document.getElementById('email');
const inputMensagem = document.getElementById('mensagem');
const erroNome = document.getElementById('erroNome');
const erroEmail = document.getElementById('erroEmail');
const erroMensagem = document.getElementById('erroMensagem');
form.addEventListener('submit', function (event) {
    event.preventDefault();
    let formValido = true;
    if (inputNome.value.trim() === '') {
        erroNome.innerText = 'O nome é obrigatório';
        inputNome.style.border = '2px solid red';
        formValido = false;
    } else if (inputNome.value.trim().length < 3) {
        erroNome.innerText = 'O nome deve ter pelo menos 3 caracteres';
        inputNome.style.border = '2px solid red';
        formValido = false;
    } else {
        erroNome.innerText = '';
        inputNome.style.border = '2px solid #42b2de';
    }
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (inputEmail.value.trim() === '') {
        erroEmail.innerText = 'O email é obrigatório';
        inputEmail.style.border = '2px solid red';
        formValido = false;
    } else if (!regexEmail.test(inputEmail.value)) {
        erroEmail.innerText = 'Digite um email válido';
        inputEmail.style.border = '2px solid red';
        formValido = false;
    } else {
        erroEmail.innerText = '';
        inputEmail.style.border = '2px solid #42b2de';
    }
    if (inputMensagem.value.trim() === '') {
        erroMensagem.innerText = 'A mensagem é obrigatória';
        inputMensagem.style.border = '2px solid red';
        formValido = false;
    } else if (inputMensagem.value.trim().length < 10) {
        erroMensagem.innerText = 'A mensagem deve ter pelo menos 10 caracteres';
        inputMensagem.style.border = '2px solid red';
        formValido = false;
    } else {
        erroMensagem.innerText = '';
        inputMensagem.style.border = '2px solid #42b2de';
    }
    if (formValido) {
        form.submit();
    } else {
        alert('Por favor, corrija os erros antes de enviar.');
    }
    
}
    
);