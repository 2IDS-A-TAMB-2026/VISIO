const form = document.getElementById('form');
const inputEmail = document.getElementById('email');
const erroEmail = document.getElementById('erroEmail');
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
    if (formValido === true) {
        form.submit();
    }
    else {
        alert('Por favor, corrija os erros no formulário antes de enviar.');
    }
});