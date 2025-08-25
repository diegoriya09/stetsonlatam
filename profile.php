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

  // 2. Obtener las últimas órdenes del usuario
  $stmt_orders = $conn->prepare("SELECT id, fecha, total, estado FROM pedidos WHERE user_id = ? ORDER BY fecha DESC LIMIT 5");
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
  <div class="page-wrapper">
    <main class="profile-main">
      <div class="profile-container">
        <aside class="profile-sidebar">
          <div class="sidebar-user">
            <div class="sidebar-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
            <h2 class="sidebar-username"><?php echo htmlspecialchars($user['name']); ?></h2>
          </div>
          <nav class="sidebar-nav">
            <a href="#" class="nav-link active" data-target="overview-panel">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256">
                <path d="M224,115.55V208a16,16,0,0,1-16,16H168a16,16,0,0,1-16-16V168a8,8,0,0,0-8-8H112a8,8,0,0,0-8,8v40a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V115.55a16,16,0,0,1,5.17-11.78l80-75.48.11-.11a16,16,0,0,1,21.53,0l.11.11,80,75.48A16,16,0,0,1,224,115.55Z"></path>
              </svg>
              <span>Overview</span>
            </a>
            <a href="#" class="nav-link" data-target="orders-panel">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256">
                <path d="M247.42,117l-14-35A15.93,15.93,0,0,0,218.58,72H184V64a8,8,0,0,0-8-8H24A16,16,0,0,0,8,72V184a16,16,0,0,0,16,16H41a32,32,0,0,0,62,0h50a32,32,0,0,0,62,0h17a16,16,0,0,0,16-16V120A7.94,7.94,0,0,0,247.42,117ZM184,88h34.58l9.6,24H184ZM24,72H168v64H24ZM72,208a16,16,0,1,1,16-16A16,16,0,0,1,72,208Zm81-24H103a32,32,0,0,0-62,0H24V152H168v12.31A32.11,32.11,0,0,0,153,184Zm31,24a16,16,0,1,1,16-16A16,16,0,0,1,184,208Zm48-24H215a32.06,32.06,0,0,0-31-24V128h48Z"></path>
              </svg>
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
  </div>

  <?php include 'footer.php'; ?>

  <?php include 'modal.php'; ?>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
  <script>
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