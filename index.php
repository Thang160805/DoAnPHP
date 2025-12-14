<?php
// Set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Tạo mới phiên làm việc
// tránh lỗi khi sử dụng các hàm header, cookie
ob_start();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel=" stylesheet" href="./template/assets/css/reset.css">
    <link rel="stylesheet" href="./template/assets/css/style.css">
    <link rel="stylesheet" href="./template/assets/css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header class="header fixed">
        <div class="container">
            <div class="body d-flex justify-content-between align-items-center"><a href=""
                    class="logo d-flex align-items-center"><img src="./template/assets/img/logo.png" alt="">
                    <h2>TroVinh</h2>
                </a>
                <nav class="nav">
                    <ul class="d-flex align-items-center">
                        <li><a href="./modules/auth/login.php" id="openLogin" class="btn-login me-3">Đăng nhập</a></li>
                        <li><a href="./modules/auth/register.php" id="openRegister" class="btn-dangky">Đăng ký</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="hero-content">
                <div class="choise-buttons"><a href="./modules/auth/login.php" class="btn">Tôi đang tìm chỗ ở</a><a
                        href="./modules/auth/login.php" class="btn">Tôi cung
                        cấp chỗ ở</a></div>
                <div class="row">
                    <div class="col-md-4 p-3">
                        <div class="tilte"><i class="fa-regular fa-handshake"></i>
                            <h3>Hỗ trợ</h3>
                        </div>
                        <div class="info">
                            <p>Chúng tôi hỗ trợ bạn tìm kiếm phòng trọ xung quanh khu vực trường Đại học Vinh một cách
                                nhanh chóng và hiệu quả nhất</p>
                        </div>
                    </div>
                    <div class="col-md-4 p-3">
                        <div class="tilte"><i class="fa-solid fa-dollar-sign"></i>
                            <h3>Giá thành</h3>
                        </div>
                        <div class="info">
                            <p>Chúng tôi luôn ưu tiên giới thiệu những phòng trọ với mức giá cả hợp lý và chất lượng
                                phòng tốt,
                                phù hợp với đa số người dùng đặc biệt là sinh viên</p>
                        </div>
                    </div>
                    <div class="col-md-4 p-3">
                        <div class="tilte"><i class="fa-regular fa-house"></i>
                            <h3>Cộng đồng</h3>
                        </div>
                        <div class="info">
                            <p>Chúng tôi đã và đang xây dựng một cộng đồng giúp chủ nhà trọ và người thuê nhà kết nối
                                với nhau một cách dễ dàng và thuật tiện</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer__top">
                <div class="footer__column footer-logo"><img src="./template/assets/img/logo.png" alt=""><span
                        style="color: #4CAF50; font-size: 20px;">Tro</span><span
                        style="color: #FB8C00; font-size: 20px;">Vinh</span></div>
                <div class="footer__column">
                    <h5 class="footer__heading">Về TroVinh</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="p-2"><a href="#">Trang chủ</a></li>
                        <li class="p-2"><a href="#">Giới thiệu</a></li>
                        <li class="p-2"><a href="#">Tìm phòng</a></li>
                        <li class="p-2"><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="footer__column">
                    <h5 class="text-white mb-3 fw-bold">Hỗ trợ khách hàng</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="p-2"><a href="#">Câu hỏi thường gặp (FAQ)</a></li>
                        <li class="p-2"><a href="#">Hướng dẫn đăng tin</a></li>
                        <li class="p-2"><a href="#">Chính sách bảo mật</a></li>
                        <li class="p-2"><a href="#">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                <div class="footer__column">
                    <h5 class="text-white mb-3 fw-bold">Kết nối với chúng tôi</h5>
                    <p class="text-white-50" style="font-size: 13px;">Theo dõi chúng tôi trên các nền
                        tảng mạng xã hội. </p>
                    <div class="social-icons mt-5"><a href="#" class="social-icon p-2 facebook" aria-label="Facebook"><i
                                class="fab fa-facebook-f"></i></a><a href="#" class="social-icon p-2 zalo"
                            aria-label="Zalo"><i class="fas fa-comment"></i></a><a href="#"
                            class="social-icon p-2 Tiktok" aria-label="Tiktok"><i class="fab fa-tiktok"></i></a><a
                            href="#" class="social-icon p-2 youtube" aria-label="Youtube"><i
                                class="fab fa-youtube"></i></a></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center text-white-50 small p-5">
                    <p>&copy;
                        2025 TroVinh. Đã đăng ký bản quyền.</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>