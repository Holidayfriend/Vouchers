<?php
// Make sure to include the PhpSpreadsheet library
require 'Xvendor/autoload.php'; // Replace with the correct path to the library

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Replace the database connection details with your own
require_once '../util_config.php';


// Get the page number, rows per page, and search text from POST data
$holder = isset($_POST['id']) ? intval($_POST['id']) : 0;

$name_is = 'Company ';
if(isset($_POST['name_is'])){
    $name_is=$_POST['name_is']; 
}



$sql = "SELECT * FROM `tbl_user` WHERE `user_type` = 'EMOLOYEE' AND `is_delete` = 0 AND status = 'ACTIVE' AND foreign_id = $holder ";


$result = $conn->query($sql);

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'surname');
$sheet->setCellValue('C1', 'email');
$sheet->setCellValue('D1', 'person_phone');
$sheet->setCellValue('E1', 'subcription');
$sheet->setCellValue('F1', 'expiry');
$sheet->setCellValue('G1', 'tax_number');

// Populate the spreadsheet with data from the database
$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['name']);
    $sheet->setCellValue('B' . $row, $data['surname']);
    $sheet->setCellValue('C' . $row, $data['email']);
    $sheet->setCellValue('D' . $row, $data['person_phone']);
    $sheet->setCellValue('E' . $row, $data['subcription']);
    $sheet->setCellValue('F' . $row, $data['expiry']);
    $sheet->setCellValue('G' . $row, $data['tax_number']);
    $row++;
}

// Save the spreadsheet as a file
$writer = new Xlsx($spreadsheet);
$filename = $name_is.'_employees.xlsx';
$writer->save($filename);

// Send the file to the user for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
readfile($filename);

// Delete the file from the server
unlink($filename);

// Close the database connection

