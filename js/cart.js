// ✅ cart.js (final funcional)
document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");
  let isLoggedIn = false;

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
          const loggedIn = data.logged_in === true;
          loadCart(loggedIn);
        } else {
          loadCart(false);
        }
      });
  } else {
    loadCart(false);
  }

  setupAddToCartButtons();
});

function setupAddToCartButtons() {
  document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', handleAddToCart);
  });
}

function handleAddToCart(e) {
  const button = e.currentTarget;
  const jwt = localStorage.getItem("jwt");

  const producto = {
    id: parseInt(button.dataset.id),
    name: button.dataset.name,
    price: parseFloat(button.dataset.price),
    image: button.dataset.image,
    quantity: 1
  };

  if (jwt) {
    fetch('php/cart/add_to_cart.php', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + jwt,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ producto_id: producto.id, quantity: 1 })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          loadCart(true);
        } else {
          console.error("Error al agregar:", data.message);
        }
      });
  } else {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const index = carrito.findIndex(p => p.id === producto.id);
    if (index !== -1) {
      carrito[index].quantity += 1;
    } else {
      carrito.push(producto);
    }
    localStorage.setItem('carrito', JSON.stringify(carrito));
    loadCart(false);
  }
}

function loadCart(isLoggedIn) {
  const carritoItems = document.getElementById('carrito-items');
  const totalCarrito = document.getElementById('total-carrito');
  const jwt = localStorage.getItem("jwt");
  let total = 0;

  carritoItems.innerHTML = ''; // ✅ limpia visualmente antes de renderizar

  if (isLoggedIn && jwt) {
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
    const id = parseInt(e.target.dataset.id);
    const jwt = localStorage.getItem("jwt");

    if (jwt) {
      fetch('php/cart/remove_from_cart.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ producto_id: id })
      }).then(() => loadCart(true));
    } else {
      let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      carrito = carrito.filter(p => p.id !== id);
      localStorage.setItem('carrito', JSON.stringify(carrito));
      loadCart(false);
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
