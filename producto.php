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
$conn->close();
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
                <h3 class="options-label">Size</h3>
                <div class="options-selector">
                  <?php foreach ($sizes as $size): ?>
                    <button class="size-btn" data-size-id="<?php echo $size['id']; ?>"><?php echo $size['name']; ?></button>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="options-group">
              <h3 class="options-label">Quantity</h3>
              <div class="quantity-selector">
                <button type="button" class="qty-btn minus">-</button>
                <input type="text" id="cantidad" value="1" readonly>
                <button type="button" class="qty-btn plus">+</button>
              </div>
            </div>

            <button class="add-to-cart-btn">Add to Cart</button>

            <div class="description-group">
              <div class="description-content">
                <p><?php echo nl2br(htmlspecialchars($producto['description'])); ?></p>
              </div>
            </div>
          </div>
        </div>
      </main>

      <?php include 'footer.php'; ?>
    </div>
  </div>

  <?php include 'modal.php'; ?>
  <script src="js/cart.js?v=<?php echo time(); ?>"></script>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
  <script>
    // Tu JavaScript se mantiene igual, ya que se basa en las clases de los botones que hemos conservado.
    document.addEventListener('DOMContentLoaded', function () {
      let selectedColorId = null;
      let selectedSizeId = null;
      const colorBtns = document.querySelectorAll('.color-btn');
      const sizeBtns = document.querySelectorAll('.size-btn');

      colorBtns.forEach(btn => {
        btn.addEventListener('click', function () {
          colorBtns.forEach(b => b.classList.remove('selected'));
          this.classList.add('selected');
          selectedColorId = this.dataset.colorId;
        });
      });

      sizeBtns.forEach(btn => {
        btn.addEventListener('click', function () {
          sizeBtns.forEach(b => b.classList.remove('selected'));
          this.classList.add('selected');
          selectedSizeId = this.dataset.sizeId;
        });
      });

      const qtyInput = document.getElementById('cantidad');
      document.querySelector('.qty-btn.plus').addEventListener('click', () => {
        qtyInput.value = parseInt(qtyInput.value) + 1;
      });
      document.querySelector('.qty-btn.minus').addEventListener('click', () => {
        let value = parseInt(qtyInput.value);
        if (value > 1) qtyInput.value = value - 1;
      });

      const addToCartBtn = document.querySelector('.add-to-cart-btn');
      if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function () {
          if (colorBtns.length > 0 && !selectedColorId) {
            Swal.fire({ icon: 'warning', text: 'Please select a color.' });
            return;
          }
          if (sizeBtns.length > 0 && !selectedSizeId) {
            Swal.fire({ icon: 'warning', text: 'Please select a size.' });
            return;
          }

          const cartData = {
            id: <?php echo $producto['id']; ?>,
            name: '<?php echo addslashes($producto['name']); ?>',
            price: <?php echo $producto['price']; ?>,
            image: '<?php echo htmlspecialchars($producto['image']); ?>',
            color: selectedColorId,
            size: selectedSizeId,
            quantity: parseInt(qtyInput.value)
          };

          addToCart(cartData);
        });
      }
    });
  </script>
</body>

</html>