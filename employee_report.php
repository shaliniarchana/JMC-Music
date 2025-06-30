<?php
// Include TCPDF library
require_once(__DIR__ . '/tpdf/TCPDF-main/tcpdf.php');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "joy_music");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Allowed columns and orders for sorting
$allowed_columns = ['id', 'name', 'contact', 'address', 'email', 'role'];
$allowed_orders = ['ASC', 'DESC'];

// Check if sorting parameters are valid, else use default
$sort_column = isset($_GET['sort_by']) && in_array($_GET['sort_by'], $allowed_columns) ? $_GET['sort_by'] : 'id';
$sort_order = isset($_GET['order']) && in_array(strtoupper($_GET['order']), $allowed_orders) ? strtoupper($_GET['order']) : 'ASC';

// SQL query to get employee data sorted
$sql = "SELECT * FROM employees ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

// Create a new PDF document
$pdf = new TCPDF();
$pdf->AddPage();

// Register and use a custom font (Roboto)
$fontPath = __DIR__ . '/tpdf/TCPDF-main/fonts/Roboto-Regular.ttf'; 
if (!file_exists($fontPath)) {
    die("Font file not found: $fontPath");
}
$fontname = TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode', '', 96);
$pdf->SetFont($fontname, '', 12);

// Set the title of the PDF
$pdf->SetTextColor(15, 5, 132); 
$pdf->SetFont($fontname, 'B', 16);
$pdf->Cell(0, 10, 'Employee Report - Joy Music Corner', 0, 1, 'C');
$pdf->Ln(5); // Line break

// Set table header style
$pdf->SetFont($fontname, 'B', 12);
$pdf->SetFillColor(107, 59, 227); // Background color for header
$pdf->SetTextColor(255, 255, 255); // White text color

// Table headers and column widths
$header = ['ID', 'Name', 'Contact', 'Address', 'Email', 'Role'];
$widths = [10, 30, 30, 40, 45, 35];

// Print the table header
foreach ($header as $key => $col) {
    $pdf->Cell($widths[$key], 10, $col, 1, 0, 'C', true);
}
$pdf->Ln(); // New line after header

// Set style for table content
$pdf->SetFont($fontname, '', 12);
$pdf->SetTextColor(0, 0, 0); // Black text

// Print each row from the database
while ($row = $result->fetch_assoc()) {
    // Store row values
    $values = [
        $row['id'],
        $row['name'],
        $row['contact'],
        $row['address'],
        $row['email'],
        $row['role']
    ];

    $lineHeight = 6; // Height of one line
    $maxLines = 1;

    // Check how many lines are needed for each cell
    foreach ($values as $i => $val) {
        $lines = $pdf->getNumLines($val, $widths[$i]);
        if ($lines > $maxLines) {
            $maxLines = $lines;
        }
    }

    $rowHeight = $lineHeight * $maxLines;

    // Print the row with calculated height
    foreach ($values as $i => $val) {
        $pdf->MultiCell($widths[$i], $rowHeight, $val, 1, 'L', false, 0, '', '', true, 0, false, true, $rowHeight, 'M');
    }
    $pdf->Ln(); // New line after row
}

// Download the PDF file
$pdf->Output('Employee_Report.pdf', 'D');
?>
