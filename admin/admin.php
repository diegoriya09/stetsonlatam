<?php
session_start();
// Si usas JWT, decodifica y verifica el rol aquí
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /'); // Redirige si no es admin
    exit;
}
?>

<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #f1eeea;
        margin: 0;
        padding: 0;
    }

    h1 {
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

    tr:hover {
        background: #f1eeea;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    td.description-cell {
        max-width: 320px;
        white-space: normal;
        overflow-wrap: break-word;
        word-break: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 1em;
    }

    th.description-header {
        max-width: 320px;
    }
</style>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/myorders.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<button id="logout-btn" style="display:none; background:#b33a3a; color:#fff; border:none; padding:10px 22px; border-radius:5px; font-weight:bold; cursor:pointer; font-size:1em;">
    <i class="fa-solid fa-right-from-bracket" style="margin-right:8px;"></i>
    Logout
</button>

<h1>Management Panel</h1>

<!-- Formulario para agregar producto -->
<form id="add-product-form" action="add_product.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Product's Name" required>
    <input type="number" name="precio" placeholder="Price" required>
    <input type="text" name="descripcion" placeholder="Description" required>
    <select name="categoria" required>
        <option value="">Categories</option>
        <option value="hats">Hats</option>
        <option value="caps">Caps</option>
    </select>
    <input type="file" name="imagen" accept="image/*">
    <button type="submit">Add product</button>
</form>

<!-- Filtro por categoría -->
<form method="GET" style="margin-top:20px;">
    <select name="category" onchange="this.form.submit()">
        <option value="" <?php if (!isset($_GET['category']) || trim($_GET['category']) == '') echo 'selected'; ?>>All categories</option>
        <option value="hats" <?php if (isset($_GET['category']) && $_GET['category'] == 'hats') echo 'selected'; ?>>Hats</option>
        <option value="caps" <?php if (isset($_GET['category']) && $_GET['category'] == 'caps') echo 'selected'; ?>>Caps</option>
    </select>
</form>

<!-- Tabla de productos -->
<table border="1" cellpadding="8" style="margin-top:20px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th class="description-header">Description</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require '../php/conexion.php';

        // Filtro por categoría si se selecciona
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        if ($category) {
            $stmt = $conn->prepare("SELECT id, name, description, price, category, image FROM productos WHERE category = ?");
            $stmt->bind_param("s", $category);
        } else {
            $stmt = $conn->prepare("SELECT id, name, description, price, category, image FROM productos");
        }
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td class='description-cell'>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>$" . number_format($row['price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>";
            if (!empty($row['image'])) {
                echo "<img src='../{$row['image']}' alt='img' style='max-width:60px;max-height:60px;'>";
            } else {
                echo "-";
            }
            echo "</td>";
            echo "<td>
                <a href='edit_product.php?id={$row['id']}' title='Edit'><i class='fa-solid fa-pen-to-square' style='color:#1a73e8; font-size:20px;'></i></a>
                <a href='delete_product.php?id={$row['id']}' title='Delete' onclick=\"return confirm('¿Eliminar este producto?');\"><i class='fa-solid fa-trash' style='color:#b33a3a; font-size:20px; margin-left:10px;'></i></a>
            </td>";
            echo "</tr>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </tbody>
</table>
<hr style="margin: 40px 0;">

<h2 style="text-align:center; color:#3c3737;">Order Management</h2>

<table border="1" cellpadding="8" style="margin: 30px auto; width: 95%; background:#fff;">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Client</th>
            <th>Email</th>
            <th>Total</th>
            <th>Date</th>
            <th>Status</th>
            <th>Update</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require '../php/conexion.php';

        $sql = "SELECT p.id, p.total, p.fecha, p.estado, u.name, u.email
                FROM pedidos p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.fecha DESC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>$" . number_format($row['total'], 2) . "</td>";
            echo "<td>{$row['fecha']}</td>";
            echo "<td>
                    <form method='POST' action='update_order_status.php'>
                        <input type='hidden' name='order_id' value='{$row['id']}'>
                        <select name='estado'>
                            <option value='Pendiente' " . ($row['estado'] == 'Pendiente' ? 'selected' : '') . ">Pendiente</option>
                            <option value='Enviado' " . ($row['estado'] == 'Enviado' ? 'selected' : '') . ">Enviado</option>
                            <option value='Cancelado' " . ($row['estado'] == 'Cancelado' ? 'selected' : '') . ">Cancelado</option>
                        </select>
                </td>";
            echo "<td><button type='submit'>Update</button></form></td>";
            echo "<td><button onclick='showOrderDetails({$row['id']})'>View</button></td>";
            echo "</tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>
<!-- Modal Detalles -->
<div id="admin-order-modal" class="ordermodal hidden">
    <div class="modal-content-order">
        <span class="close-modal-order">&times;</span>
        <h2>Productos del pedido</h2>
        <div id="admin-order-details"></div>
    </div>
</div>
<script>
    const logoutBtn = document.getElementById('logout-btn');

    if (logoutBtn) {
        if (localStorage.getItem('jwt')) {
            logoutBtn.style.display = 'inline-block';
        }

        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('jwt');
            Swal.fire({
                title: 'Closed session',
                icon: 'info',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '/index.php';
            });
        });
    }
</script>
<script>
    function showOrderDetails(orderId) {
        const modal = document.getElementById('admin-order-modal');
        const detailsDiv = document.getElementById('admin-order-details');

        fetch(`../php/order/get_detail_order.php?id=${orderId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.details.length > 0) {
                    let html = "<ul>";
                    data.details.forEach(item => {
                        html += `<li>
                        ${item.name} - Quantity: ${item.cantidad} - Price: $${item.price} - Size: ${item.size || "N/A"}
                        ${item.color ? `- Color: ${item.color}` : ""}
                    </li>`;
                    });
                    html += "</ul>";
                    detailsDiv.innerHTML = html;
                } else {
                    detailsDiv.innerHTML = "<p>No products found for this order.</p>";
                }

                modal.classList.remove("hidden");
            })
            .catch(() => {
                detailsDiv.innerHTML = "<p>Error loading details.</p>";
                modal.classList.remove("hidden");
            });
    }

    // Close modal
    document.querySelector(".close-modal-order").addEventListener("click", () => {
        document.getElementById("admin-order-modal").classList.add("hidden");
    });
    document.querySelector(".ordermodal").addEventListener("click", (e) => {
        if (e.target.classList.contains("ordermodal")) {
            e.target.classList.add("hidden");
        }
    });
</script>
<script src="js/myorders.js?v=<?php echo time(); ?>"></script>