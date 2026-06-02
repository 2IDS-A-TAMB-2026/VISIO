const toggleBtn = document.getElementById('theme-toggle');
const body = document.body;
const sunIcon = document.getElementById('icon-sun');
const moonIcon = document.getElementById('icon-moon');
const themeImages = document.querySelectorAll('.theme-img');
const themeLogos = document.querySelectorAll('.logo');

// 1. NOVA LINHA: Seleciona todos os ícones com a classe 'theme-icon'
const themeIcons = document.querySelectorAll('.theme-icon');

const savedTheme = localStorage.getItem('theme');

if (savedTheme === 'light') {
  body.classList.add('light');
}

function updateUI() {
  const isLight = body.classList.contains('light');
  if (sunIcon) sunIcon.style.display = isLight ? 'inline' : 'none';
  if (moonIcon) moonIcon.style.display = isLight ? 'none' : 'inline';

  themeImages.forEach(img => {
    if (isLight) {
      img.src = img.getAttribute('data-light');
    } else {
      img.src = img.getAttribute('data-dark');
    }
  });

  themeLogos.forEach(img => {
    if (isLight) {
      img.src = img.getAttribute('data-light');
    } else {
      img.src = img.getAttribute('data-dark');
    }
  });

  // 2. NOVO BLOCO: Altera a cor dos ícones dinamicamente
  themeIcons.forEach(icon => {
    if (isLight) {
      icon.style.color = '#2563eb'; // Cor no Modo Claro (Azul escuro/padrão)
    } else {
      icon.style.color = '#47CDFD'; // Cor no Modo Escuro (Azul Neon/Claro)
    }
    // Garante que a transição de cor seja suave
    icon.style.transition = 'color 0.3s ease';
  });
}

updateUI();

if (toggleBtn) {
  toggleBtn.addEventListener('click', () => {
    body.classList.toggle('light');
    const isLight = body.classList.contains('light');
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
    updateUI();
  });
}