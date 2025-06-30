<?php
$conn = new mysqli("localhost", "root", "", "joy_music");

// Check connection
if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
    exit();
}

$result = $conn->query("SELECT * FROM products");

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td><img src='{$row['image']}' width='50'></td>
        <td>{$row['name']}</td>
        <td>\${$row['price']}</td>
        <td>
          <a href='update_form.php?id={$row['id']}'>Edit</a> | 
          <a href='delete.php?id={$row['id']}'>Delete</a>
        </td>
    </tr>";
}
?>