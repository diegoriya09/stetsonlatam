<?php
// php/cron/wishlist_reminders.php
require_once __DIR__ . '/../conexion.php';

// Busca items en wishlist añadidos hace más de una semana
$sql = "
    SELECT u.email, u.name, p.name as product_name, p.id as product_id
    FROM wishlist w
    JOIN users u ON w.user_id = u.id
    JOIN productos p ON w.product_id = p.id
    WHERE w.added_at < NOW() - INTERVAL 1 WEEK
";

$result = $conn->query($sql);
while ($item = $result->fetch_assoc()) {
    $product_url = "https://www.stetsonlatam.com/producto" . $item['product_id'];
    $subject = "Un artículo de tu wishlist te está esperando";
    $message = "Hola " . $item['name'] . ",\n\nNotamos que el producto '" . $item['product_name'] . "' sigue en tu lista de deseos. ¡No te lo pierdas!\n\nPuedes verlo aquí: " . $product_url;
    
    // mail($item['email'], $subject, $message);
    // Idealmente, después de enviar, se marca para no volver a notificar pronto.
}
$conn->close();
echo "Proceso de recordatorios de wishlist finalizado.";
?>