<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../helper/conn.php';

$q = trim($_GET['q'] ?? '');
$out = [];
if ($q === '') {
    // return recent / first 20
    $stmt = $conn->prepare('SELECT resident_id, resident_firstname, resident_middlename, resident_lastname, resident_purok, resident_birthdate FROM residents ORDER BY resident_lastname ASC LIMIT 20');
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc())
        $out[] = $r;
    $stmt->close();
    echo json_encode($out);
    exit;
}

$like = "%$q%";
$sql = 'SELECT resident_id, resident_firstname, resident_middlename, resident_lastname, resident_purok, resident_birthdate FROM residents WHERE (resident_firstname LIKE ? OR resident_middlename LIKE ? OR resident_lastname LIKE ? OR resident_purok LIKE ?) ORDER BY resident_lastname ASC LIMIT 50';
$stmt = $conn->prepare($sql);
$l1 = $like;
$l2 = $like;
$l3 = $like;
$l4 = $like;
$stmt->bind_param('ssss', $l1, $l2, $l3, $l4);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc())
    $out[] = $r;
$stmt->close();

echo json_encode($out);
exit;

?>