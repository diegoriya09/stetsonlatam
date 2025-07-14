document.addEventListener('DOMContentLoaded', function() {
  const btnMenos = document.querySelector('.menos');
  const btnMas = document.querySelector('.mas');
  const inputCantidad = document.getElementById('cantidad');

  btnMenos.addEventListener('click', function() {
    let valor = parseInt(inputCantidad.value, 10);
    if (valor > 1) {
      inputCantidad.value = valor - 1;
    }
  });

  btnMas.addEventListener('click', function() {
    let valor = parseInt(inputCantidad.value, 10);
    inputCantidad.value = valor + 1;
  });

  document.querySelectorAll('.miniatura').forEach(function(mini) {
    mini.addEventListener('click', function() {
      document.getElementById('img-principal').src = this.src;
      document.querySelectorAll('.miniatura').forEach(m => m.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Selección de color y talla independientes
  let selectedColor = null;
  let selectedSize = null;

  document.querySelectorAll('.color-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('selected'));
      this.classList.add('selected');
      selectedColor = this.getAttribute('data-color') || null;
      // Actualizar el botón de añadir al carrito
      const addBtn = document.querySelector('.add-to-cart-btn');
      if (addBtn) addBtn.dataset.color = selectedColor;
    });
  });

  document.querySelectorAll('.size-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
      this.classList.add('selected');
      selectedSize = this.dataset.size;
      // Actualizar el botón de añadir al carrito
      const addBtn = document.querySelector('.add-to-cart-btn');
      if (addBtn) addBtn.dataset.size = selectedSize;
    });
  });
});