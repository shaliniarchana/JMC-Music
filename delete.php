<?php
$conn = new mysqli("localhost", "root", "", "joy_music");
$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id=$id");

$_SESSION['message'] = "Product deleted successfully!";


header("Location: add_to_cart.php");
exit();
?>
