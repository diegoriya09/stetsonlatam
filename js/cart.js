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
        const localCart = JSON.parse(localStorage.getItem('carrito')) || [];

        if (localCart.length > 0) {
          // Enviamos cada ítem del carrito local al backend
          const promises = localCart.map(item => {
            return fetch('php/cart/add_to_cart.php', {
              method: 'POST',
              headers: {
                'Authorization': 'Bearer ' + jwt,
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                producto_id: item.id,
                quantity: item.quantity,
                color_id: item.color_id,
                size_id: item.size_id,
              })
            });
          });

          // Esperamos que se sincronicen todos y luego limpiamos el local
          Promise.all(promises)
            .then(() => {
              localStorage.removeItem('carrito'); // Eliminamos el localStorage una vez sincronizado
              loadCart(true); // Ahora sí, cargamos desde la base de datos
            })
            .catch(err => {
              console.error("Error sincronizando carrito local:", err);
              loadCart(true); // De todas formas cargar
            });
        } else {
          loadCart(true); // No hay nada local, solo carga del backend
        }
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

  // Obtener el botón de color seleccionado para extraer el HEX
  const colorBtn = document.querySelector('.color-btn.selected');
  const hex = colorBtn ? getComputedStyle(colorBtn).getPropertyValue('--color') : '#000';

  const producto = {
    id: parseInt(button.dataset.id),
    name: button.dataset.name,
    price: parseFloat(button.dataset.price),
    image: button.dataset.image,
    quantity: quantity,
    color_id,
    color_name,
    hex,
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

    // Convertimos a número para comparar correctamente
    const parsedColorId = parseInt(color_id);
    const parsedSizeId = parseInt(size_id);

    // Buscar producto por id, color y talla
    const index = carrito.findIndex(p =>
      p.id === producto.id &&
      parseInt(p.color_id) === parsedColorId &&
      parseInt(p.size_id) === parsedSizeId
    );

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
    const producto_id = parseInt(removeBtn.dataset.id);
    const color_id = removeBtn.dataset.colorId ? parseInt(removeBtn.dataset.colorId) : null;
    const size_id = removeBtn.dataset.sizeId ? parseInt(removeBtn.dataset.sizeId) : null;
    const jwt = localStorage.getItem("jwt");

    if (jwt) {
      fetch('php/cart/remove_from_cart.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ producto_id, color_id, size_id })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            loadCart(true);
          } else {
            console.error('Error al eliminar:', data.message);
          }
        });
    } else {
      let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
      carrito = carrito.filter(p => !(p.id === producto_id && p.color_id == color_id && p.size_id == size_id));
      localStorage.setItem('carrito', JSON.stringify(carrito));
      loadCart(false);
    }
  }

  if (e.target.classList.contains("qty-btn")) {
    const btn = e.target;
    const input = btn.parentElement.querySelector(".cantidad-input");
    let qty = parseInt(input.value);
    const id = btn.dataset.id;
    const colorId = btn.dataset.colorId;
    const sizeId = btn.dataset.sizeId;

    if (btn.classList.contains("plus")) qty++;
    else if (btn.classList.contains("minus") && qty > 1) qty--;

    input.value = qty;

    updateQuantity({ id, color_id: colorId, size_id: sizeId, quantity: qty });
  }
});

function renderItem(product) {
  const {
    id,
    name,
    price,
    image,
    quantity,
    color_name,
    color_id,
    hex,
    size_name,
    size_id,
  } = product;

  return `
    <div class="carrito-item">
      <img src="${image}" alt="${name}" class="carrito-img" loading="lazy">
      <div class="carrito-info">
        <h4>${name}</h4>
        <div class="cantidad-control">
          <label>$${price.toLocaleString()} x </label>
          <div class="qty-wrapper">
            <button class="qty-btn minus" data-id="${id}" data-color-id="${color_id}" data-size-id="${size_id}">−</button>
            <input type="text" class="cantidad-input" value="${quantity}" readonly
              data-id="${id}" data-color-id="${color_id}" data-size-id="${size_id}" />
            <button class="qty-btn plus" data-id="${id}" data-color-id="${color_id}" data-size-id="${size_id}">+</button>
          </div>
        </div>
        ${color_name ? `<p><strong>Color:</strong> ${color_name} <span style="display:inline-block;width:16px;height:16px;border-radius:50%;background:${hex};border:1px solid #000;margin-left:5px;vertical-align:middle;"></span></p>` : ''}
        ${size_name ? `<p><strong>Size:</strong> ${size_name}</p>` : ''}
        <a class="remove-btn"
           data-id="${id}"
           data-color-id="${color_id}"
           data-size-id="${size_id}">
           <i class="fas fa-trash-alt"></i></a>
      </div>
    </div>
  `;
}

function updateQuantity({ id, color_id, size_id, quantity }) {
  const jwt = localStorage.getItem("jwt");

  if (quantity < 1) return;

  if (jwt) {
    fetch("php/cart/update_quantity.php", {
      method: "POST",
      headers: {
        "Authorization": "Bearer " + jwt,
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        producto_id: parseInt(id),
        color_id: parseInt(color_id),
        size_id: parseInt(size_id),
        quantity: parseInt(quantity)
      })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          loadCart(true);
        } else {
          console.error("Error en servidor:", data.message);
        }
      });
  } else {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    const index = carrito.findIndex(p =>
      p.id == id && p.color_id == color_id && p.size_id == size_id
    );
    if (index !== -1) {
      carrito[index].quantity = parseInt(quantity);
      localStorage.setItem("carrito", JSON.stringify(carrito));
      loadCart(false);
    }
  }
}
