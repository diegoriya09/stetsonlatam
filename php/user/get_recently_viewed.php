<?php
// php/user/get_recently_viewed.php

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

function getAuthorizationHeader()
{
   if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
      return trim($_SERVER["HTTP_AUTHORIZATION"]);
   }
   if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
      return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
   }
   return null;
}

$products = [];
$authHeader = getAuthorizationHeader();

// --- CASO 1: USUARIO LOGUEADO (USA JWT) ---
if ($authHeader) {
   try {
      $jwt = trim(str_replace('Bearer', '', $authHeader));
      $secret_key = "StetsonLatam1977";
      $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
      $user_id = $decoded->data->id;

      // Consulta los últimos 5 productos únicos vistos por el usuario
      $stmt = $conn->prepare("
            SELECT p.id, p.name, p.image, p.price
            FROM (
                SELECT DISTINCT product_id, visited_at
                FROM user_visits
                WHERE user_id = ?
                ORDER BY visited_at DESC
                LIMIT 5
            ) as recent_visits
            JOIN productos p ON recent_visits.product_id = p.id
        ");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $products = $result->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
   } catch (Exception $e) {
      // Si el token es inválido, no hacemos nada y dejamos que se use localStorage
   }
}

// --- CASO 2: USUARIO NO LOGUEADO (USA localStorage) ---
// El frontend enviará los IDs desde localStorage
if (empty($products) && isset($_GET['ids'])) {
   $product_ids = json_decode($_GET['ids']);

   if (is_array($product_ids) && !empty($product_ids)) {
      // Limpiar y asegurar que solo sean números
      $sanitized_ids = array_map('intval', $product_ids);
      $placeholders = implode(',', array_fill(0, count($sanitized_ids), '?'));
      $types = str_repeat('i', count($sanitized_ids));

      $stmt = $conn->prepare("
            SELECT id, name, image, price 
            FROM productos 
            WHERE id IN ($placeholders)
            ORDER BY FIELD(id, $placeholders)
        ");
      // Se vinculan los parámetros dos veces por la cláusula FIELD
      $stmt->bind_param($types . $types, ...$sanitized_ids, ...$sanitized_ids);
      $stmt->execute();
      $result = $stmt->get_result();
      $products = $result->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
   }
}

$conn->close();
echo json_encode(['success' => true, 'products' => $products]);
