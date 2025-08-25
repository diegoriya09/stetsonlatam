<?php
session_start();
// Solo necesitamos verificar la sesión. Los datos los traerá el JavaScript.
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}
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
                    <div id="sidebar-avatar" class="sidebar-avatar"></div>
                    <h2 id="sidebar-username" class="sidebar-username">Loading...</h2>
                </div>
                <nav class="sidebar-nav">
                    <a href="#" class="nav-link active" data-target="overview-panel"><span>Overview</span></a>
                    <a href="#" class="nav-link" data-target="orders-panel"><span>Orders</span></a>
                    <a href="#" class="nav-link" data-target="addresses-panel"><span>Addresses</span></a>
                    <a href="#" class="nav-link" data-target="payment-panel"><span>Payment Methods</span></a>
                    <a href="#" class="nav-link" data-target="settings-panel"><span>Settings</span></a>
                </nav>
            </aside>

            <section class="profile-content">
                <div id="overview-panel" class="content-panel active">
                    <h1 class="content-title">Overview</h1>
                    <p>Welcome back, <span id="overview-username">user</span>. From here you can manage your orders, addresses, and account details.</p>
                </div>

                <div id="orders-panel" class="content-panel">
                    <h1 class="content-title">Recent Orders</h1>
                    <div id="orders-table-container" class="orders-table-container"></div>
                    <div class="mt-6 text-center"><a href="myorders.php" class="view-all-btn">View All Orders</a></div>
                </div>

                <div id="addresses-panel" class="content-panel">
                    <h1 class="content-title">Addresses</h1>
                    <div id="address-list" class="content-grid"></div>
                    <button class="add-new-btn">Add New Address</button>
                </div>

                <div id="payment-panel" class="content-panel">
                    <h1 class="content-title">Payment Methods</h1>
                    <div id="payment-method-list" class="content-grid"></div>
                    <button class="add-new-btn">Add New Payment Method</button>
                </div>

                <div id="settings-panel" class="content-panel">
                    <h1 class="content-title">Settings</h1>
                    <p>Manage your account settings here.</p>
                </div>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <?php include 'modal.php'; ?>
    
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/profile.js?v=<?php echo time(); ?>"></script>
</body>

</html>