<?php
session_start();

/* Xóa session admin */
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin']);

/* Chống cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* Chuyển về trang login admin */
header("Location: /ThuNghiem/modules/auth/login.php");
exit;