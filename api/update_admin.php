<?php
require_once __DIR__ . '/../helper/conn.php';
require_once __DIR__ . '/../helper/auth.php';
require_once __DIR__ . '/../helper/config.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE)
    session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$name = $_POST['admin_name'] ?? '';
$password = $_POST['admin_password'] ?? '';

$photo_url = null;

// handle photo upload
if (!empty($_FILES['admin_photo'])) {
    $f = $_FILES['admin_photo'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $errMap = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds server upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form max file size',
            UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        ];
        $msg = $errMap[$f['error']] ?? ('Upload error code: ' . $f['error']);
        echo json_encode(['success' => false, 'message' => $msg, 'error_code' => $f['error']]);
        exit;
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = @mime_content_type($f['tmp_name']);
    if (!isset($allowed[$mime])) {
        echo json_encode(['success' => false, 'message' => 'Unsupported image type: ' . ($mime ?: 'unknown')]);
        exit;
    }
    $ext = $allowed[$mime];
    $uploadsDir = __DIR__ . '/../uploads/admin_photos';
    if (!is_dir($uploadsDir)) {
        if (!mkdir($uploadsDir, 0755, true)) {
            echo json_encode(['success' => false, 'message' => 'Failed to create uploads directory', 'path' => $uploadsDir]);
            exit;
        }
    }
    if (!is_writable($uploadsDir)) {
        echo json_encode(['success' => false, 'message' => 'Uploads directory not writable', 'path' => $uploadsDir]);
        exit;
    }

    if (!is_uploaded_file($f['tmp_name'])) {
        echo json_encode(['success' => false, 'message' => 'Temporary upload file is missing or invalid', 'tmp_name' => $f['tmp_name']]);
        exit;
    }

    $filename = $admin_id . '_' . time() . '.' . $ext;
    $destPath = $uploadsDir . '/' . $filename;
    if (!move_uploaded_file($f['tmp_name'], $destPath)) {
        $err = error_get_last();
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file', 'tmp' => $f['tmp_name'], 'dest' => $destPath, 'php_err' => $err]);
        exit;
    }
    // store web-accessible path
    $photo_url = rtrim(BASE_URL, '/') . '/uploads/admin_photos/' . $filename;
}

// Build update query
$fields = [];
$types = '';
$params = [];
if ($name !== '') {
    $fields[] = 'admin_name = ?';
    $types .= 's';
    $params[] = $name;
}
if ($password !== '') {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $fields[] = 'admin_password = ?';
    $types .= 's';
    $params[] = $hash;
}
if ($photo_url !== null) {
    $fields[] = 'admin_photo = ?';
    $types .= 's';
    $params[] = $photo_url;
}

if (count($fields) === 0) {
    echo json_encode(['success' => false, 'message' => 'No changes submitted']);
    exit;
}

$sql = 'UPDATE admin SET ' . implode(', ', $fields) . ' WHERE admin_id = ?';
$types .= 'i';
$params[] = $admin_id;

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'DB prepare failed: ' . $conn->error]);
    exit;
}

$bind_names[] = $types;
for ($i = 0; $i < count($params); $i++) {
    $bind_name = 'bind' . $i;
    $$bind_name = $params[$i];
    $bind_names[] = &$$bind_name;
}
call_user_func_array([$stmt, 'bind_param'], $bind_names);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'DB execute failed: ' . $stmt->error]);
    exit;
}

// update session values
if ($name !== '')
    $_SESSION['admin_name'] = $name;
if ($photo_url !== null)
    $_SESSION['admin_photo'] = $photo_url;

echo json_encode(['success' => true, 'admin_name' => $_SESSION['admin_name'] ?? null, 'admin_photo' => $_SESSION['admin_photo'] ?? null]);
exit;

?>