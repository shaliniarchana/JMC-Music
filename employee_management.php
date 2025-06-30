<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "joy_music";

// Connect to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// If connection fails, return error in JSON format
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Set the response type to JSON
header('Content-Type: application/json');

// If a POST request is made and action is given
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Get and clean the input data
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Add new employee
    if ($action === "add") {
        $stmt = $conn->prepare("INSERT INTO employees (name, contact, address, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $contact, $address, $email, $role);
        $stmt->execute();
        echo json_encode(["message" => "Employee added"]);
    } 
    // Update existing employee
    elseif ($action === "update" && $id !== null) {
        $stmt = $conn->prepare("UPDATE employees SET 
            name = COALESCE(NULLIF(?, ''), name),
            contact = COALESCE(NULLIF(?, ''), contact),
            address = COALESCE(NULLIF(?, ''), address),
            email = COALESCE(NULLIF(?, ''), email),
            role = COALESCE(NULLIF(?, ''), role)
            WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $contact, $address, $email, $role, $id);
        $stmt->execute();
        echo json_encode(["message" => "Employee updated"]);
    } 
    // Delete employee by ID
    elseif ($action === "delete" && $id !== null) {
        $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["message" => "Employee deleted"]);
    } 
    // Delete all employees
    elseif ($action === "deleteAll") {
        $conn->query("DELETE FROM employees");
        echo json_encode(["message" => "All employees deleted"]);
    } 
    // Invalid action or missing ID
    else {
        echo json_encode(["error" => "Invalid action or missing ID"]);
    }

    // End script after handling POST
    exit();
}

// If fetch is requested using GET
if (isset($_GET['fetch'])) {
    // Check sort order, default is DESC
    $sort = isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'ASC' : 'DESC';

    // Get all employee records sorted by ID
    $result = $conn->query("SELECT * FROM employees ORDER BY id $sort");

    // Store results in an array
    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    // Return employee data in JSON format
    echo json_encode($employees);
    exit();
}
?>
