<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../../../includes/database.php";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Cập nhật trạng thái yêu cầu thành từ chối (3 - không thành công)
$sql = "UPDATE yeucauthuetro SET trang_thai = 3 WHERE id = ? AND trang_thai = 0";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['success'] = "Đã từ chối yêu cầu thuê trọ!";
} else {
    $_SESSION['error'] = "Không tìm thấy yêu cầu hoặc đã được xử lý!";
}

mysqli_stmt_close($stmt);
header("Location: index.php");
exit;

