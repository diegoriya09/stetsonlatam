//Variable para mantener el estado actual del carrito
let currentCartItems = [];

document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");

  if (document.getElementById('cart-items-container')) {
    if (jwt) {
      loadCart();
    } else {
      renderCart([]);
    }
  }

  //Listener para el botón de proceder al pago
  const checkoutBtn = document.getElementById('checkout-btn');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', (e) => {
      e.preventDefault(); // Prevenimos la navegación por defecto del enlace

      // Validación: ¿Hay items en el carrito?
      if (currentCartItems.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Carrito Vacío',
          text: 'Debes añadir al menos un artículo para proceder al pago.'
        });
      } else {
        // Si la validación pasa, redirigimos al checkout
        window.location.href = checkoutBtn.href;
      }
    });
  }
});

function addToCart(productData) {
  const jwt = localStorage.getItem("jwt");
  if (!jwt) {
    // Podrías redirigir al login o mostrar un mensaje
    Swal.fire('Error', 'Debes iniciar sesión para añadir artículos al carrito.', 'error');
    return;
  }

  // RUTA CORREGIDA
  fetch('/php/cart/add_to_cart', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + jwt,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      producto_id: productData.id,
      quantity: productData.quantity,
      color_id: productData.color,
      size_id: productData.size
    })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire({ icon: 'success', title: '¡Añadido al carrito!', showConfirmButton: false, timer: 1500 });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: data.message });
      }
    });
}

async function loadCart() {
  const jwt = localStorage.getItem("jwt");
  const container = document.getElementById('cart-items-container');
  if (!container) return;

  try {
    // RUTA CORREGIDA
    const res = await fetch('/php/cart/get_cart', { method: 'GET', headers: { 'Authorization': 'Bearer ' + jwt } });
    const data = await res.json();
    if (data.success) {
      renderCart(data.cart);
    } else {
      container.innerHTML = `<p class="empty-cart">${data.message || 'No se pudo cargar el carrito.'}</p>`;
    }
  } catch (error) {
    console.error('Error al cargar el carrito:', error);
    container.innerHTML = `<p class="empty-cart">Error al cargar el carrito.</p>`;
  }
}

// Dibuja los artículos en el HTML
function renderCart(items) {
  const container = document.getElementById('cart-items-container');
  const summarySubtotal = document.getElementById('summary-subtotal');
  const summaryTotal = document.getElementById('summary-total');

  // NUEVO: Actualizamos nuestra variable global con los items
  currentCartItems = items || [];

  container.innerHTML = '';
  let subtotal = 0;

  if (currentCartItems.length === 0) {
    container.innerHTML = '<p class="empty-cart">Tu carrito está vacío.</p>';
  } else {
    currentCartItems.forEach(item => {
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
                <div class="item-quantity" data-stock="${item.stock}">
                    <button class="qty-btn" data-id="${item.cart_item_id}">-</button>
                    <input type="text" value="${item.quantity}" readonly>
                    <button class="qty-btn" data-id="${item.cart_item_id}">+</button>
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
  if (!jwt) return { success: false, message: 'Not logged in' };

  try {
    const res = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + jwt },
      body: JSON.stringify(body)
    });
    const contentType = res.headers.get("content-type");
    if (contentType && contentType.indexOf("application/json") !== -1) {
      return await res.json();
    } else {
      const textResponse = await res.text();
      throw new Error(`Server returned non-JSON response: ${textResponse}`);
    }
  } catch (error) {
    console.error(`Error al publicar en ${endpoint}:`, error);
  }
}

document.getElementById('cart-items-container')?.addEventListener('click', async e => {
  const jwt = localStorage.getItem("jwt");
  if (!jwt) return; // No hacer nada si no hay sesión

  // --- LÓGICA PARA BOTONES DE CANTIDAD (+ y -) ---
  if (e.target.matches('.qty-btn')) {
    const cart_item_id = e.target.dataset.id;
    const quantityContainer = e.target.parentElement;
    const input = quantityContainer.querySelector('input');
    const stock = parseInt(quantityContainer.dataset.stock); // Leemos el stock del data-attribute
    const currentQty = parseInt(input.value);
    let newQty;

    if (e.target.textContent === '+') {
      // ¡VERIFICACIÓN DE STOCK!
      if (currentQty >= stock) {
        Swal.fire({ icon: 'warning', title: 'Stock máximo alcanzado', text: `Solo hay ${stock} unidades disponibles.` });
        return; // Detenemos la ejecución si se excede el stock
      }
      newQty = currentQty + 1;
    } else {
      newQty = currentQty - 1;
    }

    if (newQty < 1) {
      // Lógica para eliminar el producto si la cantidad es 0
      e.target.closest('.item-remove').click(); // Simula un click en el botón de eliminar
      return;
    }

    await postToCartAPI('/php/cart/update_cart', { cart_item_id: cart_item_id, cantidad: newQty });
    loadCart(); // Recarga el carrito para mostrar el cambio
  }

  // --- LÓGICA PARA BOTÓN DE ELIMINAR ---
  if (e.target.closest('.item-remove')) {
    const cart_item_id = e.target.closest('.item-remove').dataset.id;

    // Opcional: Alerta de confirmación
    Swal.fire({
      title: '¿Eliminar Artículo?',
      text: "¿Estás seguro de que deseas eliminar este artículo de tu carrito?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3f1e1f',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminarlo!'
    }).then(async (result) => {
      if (result.isConfirmed) {
        await postToCartAPI('/php/cart/remove_from_cart', { cart_item_id: cart_item_id });
        loadCart(); // Recarga el carrito
      }
    });
  }
});