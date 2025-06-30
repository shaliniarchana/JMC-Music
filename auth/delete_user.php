<?php
session_start();
include "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];

    $sql = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION["success_message"] = "User deleted!";
    } else {
        $_SESSION["error_message"] = "Deletion failed.";
    }

    header("Location: admin_users.php");
    exit();
}
?>
