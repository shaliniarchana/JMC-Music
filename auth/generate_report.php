<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "joy_music";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="all_users_report.csv"');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, ['ID', 'Username', 'Email']);

// Fetch all users
$result = $conn->query("SELECT id, username, email FROM users");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id'], $row['username'], $row['email']]);
}

fclose($output);
exit;
?>
