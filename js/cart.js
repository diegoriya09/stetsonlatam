let isLoggedIn = false;
let userId = null;

// Verifica si hay sesión activa desde PHP
fetch('php/check_session.php', {
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('jwt')
  }
})
  .then(response => response.json())
  .then(data => {
    const jwt = localStorage.getItem('jwt');
    if (data.logged_in && jwt) { // <-- Solo sincroniza si hay JWT
      isLoggedIn = true;
      userId = data.user_id;
      const localCarrito = JSON.parse(localStorage.getItem('carrito'));
      if (localCarrito && localCarrito.length > 0) {
        Promise.all(localCarrito.map(item =>
          fetch('php/cart/add_to_cart.php', {
            method: 'POST',
            headers: {
              'Authorization': 'Bearer ' + jwt,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ producto_id: item.id, quantity: item.quantity })
          }).then(res => res.json())
        )).then(responses => {
          // Puedes revisar aquí si alguna respuesta tiene success: false
          if (responses.some(r => r.success === false)) {
            alert('Error al sincronizar el carrito');
          }
          localStorage.removeItem('carrito');
          loadCart();
        });
      } else {
        loadCart();
      }
    } else {
      loadCart();
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
      const jwt = localStorage.getItem('jwt');
      fetch('php/cart/add_to_cart.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
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
    const jwt = localStorage.getItem('jwt');
    // Siempre carga desde backend
    fetch('php/cart/get_cart.php', {
      headers: {
        'Authorization': 'Bearer ' + jwt
      }
    })
      .then(response => response.json())
      .then(carrito => {
        carrito.forEach(p => {
          total += p.price * p.quantity;
          carritoItems.innerHTML += renderItem(p.id, p.name, p.price, p.image, p.quantity);
        });
        totalCarrito.textContent = `Total: $${total.toLocaleString()}`;
      });
  } else {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito.forEach(p => {
      total += p.price * p.quantity;
      carritoItems.innerHTML += renderItem(p.id, p.name, p.price, p.image, p.quantity);
    });
    totalCarrito.textContent = `Total: $${total.toLocaleString()}`;
  }
}

document.addEventListener('click', function (e) {
  if (e.target.classList.contains('remove-btn')) {
    const id = e.target.dataset.id;

    if (isLoggedIn) {
      const jwt = localStorage.getItem('jwt');
      fetch('php/cart/remove_from_cart.php', {
        headers: {
          'Authorization': 'Bearer ' + jwt
        }
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
        <a class="remove-btn" data-id="${id}"><i class="fas fa-trash-alt"></i></a>
      </div>
    </div>`;
}

document.getElementById('logout-btn').addEventListener('click', function () {
  const jwt = localStorage.getItem('jwt');

  // 1. Obtener el carrito del backend y guardarlo en localStorage
  fetch('php/cart/get_cart.php', {
    headers: { 'Authorization': 'Bearer ' + jwt }
  })
    .then(response => response.json())
    .then(carrito => {
      localStorage.setItem('carrito', JSON.stringify(carrito)); // 🔁 Guarda antes de cerrar sesión

      // 2. Llamar al logout PHP
      fetch('php/logout.php', { method: 'POST' }).then(() => {
        isLoggedIn = false;
        userId = null;
        localStorage.removeItem('jwt');  // Elimina solo el token
        clearCartUI();
        loadCart();
      });
    });
});


function clearCartUI() {
  const carritoItems = document.getElementById('carrito-items');
  const totalCarrito = document.getElementById('total-carrito');
  if (carritoItems) carritoItems.innerHTML = '';
  if (totalCarrito) totalCarrito.textContent = 'Total: $0';
}