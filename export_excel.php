<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=product_report.xls");

// Connect to database
$conn = new mysqli("localhost", "root", "", "joy_music"); // Change credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<table border='1'>";
echo "<tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Image</th>
      </tr>";

$sql = "SELECT name, price, image FROM products"; // Adjust table/column names
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".htmlspecialchars($row['name'])."</td>";
    echo "<td>".$row['price']."</td>";
    echo "<td>".$row['image']."</td>"; // Optional: just show image filename
    echo "</tr>";
}

echo "</table>";

$conn->close();
?>