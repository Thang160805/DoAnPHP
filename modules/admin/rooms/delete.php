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

/* 1️⃣ Xóa tiện ích phòng trước */
$sql1 = "DELETE FROM phongtroutilities WHERE phongtro_id = ?";
$stmt1 = mysqli_prepare($conn, $sql1);
mysqli_stmt_bind_param($stmt1, "i", $id);
mysqli_stmt_execute($stmt1);

/* 2️⃣ Xóa phòng */
$sql2 = "DELETE FROM phongtro WHERE id = ?";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);

header("Location: index.php");
exit;