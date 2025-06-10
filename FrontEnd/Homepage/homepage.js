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