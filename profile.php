<?php
require_once 'php/conexion.php';
session_start();

// Redirigir si el usuario no ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php'); // O a tu página de login
  exit();
}

$user_id = $_SESSION['user_id'];
$user = null;
$orders = [];

try {
  // 1. Obtener detalles del usuario
  $stmt_user = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
  $stmt_user->bind_param("i", $user_id);
  $stmt_user->execute();
  $user = $stmt_user->get_result()->fetch_assoc();
  $stmt_user->close();

  // 2. OBTENER LAS ÓRDENES
  $stmt_orders = $conn->prepare("
        SELECT id, fecha, total, estado 
        FROM pedidos 
        WHERE user_id = ? 
        ORDER BY fecha DESC 
        LIMIT 5
    ");
  $stmt_orders->bind_param("i", $user_id);
  $stmt_orders->execute();
  $result_orders = $stmt_orders->get_result();
  while ($row = $result_orders->fetch_assoc()) {
    $orders[] = $row;
  }
  $stmt_orders->close();
} catch (Exception $e) {
  error_log("Error en la página de perfil: " . $e->getMessage());
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Profile | Stetson LATAM</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="img/logo.webp" type="image/x-icon">
  <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link href="css/profile.css?v=<?php echo time(); ?>" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php include "header.php"; ?>

  <main class="profile-main">
    <div class="profile-container">
      <aside class="profile-sidebar">
        <div class="sidebar-user">
          <div class="sidebar-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
          <h2 class="sidebar-username"><?php echo htmlspecialchars($user['name']); ?></h2>
        </div>
        <nav class="sidebar-nav">
          <a href="#" class="nav-link active" data-target="overview-panel">
            <span>Overview</span>
          </a>
          <a href="#" class="nav-link" data-target="orders-panel">
            <span>Orders</span>
          </a>
        </nav>
      </aside>

      <section class="profile-content">
        <div id="overview-panel" class="content-panel active">
          <h1 class="content-title">Overview</h1>
          <p>Welcome back, <?php echo htmlspecialchars(explode(' ', $user['name'])[0]); ?>. From here you can manage your orders, addresses, and account details.</p>
        </div>

        <div id="orders-panel" class="content-panel">
          <h1 class="content-title">Recent Orders</h1>
          <div class="orders-table-container">
            <table>
              <thead>
                <tr>
                  <th>Order</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($orders)): ?>
                  <?php foreach ($orders as $order): ?>
                    <tr>
                      <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                      <td><?php echo date("F j, Y", strtotime($order['fecha'])); ?></td>
                      <td><span class="status-badge status-<?php echo strtolower(htmlspecialchars($order['estado'])); ?>"><?php echo htmlspecialchars($order['estado']); ?></span></td>
                      <td>$<?php echo number_format($order['total'], 2); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem;">You have not placed any orders yet.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <?php include 'modal.php'; ?>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
  <script>
    // SCRIPT DE INTERACTIVIDAD (SIN CAMBIOS)
    document.addEventListener('DOMContentLoaded', function() {
      const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
      const contentPanels = document.querySelectorAll('.content-panel');

      navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.dataset.target;

          navLinks.forEach(l => l.classList.remove('active'));
          contentPanels.forEach(p => p.classList.remove('active'));

          this.classList.add('active');
          document.getElementById(targetId).classList.add('active');
        });
      });
    });
  </script>
</body>

</html>