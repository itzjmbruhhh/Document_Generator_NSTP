<?php
require_once __DIR__ . '/../helper/conn.php';
require_once __DIR__ . '/../lib/fpdf.php';

// ensure we always return JSON; enable errors during troubleshooting
ini_set('display_errors', '1');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

try {
    $doc = trim($_POST['doc'] ?? '');
    $resident_id = (int) ($_POST['resident_id'] ?? 0);
    $purpose = trim($_POST['notes'] ?? $_POST['purpose'] ?? '');

    if (!$doc || !$resident_id) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Missing parameters']);
        exit;
    }

    // fetch resident
    $stmt = $conn->prepare('SELECT resident_firstname, resident_middlename, resident_lastname, resident_purok, resident_birthdate FROM residents WHERE resident_id = ? LIMIT 1');
    $stmt->bind_param('i', $resident_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if (!$row) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Resident not found']);
        exit;
    }

    $fullname = trim($row['resident_firstname'] . ' ' . ($row['resident_middlename'] ? $row['resident_middlename'] . ' ' : '') . $row['resident_lastname']);
    $purok = $row['resident_purok'];
    $birth = $row['resident_birthdate'];

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

    $titleMap = [
        'barangay_clearance' => 'Barangay Clearance',
        'business_permit' => 'Business Permit',
        'certificate_residency' => 'Certificate of Residency',
        'indigency' => 'Indigency Certification',
        'no_low_income' => 'No Income / Low Income Statement'
    ];
    $title = $titleMap[$doc] ?? ucfirst(str_replace('_', ' ', $doc));

    // build PDF into string
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $today = (new DateTime())->format('F j, Y');
    $sentence = "This is to certify {$fullname} to get {$title}, on this day {$today} for the purpose of " . ($purpose ?: 'general purpose') . '.';
    $x = 50;
    $y = 780;
    $pdf->SetFont('Arial', '', 14);
    $pdf->Text($x, $y, $title);
    $y -= 22;
    $pdf->SetFont('Arial', '', 12);
    $pdf->Text($x, $y, 'Name: ' . $fullname);
    $y -= 16;
    $pdf->Text($x, $y, 'Purok: ' . $purok);
    $y -= 16;
    $pdf->Text($x, $y, 'Birthdate: ' . $birth . '   Age: ' . $age);
    $y -= 22;
    $wrapped = wordwrap($sentence, 80, "\n");
    foreach (explode("\n", $wrapped) as $line) {
        $pdf->Text($x, $y, $line);
        $y -= 14;
    }

    $pdfData = $pdf->Output('', 'S');

    if ($pdfData === null) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Failed to generate PDF']);
        exit;
    }

    $now = (new DateTime())->format('Y-m-d H:i:s');

    // insert into requests table with blob
    $insert = $conn->prepare('INSERT INTO requests (resident_id, purpose, document_type, generated_document, request_date_time) VALUES (?, ?, ?, ?, ?)');
    if (!$insert) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    // bind parameters directly; use 's' for blob data
    if (!$insert->bind_param('issss', $resident_id, $purpose, $title, $pdfData, $now)) {
        throw new Exception('bind_param failed: ' . $insert->error);
    }

    if (!$insert->execute()) {
        $err = $insert->error ?: $conn->error;
        $insert->close();
        // If the table schema lacks AUTO_INCREMENT on request_id, try fallback inserting with a generated id
        if (stripos($err, 'request_id') !== false && stripos($err, "default value") !== false) {
            // generate a reasonably unique integer id using current time and random
            $fallback_id = (int) time();
            // ensure fallback_id fits 32-bit signed int
            if ($fallback_id > 2147483646)
                $fallback_id = $fallback_id % 2147483646;

            $insert2 = $conn->prepare('INSERT INTO requests (request_id, resident_id, purpose, document_type, generated_document, request_date_time) VALUES (?, ?, ?, ?, ?, ?)');
            if (!$insert2)
                throw new Exception('Fallback prepare failed: ' . $conn->error);
            if (!$insert2->bind_param('iissss', $fallback_id, $resident_id, $purpose, $title, $pdfData, $now)) {
                throw new Exception('Fallback bind_param failed: ' . $insert2->error);
            }
            if (!$insert2->execute()) {
                $e2 = $insert2->error ?: $conn->error;
                $insert2->close();
                throw new Exception('Fallback insert failed: ' . $e2);
            }
            $inserted_id = $fallback_id;
            $insert2->close();
            echo json_encode(['ok' => true, 'request_id' => $inserted_id, 'note' => 'used_fallback_request_id']);
            exit;
        }
        throw new Exception('DB insert failed: ' . $err);
    }

    $inserted_id = $conn->insert_id;
    $insert->close();

    echo json_encode(['ok' => true, 'request_id' => $inserted_id]);
    exit;

} catch (Throwable $e) {
    // ensure no extra output corrupts JSON
    while (ob_get_level())
        ob_end_clean();
    http_response_code(500);
    $msg = $e->getMessage();
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}

?>