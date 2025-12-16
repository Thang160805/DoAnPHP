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

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Lấy thông tin yêu cầu
$sql = "SELECT phong_id, nguoi_thue_id FROM yeucauthuetro WHERE id = ? AND trang_thai = 0";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$request = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$request) {
    $_SESSION['error'] = "Không tìm thấy yêu cầu hoặc đã được xử lý!";
    header("Location: index.php");
    exit;
}

$phongId = (int)$request['phong_id'];
$nguoiThueId = (int)$request['nguoi_thue_id'];

// Bắt đầu transaction
mysqli_begin_transaction($conn);

try {
    // 1. Cập nhật trạng thái yêu cầu được duyệt thành 2 (thành công)
    $sql1 = "UPDATE yeucauthuetro SET trang_thai = 2 WHERE id = ? AND trang_thai = 0";
    $stmt1 = mysqli_prepare($conn, $sql1);
    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);
    $affected1 = mysqli_stmt_affected_rows($stmt1);
    mysqli_stmt_close($stmt1);

    if ($affected1 <= 0) {
        throw new Exception("Không thể cập nhật trạng thái yêu cầu!");
    }

    // 2. Cập nhật trạng thái phòng sang 2 (đã thuê)
    $sql2 = "UPDATE phongtro SET TrangThai = 2 WHERE id = ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "i", $phongId);
    mysqli_stmt_execute($stmt2);
    $affected2 = mysqli_stmt_affected_rows($stmt2);
    mysqli_stmt_close($stmt2);

    if ($affected2 <= 0) {
        throw new Exception("Không thể cập nhật trạng thái phòng!");
    }

    // 3. Từ chối tất cả các yêu cầu khác cho cùng phòng (trạng thái 3 - không thành công)
    $sql3 = "UPDATE yeucauthuetro SET trang_thai = 3 WHERE phong_id = ? AND id != ? AND trang_thai = 0";
    $stmt3 = mysqli_prepare($conn, $sql3);
    mysqli_stmt_bind_param($stmt3, "ii", $phongId, $id);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_close($stmt3);

    // Commit transaction
    mysqli_commit($conn);
    
    $_SESSION['success'] = "Đã duyệt yêu cầu thuê trọ thành công!";
} catch (Exception $e) {
    // Rollback nếu có lỗi
    mysqli_rollback($conn);
    $_SESSION['error'] = "Lỗi khi duyệt yêu cầu: " . $e->getMessage();
}

header("Location: index.php");
exit;