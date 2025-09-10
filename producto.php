<?php
require_once 'php/conexion.php';
session_start();

$product_id = $_GET['id'] ?? null;
if (!$product_id || !filter_var($product_id, FILTER_VALIDATE_INT)) {
  exit('Producto no válido');
}

// OBTENER TODA LA INFORMACIÓN DEL PRODUCTO
try {
  // Detalles del producto
  $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $producto = $result->fetch_assoc();
  $stmt->close();

  if (!$producto) {
    exit('Producto no encontrado');
  }

  // A FUTURO: Aquí procesarías las imágenes adicionales. Por ahora lo dejamos comentado.
  // $producto['additional_images'] = !empty($producto['images']) ? json_decode($producto['images'], true) : [];

  // Tallas disponibles
  $stmt_sizes = $conn->prepare("SELECT s.id, s.name FROM product_sizes ps JOIN sizes s ON ps.size_id = s.id WHERE ps.product_id = ?");
  $stmt_sizes->bind_param("i", $product_id);
  $stmt_sizes->execute();
  $result_sizes = $stmt_sizes->get_result();
  $sizes = $result_sizes->fetch_all(MYSQLI_ASSOC);
  $stmt_sizes->close();

  // Colores disponibles
  $stmt_colors = $conn->prepare("SELECT c.id, c.name, c.hex FROM product_colors pc JOIN colors c ON pc.color_id = c.id WHERE pc.product_id = ?");
  $stmt_colors->bind_param("i", $product_id);
  $stmt_colors->execute();
  $result_colors = $stmt_colors->get_result();
  $colors = $result_colors->fetch_all(MYSQLI_ASSOC);
  $stmt_colors->close();

  // OBTENER VARIANTES DE STOCK
  $stmt_variants = $conn->prepare("SELECT color_id, size_id, stock FROM product_variants WHERE product_id = ?");
  $stmt_variants->bind_param("i", $product_id);
  $stmt_variants->execute();
  $result_variants = $stmt_variants->get_result();
  $variants_stock = $result_variants->fetch_all(MYSQLI_ASSOC);
  $stmt_variants->close();

  // Registrar visita de usuario
  $user_id = $_SESSION['user_id'] ?? null;
  if ($user_id !== null) {
    $stmt_visit = $conn->prepare("INSERT INTO user_visits (user_id, product_id, visited_at) VALUES (?, ?, NOW())");
    $stmt_visit->bind_param("ii", $user_id, $product_id);
  } else {
    $stmt_visit = $conn->prepare("INSERT INTO user_visits (user_id, product_id, visited_at) VALUES (NULL, ?, NOW())");
    $stmt_visit->bind_param("i", $product_id);
  }
  $stmt_visit->execute();
  $stmt_visit->close();
} catch (Exception $e) {
  error_log("Error al cargar producto: " . $e->getMessage());
  exit('Error al cargar la página del producto.');
}
// --- NUEVO: Preparar variables para SEO ---
$page_title = htmlspecialchars($producto['name']) . ' | Stetson LATAM';
$meta_description = htmlspecialchars(substr(strip_tags($producto['description']), 0, 155)) . '...';
// Construir la URL canónica completa
$canonical_url = "https://www.stetsonlatam.com/producto" . $product_id;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($producto['name']); ?> | Stetson LATAM</title>

  <link rel="icon" href="img/logo.webp" type="image/x-icon">
  <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap"
    rel="stylesheet">
  <link href="css/producto.css?v=<?php echo time(); ?>" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta name="description" content="<?php echo $meta_description; ?>">

  <link rel="canonical" href="<?php echo $canonical_url; ?>" />

  <meta property="og:title" content="<?php echo $page_title; ?>" />
  <meta property="og:description" content="<?php echo $meta_description; ?>" />
  <meta property="og:url" content="<?php echo $canonical_url; ?>" />
  <meta property="og:image" content="https://www.stetsonlatam.com/<?php echo htmlspecialchars($producto['image']); ?>" />
  <meta property="og:type" content="product" />
</head>

<body>
  <div class="page-wrapper">
    <div class="content-container">
      <?php include 'header.php'; ?>

      <main class="product-main">
        <div class="product-container">

          <div class="product-gallery">
            <div class="main-image-container">
              <img id="main-product-image" src="<?php echo htmlspecialchars($producto['image']); ?>"
                alt="<?php echo htmlspecialchars($producto['name']); ?>">
            </div>
          </div>

          <div class="product-details">
            <h1 class="product-title"><?php echo htmlspecialchars($producto['name']); ?></h1>
            <p class="product-price">$<?php echo number_format($producto['price'], 2); ?></p>

            <?php if (!empty($colors)): ?>
              <div class="options-group">
                <h3 class="options-label">Color</h3>
                <div class="options-selector">
                  <?php foreach ($colors as $color): ?>
                    <button class="color-btn" style="background-color: <?php echo $color['hex']; ?>;"
                      title="<?php echo $color['name']; ?>" data-color-id="<?php echo $color['id']; ?>"></button>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if (!empty($sizes)): ?>
              <div class="options-group">
                <h3 class="options-label">Talla</h3>
                <div class="options-selector">
                  <?php foreach ($sizes as $size): ?>
                    <button class="size-btn" data-size-id="<?php echo $size['id']; ?>"><?php echo $size['name']; ?></button>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="options-group">
              <h3 class="options-label">Cantidad</h3>
              <div class="quantity-selector">
                <button type="button" class="qty-btn minus">-</button>
                <input type="text" id="quantity" value="1" readonly>
                <button type="button" class="qty-btn plus">+</button>
              </div>
            </div>

            <button class="add-to-cart-btn">Agregar al carrito</button>
            <button id="wishlist-btn" style="display:none; background:none; border:none; cursor:pointer; font-size: 1.5em; color: #3c3737; margin-left: 15px;">
              <i class="far fa-heart"></i> </button>

            <div class="description-group">
              <div class="description-content">
                <p><?php echo nl2br(htmlspecialchars($producto['description'])); ?></p>
              </div>
            </div>
          </div>
        </div>
      </main>
      <section class="reviews-section">
        <h2>Opiniones de Clientes</h2>

        <div id="ratings-summary">
        </div>

        <div id="reviews-container">
          <p>Cargando reseñas...</p>
        </div>

        <div id="review-form-container" style="display: none;">
          <h3>Deja tu opinión</h3>
          <form id="review-form">
            <div class="star-rating">
              <span>Calificación:</span>
              <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 estrellas"></label>
              <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 estrellas"></label>
              <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 estrellas"></label>
              <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 estrellas"></label>
              <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 estrella"></label>
            </div>
            <textarea name="comment" placeholder="Escribe tu reseña aquí..." required></textarea>
            <button type="submit">Enviar Reseña</button>
          </form>
        </div>
      </section>

      <style>
        .reviews-section {
          max-width: 800px;
          margin: 40px auto;
          padding: 20px;
          border-top: 1px solid #eee;
        }

        .review-item {
          border-bottom: 1px solid #eee;
          padding: 15px 0;
        }

        .review-item:last-child {
          border-bottom: none;
        }

        .admin-reply {
          background-color: #f8f9fa;
          border-left: 3px solid #3c3737;
          padding: 10px;
          margin-top: 10px;
          font-style: italic;
        }

        #review-form {
          margin-top: 20px;
        }

        #review-form textarea {
          width: 100%;
          min-height: 100px;
          margin: 10px 0;
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 5px;
        }

        #review-form button {
          padding: 10px 20px;
          cursor: pointer;
        }

        .star-rating {
          display: flex;
          flex-direction: row-reverse;
          justify-content: flex-end;
        }

        .star-rating input {
          display: none;
        }

        .star-rating label {
          font-size: 2em;
          color: #ddd;
          cursor: pointer;
          transition: color 0.2s;
        }

        .star-rating label:before {
          content: '★';
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
          color: #f2b600;
        }

        /* Estadísticas */
        #ratings-summary {
          margin-bottom: 20px;
        }

        #ratings-summary .bar-container {
          display: flex;
          align-items: center;
          margin-bottom: 5px;
          gap: 10px;
          font-size: 0.9em;
        }

        #ratings-summary .bar-wrapper {
          flex-grow: 1;
          background-color: #e9ecef;
          border-radius: 5px;
        }

        #ratings-summary .bar {
          background-color: #f2b600;
          height: 10px;
          border-radius: 5px;
        }
      </style>

      <?php include 'footer.php'; ?>
    </div>
  </div>

  <?php include 'modal.php'; ?>
  <script src="js/cart.js?v=<?php echo time(); ?>"></script>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
  <script>
    const productVariants = <?php echo json_encode($variants_stock); ?>;
    const productId = <?php echo $producto['id']; ?>;

    // Un solo listener para toda la lógica de la página
    document.addEventListener('DOMContentLoaded', function() {
      // --- Lógica para seleccionar talla, color y cantidad ---
      const jwt = localStorage.getItem('jwt');
      let selectedColorId = null;
      let selectedSizeId = null;
      let availableStock = 0;
      const colorBtns = document.querySelectorAll('.color-btn');
      const sizeBtns = document.querySelectorAll('.size-btn');
      const qtyInput = document.getElementById('quantity');
      const plusBtn = document.querySelector('.qty-btn.plus');
      const minusBtn = document.querySelector('.qty-btn.minus');
      const addToCartBtn = document.querySelector('.add-to-cart-btn');

      function updateStock() {
        if (selectedColorId && selectedSizeId) {
          const variant = productVariants.find(v => v.color_id == selectedColorId && v.size_id == selectedSizeId);
          availableStock = variant ? variant.stock : 0;
          if (parseInt(qtyInput.value) > availableStock) {
            qtyInput.value = availableStock > 0 ? availableStock : 1;
          }
        }
        addToCartBtn.disabled = availableStock <= 0;
        addToCartBtn.textContent = availableStock <= 0 ? "Sin Stock" : "Agregar al carrito";
      }
      colorBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          colorBtns.forEach(b => b.classList.remove('selected'));
          this.classList.add('selected');
          selectedColorId = this.dataset.colorId;
          updateStock();
        });
      });
      sizeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          sizeBtns.forEach(b => b.classList.remove('selected'));
          this.classList.add('selected');
          selectedSizeId = this.dataset.sizeId;
          updateStock();
        });
      });
      plusBtn.addEventListener('click', () => {
        let currentValue = parseInt(qtyInput.value);
        if (availableStock > 0 && currentValue < availableStock) {
          qtyInput.value = currentValue + 1;
        }
      });
      minusBtn.addEventListener('click', () => {
        let value = parseInt(qtyInput.value);
        if (value > 1) qtyInput.value = value - 1;
      });
      addToCartBtn.addEventListener('click', function() {
        if ((colorBtns.length > 0 && !selectedColorId) || (sizeBtns.length > 0 && !selectedSizeId)) {
          Swal.fire({
            icon: 'warning',
            text: 'Seleccione color y talla.'
          });
          return;
        }
        if (parseInt(qtyInput.value) > availableStock) {
          Swal.fire({
            icon: 'error',
            text: `Solo quedan ${availableStock} unidades en stock.`
          });
          return;
        }
        if (availableStock <= 0) {
          Swal.fire({
            icon: 'error',
            text: 'Este producto no tiene stock disponible.'
          });
          return;
        }
        const cartData = {
          id: productId,
          quantity: parseInt(qtyInput.value),
          color: selectedColorId,
          size: selectedSizeId
        };
        addToCart(cartData);
      });

      // --- Lógica para el sistema de reseñas ---
      if (jwt) {
        document.getElementById('review-form-container').style.display = 'block';
      }
      fetchReviews(productId);
      document.getElementById('review-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const rating = this.querySelector('input[name="rating"]:checked');
        const comment = this.querySelector('textarea[name="comment"]').value;
        if (!rating) {
          Swal.fire('Error', 'Por favor, selecciona una calificación de estrellas.', 'error');
          return;
        }
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('rating', rating.value);
        formData.append('comment', comment);

        fetch('/php/reviews/add_review', {
            method: 'POST',
            headers: {
              'Authorization': 'Bearer ' + jwt
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire('¡Éxito!', data.message, 'success');
              fetchReviews(productId);
              this.reset();
            } else {
              Swal.fire('Error', data.message, 'error');
            }
          })
          .catch(err => Swal.fire('Error', 'Ocurrió un problema de conexión.', 'error'));
      });
    });

    async function fetchReviews(productId) {
      try {
        const res = await fetch(`/php/reviews/get_reviews?id=${productId}`);
        const data = await res.json();
        if (data.success) {
          renderReviews(data.reviews);
        }
      } catch (error) {
        console.error("Error al cargar reseñas:", error);
      }
    }

    function renderReviews(reviews) {
      const container = document.getElementById('reviews-container');
      const summaryContainer = document.getElementById('ratings-summary');
      container.innerHTML = '';
      summaryContainer.innerHTML = '';
      if (reviews.length === 0) {
        container.innerHTML = '<p>Este producto aún no tiene reseñas. ¡Sé el primero!</p>';
        return;
      }
      const ratingCounts = {
        5: 0,
        4: 0,
        3: 0,
        2: 0,
        1: 0
      };
      reviews.forEach(review => ratingCounts[review.rating]++);
      for (let i = 5; i >= 1; i--) {
        const percentage = (reviews.length > 0) ? (ratingCounts[i] / reviews.length) * 100 : 0;
        summaryContainer.innerHTML += `
                    <div class="bar-container">
                        <span>${i} ★</span>
                        <div class="bar-wrapper"><div class="bar" style="width: ${percentage}%;"></div></div>
                        <span>(${ratingCounts[i]})</span>
                    </div>`;
      }
      reviews.forEach(review => {
        const reviewElement = document.createElement('div');
        reviewElement.className = 'review-item';
        reviewElement.innerHTML = `
                    <h4>${review.user_name}</h4>
                    <p style="color: #f2b600;">${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}</p>
                    <p>${review.comment}</p>
                    <small>${new Date(review.created_at).toLocaleDateString()}</small>
                    ${review.reply_text ? `<div class="admin-reply"><strong>Respuesta de la tienda:</strong><p>${review.reply_text}</p></div>` : ''}
                `;
        container.appendChild(reviewElement);
      });
    }

    const wishlistBtn = document.getElementById('wishlist-btn');
    const heartIcon = wishlistBtn.querySelector('i');

    // Función para actualizar el ícono del corazón
    function updateWishlistIcon(inWishlist) {
      if (inWishlist) {
        heartIcon.classList.remove('far'); // Quita clase de corazón vacío
        heartIcon.classList.add('fas'); // Añade clase de corazón lleno
        heartIcon.style.color = '#d9534f'; // Color rojo
      } else {
        heartIcon.classList.remove('fas');
        heartIcon.classList.add('far');
        heartIcon.style.color = '#3c3737'; // Color original
      }
    }

    // Comprobar el estado inicial si el usuario está logueado
    if (jwt) {
      wishlistBtn.style.display = 'inline-block';
      fetch(`/php/user/get_wishlist_status?product_id=${productId}`, {
          headers: {
            'Authorization': 'Bearer ' + jwt
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            updateWishlistIcon(data.inWishlist);
          }
        });
    } else {
      wishlistBtn.style.display = 'none';
    } 

    // Manejar el clic en el botón de wishlist
    wishlistBtn.addEventListener('click', () => {
      fetch('/php/user/toggle_wishlist', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + jwt
          },
          body: JSON.stringify({
            product_id: productId
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (data.status === 'added') {
              updateWishlistIcon(true);
              Swal.fire('¡Añadido!', 'Producto añadido a tu wishlist.', 'success');
            } else {
              updateWishlistIcon(false);
              Swal.fire('Eliminado', 'Producto eliminado de tu wishlist.', 'info');
            }
          }
        });
    });
  </script>
</body>

</html>

<?php
// --- AÑADIDO: Cerramos la conexión aquí, al final de todo ---

$conn->close();

?>