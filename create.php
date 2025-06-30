<?php
session_start(); 

$conn = new mysqli("localhost", "root", "", "joy_music");


if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
}

$name = $_POST['name'];
$price = $_POST['price'];

$image = '';
if ($_FILES['image']['name']) {
    $image = 'uploads/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image);
}

$stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
$stmt->bind_param("sds", $name, $price, $image);
$stmt->execute();


$_SESSION['message'] = "Product added successfully!";


header("Location: add_to_cart.php");
exit();
?>
