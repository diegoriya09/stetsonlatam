<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <title>Localizador de tiendas | Stetson LATAM</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="img/logo.webp" type="image/x-icon">
   <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
   <link href="css/store_locator.css?v=<?php echo time(); ?>" rel="stylesheet">
   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>

<body>
   <?php include "header.php"; ?>
   <main class="locator-main">
      <div class="locator-container">
         <aside class="locator-sidebar">
            <h2>UBICACIONES DE LAS TIENDAS</h2>
            <p>Encuentra productos seleccionados de Stetson en un minorista cerca de ti.</p>
         </aside>
         <div class="locator-content">
            <div id="map-placeholder" class="map-placeholder">
               <p>Cargando mapa...</p>
            </div>
            <div id="store-results-list" class="store-results-list">
               <p>Cargando tiendas...</p>
            </div>
         </div>
      </div>
   </main>
   <?php include 'footer.php'; ?>
   <script src="js/store_locator.js?v=<?php echo time(); ?>"></script>
</body>

</html>