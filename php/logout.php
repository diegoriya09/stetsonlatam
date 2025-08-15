<?php
session_start();
session_unset();  // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
http_response_code(200);
echo json_encode(['success' => true, 'message' => 'Sesión cerrada']);
?>