<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
if (!isset($_SESSION['username'])) {
  header('Location: ../../modules/auth/login.php'); exit;
}

require_once __DIR__ . "/../../includes/database.php";
require_once __DIR__ . "/../../includes/funtions.php";

$TenDangNhap = $_SESSION['username'];
$user = getTaiKhoan($TenDangNhap);
$userId = $user['id'];

$yeucauId = (int)($_GET['yeucau_id'] ?? 0);
$phongId = (int)($_GET['phong_id'] ?? 0);

if ($yeucauId <= 0 || $phongId <= 0) {
    $_SESSION['message'] = 'Thông tin không hợp lệ!';
    $_SESSION['message_type'] = 'error';
    header('Location: Profile.php');
    exit;
}

// Kiểm tra yêu cầu thuộc về user này và đang ở trạng thái 2 (đang thuê)
$sqlCheck = "SELECT id FROM yeucauthuetro WHERE id = ? AND nguoi_thue_id = ? AND trang_thai = 2";
$stmtCheck = mysqli_prepare($conn, $sqlCheck);
mysqli_stmt_bind_param($stmtCheck, "ii", $yeucauId, $userId);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);

if (mysqli_num_rows($resultCheck) == 0) {
    $_SESSION['message'] = 'Không tìm thấy yêu cầu hoặc bạn không có quyền trả phòng này!';
    $_SESSION['message_type'] = 'error';
    mysqli_stmt_close($stmtCheck);
    header('Location: Profile.php');
    exit;
}
mysqli_stmt_close($stmtCheck);

// Bắt đầu transaction
mysqli_begin_transaction($conn);

try {
    // 1. Cập nhật trạng thái phòng về 1 (còn trống)
    $sql1 = "UPDATE phongtro SET TrangThai = 1 WHERE id = ? AND TrangThai = 2";
    $stmt1 = mysqli_prepare($conn, $sql1);
    mysqli_stmt_bind_param($stmt1, "i", $phongId);
    mysqli_stmt_execute($stmt1);
    $affected1 = mysqli_stmt_affected_rows($stmt1);
    mysqli_stmt_close($stmt1);

    if ($affected1 <= 0) {
        throw new Exception("Không thể cập nhật trạng thái phòng!");
    }

    // 2. Cập nhật trạng thái yêu cầu thuê trọ thành 4 (đã trả phòng)
    $sql2 = "UPDATE yeucauthuetro SET trang_thai = 4 WHERE id = ? AND trang_thai = 2";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "i", $yeucauId);
    mysqli_stmt_execute($stmt2);
    $affected2 = mysqli_stmt_affected_rows($stmt2);
    mysqli_stmt_close($stmt2);

    if ($affected2 <= 0) {
        throw new Exception("Không thể cập nhật trạng thái yêu cầu!");
    }

    // Commit transaction
    mysqli_commit($conn);
    
    $_SESSION['message'] = 'Trả phòng thành công! Phòng đã được đánh dấu là còn trống.';
    $_SESSION['message_type'] = 'success';
} catch (Exception $e) {
    // Rollback nếu có lỗi
    mysqli_rollback($conn);
    $_SESSION['message'] = 'Lỗi khi trả phòng: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
}

header('Location: Profile.php');
exit;