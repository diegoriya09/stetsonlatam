<?php
// wishlist.php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
   <title>Mi Lista de Deseos | Stetson LATAM</title>
   <meta charset="UTF-8">
   <link rel="icon" href="/img/logo.webp" type="image/x-icon">
   <link href="/css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
   <link href="/css/wishlist.css?v=<?php echo time(); ?>" rel="stylesheet">
   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
   <?php include "header.php"; ?>
   <main class="wishlist-main">
      <h1>Mi Lista de Deseos</h1>
      <div id="wishlist-container" class="wishlist-grid">
         <p>Cargando tu lista de deseos...</p>
      </div>
   </main>
   <?php include 'footer.php'; ?>
   <?php include 'modal.php'; ?>
   <script src="/js/wishlist.js?v=<?php echo time(); ?>"></script>
</body>

</html>