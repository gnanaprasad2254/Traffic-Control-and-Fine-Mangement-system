<?php
include '../includes/db.php';
require '../vendor/fpdf/fpdf/original/fpdf.php';
$challan_id = isset($_GET['challan_id']) ? intval($_GET['challan_id']) : 0;

if ($challan_id <= 0) {
    die("Invalid challan ID");
}

// Fetch challan details
$query = "SELECT v.*, r.violation_type, r.description 
          FROM violation v 
          JOIN rules r ON v.violation_type = r.rule_id 
          WHERE v.violation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $challan_id);
$stmt->execute();
$result = $stmt->get_result();
$challan = $result->fetch_assoc();

if (!$challan) {
    die("Challan not found");
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'OFFICIAL PAYMENT RECEIPT', 0, 1, 'C');
$pdf->Ln(10);

// Receipt Details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Receipt Number:', 0, 0);
$pdf->Cell(0, 10, 'RCPT-' . str_pad($challan_id, 6, '0', STR_PAD_LEFT), 0, 1);
$pdf->Cell(50, 10, 'Date:', 0, 0);
$pdf->Cell(0, 10, date('d/m/Y H:i:s'), 0, 1);
$pdf->Cell(50, 10, 'Vehicle Number:', 0, 0);
$pdf->Cell(0, 10, $challan['vehicle_number'], 0, 1);
$pdf->Ln(5);

// Payment Details
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Payment Details', 0, 1);
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(50, 10, 'Violation Type:', 0, 0);
$pdf->Cell(0, 10, $challan['violation_type'], 0, 1);
$pdf->Cell(50, 10, 'Description:', 0, 0);
$pdf->MultiCell(0, 10, $challan['description'], 0, 1);
$pdf->Cell(50, 10, 'Location:', 0, 0);
$pdf->Cell(0, 10, $challan['location'], 0, 1);
$pdf->Cell(50, 10, 'Date of Violation:', 0, 0);
$pdf->Cell(0, 10, $challan['created_at'], 0, 1);
$pdf->Ln(5);

// Amount
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(50, 10, 'Amount Paid:', 0, 0);
$pdf->Cell(0, 10, 'Rs. ' . number_format($challan['fine_amount'], 2), 0, 1);
$pdf->Ln(10);

// Footer
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, 'This is an official receipt. Please keep it for your records.', 0, 1, 'C');

// Output the PDF
$pdf->Output('D', 'receipt_'.$challan_id.'.pdf');
$query = "UPDATE violation
          SET status = 'paid'
          WHERE violation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $challan_id);
$stmt->execute();
?>