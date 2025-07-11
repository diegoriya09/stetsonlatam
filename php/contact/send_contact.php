<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $correo = trim($_POST['correo'] ?? '');
  $asunto = trim($_POST['asunto'] ?? '');
  $mensaje = trim($_POST['mensaje'] ?? '');

  if (!$nombre || !$correo || !$asunto || !$mensaje || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die('All fields are required and the email must be valid.');
  }

  // Enviar correo (requiere configuración de servidor)
  $to = 'contacto@stetsonlatam.com';
  $subject = "Contact: $asunto";
  $body = "Name: $nombre\nEmail: $correo\nMessage:\n$mensaje";
  $headers = "From: $correo";

  if (mail($to, $subject, $body, $headers)) {
    echo "Message sent successfully.";
  } else {
    echo "Error sending message.";
  }

  // Si prefieres guardar en la base de datos, aquí puedes hacer el insert
}
?>