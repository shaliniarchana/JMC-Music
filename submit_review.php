<?php
$conn = new mysqli("localhost", "root", "", "joy_music");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);

    if ($product_id > 0 && $rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, rating) VALUES (?, ?)");
        $stmt->bind_param("ii", $product_id, $rating);
        if ($stmt->execute()) {
            echo "Thank you for your rating!";
        } else {
            echo "Error saving rating.";
        }
        $stmt->close();
    } else {
        echo "Invalid input.";
    }
}
?>
