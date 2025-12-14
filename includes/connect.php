<?php
$host = 'localhost';
$port = '3307';
$dbname = 'qlpt';
$username = 'root';
$password = '';

// Kết nối MySQLi (thủ tục)
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Kiểm tra kết nối
if (!$conn) {
    die('❌ Lỗi kết nối CSDL: ' . mysqli_connect_error());
}

// Set charset (rất quan trọng để tránh lỗi tiếng Việt)
mysqli_set_charset($conn, 'utf8mb4');
?>