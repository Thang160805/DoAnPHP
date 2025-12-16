<?php
session_start();

unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin']);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

header("Location: /CaseStudy/modules/auth/login.php");
exit;