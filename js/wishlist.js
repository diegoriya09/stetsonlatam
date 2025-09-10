// js/wishlist.js

document.addEventListener('DOMContentLoaded', () => {
   loadWishlist();

   // Usamos delegación de eventos para manejar los clics en los botones de eliminar
   const container = document.getElementById('wishlist-container');
   container.addEventListener('click', (e) => {
      if (e.target.classList.contains('remove-wishlist-btn') || e.target.closest('.remove-wishlist-btn')) {
         const button = e.target.closest('.remove-wishlist-btn');
         const productId = button.dataset.productId;
         removeFromWishlist(productId);
      }
   });
});

async function loadWishlist() {
   const container = document.getElementById('wishlist-container');
   const jwt = localStorage.getItem('jwt');

   if (!jwt) {
      container.innerHTML = '<p class="wishlist-empty">Debes <a href="/login" class="login-link">iniciar sesión</a> para ver tu lista de deseos.</p>';
      return;
   }

   try {
      const res = await fetch('/php/user/get_wishlist', {
         method: 'GET',
         headers: { 'Authorization': 'Bearer ' + jwt }
      });

      const data = await res.json();
      if (data.success) {
         renderWishlist(data.wishlist);
      } else {
         container.innerHTML = `<p class="wishlist-empty">${data.message}</p>`;
      }
   } catch (error) {
      console.error('Error al cargar la wishlist:', error);
      container.innerHTML = '<p class="wishlist-empty">Ocurrió un error al cargar tu lista de deseos.</p>';
   }
}

function renderWishlist(items) {
   const container = document.getElementById('wishlist-container');
   container.innerHTML = '';

   if (!items || items.length === 0) {
      container.innerHTML = '<p class="wishlist-empty">Tu lista de deseos está vacía.</p>';
      return;
   }

   items.forEach(item => {
      const productCard = document.createElement('div');
      productCard.className = 'wishlist-item';
      productCard.innerHTML = `
            <div class="item-image-container">
                <a href="/producto/${item.id}"><img src="/${item.image}" alt="${item.name}"></a>
            </div>
            <div class="item-details">
                <h3>${item.name}</h3>
                <p class="price">$${parseFloat(item.price).toFixed(2)}</p>
                <div class="item-actions">
                    <a href="/producto/${item.id}" class="view-product-btn">Ver Producto</a>
                    <button class="remove-wishlist-btn" data-product-id="${item.id}">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </div>
            </div>
        `;
      container.appendChild(productCard);
   });
}

function removeFromWishlist(productId) {
   const jwt = localStorage.getItem('jwt');
   if (!jwt) return;

   // Reutilizamos el endpoint toggle_wishlist, que ya sabe cómo eliminar
   fetch('/php/user/toggle_wishlist', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/json',
         'Authorization': 'Bearer ' + jwt
      },
      body: JSON.stringify({ product_id: productId })
   })
      .then(res => res.json())
      .then(data => {
         if (data.success && data.status === 'removed') {
            Swal.fire({
               icon: 'info',
               title: 'Eliminado',
               text: 'El producto fue eliminado de tu lista de deseos.',
               timer: 1500,
               showConfirmButton: false
            });
            // Recargar la lista para mostrar el cambio
            loadWishlist();
         } else {
            Swal.fire('Error', 'No se pudo eliminar el producto.', 'error');
         }
      });
}