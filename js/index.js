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
            closeBtn.onclick = function () {
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
          registerTab.classList.add('border-[#181411]', 'text-[#181411]');
          registerTab.classList.remove('border-transparent', 'text-[#7a7671]');
          loginTab.classList.remove('border-[#181411]', 'text-[#181411]');
          loginTab.classList.add('border-transparent', 'text-[#7a7671]');
          loginFormSection.style.display = 'none';
          registerFormSection.style.display = 'block';
        }
        // Asignar evento a la X para cerrar el modal
        const closeBtn = modal.querySelector('.close');
        if (closeBtn) {
          closeBtn.onclick = function () {
            modal.style.display = 'none';
          };
        }
      }
    });
  }

  const input = document.getElementById('search-input');
  const container = document.getElementById('productos-container');

  // Indicador liviano de "cargando"
  function showLoading() {
    container.innerHTML = '<p class="col-span-3 text-center py-6">Searching...</p>';
  }

  // Debounce: evita bombardear al servidor en cada tecla
  function debounce(fn, delay = 300) {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), delay);
    };
  }

  let controller = null;
  const runSearch = debounce(async (q) => {
    try {
      if (controller) controller.abort();
      controller = new AbortController();

      showLoading();

      const res = await fetch('php/search_products.php?q=' + encodeURIComponent(q), {
        signal: controller.signal,
        headers: { 'X-Requested-With': 'fetch' }
      });
      if (!res.ok) throw new Error('HTTP ' + res.status);

      const html = await res.text();
      container.innerHTML = html;
    } catch (err) {
      if (err.name === 'AbortError') return; // petición cancelada por nueva tecla
      console.error(err);
      container.innerHTML = '<p class="col-span-3 text-center text-red-600 py-6">Error finding.</p>';
    }
  }, 350);

  // Buscar mientras escribe
  input.addEventListener('input', (e) => runSearch(e.target.value));

  // Enter no recarga página
  document.getElementById('search-form').addEventListener('submit', (e) => e.preventDefault());
});

