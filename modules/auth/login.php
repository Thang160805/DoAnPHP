<?php
session_start();
require_once __DIR__ . "/../../includes/database.php";

if (!isset($_SESSION['login_fail'])) {
    $_SESSION['login_fail'] = 0;
}

$message = "";
$captchaImage = null;

function createCaptcha() {
    $code = rand(1000, 9999);
    $_SESSION['captcha_code'] = $code;
    return $code;
}

// GET → hiển thị captcha nếu cần
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SESSION['login_fail'] >= 5) {
        $captchaImage = createCaptcha();
    }
}

// POST → xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kiểm tra captcha nếu vượt quá số lần
    if ($_SESSION['login_fail'] >= 5) {
        if (empty($_POST['captcha']) || $_POST['captcha'] != $_SESSION['captcha_code']) {
            $message = "Sai mã xác minh!";
        }
    }

    if ($message === "") {

        $user = checkLogin($username, $password);

        if ($user) {
            $_SESSION['login_fail'] = 0;
            $_SESSION['username'] = $user['TenDangNhap'];
            $_SESSION['role']     = (int)$user['Vaitro']; // 0 = admin

            // 🔀 Redirect theo role
            if ($user['Vaitro'] === '0' || $user['Vaitro'] === 0) {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../users/home.php");
            }
            exit;

        } else {
            $_SESSION['login_fail']++;
            $message = "Sai tên đăng nhập hoặc mật khẩu!";
        }
    }

    // Tạo lại captcha nếu vẫn fail
    if ($_SESSION['login_fail'] >= 5) {
        $captchaImage = createCaptcha();
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
    <link rel=" stylesheet" href="/ThucHanhPHP/template/assets/css/reset.css">
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/style.css">
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/DK_DN.css">
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
        .captcha-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0 20px 0;
        }

        .captcha-box img {
            border: 1px solid #ccc;
            border-radius: 6px;
            height: 40px;
        }

        .captcha-refresh {
            background: #f5f5f5;
            border: none;
            padding: 10px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .captcha-refresh:hover {
            background: #e6e6e6;
        }

        .input-group i {
            margin-right: 10px;
        }
    }
    </style>
</head>

<body>
    <div class="split-container">
        <div class="split-left">
            <div class="welcome-text">
                <h1>Chào mừng trở lại !</h1>
                <p>Nơi bắt đầu hành trình của bạn.</p>
            </div>
        </div>
        <div class="split-right">
            <div class="auth-form-container"><a href="../../index.php" class="back-link">&larr;
                    Quay lại trang chủ</a>
                <h2>Đăng nhập</h2>
                <?php 
                   if($message != null && !empty($message)){?>
                <div class="error-message" id="email-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span class="error-text">
                        <?php echo $message; ?></span>
                </div>
                <?php  } ?>
                <form action="login.php" method="POST" id="login-form">
                    <div class="input-group"><i class="fa-solid fa-user"></i><input type="text" id="username"
                            name="username" placeholder="Tên đăng nhập" required></div>
                    <div class="input-group"><i class="fa-solid fa-lock"></i><input type="password" id="password"
                            name="password" placeholder="Mật khẩu" required></div>
                    <?php if ($_SESSION['login_fail'] >= 5) { ?>
                    <div class="input-group">
                        <i class="fa-solid fa-shield-halved"></i>
                        <input type="text" name="captcha" placeholder="Nhập mã xác minh" required>
                    </div>

                    <div class="captcha-box">
                        <div style="
                           font-size: 24px;
                           font-weight: bold;
                           letter-spacing: 6px;
                           background: #f3f3f3;
                           padding: 8px 14px;
                           border-radius: 6px;
                           border: 1px solid #ccc;
                           display: inline-block;
                        ">
                            <?= $captchaImage ?>
                        </div>

                        <!-- Nút refresh (load lại trang == tạo captcha mới) -->
                        <button type="submit" name="refresh" class="captcha-refresh">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                    <?php } ?>
                    <div class="form-options"><a href="#" class="forgot-password">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                </form>
                <div class="divider"><span>HOẶC TIẾP TỤC VỚI</span></div>
                <div class="social-login"><button class="btn btn-social btn-google"><i
                            class="fa-brands fa-google"></i>Google </button><button
                        class="btn btn-social btn-facebook"><i class="fa-brands fa-facebook-f"></i>Facebook
                    </button>
                </div>
                <div class="toggle-link">
                    <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="./template/assets/js/DK_DN.js"></script>
</body>

</html>