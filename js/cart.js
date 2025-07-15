// ✅ cart.js (final funcional)
document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");

  // Verificamos sesión válida
  fetch("php/check_session.php", {
    method: "GET",
    headers: {
      "Authorization": "Bearer " + jwt
    }
  })
    .then(res => res.json())
    .then(data => {
      if (data.logged_in) {
        loadCart(true);  // ✅ Usuario logueado, carga desde base de datos
      } else {
        localStorage.removeItem("jwt"); // Sesión inválida, limpiar
        loadCart(false); // Cargar local
      }
    })
    .catch(() => loadCart(false));

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

  // Busca el input de cantidad relativo al botón presionado
  const inputCantidad = button.closest('.info-producto').querySelector('#cantidad');
  const quantity = inputCantidad ? parseInt(inputCantidad.value, 10) : 1;

  // Obtener color y talla seleccionados de los data attributes del botón
  const color_id = button.dataset.colorId || null;
  const color_name = button.dataset.colorName || null;
  const size_id = button.dataset.sizeId || null;
  const size_name = button.dataset.sizeName || null;

  // Validar selección antes de agregar
  if (!color_id || !size_id) {
    if (window.Swal) {
      Swal.fire({
        icon: 'warning',
        title: 'Choose color and size',
        text: 'You must select both before adding to the cart.'
      });
    } else {
      alert('You must choose color and size before adding to the cart.');
    }
    return;
  }

  const producto = {
    id: parseInt(button.dataset.id),
    name: button.dataset.name,
    price: parseFloat(button.dataset.price),
    image: button.dataset.image,
    quantity: quantity,
    color_id,
    color_name,
    size_id,
    size_name
  };

  if (jwt) {
    // Enviar color y talla
    const body = {
      producto_id: producto.id,
      quantity: quantity,
      color_id: color_id,
      size_id: size_id
    };
    fetch('php/cart/add_to_cart.php', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + jwt,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(body)
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          loadCart(true);
        } else {
          console.error("Error adding:", data.message);
        }
      });
  } else {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    // Buscar producto por id, color y talla
    const index = carrito.findIndex(p => p.id === producto.id && p.color === color && p.size === size);
    if (index !== -1) {
      carrito[index].quantity += quantity;
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
  let total = 0;

  carritoItems.innerHTML = ''; // Limpia el contenido del carrito antes de renderizar

  if (isLoggedIn) {
    const jwt = localStorage.getItem("jwt");
    fetch('php/cart/get_cart.php', {
      headers: {
        'Authorization': 'Bearer ' + jwt
      }
    })
      .then(response => response.json())
      .then(carrito => {
        carrito.forEach(p => {
          total += p.price * p.quantity;
          carritoItems.innerHTML += renderItem(p);
        });
        totalCarrito.textContent = `Total: $${total.toLocaleString()}`;
      });
  } else {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito.forEach(p => {
      total += p.price * p.quantity;
      carritoItems.innerHTML += renderItem(p); // Renderiza cada producto
    });
    totalCarrito.textContent = `Total: $${total.toLocaleString()}`;
  }
}


document.addEventListener('click', function (e) {
  const removeBtn = e.target.closest('.remove-btn');
  if (removeBtn) {
    const id = parseInt(removeBtn.dataset.id);
    const color_id = parseInt(removeBtn.dataset.colorId);
    const size_id = parseInt(removeBtn.dataset.sizeId);
    const jwt = localStorage.getItem("jwt");

    console.log("Eliminar:", { id, color_id, size_id }); // ✅ depuración

    if (jwt) {
      fetch('php/cart/remove_from_cart.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ producto_id: id, color_id, size_id })
      }).then(() => loadCart(true));
    } else {
      let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      carrito = carrito.filter(p => !(p.id === id && p.color_id === color_id && p.size_id === size_id));
      localStorage.setItem('carrito', JSON.stringify(carrito));
      loadCart(false);
    }
  }
});

function renderItem(product) {
  const {
    id, name, price, image, quantity,
    color_id, color_name, size_id, size_name, hex
  } = product;
  return `
    <div class="carrito-item">
      <img src="${image}" alt="${name}" class="carrito-img" loading="lazy">
      <div class="carrito-info">
        <h4>${name}</h4>
        <p>$${price.toLocaleString()} x ${quantity}</p>
        ${color_name ? `<p><strong>Color:</strong> ${color_name} ${hex ? `<span style="display:inline-block;width:18px;height:18px;border-radius:50%;background:${hex};border:1px solid #ccc;margin-left:6px;vertical-align:middle;"></span>` : ''}</p>` : ''}
        ${size_name ? `<p><strong>Size:</strong> ${size_name}</p>` : ''}
          <a class="remove-btn"
           data-id="${id}"
           data-color-id="${color_id}"
           data-size-id="${size_id}">
          <i class="fas fa-trash-alt"></i>
        </a>
      </div>
    </div>`;
}