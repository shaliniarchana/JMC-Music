<?php
$conn = new mysqli("localhost", "root", "", "joy_music");

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];

$stmt = $conn->prepare("UPDATE products SET name=?, price=? WHERE id=?");
$stmt->bind_param("sdi", $name, $price, $id);
$stmt->execute();

// ✅ Set session message
$_SESSION['message'] = "Product updated successfully!";

// ✅ Redirect back to add_product.php
header("Location: add_to_cart.php");
exit();
?>
