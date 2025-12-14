<?php
session_start();

// Chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header('Location: ../../modules/auth/login.php');
    exit;
}

require_once __DIR__ . "/../../includes/database.php";
require_once __DIR__ . "/../../includes/funtions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Profile.php');
    exit;
}

$TenDangNhap = $_SESSION['username'];

/* ========================
   1. LẤY DỮ LIỆU
   ======================== */

$oldPass     = $_POST['oldpass'] ?? '';
$newPass     = $_POST['newpass'] ?? '';
$confirmPass = $_POST['confirmpass'] ?? '';

/* ========================
   2. VALIDATE CƠ BẢN
   ======================== */

// Không được rỗng
if ($oldPass === '' || $newPass === '' || $confirmPass === '') {
    $_SESSION['message'] = 'Vui lòng nhập đầy đủ thông tin!';
    $_SESSION['message_type'] = 'error';
    $_SESSION['active_tab'] = 'doi-mat-khau';
    header('Location: Profile.php');
    exit;
}

// Mật khẩu mới tối thiểu 6 ký tự
if (strlen($newPass) < 6) {
    $_SESSION['message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
    $_SESSION['message_type'] = 'error';
    $_SESSION['active_tab'] = 'doi-mat-khau';
    header('Location: Profile.php');
    exit;
}

// Mật khẩu xác nhận không khớp
if ($newPass !== $confirmPass) {
    $_SESSION['message'] = 'Xác nhận mật khẩu không khớp!';
    $_SESSION['message_type'] = 'error';
    $_SESSION['active_tab'] = 'doi-mat-khau';
    header('Location: Profile.php');
    exit;
}

/* ========================
   3. KIỂM TRA MẬT KHẨU CŨ
   ======================== */

if (!checkPassword($TenDangNhap, $oldPass)) {
    $_SESSION['message'] = 'Mật khẩu cũ không đúng!';
    $_SESSION['message_type'] = 'error';
    $_SESSION['active_tab'] = 'doi-mat-khau';
    header('Location: Profile.php');
    exit;
}

/* ========================
   4. HASH & UPDATE MẬT KHẨU
   ======================== */

$newHash = password_hash($newPass, PASSWORD_DEFAULT);

$sql = "UPDATE TaiKhoan SET MatKhau = ? WHERE TenDangNhap = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['message'] = 'Lỗi hệ thống!';
    $_SESSION['message_type'] = 'error';
    $_SESSION['active_tab'] = 'doi-mat-khau';
    header('Location: Profile.php');
    exit;
}

mysqli_stmt_bind_param($stmt, 'ss', $newHash, $TenDangNhap);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['message'] = 'Đổi mật khẩu thành công!';
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = 'Đổi mật khẩu thất bại!';
    $_SESSION['message_type'] = 'error';
}

$_SESSION['active_tab'] = 'doi-mat-khau';

mysqli_stmt_close($stmt);

header('Location: Profile.php');
exit;