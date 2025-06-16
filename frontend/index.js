document.querySelector('#registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const name = document.querySelector('#name').value;
  const email = document.querySelector('#email').value;
  const password = document.querySelector('#password').value;

  const res = await fetch('http://localhost:3001/api/auth/register', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, email, password })
  });

  const data = await res.json();
  alert(data.message);
});

document.addEventListener('DOMContentLoaded', function () {
  const cta = document.querySelector('.cta');
  if (cta) {
    cta.addEventListener('click', function () {
      document.querySelector('#categories').scrollIntoView({ behavior: 'smooth' });
    });
  }

  const cartIcon = document.querySelector('.cart-icon');
  if (cartIcon) {
    cartIcon.addEventListener('click', function () {
      window.location.href = '/cart.html'; // o ajusta según la ruta de tu carrito
    });
  }
});

// Abrir modal al hacer clic en el ícono de usuario
const userIcon = document.querySelector('img[alt="User"]');
const modal = document.getElementById('user-modal');
const closeBtn = document.querySelector('.modal .close');

userIcon.addEventListener('click', () => {
  modal.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
  modal.style.display = 'none';
});

window.addEventListener('click', function (event) {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
});

// Switch entre login y registro
const loginSection = document.getElementById('login-form');
const registerSection = document.getElementById('register-form');
const toRegister = document.getElementById('switch-to-register');
const toLogin = document.getElementById('switch-to-login');

toRegister.addEventListener('click', (e) => {
  e.preventDefault();
  loginSection.style.display = 'none';
  registerSection.style.display = 'block';
});

toLogin.addEventListener('click', (e) => {
  e.preventDefault();
  registerSection.style.display = 'none';
  loginSection.style.display = 'block';
});
