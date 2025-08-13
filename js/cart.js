// ✅ cart.js (final funcional)
document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");

  // Verifica el token con el servidor
  fetch("php/check_session.php", {
    method: "GET",
    headers: {
      Authorization: "Bearer " + jwt,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.logged_in) {
        // ✅ Llama al backend para obtener las órdenes del usuario
        fetch("php/order/get_cart.php", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + jwt,
          },
        })
          .then((res) => res.json())
          .then((cartData) => {
            if (cartData.success && cartData.cart.length > 0) {
              const total = cartData.cart.reduce((acc, item) => acc + item.price * item.quantity, 0);
              renderCart(cartData.cart, total);
            } else {
              cartSection.innerHTML =
                "<p>No items found in the cart.</p>";
            }
          })
          .catch((error) => {
            console.error("Error getting cart:", error);
            cartSection.innerHTML =
              "<p>Error getting your cart. Please try again later.</p>";
          });
      } else {
        cartSection.innerHTML = `<p>${data.message}</p>`;
      }
    })
    .catch((err) => {
      console.error("Error verifying session:", err);
      cartSection.innerHTML =
        "<p>Could not verify your session. Please try again later.</p>";
    });
});


function setupAddToCartButtons() {
  document.querySelectorAll('.add-to-cart-btn.flex.min-w-[84px].max-w-[480px].cursor-pointer.items-center.justify-center.overflow-hidden.rounded-lg.h-10.px-4.bg-[#e68019].text-[#181411].text-sm.font-bold.leading-normal.tracking-[0.015em]').forEach(button => {
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
  if (!color_id && !size_id) {
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

  if (!color_id) {
    if (window.Swal) {
      Swal.fire({
        icon: 'warning',
        title: 'Choose a color',
        text: 'You must select a color before adding to the cart.'
      });
    } else {
      alert('You must choose a color before adding to the cart.');
    }
    return;
  }

  if (!size_id) {
    if (window.Swal) {
      Swal.fire({
        icon: 'warning',
        title: 'Choose a size',
        text: 'You must select a size before adding to the cart.'
      });
    } else {
      alert('You must choose a size before adding to the cart.');
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

// Función para mostrar el carrito
function renderCart(carts, total) {
  const tableBody = document.querySelector("tbody");
  tableBody.innerHTML = ""; // Vaciar la tabla

  carts.forEach((cart) => {
    const row = document.createElement("tr");
    row.classList.add("border-t", "border-t-[#e5e0dc]");

    row.innerHTML = `
      <td class="h-[72px] px-4 py-2 w-[400px] text-[#181411] text-sm font-normal leading-normal">
        ${cart.name}
      </td>
      <td class="h-[72px] px-4 py-2 w-[400px] text-[#887563] text-sm font-normal leading-normal">
        ${cart.price}
      </td>
      <td class="h-[72px] px-4 py-2 w-[400px] text-[#887563] text-sm font-normal leading-normal">
        ${cart.quantity}
      </td>
      <td class="h-[72px] px-4 py-2 w-[400px] text-[#887563] text-sm font-normal leading-normal">
        ${cart.size_name}
      </td>
      <td class="h-[72px] px-4 py-2 w-[400px] text-[#887563] text-sm font-normal leading-normal">
        ${cart.color_name}
      </td>
      <td class="h-[72px] px-4 py-2 w-[400px] text-[#887563] text-sm font-normal leading-normal">
        ${cart.total}
      </td>
    `;

    tableBody.appendChild(row);
  });
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

