<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../helper/conn.php';

$resident_id = (int) ($_GET['resident_id'] ?? 0);
if (!$resident_id) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare('SELECT request_id, document_type, purpose, request_date_time FROM requests WHERE resident_id = ? ORDER BY request_date_time DESC');
$stmt->bind_param('i', $resident_id);
$stmt->execute();
$res = $stmt->get_result();
$out = [];
while ($r = $res->fetch_assoc()) {
    $out[] = $r;
}
$stmt->close();

echo json_encode($out);
exit;

?>