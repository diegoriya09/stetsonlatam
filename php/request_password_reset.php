<?php
// php/auth/request_password_reset.php

require_once 'conexion.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, introduce un correo válido.']);
    exit;
}

// 1. Buscar al usuario por su email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    // 2. Generar un token seguro y una fecha de expiración
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // 3. Guardar el token y la fecha en la base de datos
    $stmt_update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?");
    $stmt_update->bind_param("ssi", $token, $expires_at, $user['id']);
    $stmt_update->execute();
    $stmt_update->close();

    // 4. Enviar el correo electrónico con PHPMailer
    $reset_link = "https://stetsonlatam.com/reset-password.php?token=" . $token;
    
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP (reemplaza con tus datos)
        $mail->isSMTP();
        $mail->Host       = 'lin409.loading.es'; // Servidor SMTP de tu hosting
        $mail->SMTPAuth   = true;
        $mail->Username   = 'stetsonlatam@stetsonlatam.com';
        $mail->Password   = 'Dinalsom1977@';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 465;

        // Destinatarios
        $mail->setFrom('stetsonlatam@stetsonlatam.com', 'Stetson Latam');
        $mail->addAddress($email);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña - Stetson Latam';
        $mail->Body    = "Hola,<br><br>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:<br><br><a href='{$reset_link}'>Restablecer Contraseña</a><br><br>Si no solicitaste esto, puedes ignorar este correo. El enlace expirará en 1 hora.";
        
        $mail->send();
    } catch (Exception $e) {
        // No mostrar el error al usuario, pero sí registrarlo
        error_log("Error al enviar correo de reseteo: " . $mail->ErrorInfo);
    }
}

// IMPORTANTE: Por seguridad, siempre muestra un mensaje de éxito,
// incluso si el email no existe. Esto previene que alguien pueda "adivinar" qué correos están registrados.
echo json_encode(['success' => true, 'message' => 'Si tu correo está registrado, recibirás un enlace para restablecer tu contraseña.']);
$conn->close();
?>