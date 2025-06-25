document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");
  console.log("JWT encontrado:", jwt); // 👈 Verifica que sí está

  if (jwt) {
    fetch("php/check_session.php", {
      method: "GET",
      headers: {
        "Authorization": "Bearer " + jwt // 👈 Aquí va el token
      }
    })
    .then((res) => res.json())
    .then((data) => {
      console.log("Respuesta de check_session.php:", data); // 👈 Aquí deberías ver logged_in: true
      if (data.logged_in) {
        // ✅ Usuario autenticado
        console.log("Usuario logueado:", data.user_id);
      } else {
        // ❌ Token inválido o expirado
        console.warn("Sesión inválida:", data.message || data.error);
      }
    })
    .catch((err) => {
      console.error("Error en check_session.php:", err);
    });
  } else {
    console.warn("No hay token guardado en localStorage");
  }

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
      button.addEventListener('click', () => {
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

    if (jwt) {
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

      if (jwt) {
        fetch('php/cart/remove_from_cart.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
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
});