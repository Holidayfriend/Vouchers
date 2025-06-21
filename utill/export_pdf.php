<?php
// Include Composer autoloader
require_once 'vendor/autoload.php';
require_once '../util_config.php';

// Use the TCPDF namespace with alias
use TCPDF as TCPDF;

// Fetch 'holder' value from the URL
$holder = $_GET['holder'];
$name = $_GET['name'];

// Your SQL query
$sql = "SELECT * FROM `tbl_user` WHERE foreign_id = $holder AND user_type = 'EMOLOYEE' AND is_delete = 0 AND status = 'ACTIVE'";

// Execute the query
$result = $conn->query($sql);
$name = $name.' '.'Employee';
// Check if the query was successful
if ($result) {
    // Check if there are rows returned
    if ($result->num_rows > 0) {
        // Create a PDF instance
        $pdf = new TCPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($name);

        // Enable auto page break
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font for the title
        $pdf->SetFont('helvetica', 'B', 20);

        // Title at the top
        $pdf->Cell(0, 10, $name, 0, 1, 'C');
        $pdf->Ln(10); // Add some space

        // Set font back to normal
        $pdf->SetFont('helvetica', '', 8);

        // Header row
        $pdf->Cell(20, 10, 'Name', 1);
        $pdf->Cell(40, 10, 'Email', 1);
        $pdf->Cell(30, 10, 'Phone', 1);
        $pdf->Cell(25, 10, 'Contract', 1);
        $pdf->Cell(30, 10, 'Expiry date', 1);
        $pdf->Cell(40, 10, 'Tax Number', 1);
        $pdf->Ln(); // Move to the next line

        // Iterate through the result set and add data to the table
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['name'], 1);
            $pdf->Cell(40, 10, $row['email'], 1);
            $pdf->Cell(30, 10, $row['person_phone'], 1);
            $pdf->Cell(25, 10, $row['subcription'], 1);
            $pdf->Cell(30, 10, $row['expiry'], 1);
            $pdf->Cell(40, 10, $row['tax_number'], 1);
            $pdf->Ln(); // Move to the next line
        }

        // Output the PDF to the browser
        $pdf->Output($name.'.pdf', 'D'); // 'D' for download
    } else {
        $pdf = new TCPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($name);

        // Enable auto page break
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font for the title
        $pdf->SetFont('helvetica', 'B', 20);

        // Title at the top
        $pdf->Cell(0, 10, $name, 0, 1, 'C');
        $pdf->Ln(10); // Add some space
        // No user found with the given 'holder' value
        $pdf->Output($name.'.pdf', 'D'); // 'D' for download
    }
} else {
    // Query execution failed
    echo 'Error executing the query: ' . $conn->error;
}

// Close the database connection
$conn->close();
?>
