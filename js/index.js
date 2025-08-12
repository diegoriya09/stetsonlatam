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
});

