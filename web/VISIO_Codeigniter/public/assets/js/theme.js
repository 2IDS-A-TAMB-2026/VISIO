const toggleBtn = document.getElementById('theme-toggle');
const body = document.body;
const sunIcon = document.getElementById('icon-sun');
const moonIcon = document.getElementById('icon-moon');
const themeImages = document.querySelectorAll('.theme-img');
const themeLogos = document.querySelectorAll('.logo');
const themeVideos = document.querySelectorAll('.theme-video');
const savedTheme = localStorage.getItem('theme');

if (savedTheme === 'light') {
  body.classList.add('light');
}

function updateUI() {
  const isLight = body.classList.contains('light');

  if (sunIcon) sunIcon.style.display = isLight ? 'inline' : 'none';
  if (moonIcon) moonIcon.style.display = isLight ? 'none' : 'inline';

  themeImages.forEach(img => {
    img.src = isLight
      ? img.getAttribute('data-light')
      : img.getAttribute('data-dark');
  });

  themeLogos.forEach(img => {
    img.src = isLight
      ? img.getAttribute('data-light')
      : img.getAttribute('data-dark');
  });

  themeVideos.forEach(video => {
    video.src = isLight
      ? video.getAttribute('data-light')
      : video.getAttribute('data-dark');

    video.load();
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
