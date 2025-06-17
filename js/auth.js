document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.querySelector('#login-form form');

  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(loginForm);

      const response = await fetch('php/login.php', {
        method: 'POST',
        body: formData,
      });

      const result = await response.json();

      if (result.token) {
        localStorage.setItem('jwt', result.token);
        Swal.fire({
          title: 'Bienvenido',
          text: 'Inicio de sesión exitoso',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire("Error", result.error || "No se pudo iniciar sesión", "error");
      }
    });
  }

  // Mostrar u ocultar botón de logout
  const userIcon = document.getElementById('open-user-modal');
  const logoutBtn = document.getElementById('logout-btn');

  if (localStorage.getItem('jwt')) {
    if (userIcon) userIcon.style.display = 'none';
    if (logoutBtn) logoutBtn.style.display = 'inline-block';
  } else {
    if (logoutBtn) logoutBtn.style.display = 'none';
  }

  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      localStorage.removeItem('jwt');
      location.reload();
    });
  }
});
