// js/cart.js (Versión final para usuarios logueados)
document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");

  // Si estamos en la página del carrito, cargamos los datos.
  if (document.getElementById('cart-items-container')) {
    if (jwt) {
      loadCart();
    } else {
      // Si no hay JWT, muestra el carrito como vacío.
      renderCart([]);
    }
  }
});

// Función global para que producto.php pueda llamarla
function addToCart(productData) {
  const jwt = localStorage.getItem("jwt");
  if (!jwt) {
    // Podrías redirigir al login o mostrar un mensaje
    Swal.fire('Error', 'You must be logged in to add items to the cart.', 'error');
    return;
  }

  fetch('php/cart/add_to_cart.php', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + jwt,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      producto_id: productData.id,
      cantidad: productData.quantity,
      color_id: productData.color,
      size_id: productData.size
    })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire({ icon: 'success', title: 'Added to cart!', showConfirmButton: false, timer: 1500 });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: data.message });
      }
    });
}

// Carga los datos del carrito desde el backend
async function loadCart() {
  const jwt = localStorage.getItem("jwt");
  const container = document.getElementById('cart-items-container');
  if (!container) return; // Salir si no estamos en la página del carrito

  try {
    const res = await fetch('php/cart/get_cart.php', { headers: { 'Authorization': 'Bearer ' + jwt } });
    const data = await res.json();
    if (data.success) {
      renderCart(data.cart);
    } else {
      container.innerHTML = `<p class="empty-cart">${data.message || 'Could not load cart.'}</p>`;
    }
  } catch (error) {
    console.error('Error loading cart:', error);
    container.innerHTML = `<p class="empty-cart">Error loading cart.</p>`;
  }
}

// Dibuja los artículos en el HTML
function renderCart(items) {
  const container = document.getElementById('cart-items-container');
  const summarySubtotal = document.getElementById('summary-subtotal');
  const summaryTotal = document.getElementById('summary-total');

  container.innerHTML = '';
  let subtotal = 0;

  if (!items || items.length === 0) {
    container.innerHTML = '<p class="empty-cart">Your cart is empty.</p>';
  } else {
    items.forEach(item => {
      const itemTotal = item.price * item.quantity;
      subtotal += itemTotal;

      const itemElement = document.createElement('div');
      itemElement.className = 'cart-item';
      itemElement.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="item-image">
                <div class="item-details">
                    <h3>${item.name}</h3>
                    ${item.size_name ? `<p>Size: ${item.size_name}</p>` : ''}
                    ${item.color_name ? `<p>Color: ${item.color_name}</p>` : ''}
                </div>
                <div class="item-quantity">
                    <button class="qty-btn" data-id="${item.cart_item_id}" data-qty="${item.quantity - 1}">-</button>
                    <input type="text" value="${item.quantity}" readonly>
                    <button class="qty-btn" data-id="${item.cart_item_id}" data-qty="${item.quantity + 1}">+</button>
                </div>
                <div class="item-total">$${itemTotal.toFixed(2)}</div>
                <button class="item-remove" data-id="${item.cart_item_id}"><i class="fas fa-trash-alt"></i></button>
            `;
      container.appendChild(itemElement);
    });
  }

  summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
  summaryTotal.textContent = `$${subtotal.toFixed(2)}`;
}

// Envía datos (POST) a la API del carrito
async function postToCartAPI(endpoint, body) {
  const jwt = localStorage.getItem("jwt");
  if (!jwt) return;

  try {
    const res = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + jwt },
      body: JSON.stringify(body)
    });
    return await res.json();
  } catch (error) {
    console.error(`Error posting to ${endpoint}:`, error);
  }
}

// Maneja los clics para actualizar o eliminar
document.getElementById('cart-items-container')?.addEventListener('click', async e => {
  const target = e.target.closest('.qty-btn, .item-remove');
  if (!target) return;

  const cart_item_id = target.dataset.id;

  if (target.matches('.qty-btn')) {
    const cantidad = parseInt(target.dataset.qty);
    if (cantidad > 0) {
      await postToCartAPI('php/cart/update_cart.php', { cart_item_id, cantidad });
    } else {
      await postToCartAPI('php/cart/remove_from_cart.php', { cart_item_id });
    }
  } else if (target.matches('.item-remove')) {
    await postToCartAPI('php/cart/remove_from_cart.php', { cart_item_id });
  }

  loadCart(); // Recarga el carrito después de cualquier acción
});