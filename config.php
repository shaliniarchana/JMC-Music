<?php
$servername = "localhost";
$username = "root";  
$password = "";     
$dbname = "joy_music"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
