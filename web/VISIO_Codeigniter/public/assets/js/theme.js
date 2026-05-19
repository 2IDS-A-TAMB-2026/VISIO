const toggleBtn = document.getElementById('theme-toggle');
const body = document.body;
const sunIcon = document.getElementById('icon-sun');
const moonIcon = document.getElementById('icon-moon');
const logo = document.getElementById('logo');

const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'light') {
    body.classList.add('light');
}

function updateUI() {
    const isLight = body.classList.contains('light');
    if (sunIcon) sunIcon.style.display = isLight ? 'inline' : 'none';
    if (moonIcon) moonIcon.style.display = isLight ? 'none' : 'inline';
    if (logo) {
        logo.src = isLight
            ? 'public/assets/Marca/Logo/LogoLight.jpg'
            : 'public/assets/Marca/Logo/LogoDark.jpg';
    }
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
