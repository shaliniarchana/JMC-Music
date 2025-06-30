<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Both fields are required!";
        header("Location: ../login.php");
        exit();
    }
    
    // Check if the login is for admin
    if ($username === "admin" && $password === "admin123") {
        $_SESSION["username"] = "admin";
      
        header("Location: ../admin_all.html");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();
    
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["success"] = "Login successful! Welcome, $username.";

            if ($_SESSION["username"] === "admin") {
                header("Location: ./admin_users.php");
                exit(); 
            } else {
                header("Location: ../HOMEPAGE.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid password!";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found!";
        header("Location: ../login.php");
        exit();
    }
    
    $stmt->close();
    $conn->close();
}
