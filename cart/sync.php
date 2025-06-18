<?php
session_start();
require '<php/conexion.php'; // conexión

if (isset($_SESSION['users_id'])) {
  $userId = $_SESSION['users_id'];
  $carritoLocal = json_decode(file_get_contents('php://input'), true);

  foreach ($carritoLocal as $item) {
    $productoId = $item['id'];
    $quantity = $item['quantity'];

    // Buscar si ya existe
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE users_id = ? AND producto_id = ?");
    $stmt->execute([$userId, $productoId]);

    if ($stmt->rowCount() > 0) {
      // Actualizar cantidad
      $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE users_id = ? AND producto_id = ?")
          ->execute([$cantidad, $userId, $productoId]);
    } else {
      // Insertar nuevo
      $pdo->prepare("INSERT INTO cart (users_id, producto_id, quantity) VALUES (?, ?, ?)")
          ->execute([$userId, $productoId, $cant]);
    }
  }

  echo json_encode(['status' => 'ok']);
}
?>