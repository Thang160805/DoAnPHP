<?php
session_start(); // PHẢI ở dòng đầu tiên của mọi file có dùng session
// (Tùy chọn) Có thể cho phép cache private, nhưng an toàn nhất khi debug là tắt:
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

$avatar = $user['Avatar'];

if (filter_var($avatar, FILTER_VALIDATE_URL)) {
    $src = $avatar;
} else {
    $src = '/CaseStudy/template/assets/img/' . $avatar;
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
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/LienHe.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
</head>

<body>
    <header class="header fixed">
        <div class="container">
            <div class="body d-flex justify-content-between align-items-center">
                <a href="./home.php" class="logo">
                    <img src="../../template/assets/img/logo.png" alt />
                    <span class="title">TroVinh</span>
                </a>
                <nav class="nav">
                    <ul class="nav-links">
                        <li><a href="home.php">Trang chủ</a></li>
                        <li><a href="GioiThieu.php">Giới thiệu</a></li>
                        <li><a href="LienHe.php" class="active">Liên hệ</a></li>
                        <li><a href="TimPhong.php">Tìm phòng</a></li>
                    </ul>

                </nav>

                <a href="Profile.php" class="profile">
                    <?php if(!empty($user)){ ?>
                    <img class="avatar" src="<?php echo $src ?>" alt="">
                    <span>
                        <?php echo $user['HoTen'] ?></span>
                    <?php } ?>
                </a>
            </div>
        </div>
    </header>
    <main class="container">

        <div class="page-header">
            <h1>Liên hệ với chúng tôi</h1>
            <p>Chúng tôi luôn sẵn sàng lắng nghe bạn. Vui lòng điền vào biểu mẫu dưới đây hoặc sử dụng thông tin
                liên hệ
                trực tiếp.</p>
        </div>

        <div class="contact-wrapper">

            <div class="contact-info">
                <h3>Thông tin liên hệ</h3>
                <p>Bạn có thể liên hệ với văn phòng hỗ trợ của TroVinh qua các kênh dưới đây.</p>

                <ul class="info-list">
                    <li class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="info-text">
                            <strong>Địa chỉ:</strong>
                            <span>182 Lê Duẩn, Phường Bến Thủy, Thành phố Vinh, Nghệ An</span>
                        </div>
                    </li>
                    <li class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div class="info-text">
                            <strong>Email hỗ trợ:</strong>
                            <span>hotro@phongtrovinh.com</span>
                        </div>
                    </li>
                    <li class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div class="info-text">
                            <strong>Hotline:</strong>
                            <span>0829.028.846 (Hỗ trợ sinh viên)</span>
                        </div>
                    </li>
                </ul>

                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Zalo"><i class="fas fa-comment-dots"></i></a>
                    <a href="#" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
                </div>
            </div>

            <div class="contact-form">
                <h3>Gửi tin nhắn cho chúng tôi</h3>

                <form action="LienHeServlet" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hoTen">Họ và tên</label>
                            <input type="text" id="hoTen" name="hoTen" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="chuDe">Chủ đề</label>
                        <select id="chuDe" name="chuDe">
                            <option value="hoTroKyThuat">Hỗ trợ kỹ thuật</option>
                            <option value="baoCaoViPham">Báo cáo vi phạm</option>
                            <option value="gopY">Đóng góp ý kiến</option>
                            <option value_="khac">Khác</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="noiDung">Nội dung tin nhắn</label>
                        <textarea id="noiDung" name="noiDung" rows="6" required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                    </button>
                </form>
            </div>

        </div>

        <div class="map-section">
            <h2>Vị trí của chúng tôi</h2>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.1101298370536!2d105.69316507496644!3d18.659053382461565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cddf0bf20f23%3A0x86154b56a284fa6d!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBWaW5o!5e0!3m2!1svi!2s!4v1762087381636!5m2!1svi!2s"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </main>
    <?php require_once "../../template/layouts/footer.php"  ?>
</body>

</html>