<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'php/conexion.php';
// Ahora puedes ejecutar la consulta
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE user_id = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pedidos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>My Orders</title>
   <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
   <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/product.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/myorders.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/wishlist.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>

<body>
   <?php include 'modal.php'; ?>

   <div class="px-40 flex flex-1 justify-center py-5">
      <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
         <div class="flex flex-wrap gap-2 p-4">
            <a class="text-[#887563] text-base font-medium leading-normal" href="index.php">Home</a>
            <span class="text-[#887563] text-base font-medium leading-normal">/</span>
            <span class="text-[#181411] text-base font-medium leading-normal">My Orders</span>
         </div>
         <div class="flex flex-wrap justify-between gap-3 p-4">
            <p class="text-[#181411] tracking-light text-[32px] font-bold leading-tight min-w-72">My Orders</p>
         </div>
         <div class="px-4 py-3 @container">
            <div class="flex overflow-hidden rounded-lg border border-[#e5e0dc] bg-white">
               <table class="flex-1">
                  <thead>
                     <tr class="bg-white">
                        <th class="px-4 py-3 text-left text-[#181411] w-14 text-sm font-medium leading-normal">Order
                        </th>
                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Date
                        </th>
                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Status
                        </th>
                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Total
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach ($pedidos as $pedido): ?>
                     <tr class="border-t border-t-[#e5e0dc]">
                        <td class="h-[72px] px-4 py-2 w-14 text-sm font-normal leading-normal">
                           <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10"></div>
                           #<?php echo $pedido['id']; ?>
                        </td>
                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#181411] text-sm font-normal leading-normal">
                           <?php echo $pedido['fecha']; ?>
                        </td>
                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#887563] text-sm font-normal leading-normal">
                           <?php echo $pedido['estado']; ?>
                        </td>
                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#887563] text-sm font-normal leading-normal">
                           $<?php echo $pedido['total']; ?>
                        </td>
                     </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>

   <!-- Modal para detalle del pedido -->
   <div class="ordermodal hidden">
      <div class="modal-content-order">
         <span class="close-modal-order">&times;</span>
         <h2>Order Details</h2>
         <div id="orderDetails"></div>
      </div>
   </div>

   <?php include 'footer.php'; ?>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>