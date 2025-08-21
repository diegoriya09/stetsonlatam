<?php
// php/search_products.php
require_once __DIR__ . '/conexion.php';
header('Content-Type: text/html; charset=utf-8');

$q = trim($_GET['q'] ?? '');
$like = "%$q%";

// Si tu tabla se llama "productos" y tiene: id, name, price, image, activo (opcional)
if ($q === '') {
  // Sin término: devuelve un set por defecto (ajusta el ORDER/LIMIT a tu gusto)
  $sql = "SELECT id, name, price, image FROM productos WHERE 1 ORDER BY id DESC LIMIT 60";
  $stmt = $conn->prepare($sql);
} else {
  $sql = "SELECT id, name, price, image
          FROM productos
          WHERE name LIKE ?
          ORDER BY
            CASE WHEN name LIKE CONCAT(?, '%') THEN 0 ELSE 1 END,
            name ASC
          LIMIT 120";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $like, $q);
}

$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  // Importante: envolver en un contenedor con span de 3 columnas para que ocupe el ancho del grid
  echo '<p class="col-span-3 text-center text-[#7a7671] py-6">No se encontraron productos.</p>';
  exit;
}

while ($row = $res->fetch_assoc()) {
  // Reusa tu misma tarjeta. Aquí uso bg-contain para NO recortar imágenes.
  echo '<div class="flex flex-col gap-3 pb-3 producto-item" data-price="' . htmlspecialchars($row['price']) . '">
          <a href="producto.php?id=' . intval($row['id']) . '&from=hats" class="flex flex-col gap-3 pb-3 hover:scale-[1.03] transition-transform">
            <div class="w-full bg-center bg-no-repeat aspect-[3/4] bg-contain bg-white rounded-lg"
                 style="background-image: url(\'' . htmlspecialchars($row['image']) . '\');"></div>
            <div>
              <p class="text-[#151514] text-base font-medium leading-normal">' . htmlspecialchars($row['name']) . '</p>
              <p class="text-[#7a7671] text-sm font-normal leading-normal">$' . number_format((float)$row['price'], 2) . '</p>
            </div>
          </a>
        </div>';
}