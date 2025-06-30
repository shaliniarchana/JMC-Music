<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'joy_music');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate inputs
$order_id = $_POST['order_id'] ?? null;
$status = $_POST['status'] ?? '';
$estimated_delivery_date = $_POST['estimated_delivery_date'] ?? '';

if ($order_id && in_array($status, ['processing', 'on_the_way', 'delivered']) && $estimated_delivery_date) {
    $stmt = $conn->prepare("UPDATE orders SET status = ?, estimated_delivery_date = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $estimated_delivery_date, $order_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Order updated successfully.";
} else {
    $_SESSION['error'] = "Invalid update request.";
}

header("Location: delivery_management.php");
exit();
?>
