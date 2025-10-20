<?php
// php/get_shipping_rate.php
require_once 'conexion.php';
header('Content-Type: application/json');

// --- CONFIGURACIÓN ---
$api_key = 'TU_API_KEY_DE_EXCHANGERATE-API'; // <-- PEGA AQUÍ TU API KEY
$base_currency = 'COP'; // Moneda en la que están guardados tus precios
$target_currency = 'USD'; // Moneda a la que quieres convertir

// --- FUNCIÓN PARA OBTENER LA TASA DE CAMBIO (CON CACHÉ) ---
function get_conversion_rate($api_key, $base, $target)
{
   $cache_file = __DIR__ . '/currency_cache.json';
   $cache_lifetime = 3600 * 12; // Actualizar cada 12 horas

   if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_lifetime) {
      // Si el caché es reciente, lo usamos
      $cache = json_decode(file_get_contents($cache_file), true);
      return $cache['conversion_rates'][$target] ?? null;
   } else {
      // Si el caché es viejo o no existe, llamamos al API
      $url = "https://v6.exchangerate-api.com/v6/{$api_key}/latest/{$base}";
      $response = @file_get_contents($url);
      if ($response === FALSE) {
         return null; // Error al contactar el API
      }

      $data = json_decode($response, true);
      if ($data && $data['result'] === 'success') {
         file_put_contents($cache_file, $response); // Guardamos la respuesta en el caché
         return $data['conversion_rates'][$target] ?? null;
      }
      return null;
   }
}

// Normalizamos la entrada para mejorar las coincidencias (ej. "Bogota D.C." -> "Bogota")
$department = trim($_GET['departamento'] ?? '');
if (empty($department)) {
   echo json_encode(['success' => false, 'message' => 'Departamento no proporcionado.']);
   exit;
}

// Lógica para el caso especial "Resto del mundo"
if (strtolower($department) === 'resto del mundo') {
   echo json_encode(['success' => true, 'requires_quote' => true, 'message' => 'Se requiere cotización de envío.']);
   exit;
}

// 1. Obtenemos el precio en la moneda base (COP) de la base de datos
$stmt = $conn->prepare("SELECT price FROM shipping_rates WHERE departamento = ?");
$stmt->bind_param("s", $department);
$stmt->execute();
$result = $stmt->get_result();
$rate_cop = $result->fetch_assoc();
$stmt->close();

if ($rate_cop) {
   $price_in_cop = (float)$rate_cop['price'];

   // 2. Obtenemos la tasa de conversión
   $conversion_rate = get_conversion_rate($api_key, $base_currency, $target_currency);

   if ($conversion_rate !== null) {
      // 3. Calculamos el precio final en la moneda objetivo (USD)
      $price_in_usd = $price_in_cop * $conversion_rate;
      echo json_encode(['success' => true, 'price' => round($price_in_usd, 2)]);
   } else {
      // Si el API de conversión falla, podrías devolver un error
      echo json_encode(['success' => false, 'message' => 'No se pudo obtener la tasa de cambio.']);
   }
} else {
   echo json_encode(['success' => false, 'message' => 'No hay envíos disponibles para esta ubicación.']);
}

$conn->close();
