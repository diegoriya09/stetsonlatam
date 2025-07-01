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
});