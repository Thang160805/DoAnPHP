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
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/GioiThieu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header class="header fixed gioithieu">
        <div class="container">
            <div class="body d-flex justify-content-between align-items-center">
                <a href="" class="logo">
                    <img src="../../template/assets/img/logo.png" alt />
                    <span class="title">TroVinh</span>
                </a>
                <nav class="nav">
                    <ul class="nav-links">
                        <li><a href="home.php">Trang chủ</a></li>
                        <li><a href="GioiThieu.php" class="active">Giới thiệu</a></li>
                        <li><a href="LienHe.php">Liên hệ</a></li>
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
    <section class="hero-section">
        <div class="container">
            <h1>Về TroVinh</h1>
            <p>Sứ mệnh của chúng tôi là kết nối cộng đồng sinh viên Đại học Vinh với những không gian sống an toàn,
                tiện
                nghi và phù hợp nhất.</p>
        </div>
    </section>

    <main class="container">

        <section class="mission-section">
            <div class="mission-content">
                <h2 class="section-title">Sứ mệnh của chúng tôi</h2>
                <p class="lead-text">
                    Chúng tôi hiểu rõ những khó khăn của tân sinh viên khi lần đầu tìm kiếm nhà trọ xa nhà.
                </p>
                <p>
                    PhongTroVinh ra đời không chỉ là một website, mà là một người bạn đồng hành. Chúng tôi cam kết
                    tạo
                    ra một nền tảng minh bạch, nơi mọi thông tin phòng trọ đều được xác thực, giúp sinh viên và phụ
                    huynh an tâm tuyệt đối.
                </p>
                <p>
                    Đối với các chủ nhà, chúng tôi cung cấp một kênh hiệu quả để tiếp cận đúng đối tượng sinh viên
                    văn
                    minh, lịch sự.
                </p>
                <a href="DanhSachPhongServlet" class="btn-primary">
                    <i class="fas fa-search"></i> Bắt đầu tìm kiếm
                </a>
            </div>
            <div class="mission-image">

                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wzNjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.0.3&q=80&w=1080"
                    alt="Sinh viên Đại học Vinh">
            </div>
        </section>

        <section class="values-section">
            <div class="container">
                <div class="section-header">
                    <h2>Vì sao chọn chúng tôi?</h2>
                    <p>Những giá trị cốt lõi làm nên sự khác biệt của TroVinh.</p>
                </div>

                <div class="values-grid">
                    <div class="value-card">
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3>Tin cậy & Xác thực</h3>
                        <p>Mọi bài đăng đều được kiểm duyệt. Chúng tôi có hệ thống báo cáo để loại bỏ các
                            tin tức không chính xác.</p>
                    </div>

                    <div class="value-card">
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3>Minh bạch & Đánh giá</h3>
                        <p>Hệ thống đánh giá từ chính các sinh viên đã ở, giúp bạn có cái nhìn khách quan
                            nhất trước khi quyết định.</p>
                    </div>

                    <div class="value-card">
                        <div class="icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>An toàn & Rõ ràng</h3>
                        <p>Chúng tôi cung cấp các mẫu hợp đồng và quy trình thanh toán
                            rõ ràng để bảo vệ quyền lợi người thuê.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="location-section">
            <div class="section-header">
                <h2>Hoạt động tại Đại học Vinh</h2>
                <p>Chúng tôi tập trung 100% vào khu vực xung quanh Đại học Vinh để đảm bảo chất lượng và sự hỗ trợ
                    tốt
                    nhất.</p>
            </div>

            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.1101298370536!2d105.69316507496644!3d18.659053382461565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cddf0bf20f23%3A0x86154b56a284fa6d!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBWaW5o!5e0!3m2!1svi!2s!4v1762087381636!5m2!1svi!2s"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>

    </main>
    <?php require_once "../../template/layouts/footer.php"  ?>
</body>

</html>