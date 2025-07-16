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

  const jwt = localStorage.getItem('jwt');
  const misPedidosLink = document.getElementById('mis-pedidos-link');
  const misPedidosLinkMobile = document.getElementById('mis-pedidos-link-mobile');
  const logoutBtn = document.getElementById('logout-btn');
  const loginBtn = document.getElementById('open-user-modal');

  if (jwt) {
    fetch("php/check_session.php", {
      method: "GET",
      headers: {
        "Authorization": "Bearer " + jwt
      }
    })
      .then(res => res.json())
      .then(data => {
        if (data.logged_in) {
          if (misPedidosLink) misPedidosLink.style.display = "inline-block";
          if (misPedidosLinkMobile) misPedidosLinkMobile.style.display = "block";
          if (logoutBtn) logoutBtn.style.display = "inline-block";
          if (loginBtn) loginBtn.style.display = "none";
        }
      })
      .catch(() => {
        if (misPedidosLink) misPedidosLink.style.display = "none";
        if (misPedidosLinkMobile) misPedidosLinkMobile.style.display = "none";
      });
  } else {
    if (misPedidosLink) misPedidosLink.style.display = "none";
    if (misPedidosLinkMobile) misPedidosLinkMobile.style.display = "none";
    if (logoutBtn) logoutBtn.style.display = "none";
    if (loginBtn) loginBtn.style.display = "inline-block";
  }


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

  hamburger.addEventListener('click', function () {
    mobileMenu.classList.toggle('active');
  });

  // Opcional: cerrar menú al hacer click fuera
  document.addEventListener('click', function (e) {
    if (!mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
      mobileMenu.classList.remove('active');
    }
  });
});

// Abrir y cerrar carrito (sidebar)
document.getElementById('btn-carrito').addEventListener('click', () => {
  document.getElementById('carrito-sidebar').classList.add('open');
  //const jwt = localStorage.getItem("jwt");
  //const isLoggedIn = !!jwt;
  //loadCart(isLoggedIn); // actualiza contenido
});

document.getElementById('cerrar-carrito').addEventListener('click', () => {
  document.getElementById('carrito-sidebar').classList.remove('open');
});

document.querySelector('.pagar-btn').addEventListener('click', function () {
  document.getElementById('checkout-modal').style.display = 'flex';
});
document.getElementById('cerrar-checkout').addEventListener('click', function () {
  document.getElementById('checkout-modal').style.display = 'none';
  document.getElementById('checkout-form').style.display = 'block';
  document.getElementById('checkout-confirm').style.display = 'none';
});

document.getElementById('pais-select').addEventListener('change', function () {
  const selected = this.options[this.selectedIndex];
  const code = selected.getAttribute('data-code') || '';
  document.getElementById('codigo-pais').textContent = code;
});

document.querySelector('select[name="metodo"]').addEventListener('change', function () {
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

document.getElementById('checkout-form').addEventListener('submit', function (e) {
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

