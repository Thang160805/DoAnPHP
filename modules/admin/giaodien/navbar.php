<?php
require_once __DIR__ . "/../auth.php";

$username = $_SESSION['username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>
<style>
body{
    margin:0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background:#f4f6f9;
}
.wrapper{
    display:flex;
}
.sidebar{
    width:240px;
    background:linear-gradient(180deg,#1e293b,#0f172a);
    color:#fff;
    min-height:100vh;
    padding-top:10px;
}
.sidebar h2{
    text-align:center;
    padding:15px 10px;
    margin:0;
    font-size:22px;
}
.sidebar .hello{
    text-align:center;
    font-size:14px;
    color:#cbd5f5;
    margin-bottom:15px;
}
.sidebar a{
    display:block;
    padding:12px 18px;
    color:#e5e7eb;
    text-decoration:none;
    font-size:15px;
}
.sidebar a:hover,
.sidebar a.active{
    background:#4f46e5;
    color:#fff;
    border-radius:6px;
    margin:0 10px;
}
.content{
    flex:1;
    padding:25px;
}
.logout{
    background:#7c2d12;
    margin:15px 10px;
    border-radius:6px;
    text-align:center;
}
.logout:hover{
    background:#9a3412;
}
</style>
</head>

<body>
<div class="wrapper">
<div class="sidebar">
    <h2>👑 ADMIN</h2>

    <div class="hello">
        Xin chào,<br>
        <strong><?= htmlspecialchars($username) ?></strong>
    </div>

    <a href="/ThuNghiem/modules/admin/index.php">🏠 Dashboard</a>
    <a href="/ThuNghiem/modules/admin/rooms/index.php">🏘 Quản lý phòng</a>
    <a href="/ThuNghiem/modules/admin/users/index.php">👤 Quản lý admin</a>
    <a href="/ThuNghiem/modules/admin/requests/index.php">📋 Yêu cầu thuê trọ</a>

   <a href="/ThuNghiem/modules/admin/logout.php"
   onclick="return confirm('Bạn có chắc chắn muốn đăng xuất admin?');">
   🚪 Đăng xuất
</a>
</div>

<div class="content">