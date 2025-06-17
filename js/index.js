document.addEventListener("DOMContentLoaded", function () {
  const openModalBtn = document.getElementById("open-user-modal");
  const modal = document.getElementById("user-modal");
  const closeModalBtn = modal.querySelector(".close");

  const loginForm = document.getElementById("login-form");
  const registerForm = document.getElementById("register-form");
  const switchToRegister = document.getElementById("switch-to-register");
  const switchToLogin = document.getElementById("switch-to-login");

  // Abrir modal
  openModalBtn.addEventListener("click", function (e) {
    e.preventDefault();
    modal.style.display = "block";
    loginForm.style.display = "block";
    registerForm.style.display = "none";
  });

  // Cerrar modal
  closeModalBtn.addEventListener("click", function () {
    modal.style.display = "none";
  });

  // Cerrar modal si se hace clic fuera del contenido
  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  // Cambiar a formulario de registro
  if (switchToRegister) {
    switchToRegister.addEventListener("click", function (e) {
      e.preventDefault();
      loginForm.style.display = "none";
      registerForm.style.display = "block";
    });
  }

  // Cambiar a formulario de login
  if (switchToLogin) {
    switchToLogin.addEventListener("click", function (e) {
      e.preventDefault();
      registerForm.style.display = "none";
      loginForm.style.display = "block";
    });
  }
});


