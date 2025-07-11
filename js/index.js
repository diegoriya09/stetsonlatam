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
        alert("Please complete all fields.");
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
              title: 'Successful registration!',
              text: 'You can now log in',
              confirmButtonText: 'Go to login'
            }).then(() => {
              // ✅ Mostrar formulario de login
              registerFormSection.style.display = 'none';
              loginFormSection.style.display = 'block';
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: data.message || 'There was an error while registering'
            });
          }
        })
        .catch(err => {
          Swal.fire({
            icon: 'error',
            title: 'Network error',
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
      title: 'Closed session',
      icon: 'info',
      confirmButtonText: 'OK'
    }).then(() => {
      location.reload();
    });
  });

  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  const title = document.getElementById('hero-title');
  const text = document.getElementById('hero-text');
  const button = document.getElementById('hero-btn');

  let currentIndex = 0;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
      dots[i].classList.toggle('active', i === index);
    });

    const activeSlide = slides[index];
    title.textContent = activeSlide.dataset.title;
    text.textContent = activeSlide.dataset.text;
    button.setAttribute('href', activeSlide.dataset.link);
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
  }

  // Cambio automático cada 5 segundos
  setInterval(nextSlide, 5000);

  // Click en los puntos
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      currentIndex = parseInt(dot.dataset.index);
      showSlide(currentIndex);
    });
  });

  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');

  hamburger.addEventListener('click', function() {
    mobileMenu.classList.toggle('active');
  });

  // Opcional: cerrar menú al hacer click fuera
  document.addEventListener('click', function(e) {
    if (!mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
      mobileMenu.classList.remove('active');
    }
  });
});

// Abrir y cerrar carrito (sidebar)
document.getElementById('btn-carrito').addEventListener('click', () => {
  document.getElementById('carrito-sidebar').classList.add('open');
  const jwt = localStorage.getItem("jwt");
  const isLoggedIn = !!jwt;
  loadCart(isLoggedIn); // actualiza contenido
});

document.getElementById('cerrar-carrito').addEventListener('click', () => {
  document.getElementById('carrito-sidebar').classList.remove('open');
});

document.querySelector('.pagar-btn').addEventListener('click', function() {
  document.getElementById('checkout-modal').style.display = 'flex';
});
document.getElementById('cerrar-checkout').addEventListener('click', function() {
  document.getElementById('checkout-modal').style.display = 'none';
  document.getElementById('checkout-form').style.display = 'block';
  document.getElementById('checkout-confirm').style.display = 'none';
});

document.getElementById('pais-select').addEventListener('change', function() {
  const selected = this.options[this.selectedIndex];
  const code = selected.getAttribute('data-code') || '';
  document.getElementById('codigo-pais').textContent = code;
});

document.querySelector('select[name="metodo"]').addEventListener('change', function() {
  const tarjetaFields = document.getElementById('tarjeta-fields');
  const pseFields = document.getElementById('pse-fields');
  if (this.value === 'tarjeta') {
    tarjetaFields.style.display = 'block';
    pseFields.style.display = 'none';
  } else if (this.value === 'pse') {
    tarjetaFields.style.display = 'none';
    pseFields.style.display = 'block';
  } else {
    tarjetaFields.style.display = 'none';
    pseFields.style.display = 'none';
  }
});

document.getElementById('checkout-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const metodo = this.metodo.value;
  // Concatenar código de país y teléfono
  const codigoPais = document.getElementById('codigo-pais').textContent;
  const telefono = this.telefono.value;
  const telefonoCompleto = codigoPais + telefono;

  if (metodo === 'tarjeta') {
    const numero = this.numero_tarjeta.value.trim();
    const nombre = this.nombre_tarjeta.value.trim();
    const expiracion = this.expiracion.value.trim();
    const cvv = this.cvv.value.trim();

    if (!numero || !nombre || !expiracion || !cvv) {
      alert('Please complete all card information.');
      return;
    }
    // Puedes agregar validaciones adicionales aquí (longitud, formato, etc.)
  }

  if (metodo === 'pse') {
    const banco = this.banco_pse.value;
    const tipoCuenta = this.tipo_cuenta_pse.value;
    const documento = this.documento_pse.value.trim();
    if (!banco || !tipoCuenta || !documento) {
      alert('Please complete all PSE data.');
      return;
    }
    // Aquí puedes simular el pago o enviar los datos a tu backend
  }

  // Simulación de pago exitoso
  document.getElementById('checkout-form').style.display = 'none';
  document.getElementById('checkout-confirm').style.display = 'block';
  document.getElementById('checkout-confirm').innerHTML = `
    <h3>Successful payment!</h3>
    <p>Thank you for your purchase. We have sent you a message with the summary.</p>
    <p><strong>Phone:</strong> ${telefonoCompleto}</p>
  `;
});

const eyeIcons = {
   open: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" /></svg>',
   closed: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon"><path d="M3.53 2.47a.75.75 0 00-1.06 1.06l18 18a.75.75 0 101.06-1.06l-18-18zM22.676 12.553a11.249 11.249 0 01-2.631 4.31l-3.099-3.099a5.25 5.25 0 00-6.71-6.71L7.759 4.577a11.217 11.217 0 014.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113z" /><path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0115.75 12zM12.53 15.713l-4.243-4.244a3.75 3.75 0 004.243 4.243z" /><path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 00-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 016.75 12z" /></svg>'
};

function addListeners() {
   const toggleButton = document.querySelector(".toggle-button");
   
   if (!toggleButton) {
      return;
   }
   
   toggleButton.addEventListener("click", togglePassword);
}

function togglePassword() {
   const passwordField = document.querySelector("#password-field");
   const toggleButton = document.querySelector(".toggle-button");
   
   if (!passwordField || !toggleButton) {
      return;
   }
   
   toggleButton.classList.toggle("open");
   
   const isEyeOpen = toggleButton.classList.contains("open");

   toggleButton.innerHTML = isEyeOpen ? eyeIcons.closed : eyeIcons.open;
   passwordField.type = isEyeOpen ? "text" : "password";
}

