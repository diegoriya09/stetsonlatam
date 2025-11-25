<?php
// php/webhook_mercado_pago.php (VERSIÓN CON cURL - SIN SDK)

// Solo requerimos la conexión a nuestra base de datos.
require 'conexion.php';

// 1. CONFIGURA TU ACCESS TOKEN

$log_file = __DIR__ . '/webhook_log.txt';
$log_message = "[" . date("Y-m-d H:i:s") . "] " . file_get_contents('php://input') . "\n\n";
file_put_contents($log_file, $log_message, FILE_APPEND);

// Debe ser el mismo que usaste para crear el pago (Sandbox o Producción).
$access_token = "APP_USR-7493389823882807-112515-74cf048ad84435669297aeae24865865-12742422";

// 2. RECIBE LA NOTIFICACIÓN
$body = file_get_contents('php://input');
$data = json_decode($body, true);

// 3. PROCESA LA NOTIFICACIÓN DE PAGO
if (isset($data['type']) && $data['type'] === 'payment') {
   $payment_id = $data['data']['id'];

   // 4. VERIFICAMOS EL PAGO USANDO cURL
   // En lugar de usar el SDK (Payment::find_by_id), hacemos una llamada directa a la API.
   $curl = curl_init();

   curl_setopt_array($curl, [
      // Construimos la URL del API para obtener la información del pago
      CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/' . $payment_id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET', // Usamos GET para consultar información
      CURLOPT_HTTPHEADER => [
         'Authorization: Bearer ' . $access_token, // La autenticación es crucial
      ],
   ]);

   $response = curl_exec($curl);
   $err = curl_error($curl);
   curl_close($curl);

   if ($err) {
      error_log("cURL Error en Webhook: " . $err);
      http_response_code(500);
      exit();
   }

   // Decodificamos la respuesta que nos dio Mercado Pago
   $payment_details = json_decode($response, true);

   // 5. ACTUALIZAMOS NUESTRA BASE DE DATOS
   // Si la respuesta es válida, el pago fue aprobado y tiene un external_reference...
   if ($payment_details && isset($payment_details['status']) && $payment_details['status'] === 'approved' && isset($payment_details['external_reference'])) {

      $pedido_id = $payment_details['external_reference'];

      $conn->begin_transaction();
      try {
         $stmt_check = $conn->prepare("SELECT user_id, estado FROM pedidos WHERE id = ? FOR UPDATE");
         $stmt_check->bind_param("i", $pedido_id);
         $stmt_check->execute();
         $pedido = $stmt_check->get_result()->fetch_assoc();
         $stmt_check->close();

         if ($pedido && $pedido['estado'] === 'PendienteDePago') {
            // ACCIÓN 1: ACTUALIZAR ESTADO A 'PAGADO'
            $stmt_update = $conn->prepare("UPDATE pedidos SET estado = 'Pagado' WHERE id = ?");
            $stmt_update->bind_param("i", $pedido_id);
            $stmt_update->execute();
            $stmt_update->close();

            // ACCIÓN 2: DESCONTAR EL STOCK
            $stmt_details = $conn->prepare("SELECT producto_id, cantidad, color_id, size_id FROM pedido_detalle WHERE pedido_id = ?");
            $stmt_details->bind_param("i", $pedido_id);
            $stmt_details->execute();
            $items_pedido = $stmt_details->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt_details->close();
            $stmt_decrement = $conn->prepare("UPDATE product_variants SET stock = stock - ? WHERE product_id = ? AND color_id = ? AND size_id = ?");
            foreach ($items_pedido as $item) {
               $stmt_decrement->bind_param("iiii", $item['cantidad'], $item['producto_id'], $item['color_id'], $item['size_id']);
               $stmt_decrement->execute();
            }
            $stmt_decrement->close();

            // ACCIÓN 3: VACIAR CARRITO
            $user_id = $pedido['user_id'];
            $stmt_clear = $conn->prepare("DELETE FROM cart WHERE users_id = ?");
            $stmt_clear->bind_param("i", $user_id);
            $stmt_clear->execute();
            $stmt_clear->close();

            $conn->commit();
         } else {
            $conn->rollback();
         }
      } catch (Exception $e) {
         $conn->rollback();
         error_log("Error en DB Webhook para pedido $pedido_id: " . $e->getMessage());
         http_response_code(500);
         exit();
      }
   }
}

// 6. RESPONDEMOS 200 OK A MERCADO PAGO
// Esto le dice a Mercado Pago "Recibí tu mensaje, no me lo envíes de nuevo".
http_response_code(200);
exit();
