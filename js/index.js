document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('user-modal');
  const openModalBtn = document.getElementById('open-user-modal');
  const closeModalBtn = document.querySelector('.close');
  const loginFormSection = document.getElementById('login-form');
  const registerFormSection = document.getElementById('register-form');
  const switchToRegister = document.getElementById('switch-to-register');
  const switchToLogin = document.getElementById('switch-to-login');
  const registerForm = document.getElementById('registerForm');
  const loginForm = document.querySelector('#login-form form');

  // Abrir modal
  if (openModalBtn && modal) {
    openModalBtn.addEventListener('click', (e) => {
      e.preventDefault();
      modal.style.display = 'block';
    });
  }

  // Cerrar modal
  if (closeModalBtn && modal) {
    closeModalBtn.addEventListener('click', () => {
      modal.style.display = 'none';
    });
  }

  // Cambiar a formulario de registro
  if (switchToRegister && loginFormSection && registerFormSection) {
    switchToRegister.addEventListener('click', (e) => {
      e.preventDefault();
      loginFormSection.style.display = 'none';
      registerFormSection.style.display = 'block';
    });
  }

  // Cambiar a formulario de login
  if (switchToLogin && loginFormSection && registerFormSection) {
    switchToLogin.addEventListener('click', (e) => {
      e.preventDefault();
      registerFormSection.style.display = 'none';
      loginFormSection.style.display = 'block';
    });
  }

  // Validar el formulario de registro antes de enviar
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const name = registerForm.querySelector('input[name="name"]').value;
      const email = registerForm.querySelector('input[name="email"]').value;
      const password = registerForm.querySelector('input[name="password"]').value;

      if (!name || !email || !password) {
        alert("Por favor, completa todos los campos.");
        return;
      }

      // Enviar los datos por fetch
      fetch("php/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ name, email, password })
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") {
            // ✅ Aquí va Swal.fire
            Swal.fire({
              icon: 'success',
              title: '¡Registro exitoso!',
              text: 'Ahora puedes iniciar sesión',
              confirmButtonText: 'Ir al login'
            }).then(() => {
              // ✅ Mostrar formulario de login
              registerFormSection.style.display = 'none';
              loginFormSection.style.display = 'block';
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: data.message || 'Hubo un error al registrar'
            });
          }
        })
        .catch(err => {
          Swal.fire({
            icon: 'error',
            title: 'Error de red',
            text: err.message
          });
        });
    });
  }

  const logoutBtn = document.getElementById('logout-btn');

  if (localStorage.getItem('jwt')) {
    logoutBtn.style.display = 'inline-block';
  }

  logoutBtn.addEventListener('click', () => {
    localStorage.removeItem('jwt');
    Swal.fire({
      title: 'Sesión cerrada',
      icon: 'info',
      confirmButtonText: 'OK'
    }).then(() => {
      location.reload();
    });
  });

  let cartCount = 0;
  const cartCountElement = document.getElementById("cart-count");
  const addToCartButtons = document.querySelectorAll(".add-to-cart");

  addToCartButtons.forEach(button => {
    button.addEventListener("click", () => {
      cartCount++;
      cartCountElement.textContent = cartCount;
      cartCountElement.style.display = "block";
    });
  });

  const slides = document.querySelectorAll('.hero-slider .slide');
  const dots   = document.querySelectorAll('.dot');
  const title  = document.getElementById('hero-title');
  const text   = document.getElementById('hero-text');
  const btn    = document.getElementById('hero-btn');

  let curr = 0;

  function showSlide(i) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[i].classList.add('active');
    dots[i].classList.add('active');

    title.innerText = slides[i].dataset.title;
    text.innerText  = slides[i].dataset.text;
    btn.href        = slides[i].dataset.link;
  }

  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      curr = parseInt(dot.dataset.index);
      showSlide(curr);
    });
  });

  // Avance automático cada 7 segundos
  setInterval(() => {
    curr = (curr + 1) % slides.length;
    showSlide(curr);
  }, 7000);
});

// Abrir y cerrar carrito (sidebar)
document.getElementById('btn-carrito').addEventListener('click', () => {
  document.getElementById('carrito-sidebar').classList.add('open');
  loadCart(); // actualiza contenido
});

document.getElementById('cerrar-carrito').addEventListener('click', () => {
  document.getElementById('carrito-sidebar').classList.remove('open');
});