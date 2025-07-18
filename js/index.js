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

  const form = this;
  const metodo = form.metodo.value;

  // Concatenar código de país y teléfono
  const codigoPais = document.getElementById('codigo-pais').textContent;
  const telefono = form.telefono.value;
  const telefonoCompleto = codigoPais + telefono;

  // Validaciones frontend
  if (metodo === 'tarjeta') {
    const numero = form.numero_tarjeta.value.trim();
    const nombre = form.nombre_tarjeta.value.trim();
    const expiracion = form.expiracion.value.trim();
    const cvv = form.cvv.value.trim();
    if (!numero || !nombre || !expiracion || !cvv) {
      alert('Please complete all card information.');
      return;
    }
  }

  if (metodo === 'pse') {
    const banco = form.banco_pse.value;
    const tipoCuenta = form.tipo_cuenta_pse.value;
    const documento = form.documento_pse.value.trim();
    if (!banco || !tipoCuenta || !documento) {
      alert('Please complete all PSE data.');
      return;
    }
  }

  // Enviar al backend (checkout.php)
  const form = document.getElementById("checkout-form");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault(); // 👈 Evita que se recargue la página

      const formData = new FormData(form);

      fetch("ruta_a_tu_php/checkout.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.text()) // O .json() si devuelves JSON
        .then(result => {
          console.log(result);
          // Puedes mostrar un mensaje o cerrar el modal aquí
          if (result.includes("¡Pago procesado correctamente!")) {
            alert("✅ Pago exitoso");
            form.reset();
            localStorage.removeItem("cart");
            document.getElementById("cart-items").innerHTML = "";
            document.getElementById("cart-total").textContent = "$0";
            document.getElementById("cart-count").textContent = "0";
          } else {
            alert("❌ Error: " + result);
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("Hubo un error al procesar el pago.");
        });
    });
  }
});

