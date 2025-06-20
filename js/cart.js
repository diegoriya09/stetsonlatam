let isLoggedIn = false;
let userId = null;

// Verifica si hay sesión activa desde PHP
fetch('php/check_session.php')
  .then(response => response.json())
  .then(data => {
    if (data.logged_in) {
      isLoggedIn = true;
      userId = data.user_id;
      loadCart(); // Cargar desde backend si está logueado
    } else {
      loadCart(); // Cargar desde localStorage si no
    }
});

document.querySelectorAll('.add-to-cart-btn').forEach(button => {
  button.addEventListener('click', () => {
    const producto = {
      id: button.dataset.id,
      name: button.dataset.name,
      price: parseFloat(button.dataset.price),
      image: button.dataset.image,
      quantity: 1
    };

    if (isLoggedIn) {
      fetch('php/cart/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ producto_id: producto.id, quantity: 1 })
      }).then(() => loadCart());
    } else {
      let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      const index = carrito.findIndex(p => p.id === producto.id);
      if (index !== -1) {
        carrito[index].quantity += 1;
      } else {
        carrito.push(producto);
      }
      localStorage.setItem('carrito', JSON.stringify(carrito));
      loadCart();
    }
  });
});

function loadCart() {
  const carritoItems = document.getElementById('carrito-items');
  const totalCarrito = document.getElementById('total-carrito');
  let total = 0;

  carritoItems.innerHTML = '';

  if (isLoggedIn) {
    fetch('php/cart/get_cart.php')
      .then(res => res.json())
      .then(productos => {
        productos.forEach(p => {
          total += p.price * p.quantity;
          carritoItems.innerHTML += renderItem(p.name, p.price, p.image, p.quantity);
        });
        totalCarrito.textContent = `Total: $${total.toLocaleString()}`;
        updateCartCount(productos);
      });
  } else {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito.forEach(p => {
      total += p.price * p.quantity;
      carritoItems.innerHTML += renderItem(p.name, p.price, p.image, p.quantity);
    });
    totalCarrito.textContent = `Total: $${total.toLocaleString()}`;
    updateCartCount(carrito);
  }
}

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('remove-btn')) {
    const id = e.target.dataset.id;

    if (isLoggedIn) {
      fetch('php/cart/remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ producto_id: id })
      }).then(() => loadCart());
    } else {
      let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      carrito = carrito.filter(p => p.id !== id);
      localStorage.setItem('carrito', JSON.stringify(carrito));
      loadCart();
    }
  }
});

function renderItem(id, name, price, image, quantity) {
  return `
    <div class="carrito-item">
      <img src="${image}" alt="${name}" class="carrito-img">
      <div class="carrito-info">
        <h4>${name}</h4>
        <p>$${price.toLocaleString()} x ${quantity}</p>
        <button class="remove-btn" data-id="${id}">Eliminar</button>
      </div>
    </div>`;
}

function updateCartCount(productos) {
  const total = productos.reduce((acc, p) => acc + p.quantity, 0);
  const count = document.getElementById('cart-count');
  count.textContent = total;
  count.style.display = total > 0 ? 'block' : 'none';
}
