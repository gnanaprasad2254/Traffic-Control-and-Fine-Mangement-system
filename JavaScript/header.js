  const loginButton = document.getElementById('loginButton');
  const loginMenu = document.getElementById('loginMenu');

  loginButton.addEventListener('click', () => {
    loginMenu.classList.toggle('hidden');
  });

  window.addEventListener('click', (e) => {
    if (!loginButton.contains(e.target) && !loginMenu.contains(e.target)) {
      loginMenu.classList.add('hidden');
    }
  });
