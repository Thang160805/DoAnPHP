<?php
session_start();
require_once __DIR__ . "/../../includes/database.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🧩 Lấy dữ liệu từ form
    $TenDangNhap   = trim($_POST['username'] ?? '');
    $HoTen         = trim($_POST['fullname'] ?? '');
    $Email         = trim($_POST['email'] ?? '');
    $Password      = $_POST['password'] ?? '';
    $ConfirmPass   = $_POST['confirm-password'] ?? '';
    $VaiTroInput   = $_POST['role'] ?? 'user';

    // Gán mã vai trò
    $VaiTro = ($VaiTroInput === 'landlord') ? 1 : 2; // 1 = chủ trọ, 2 = người dùng

    // ⚙️ Kiểm tra dữ liệu rỗng
    if ($TenDangNhap === '' || $Email === '' || $Password === '' || $HoTen === '') {
        $message = 'Vui lòng nhập đầy đủ thông tin!';
    }
    // ⚙️ Kiểm tra mật khẩu khớp
    elseif ($Password !== $ConfirmPass) {
        $message = 'Mật khẩu và xác nhận mật khẩu không khớp!';
    }
    // ⚙️ Kiểm tra tên đăng nhập trùng
    elseif (checkUserName($TenDangNhap)) {
        $message = 'Tên đăng nhập đã tồn tại!';
    }
    // ⚙️ Kiểm tra email trùng (nếu muốn)
    elseif (checkEmail($Email)) {
        $message = 'Email đã được sử dụng!';
    }
    else {
        // ✅ Mã hoá mật khẩu
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

        $data = [
            'TenDangNhap' => $TenDangNhap,
            'MatKhau'     => $hashedPassword,
            'HoTen'       => $HoTen,
            'Email'       => $Email,
            'VaiTro'      => $VaiTro,
            'Trangthai'   => 1,
        ];

        

        if (insertTaiKhoan($data)) {
            header('Location: login.php');
            exit;
        } else {
            $message = 'Đăng ký thất bại! Vui lòng thử lại.';
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel=" stylesheet" href="../../template/assets/css/reset.css">
    <link rel="stylesheet" href="../../template/assets/css/style.css">
    <link rel="stylesheet" href="../../template/assets/css/DK_DN.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .error-message {
        background-color: #f8d7da;
        /* Màu nền hồng nhạt */
        color: #721c24;
        /* Màu chữ đỏ sẫm */
        border: 1px solid #f5c6cb;
        /* Viền hồng đậm hơn */
        border-radius: 8px;
        /* Bo góc */
        padding: 0.85rem 1rem;
        /* Tăng padding */
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 15px;
        /* Giữ khoảng cách giữa các phần */

        display: flex;
        /* Thêm để căn icon + text */
        align-items: center;
        gap: 0.6rem;
        /* Khoảng cách giữa icon và text */

        width: 100%;
        /* Chiếm toàn bộ chiều ngang container */
        min-height: 45px;
        /* ✅ đảm bảo có chiều cao dù nội dung ngắn */
    }

    .error-message i {
        color: #dc3545;
        /* Màu đỏ cho icon */
        font-size: 1.1rem;
        line-height: 1;
        /* Đảm bảo icon căn chuẩn */
    }

    /* Span chứa text lỗi */
    .error-message .error-text {
        flex-grow: 1;
        /* Cho phép text tự dãn ra */
    }
    </style>
</head>

<body>
    <div class="split-container">

        <div class="split-left">
            <div class="welcome-text">
                <h1>Tham gia cùng chúng tôi</h1>
                <p>Khám phá và kết nối ngay hôm nay.</p>
            </div>
        </div>

        <div class="split-right">
            <div class="auth-form-container">
                <a href="../../index.php" class="back-link">&larr; Quay lại trang chủ</a>
                <h2>Tạo tài khoản</h2>
                <?php 
                   if($message != null && !empty($message)){?>
                <div class="error-message" id="email-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span class="error-text">
                        <?php echo $message; ?></span>
                </div>
                <?php  } ?>
                <form action="./register.php" method="POST" id="register-form">
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" required>
                    </div>
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Tên đăng nhập" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Email của bạn" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-user-gear option-role"></i>
                        <select id="role" name="role" required>
                            <option value="">Chọn loại tài khoản</option>
                            <option value="user">Tôi đang tìm chỗ ở</option>
                            <option value="landlord">Tôi cung cấp chỗ ở</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
                    </div>


                    <div class="input-group">
                        <i class="fa-solid fa-check-double"></i>
                        <input type="password" id="confirm-password" name="confirm-password"
                            placeholder="Xác nhận mật khẩu" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Tạo tài khoản</button>
                </form>

                <div class="toggle-link">
                    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
                </div>
            </div>
        </div>

    </div>

    <script src="./template/assets/js/DK_DN.js"></script>
</body>

</html>