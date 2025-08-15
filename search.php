<?php
require_once 'php/conexion.php';
session_start();

$q = $_GET['q'] ?? '';
$q = trim($q);

$productos = [];

if ($q !== '') {
   // Consulta usando LIKE para buscar en nombre o descripción
   $sql = "SELECT * FROM productos 
            WHERE name LIKE CONCAT('%', ?, '%') 
               OR description LIKE CONCAT('%', ?, '%')
            ORDER BY name ASC";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("ss", $q, $q);
   $stmt->execute();
   $result = $stmt->get_result();

   while ($row = $result->fetch_assoc()) {
      $productos[] = $row;
   }
   $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <title>Resultados de búsqueda</title>
</head>

<body>
   <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f3f2f2] px-10 py-3">
      <div class="flex items-center gap-8">
         <div class="flex items-center gap-4 text-[#151514]">
            <div class="size-4">
               <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
               </svg>
            </div>
            <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="index.php">Stetson Latam</a></h2>
         </div>
         <div class="flex items-center gap-9">
            <a class="text-[#151514] text-sm font-medium leading-normal" href="hats.php">Hats</a>
            <a class="text-[#151514] text-sm font-medium leading-normal" href="caps.php">Caps</a>
         </div>
      </div>
      <div class="flex flex-1 justify-end gap-8">
         <label class="flex flex-col min-w-40 !h-10 max-w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
               <div
                  class="text-[#7a7671] flex border-none bg-[#f3f2f2] items-center justify-center pl-4 rounded-l-lg border-r-0"
                  data-icon="MagnifyingGlass"
                  data-size="24px"
                  data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                     <path
                        d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                  </svg>
               </div>
               <input
                  placeholder="Search"
                  class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border-none bg-[#f3f2f2] focus:border-none h-full placeholder:text-[#7a7671] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                  value="" />
            </div>
         </label>
         <div class="flex gap-2">
            <button
               id="logout-btn"
               style="display:none;"
               class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
               <div class="text-[#181411]" data-icon="SignOut" data-size="20px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                     <path d="M216,128a8,8,0,0,1-8,8H104v16a8,8,0,0,1-13.66,5.66l-32-32a8,8,0,0,1,0-11.32l32-32A8,8,0,0,1,104,104v16h104A8,8,0,0,1,216,128ZM128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Z"></path>
                  </svg>
               </div>
            </button>
            <button
               id="user-btn"
               class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f3f2f2] text-[#151514] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
               <div class="text-[#151514]" data-icon="User" data-size="20px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                     <path
                        d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
                  </svg>
               </div>
            </button>
            <button
               id="cart-btn"
               class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f3f2f2] text-[#151514] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
               <div class="text-[#151514]" data-icon="ShoppingBag" data-size="20px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                     <path
                        d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
                  </svg>
               </div>
            </button>
            <div
               class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
               style='background-image: url("img/logo.webp");'></div>
         </div>
      </div>
   </header>
   <h1>Resultados de búsqueda para: "<?php echo htmlspecialchars($q); ?>"</h1>

   <?php if (count($productos) > 0): ?>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
         <?php foreach ($productos as $producto): ?>
            <div class="producto flex flex-col gap-3 pb-3">
               <a href="producto.php?id=<?php echo $producto['id']; ?>" class="flex flex-col gap-3 pb-3 hover:scale-[1.03] transition-transform">
                  <div
                     class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg"
                     style='background-image: url("<?php echo htmlspecialchars($producto["image"]); ?>");'></div>
                  <div>
                     <p class="text-[#151514] text-base font-medium leading-normal"><?php echo htmlspecialchars($producto["name"]); ?></p>
                     <p class="text-[#7a7671] text-sm font-normal leading-normal">$<?php echo number_format($producto["price"], 2); ?></p>
                  </div>
               </a>
            </div>
         <?php endforeach; ?>
      </div>
   <?php else: ?>
      <p>No se encontraron resultados.</p>
   <?php endif; ?>
   <?php include 'modal.php'; ?>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>