<?php
require '../php/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $estado = $_POST['estado'];

    $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $order_id);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=Status updated");
    } else {
        echo "Error updating status.";
    }

    $stmt->close();
    $conn->close();
}
?>
