document.addEventListener('DOMContentLoaded', () => {
  // Redirección para los botones del navbar
  const userBtn = document.getElementById('user-btn');
  const cartBtn = document.getElementById('cart-btn');

  if (userBtn) {
    userBtn.addEventListener('click', () => {
      const jwt = localStorage.getItem('jwt');
      if (jwt) {
        window.location.href = 'profile.php';
      } else {
        // Mostrar modal login/register si existe
        const modal = document.getElementById('user-modal');
        if (modal) {
          modal.style.display = 'block';
          // Asignar evento a la X para cerrar el modal
          const closeBtn = modal.querySelector('.close');
          if (closeBtn) {
            closeBtn.onclick = function() {
              modal.style.display = 'none';
            };
          }
        } else {
          alert('Por favor inicia sesión o regístrate.');
        }
      }
    });
  }

  if (cartBtn) {
    cartBtn.addEventListener('click', () => {
      window.location.href = 'cart.php';
    });
  }

  // Botón Sign Up (Join Stetson Community)
  const signUpBtn = document.getElementById('open-user-modal');
  if (signUpBtn) {
    signUpBtn.addEventListener('click', () => {
      const modal = document.getElementById('user-modal');
      if (modal) {
        modal.style.display = 'block';
        // Mostrar pestaña de registro
        const loginTab = document.getElementById('switch-to-login');
        const registerTab = document.getElementById('switch-to-register');
        const loginFormSection = document.getElementById('login-form');
        const registerFormSection = document.getElementById('register-form');
        if (loginTab && registerTab && loginFormSection && registerFormSection) {
          registerTab.classList.add('border-[#3c3737]', 'text-[#3c3737]');
          registerTab.classList.remove('border-transparent', 'text-[#7a7671]');
          loginTab.classList.remove('border-[#3c3737]', 'text-[#3c3737]');
          loginTab.classList.add('border-transparent', 'text-[#7a7671]');
          loginFormSection.style.display = 'none';
          registerFormSection.style.display = 'block';
        }
        // Asignar evento a la X para cerrar el modal
        const closeBtn = modal.querySelector('.close');
        if (closeBtn) {
          closeBtn.onclick = function() {
            modal.style.display = 'none';
          };
        }
      }
    });
  }
});

