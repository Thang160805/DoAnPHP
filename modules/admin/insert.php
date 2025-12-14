<?php
include __DIR__ . "/../../includes/connect.php";

$tenDangNhap = "admin";
$matKhauGoc  = "123";
$matKhauHash = password_hash($matKhauGoc, PASSWORD_DEFAULT);

$hoTen   = "Quản trị viên";
$email   = "admin@gmail.com";
$phone   = "0123456789";
$diaChi  = "Nghi Lộc";
$vaitro  = 0;
$trangthai = 1;

$sql = "INSERT INTO taikhoan
        (TenDangNhap, MatKhau, HoTen, Email, Phone, DiaChi, NgayTao, TrangThai, Vaitro)
        VALUES
        (:tendangnhap, :matkhau, :hoten, :email, :phone, :diachi, NOW(), :trangthai, :vaitro)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':tendangnhap' => $tenDangNhap,
    ':matkhau'     => $matKhauHash,
    ':hoten'       => $hoTen,
    ':email'       => $email,
    ':phone'       => $phone,
    ':diachi'      => $diaChi,
    ':trangthai'   => $trangthai,
    ':vaitro'      => $vaitro
]);

echo "✅ Thêm tài khoản thành công";
