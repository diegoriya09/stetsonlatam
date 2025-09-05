<?php
// admin/index.php (CÓDIGO COMPLETO Y FINAL)

session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /');
    exit;
}

require '../php/conexion.php';

// ADAPTADO: Usamos 'section' para evitar problemas con ModSecurity
$view = $_GET['section'] ?? 'products';

// Lógica de Stock Manager 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_stock') {
    // ADAPTADO: Usamos 'pid' para evitar problemas con ModSecurity
    $product_id = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
    $stocks = $_POST['stock'] ?? [];

    if ($product_id > 0 && !empty($stocks)) {
        $stmt = $conn->prepare("INSERT INTO product_variants (product_id, color_id, size_id, stock) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE stock = VALUES(stock)");
        foreach ($stocks as $color_id => $sizes) {
            foreach ($sizes as $size_id => $stock) {
                $stock_value = (int)$stock;
                $stmt->bind_param("iiii", $product_id, $color_id, $size_id, $stock_value);
                $stmt->execute();
            }
        }
        $stmt->close();
        $success_message = "¡Stock actualizado correctamente para el producto ID: $product_id!";
    }
}

// Lógica para la vista de STOCK
if ($view === 'stock') {
    $products_for_stock_result = $conn->query("SELECT id, name FROM productos ORDER BY name ASC");
    // ADAPTADO: Usamos 'pid'
    $selected_product_id = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
    $selected_product_stock = null;
    $colors = [];
    $sizes = [];
    $stock_map = [];

    if ($selected_product_id > 0) {
        $product_stmt = $conn->prepare("SELECT id, name FROM productos WHERE id = ?");
        $product_stmt->bind_param("i", $selected_product_id);
        $product_stmt->execute();
        $selected_product_stock = $product_stmt->get_result()->fetch_assoc();
        $product_stmt->close();

        $colors_result = $conn->query("SELECT id, name FROM colors ORDER BY name ASC");
        while ($row = $colors_result->fetch_assoc()) {
            $colors[] = $row;
        }

        $sizes_result = $conn->query("SELECT id, name FROM sizes ORDER BY id ASC");
        while ($row = $sizes_result->fetch_assoc()) {
            $sizes[] = $row;
        }

        $stock_stmt = $conn->prepare("SELECT color_id, size_id, stock FROM product_variants WHERE product_id = ?");
        $stock_stmt->bind_param("i", $selected_product_id);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();
        while ($row = $stock_result->fetch_assoc()) {
            $stock_map[$row['color_id']][$row['size_id']] = $row['stock'];
        }
        $stock_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f1eeea;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 95%;
            margin: auto;
        }

        h1,
        h2 {
            text-align: center;
            color: #3c3737;
            margin-top: 30px;
        }

        form {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(60, 55, 55, 0.08);
            max-width: 500px;
            margin: 30px auto 10px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form input[type="file"] {
            flex: 1 1 180px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background: #3c3737;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        form button:hover {
            background: #5a2323;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(60, 55, 55, 0.08);
        }

        th,
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background: #3c3737;
            color: #fff;
            font-weight: 600;
        }

        td.description-cell {
            max-width: 320px;
            white-space: normal;
            overflow-wrap: break-word;
            text-align: left;
        }

        .admin-nav {
            text-align: center;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .admin-nav a {
            text-decoration: none;
            color: #3c3737;
            font-size: 1.2em;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .admin-nav a:hover {
            background-color: #f1eeea;
        }

        .admin-nav a.active {
            background-color: #3c3737;
            color: #fff;
        }

        #logout-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            z-index: 10;
            background: #b33a3a;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 1em;
        }

        .stock-table {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .stock-table th,
        .stock-table td {
            border: 1px solid #ccc;
        }

        .stock-input {
            width: 60px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .success-message {
            text-align: center;
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin: 20px auto;
            max-width: 800px;
        }

        .ordermodal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal-content-order {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .close-modal-order {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <button id="logout-btn" style="display:none;"><i class="fa-solid fa-right-from-bracket" style="margin-right:8px;"></i>Logout</button>
    <div class="container">
        <h1>Management Panel</h1>

        <nav class="admin-nav">
            <a href="?section=products" class="<?php if ($view === 'products') echo 'active'; ?>">Gestionar Productos</a>
            <a href="?section=orders" class="<?php if ($view === 'orders') echo 'active'; ?>">Gestionar Pedidos</a>
            <a href="?section=stock" class="<?php if ($view === 'stock') echo 'active'; ?>">Gestionar Stock</a>
        </nav>

        <?php if (isset($success_message)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <?php if ($view === 'products'): ?>
            <form id="add-product-form" action="add_product" method="POST" enctype="multipart/form-data">
                <input type="text" name="nombre" placeholder="Nombre del producto" required>
                <input type="number" name="precio" placeholder="Precio" step="0.01" required>
                <input type="text" name="descripcion" placeholder="Descripción" required>
                <select name="categoria" required>
                    <option value="">Categorías</option>
                    <option value="hats">Sombreros</option>
                    <option value="caps">Cachuchas</option>
                </select>
                <input type="file" name="imagen" accept="image/*">
                <button type="submit">Añadir producto</button>
            </form>

            <form method="GET" style="margin-top:20px;">
                <input type="hidden" name="section" value="products">
                <select name="category" onchange="this.form.submit()">
                    <option value="" <?php if (!isset($_GET['category']) || trim($_GET['category']) == '') echo 'selected'; ?>>Todas las categorías</option>
                    <option value="hats" <?php if (isset($_GET['category']) && $_GET['category'] == 'hats') echo 'selected'; ?>>Sombreros</option>
                    <option value="caps" <?php if (isset($_GET['category']) && $_GET['category'] == 'caps') echo 'selected'; ?>>Cachuchas</option>
                </select>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';
                    $sql_products = $category_filter ? "SELECT id, name, description, price, category, image FROM productos WHERE category = ? ORDER BY id DESC" : "SELECT id, name, description, price, category, image FROM productos ORDER BY id DESC";
                    $stmt_products = $conn->prepare($sql_products);
                    if ($category_filter) $stmt_products->bind_param("s", $category_filter);
                    $stmt_products->execute();
                    $result_products = $stmt_products->get_result();
                    while ($row = $result_products->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td class='description-cell'>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>$" . number_format($row['price'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td><img src='../{$row['image']}' alt='" . htmlspecialchars($row['name']) . "' style='max-width:60px;max-height:60px;'></td>";
                        echo "<td>
                                <a href='/admin/edit{$row['id']}' title='Edit'><i class='fa-solid fa-pen-to-square' style='color:#1a73e8; font-size:20px;'></i></a>
                                <a href='/admin/delete{$row['id']}' title='Delete' onclick=\"return confirm('¿Eliminar este producto?');\"><i class='fa-solid fa-trash' style='color:#b33a3a; font-size:20px; margin-left:10px;'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                    $stmt_products->close();
                    ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($view === 'orders'): ?>
            <h2>Gestión de pedidos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Actualizar</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_orders = "SELECT p.id, p.total, p.fecha, p.estado, u.name, u.email FROM pedidos p JOIN users u ON p.user_id = u.id ORDER BY p.fecha DESC";
                    $result_orders = $conn->query($sql_orders);
                    while ($row = $result_orders->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>$" . number_format($row['total'], 2) . "</td>
                                <td>{$row['fecha']}</td>
                                <td>
                                    <form method='POST' action='update_order_status' style='margin:0;padding:0;background:none;box-shadow:none;'>
                                        <input type='hidden' name='order_id' value='{$row['id']}'>
                                        <select name='estado'>
                                            <option value='Pending' " . ($row['estado'] == 'Pending' ? 'selected' : '') . ">Pendiente</option>
                                            <option value='Enviado' " . ($row['estado'] == 'Enviado' ? 'selected' : '') . ">Enviado</option>
                                            <option value='Cancelado' " . ($row['estado'] == 'Cancelado' ? 'selected' : '') . ">Cancelado</option>
                                        </select>
                                </td>
                                <td>
                                    <button type='submit' title='Update' style='background:none;border:none;cursor:pointer;'><i class='fas fa-save' style='color:#28a745;font-size:18px;'></i></button>
                                    </form>
                                </td>
                                <td>
                                    <button title='View details' onclick='showOrderDetails({$row['id']})' style='background:none;border:none;cursor:pointer;'><i class='fas fa-eye' style='color:#007bff;font-size:18px;'></i></button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($view === 'stock'): ?>
            <h2>Gestión de Stock por Variante</h2>
            <div style="text-align:center; margin-bottom: 20px;">
                <form action="/admin" method="GET" style="display:inline-block; max-width:600px; background:none; box-shadow:none;">
                    <input type="hidden" name="section" value="stock">
                    <label for="pid">Selecciona un producto para editar su stock:</label>
                    <select name="pid" id="pid" onchange="this.form.submit()">
                        <option value="">-- Elige un producto --</option>
                        <?php
                        // Aseguramos que el puntero del resultado esté al inicio
                        if ($products_for_stock_result->num_rows > 0) {
                            mysqli_data_seek($products_for_stock_result, 0);
                        }
                        while ($product = $products_for_stock_result->fetch_assoc()): ?>
                            <option value="<?php echo $product['id']; ?>" <?php echo ($selected_product_id == $product['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($product['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>

            <?php if ($selected_product_stock): ?>
                <form action="admin/admin?section=stock&pid=<?php echo $selected_product_id; ?>" method="POST" style="max-width:none; background:none; box-shadow:none;">
                    <input type="hidden" name="action" value="update_stock">
                    <input type="hidden" name="pid" value="<?php echo $selected_product_id; ?>">
                    <table class="stock-table">
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
                                                value="<?php echo $stock_map[$color['id']][$size['id']] ?? 0; ?>" min="0">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="text-align:center; margin-top:20px;">
                        <button type="submit">Guardar Cambios de Stock</button>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="ordermodal hidden">
        <div class="modal-content-order">
            <span class="close-modal-order">&times;</span>
            <h2>Detalles de los productos del pedido</h2>
            <div id="admin-order-details"></div>
        </div>
    </div>

    <script>
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn && localStorage.getItem('jwt')) {
            logoutBtn.style.display = 'inline-block';
            logoutBtn.addEventListener('click', () => {
                localStorage.removeItem('jwt');
                Swal.fire({
                        title: 'Sesión cerrada',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    })
                    .then(() => {
                        window.location.href = '/';
                    });
            });
        }

        function showOrderDetails(orderId) {
            const modal = document.querySelector('.ordermodal');
            const detailsDiv = document.getElementById('admin-order-details');

            fetch(`/php/order/get_detail_order${orderId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.details.length > 0) {
                        let html = "<ul>";
                        data.details.forEach(item => {
                            html += `<li>${item.name} - Cant: ${item.cantidad} - Precio: $${item.price} - Talla: ${item.size_nombre || "N/A"} - Color: ${item.color_nombre || "N/A"}</li>`;
                        });
                        html += "</ul>";
                        detailsDiv.innerHTML = html;
                    } else {
                        detailsDiv.innerHTML = "<p>No se encontraron productos para este pedido.</p>";
                    }
                    modal.classList.remove("hidden");
                })
                .catch(() => {
                    detailsDiv.innerHTML = "<p>Error al cargar los detalles.</p>";
                    modal.classList.remove("hidden");
                });
        }

        const ordermodal = document.querySelector(".ordermodal");
        if (ordermodal) {
            const closeBtn = document.querySelector(".close-modal-order");
            closeBtn.addEventListener("click", () => ordermodal.classList.add("hidden"));
            ordermodal.addEventListener("click", (e) => {
                if (e.target === ordermodal) {
                    ordermodal.classList.add("hidden");
                }
            });
        }
    </script>
</body>

</html>
<?php
// Cerrar la conexión a la base de datos una sola vez al final
$conn->close();
?>