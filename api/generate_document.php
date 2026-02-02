<?php
require_once __DIR__ . '/../helper/conn.php';
require_once __DIR__ . '/../lib/fpdf.php';

$doc = trim($_GET['doc'] ?? '');
$resident_id = (int) ($_GET['resident_id'] ?? 0);

if (!$doc || !$resident_id) {
    http_response_code(400);
    echo 'Missing parameters';
    exit;
}

$stmt = $conn->prepare('SELECT resident_firstname, resident_middlename, resident_lastname, resident_purok, resident_birthdate FROM residents WHERE resident_id = ? LIMIT 1');
$stmt->bind_param('i', $resident_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    echo 'Resident not found';
    exit;
}

$fullname = trim($row['resident_firstname'] . ' ' . ($row['resident_middlename'] ? $row['resident_middlename'] . ' ' : '') . $row['resident_lastname']);
$purok = $row['resident_purok'];
$birth = $row['resident_birthdate'];
// purpose/notes passed from document step
$purpose = trim($_GET['notes'] ?? $_GET['purpose'] ?? '');

// compute age
$age = '';
if ($birth) {
    try {
        $b = new DateTime($birth);
        $today = new DateTime();
        $age = $today->diff($b)->y;
    } catch (Exception $e) {
        $age = '';
    }
}

// Compose content variations based on $doc
$titleMap = [
    'barangay_clearance' => 'Barangay Clearance',
    'business_permit' => 'Business Permit',
    'certificate_residency' => 'Certificate of Residency',
    'indigency' => 'Indigency Certification',
    'no_low_income' => 'No Income / Low Income Statement'
];
$title = $titleMap[$doc] ?? ucfirst(str_replace('_', ' ', $doc));

// generate PDF in-memory
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Compose formatted paragraph
$today = (new DateTime())->format('F j, Y');
$docLabel = $title;
$purposeText = $purpose ?: 'general purpose';
$sentence = "This is to certify {$fullname} to get {$docLabel}, on this day {$today} for the purpose of {$purposeText}.";

// Layout lines top-down: PDF coordinates y decreases from top (approx 800 points)
$x = 50; // left margin in points
$y = 780; // starting y position
$pdf->SetFont('Arial', '', 14);
$pdf->Text($x, $y, $docLabel);
$y -= 22;
$pdf->SetFont('Arial', '', 12);
$pdf->Text($x, $y, 'Name: ' . $fullname);
$y -= 16;
$pdf->Text($x, $y, 'Purok: ' . $purok);
$y -= 16;
$pdf->Text($x, $y, 'Birthdate: ' . $birth . '   Age: ' . $age);
$y -= 22;

// split sentence into wrapped lines ~80 chars per line
$wrapped = wordwrap($sentence, 80, "\n");
foreach (explode("\n", $wrapped) as $line) {
    $pdf->Text($x, $y, $line);
    $y -= 14;
}

// If download requested, send Content-Disposition with filename
$download = isset($_GET['download']) && ($_GET['download'] === '1' || $_GET['download'] === 'true');
$pdfData = $pdf->Output('', 'S');
if ($download) {
    // build filename: <DocumentType>_<Name>.pdf, sanitize
    $safeDoc = preg_replace('/[^A-Za-z0-9_\-]/', '_', str_replace(' ', '_', $docLabel));
    $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', str_replace(' ', '_', $fullname));
    $filename = $safeDoc . '_' . $safeName . '.pdf';
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $pdfData;
    exit;
}

// default: inline preview
header('Content-Type: application/pdf');
echo $pdfData;
exit;

?>