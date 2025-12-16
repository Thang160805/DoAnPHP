<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
if (!isset($_SESSION['username'])) {
  header('Location: ../../modules/auth/login.php'); exit;
}
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../../../includes/database.php";

$id     = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

/* XÁC ĐỊNH HÀNH ĐỘNG */
if ($action === 'hide') {
    // Ẩn phòng
    $newStatus = 2;
} elseif ($action === 'show') {
    // Hiện lại → đã duyệt
    $newStatus = 1;
} else {
    header("Location: index.php");
    exit;
}

/* UPDATE */
$sql = "UPDATE phongtro SET TrangThai = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $newStatus, $id);
mysqli_stmt_execute($stmt);

header("Location: index.php");
exit;