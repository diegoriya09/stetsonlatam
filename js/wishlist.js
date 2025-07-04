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
        loadWishlist(true);  // Usuario logueado, carga desde base de datos
      } else {
        localStorage.removeItem("jwt"); // Sesión inválida, limpiar
        loadWishlist(false); // Cargar local
      }
    })
    .catch(() => loadWishlist(false));

  setupWishlistButtons();
});

function setupWishlistButtons() {
  document.querySelectorAll('.wishlist-btn').forEach(button => {
    button.addEventListener('click', handleWishlistToggle);
  });
}

function handleWishlistToggle(e) {
  const button = e.currentTarget;
  const jwt = localStorage.getItem("jwt");
  const productId = button.dataset.id;

  if (jwt) {
    // Usuario logueado: agregar o quitar en base de datos
    if (button.classList.contains('active')) {
      fetch('php/wishlist/remove.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ producto_id: productId })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            button.classList.remove('active');
            loadWishlist(true);
          }
        });
    } else {
      fetch('php/wishlist/add.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ producto_id: productId })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            button.classList.add('active');
            loadWishlist(true);
          }
        });
    }
  } else {
    // No logueado: usar localStorage
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    if (wishlist.includes(productId)) {
      wishlist = wishlist.filter(id => id !== productId);
      button.classList.remove('active');
    } else {
      wishlist.push(productId);
      button.classList.add('active');
    }
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    loadWishlist(false);
  }
}

function loadWishlist(isLoggedIn) {
  const wishlistItems = document.getElementById('wishlist-items');
  const emptyMsg = document.getElementById('wishlist-empty');
  if (!wishlistItems) return;
  wishlistItems.innerHTML = '';

  const jwt = localStorage.getItem("jwt");

  if (isLoggedIn && jwt) {
    fetch('php/wishlist/get.php', {
      headers: {
        'Authorization': 'Bearer ' + jwt
      }
    })
      .then(response => response.json())
      .then(productos => {
        if (!productos || productos.length === 0) {
          emptyMsg.style.display = '';
        } else {
          emptyMsg.style.display = 'none';
          productos.forEach(producto => {
            wishlistItems.innerHTML += renderWishlistItem(producto);
          });
        }
        updateWishlistButtons(productos.map(p => String(p.id)));
      });
  } else {
    // No logueado: pide productos por IDs en localStorage
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    if (wishlist.length === 0) {
      emptyMsg.style.display = '';
      return;
    }
    emptyMsg.style.display = 'none';
    fetch(`php/wishlist/get_products_by_ids.php?ids=${wishlist.join(',')}`)
      .then(res => res.json())
      .then(data => {
        const productos = data.productos || [];
        productos.forEach(producto => {
          wishlistItems.innerHTML += renderWishlistItem(producto);
        });
        updateWishlistButtons(wishlist);
      });
  }
}

document.addEventListener('click', function (e) {
  if (e.target.closest('.remove-btn')) {
    const button = e.target.closest('.remove-btn');
    const productId = button.dataset.id;
    const jwt = localStorage.getItem("jwt");

    if (jwt) {
      fetch('php/wishlist/remove.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ producto_id: productId })
      }).then(() => loadWishlist(true));
    } else {
      let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
      wishlist = wishlist.filter(id => id !== productId);
      localStorage.setItem('wishlist', JSON.stringify(wishlist));
      loadWishlist(false);
    }
  }
});

// Marca los botones activos según la wishlist actual
function updateWishlistButtons(wishlistIds) {
  document.querySelectorAll('.wishlist-btn').forEach(button => {
    if (wishlistIds.includes(button.dataset.id)) {
      button.classList.add('active');
    } else {
      button.classList.remove('active');
    }
  });
}

function renderWishlistItem(producto) {
  return `
    <article class="card-item">
      <a href="producto.php?id=${producto.id}" class="card-link">
        <img src="${producto.image}" alt="${producto.name}" loading="lazy">
        <h3>${producto.name}</h3>
        <p>$${Number(producto.price).toLocaleString()}</p>
      </a>
      <button class="remove-btn" data-id="${producto.id}" title="Remove from wishlist">
        <i class="fas fa-trash-alt"></i>
      </button>
    </article>
  `;
}