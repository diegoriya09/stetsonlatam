<?php
// admin/edit_stock.php (CÓDIGO COMPLETO Y ADAPTADO A TUS TABLAS DE RELACIÓN)

session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
   header('Location: /');
   exit;
}

require '../php/conexion.php';

// --- LÓGICA POST: Guardar los cambios de stock (SIN CAMBIOS) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
   $stocks = $_POST['stock'] ?? [];

   if ($product_id > 0 && !empty($stocks)) {
      $stmt = $conn->prepare("
            INSERT INTO product_variants (product_id, color_id, size_id, stock) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE stock = VALUES(stock)
        ");

      foreach ($stocks as $color_id => $sizes) {
         foreach ($sizes as $size_id => $stock) {
            $stock_value = (int)$stock >= 0 ? (int)$stock : 0;
            $stmt->bind_param("iiii", $product_id, $color_id, $size_id, $stock_value);
            $stmt->execute();
         }
      }
      $stmt->close();

      // --- NUEVO: Lógica de Notificación de Stock Bajo ---
      define('LOW_STOCK_THRESHOLD', 10); // Define el umbral de stock bajo
      $admin_user_id = 1; // ID del administrador que recibirá las notificaciones

      $product_name_stmt = $conn->prepare("SELECT name FROM productos WHERE id = ?");
      $product_name_stmt->bind_param("i", $product_id);
      $product_name_stmt->execute();
      $product_name = $product_name_stmt->get_result()->fetch_assoc()['name'];
      $product_name_stmt->close();

      $stmt_notify = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
      $notification_link = "/admin/edit_stock" . $product_id;

      foreach ($stocks as $color_id => $sizes) {
         foreach ($sizes as $size_id => $stock) {
            $stock_value = (int)$stock;
            if ($stock_value > 0 && $stock_value < LOW_STOCK_THRESHOLD) {
               $message = "Stock bajo para {$product_name}: Solo quedan {$stock_value} unidades.";
               $stmt_notify->bind_param("iss", $admin_user_id, $message, $notification_link);
               $stmt_notify->execute();
            }
         }
      }
      $stmt_notify->close();
      // --- FIN DE LA LÓGICA DE NOTIFICACIÓN ---

      $conn->commit();

      header("Location: admin?msg=Stock+actualizado+correctamente");
      exit;
   }
}


// --- LÓGICA GET: Mostrar el formulario para editar ---
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
   die("ID de producto inválido.");
}

// Obtener nombre del producto
$product_stmt = $conn->prepare("SELECT name FROM productos WHERE id = ?");
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();
if ($product_result->num_rows === 0) {
   die("Producto no encontrado.");
}
$product = $product_result->fetch_assoc();
$product_name = $product['name'];
$product_stmt->close();

// --- MODIFICADO: Obtener solo los colores ASIGNADOS a este producto ---
$colors = [];
$colors_stmt = $conn->prepare("
    SELECT c.id, c.name 
    FROM colors c
    JOIN product_colors pc ON c.id = pc.color_id
    WHERE pc.product_id = ?
    ORDER BY c.name ASC
");
$colors_stmt->bind_param("i", $product_id);
$colors_stmt->execute();
$colors_result = $colors_stmt->get_result();
while ($row = $colors_result->fetch_assoc()) {
   $colors[] = $row;
}
$colors_stmt->close();

// --- MODIFICADO: Obtener solo las tallas ASIGNADAS a este producto ---
$sizes = [];
$sizes_stmt = $conn->prepare("
    SELECT s.id, s.name 
    FROM sizes s
    JOIN product_sizes ps ON s.id = ps.size_id
    WHERE ps.product_id = ?
    ORDER BY s.id ASC
");
$sizes_stmt->bind_param("i", $product_id);
$sizes_stmt->execute();
$sizes_result = $sizes_stmt->get_result();
while ($row = $sizes_result->fetch_assoc()) {
   $sizes[] = $row;
}
$sizes_stmt->close();


// --- Lógica para obtener el stock existente (SIN CAMBIOS) ---
$stock_map = [];
$stock_stmt = $conn->prepare("SELECT color_id, size_id, stock FROM product_variants WHERE product_id = ?");
$stock_stmt->bind_param("i", $product_id);
$stock_stmt->execute();
$stock_result = $stock_stmt->get_result();
while ($row = $stock_result->fetch_assoc()) {
   $stock_map[$row['color_id']][$row['size_id']] = $row['stock'];
}
$stock_stmt->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <title>Gestionar Stock de <?php echo htmlspecialchars($product_name); ?></title>
   <style>
      /* Tus estilos (sin cambios) */
      body {
         font-family: 'Segoe UI', Arial, sans-serif;
         background: #f1eeea;
         margin: 0;
         padding: 20px;
      }

      .stock-container {
         background: #fff;
         max-width: 1200px;
         margin: 20px auto;
         padding: 20px 30px;
         border-radius: 10px;
         box-shadow: 0 2px 12px rgba(60, 55, 55, 0.10);
      }

      h2 {
         text-align: center;
         color: #3c3737;
         margin-bottom: 24px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }

      th,
      td {
         border: 1px solid #ccc;
         padding: 8px;
         text-align: center;
      }

      th {
         background-color: #f2f2f2;
      }

      .stock-input {
         width: 60px;
         padding: 5px;
         text-align: center;
         border: 1px solid #ccc;
         border-radius: 4px;
         font-size: 1em;
      }

      .action-buttons {
         text-align: center;
         margin-top: 25px;
      }

      button {
         background: #3c3737;
         color: #fff;
         border: none;
         padding: 12px 25px;
         border-radius: 5px;
         font-weight: bold;
         font-size: 1em;
         cursor: pointer;
         transition: background 0.2s;
      }

      button:hover {
         background: #5a2323;
      }

      a.back-link {
         display: inline-block;
         margin-right: 15px;
         text-decoration: none;
         background: #6c757d;
         color: #fff;
         padding: 12px 25px;
         border-radius: 5px;
         font-weight: bold;
         font-size: 1em;
      }

      a.back-link:hover {
         background: #5a6268;
      }

      .no-variants {
         text-align: center;
         font-size: 1.1em;
         color: #777;
         margin: 30px;
      }
   </style>
</head>

<body>
   <div class="stock-container">
      <h2>Gestionar Stock para: <strong><?php echo htmlspecialchars($product_name); ?></strong></h2>

      <?php if (!empty($colors) && !empty($sizes)): ?>
         <form action="edit_stock" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

            <table>
               <thead>
                  <tr>
                     <th>Talla / Color</th>
                     <?php foreach ($colors as $color): ?>
                        <th><?php echo htmlspecialchars($color['name']); ?></th>
                     <?php endforeach; ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($sizes as $size): ?>
                     <tr>
                        <td><strong><?php echo htmlspecialchars($size['name']); ?></strong></td>
                        <?php foreach ($colors as $color): ?>
                           <td>
                              <input type="number" class="stock-input"
                                 name="stock[<?php echo $color['id']; ?>][<?php echo $size['id']; ?>]"
                                 value="<?php echo $stock_map[$color['id']][$size['id']] ?? 0; ?>"
                                 min="0">
                           </td>
                        <?php endforeach; ?>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>

            <div class="action-buttons">
               <a href="admin" class="back-link">Volver al Panel</a>
               <button type="submit">Guardar Cambios de Stock</button>
            </div>
         </form>
      <?php else: ?>
         <p class="no-variants">Este producto no tiene colores o tallas asignadas. Por favor, asígnalas antes de gestionar el stock.</p>
         <div class="action-buttons">
            <a href="admin" class="back-link">Volver al Panel</a>
         </div>
      <?php endif; ?>
   </div>
</body>

</html>