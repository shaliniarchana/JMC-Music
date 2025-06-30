<?php
session_start();
include "../config/database.php";

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null;

    // Fetch current data from the database
    $sql = "SELECT username, email FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Check if the data has changed
    if ($result["username"] == $username && $result["email"] == $email && !$password) {
        // No changes were made
        $_SESSION["error_message"] = "No changes were made. Please update at least one field.";
        header("Location: edit_user.php?id=" . $user_id);
        exit();
    }

    // Prepare and execute the update query if data has changed
    if ($password) {
        // Update with password change
        $sql = "UPDATE users SET username=?, email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $password, $user_id);
    } else {
        // Update without password change
        $sql = "UPDATE users SET username=?, email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $email, $user_id);
    }

    if ($stmt->execute()) {
        // Success message after successful update
        $_SESSION["success_message"] = "User updated successfully!";
    } else {
        // Error message if update fails
        $_SESSION["error_message"] = "Failed to update user. Please try again.";
    }

    // Redirect back to the edit page with the success/error message
    header("Location: edit_user.php?id=" . $user_id);
    exit();
}
?>
