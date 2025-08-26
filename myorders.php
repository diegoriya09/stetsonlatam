<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>Mis pedidos</title>
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
   <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
   <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden">
      <div class="layout-container flex h-full grow flex-col">
         <?php include 'header.php'; ?>
         <div class="px-40 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
               <div class="flex flex-wrap gap-2 p-4">
                  <a class="text-[#887563] text-base font-medium leading-normal" href="profile.php">Perfil</a>
                  <span class="text-[#3c3737] text-base font-medium leading-normal">/</span>
                  <span class="text-[#3c3737] text-base font-medium leading-normal">Mis pedidos</span>
               </div>
               <div class="flex flex-wrap justify-between gap-3 p-4">
                  <p class="text-[#3c3737] tracking-light text-[32px] font-bold leading-tight min-w-72">Mis pedidos</p>
               </div>
               <div class="px-4 py-3 @container">
                  <div class="flex overflow-hidden rounded-lg border border-[#e5e0dc] bg-white">
                     <table class="flex-1">
                        <thead>
                           <tr class="bg-white">
                              <th class="px-4 py-3 text-left text-[#3c3737] w-14 text-sm font-medium leading-normal">Pedido
                              </th>
                              <th class="px-4 py-3 text-left text-[#3c3737] w-[400px] text-sm font-medium leading-normal">Fecha
                              </th>
                              <th class="px-4 py-3 text-left text-[#3c3737] w-[400px] text-sm font-medium leading-normal">Estado
                              </th>
                              <th class="px-4 py-3 text-left text-[#3c3737] w-[400px] text-sm font-medium leading-normal">Total
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                           <!-- JavaScript will dynamically populate this section -->
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <?php include 'footer.php'; ?>
      </div>
   </div>
   <?php include 'modal.php'; ?>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
   <script src="js/myorders.js?v=<?php echo time(); ?>"></script>

</body>

</html>