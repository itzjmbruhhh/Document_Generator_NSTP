<?php
require_once __DIR__ . '/../helper/conn.php';

$request_id = (int) ($_GET['request_id'] ?? 0);
$download = isset($_GET['download']) && ($_GET['download'] === '1' || $_GET['download'] === 'true');

if (!$request_id) {
    http_response_code(400);
    echo 'Missing request_id';
    exit;
}

$stmt = $conn->prepare('SELECT r.generated_document, r.document_type, res.resident_firstname, res.resident_middlename, res.resident_lastname FROM requests r LEFT JOIN residents res ON res.resident_id = r.resident_id WHERE r.request_id = ? LIMIT 1');
$stmt->bind_param('i', $request_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    http_response_code(404);
    echo 'Not found';
    exit;
}
$stmt->bind_result($blob, $docType, $first, $middle, $last);
$stmt->fetch();
$stmt->close();

if ($blob === null) {
    http_response_code(404);
    echo 'No file stored';
    exit;
}

$fullname = trim($first . ' ' . ($middle ? $middle . ' ' : '') . $last);
$safeDoc = preg_replace('/[^A-Za-z0-9_\\-]/', '_', str_replace(' ', '_', $docType));
$safeName = preg_replace('/[^A-Za-z0-9_\\-]/', '_', str_replace(' ', '_', $fullname));
$filename = $safeDoc . '_' . $safeName . '.pdf';

header('Content-Type: application/pdf');
if ($download)
    header('Content-Disposition: attachment; filename="' . $filename . '"');
else
    header('Content-Disposition: inline; filename="' . $filename . '"');

echo $blob;
exit;

?>