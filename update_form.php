<?php
$conn = new mysqli("localhost", "root", "", "joy_music");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id=$id");
$row = $result->fetch_assoc();
?>

<form action="update.php" method="POST">
  <input type="hidden" name="id" value="<?= $row['id'] ?>">
  <input type="text" name="name" value="<?= $row['name'] ?>"><br>
  <input type="number" name="price" value="<?= $row['price'] ?>"><br>
  <button type="submit">Update Product</button>
</form>
