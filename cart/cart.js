// Leer carrito del localStorage (si existe)
  function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
  }

  // Guardar carrito actualizado en localStorage
  function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
  }

  // Agregar producto al carrito
  function addToCart(productId, name, price) {
    let cart = getCart();
    const index = cart.findIndex(item => item.producto_id == productId);

    if (index >= 0) {
      cart[index].quantiy += 1;
    } else {
      cart.push({ producto_id: parseInt(productId), name, price: parseInt(price), quantity: 1 });
    }

    saveCart(cart);
    alert("Producto agregado al carrito");
  }

  // Escuchar clics en botones
  document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      const price = this.getAttribute('data-price');
      addToCart(id, name, price);
    });
  });

  function mostrarCarrito() {
    const cart = getCart();
    const cartItems = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");
    cartItems.innerHTML = "";

    let total = 0;

    cart.forEach((item, index) => {
      const subtotal = item.price * item.quantity;
      total += subtotal;

      cartItems.innerHTML += `
        <tr>
          <td>${item.name}</td>
          <td>$${item.price.toLocaleString()}</td>
          <td>${item.quantity}</td>
          <td>$${subtotal.toLocaleString()}</td>
          <td><button onclick="eliminarDelCarrito(${index})">Eliminar</button></td>
        </tr>
      `;
    });

    cartTotal.textContent = "$" + total.toLocaleString();
  }

  function eliminarDelCarrito(index) {
    const cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    mostrarCarrito();
  }

  function finalizarCompra() {
    const cart = getCart();
    if (cart.length === 0) {
      alert("El carrito está vacío");
      return;
    }

    // Validar si el usuario está logueado (por ejemplo, con una cookie o sessionStorage)
    const isLoggedIn = sessionStorage.getItem("token") !== null;

    if (isLoggedIn) {
      alert("Procesando pago con sesión activa...");
      // Aquí llamarías a un backend que procese la compra del usuario logueado
    } else {
      alert("Procesando pago como invitado...");
      // Aquí puedes redirigir al pago para no logueado
    }

    // Vaciar carrito después de pagar
    localStorage.removeItem("cart");
    mostrarCarrito();
  }

  // Llamar la función al cargar
  document.addEventListener("DOMContentLoaded", mostrarCarrito);

  function guardarCarritoEnServidor(token, userId) {
  const cart = getCart(); // viene de paso 1

  if (cart.length === 0) return;

  fetch('/cart/add.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      productos: cart
    })
  })
  .then(res => res.json())
  .then(data => {
    console.log(data.message);
    localStorage.removeItem("cart"); // limpiar localStorage
  })
  .catch(err => console.error('Error al guardar el carrito:', err));
}

function mostrarCarritoDesdeServidor() {
  fetch('/cart/cart.php')
    .then(res => {
      if (!res.ok) throw new Error("No autenticado");
      return res.json();
    })
    .then(data => {
      console.log("Carrito desde BD:", data);
      renderizarCarrito(data); // función para mostrar en HTML
    })
    .catch(err => {
      console.warn("No se pudo obtener el carrito del servidor:", err.message);
      // Si no está logueado, mostrar el carrito local
      renderizarCarrito(getCart());
    });
}

function renderizarCarrito(carrito) {
  const contenedor = document.getElementById("cart-container");
  contenedor.innerHTML = "";

  if (carrito.length === 0) {
    contenedor.innerHTML = "<p>Tu carrito está vacío.</p>";
    return;
  }

  carrito.forEach(item => {
    const div = document.createElement("div");
    div.classList.add("cart-item");
    div.innerHTML = `
      <img src="${item.image}" alt="${item.name}" width="60">
      <strong>${item.name}</strong><br>
      Precio: $${item.price} <br>
      Cantidad: ${item.quantity}
    `;
    contenedor.appendChild(div);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  mostrarCarritoDesdeServidor(); // o mostrar desde localStorage si no logueado
});

document.querySelectorAll('.add-to-cart-btn').forEach(button => {
  button.addEventListener('click', () => {
    const producto = {
      id: button.dataset.id,
      name: button.dataset.name,
      price: button.dataset.price,
      image: button.dataset.image
    };

    // Verificar si el usuario está logueado con fetch a un archivo PHP
    fetch('/cart/verify_login.php')
      .then(res => res.json())
      .then(data => {
        if (data.logueado) {
          // Guardar en base de datos
          agregarProductoAlCarritoBD(producto);
        } else {
          // Guardar en localStorage
          agregarProductoAlLocalStorage(producto);
        }
      });
  });
});

function agregarProductoAlCarritoBD(producto) {
  fetch('/cart/add.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(producto)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Producto agregado al carrito.');
      mostrarCarritoDesdeServidor();
    } else {
      alert('Error al agregar el producto.');
    }
  });
}

function agregarProductoAlLocalStorage(producto) {
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

  const index = carrito.findIndex(p => p.id == producto.id);

  if (index !== -1) {
    carrito[index].cantidad += 1;
  } else {
    producto.cantidad = 1;
    carrito.push(producto);
  }

  localStorage.setItem('carrito', JSON.stringify(carrito));
  alert('Producto agregado al carrito (local)');
  renderizarCarrito(carrito);
}






