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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Profile.php');
    exit;
}

/* ========================
   1. LẤY & VALIDATE DATA
   ======================== */

$HoTen  = trim($_POST['HoTen'] ?? '');
$Phone  = trim($_POST['Phone'] ?? '');
$DiaChi = trim($_POST['DiaChi'] ?? '');

// Validate bắt buộc
if ($HoTen === '') {
    $_SESSION['message'] = 'Vui lòng nhập đầy đủ họ tên!';
    $_SESSION['message_type'] = 'error';
    header('Location: Profile.php');
    exit;
}


if ($Phone === '') {
    $_SESSION['message'] = 'Vui lòng nhập số điện thoại!';
    $_SESSION['message_type'] = 'error';
    header('Location: Profile.php');
    exit;
}


if (!preg_match('/^[0-9]{10}$/', $Phone)) {
    $_SESSION['message'] = 'Số điện thoại phải gồm đúng 10 chữ số và không chứa ký tự!';
    $_SESSION['message_type'] = 'error';
    header('Location: Profile.php');
    exit;
}

if ($DiaChi === '') {
    $_SESSION['message'] = 'Vui lòng nhập địa chỉ!';
    $_SESSION['message_type'] = 'error';
    header('Location: Profile.php');
    exit;
}


$data = [
    'HoTen'  => $HoTen,
    'Phone'  => $Phone,
    'DiaChi' => $DiaChi,
];


$uploadDir = __DIR__ . '/../../template/assets/img/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (
    isset($_FILES['Avatar']) &&
    $_FILES['Avatar']['error'] === UPLOAD_ERR_OK
) {
    $tmpName  = $_FILES['Avatar']['tmp_name'];
    $fileSize = $_FILES['Avatar']['size'];
    $ext      = strtolower(pathinfo($_FILES['Avatar']['name'], PATHINFO_EXTENSION));

    if (!getimagesize($tmpName)) {
        $_SESSION['message'] = 'File upload không phải ảnh!';
        $_SESSION['message_type'] = 'error';
        header('Location: Profile.php');
        exit;
    }

    if ($fileSize > 2 * 1024 * 1024) {
        $_SESSION['message'] = 'Ảnh đại diện tối đa 2MB!';
        $_SESSION['message_type'] = 'error';
        header('Location: Profile.php');
        exit;
    }

    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowedExt)) {
        $_SESSION['message'] = 'Chỉ cho phép ảnh jpg, jpeg, png, gif!';
        $_SESSION['message_type'] = 'error';
        header('Location: Profile.php');
        exit;
    }

    $newFileName = 'avatar_' . $TenDangNhap . '_' . time() . '.' . $ext;
    $uploadPath  = $uploadDir . $newFileName;

    if (!move_uploaded_file($tmpName, $uploadPath)) {
        $_SESSION['message'] = 'Upload ảnh thất bại!';
        $_SESSION['message_type'] = 'error';
        header('Location: Profile.php');
        exit;
    }

    if (!empty($user['Avatar'])) {
        $oldAvatarPath = $uploadDir . $user['Avatar'];
        if (file_exists($oldAvatarPath)) {
            unlink($oldAvatarPath);
        }
    }

    $data['Avatar'] = $newFileName;
}


if (updateTaiKhoan($TenDangNhap, $data)) {
    $_SESSION['message'] = 'Cập nhật thông tin thành công!';
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = 'Cập nhật thất bại!';
    $_SESSION['message_type'] = 'error';
}

header('Location: Profile.php');
exit;

?>