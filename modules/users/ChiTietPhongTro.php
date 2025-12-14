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
    $src = '/ThucHanhPHP/template/assets/img/' . $avatar;
}

$idPhong = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$phong = getPhongById($idPhong);
$tienIchList = getTienIchByPhongId($idPhong);

if (!$phong) {
    echo 'Phòng không tồn tại!';
    exit;
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
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/ChiTietPhong.css">
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
            <div class="body d-flex justify-content-between align-items-center">
                <a href="./home.php" class="logo">
                    <img src="../../template/assets/img/logo.png" alt />
                    <span class="title">TroVinh</span>
                </a>
                <nav class="nav">
                    <ul class="nav-links">
                        <li><a href="home.php">Trang chủ</a></li>
                        <li><a href="GioiThieu.php">Giới thiệu</a></li>
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
    <main class="container">
        <section class="room-detail-page">
            <h1 class="room-title">
                <?php echo $phong['title'] ?></h1>

            <div class="room-detail-container">

                <div class="main-content">

                    <section class="image-gallery">

                        <div class="main-image">
                            <img src="<?php echo $phong['AnhChinh'] ?>" alt="Ảnh chính phòng trọ">
                        </div>
                    </section>

                    <section class="overview">
                        <div class="price">
                            <?php echo formatCurrency($phong['price']) ?> VNĐ/tháng </div>
                        <div class="info-item">
                            <i class="fas fa-ruler-combined"></i> Diện tích: <strong>
                                <?php echo $phong['DienTich'] ?> m²</strong>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i> Địa chỉ: <strong>
                                <?php echo $phong['DiaChi'] ?></strong>
                        </div>
                        <a href="#map-section" class="btn-view-map">Xem trên bản đồ</a>
                    </section>

                    <section class="description">
                        <h2>Mô tả chi tiết</h2>
                        <p>
                            <?php echo $phong['description'] ?></p>

                    </section>



                    <section class="amenities">
                        <h2>Đặc điểm & Tiện ích</h2>
                        <ul class="tien-ich-list">
                            <?php
        if (!empty($tienIchList)) {
            
            foreach ($tienIchList as $ti) {
                echo '<li>';
                echo '<i class="' . getTienIchIcon($ti['TenTienIch']) . '"></i> ';
                echo htmlspecialchars($ti['TenTienIch']);
                echo '</li>';
            }
        } else {
            echo '<li>Chưa có thông tin tiện ích</li>';
        }
        ?>
                        </ul>
                    </section>


                    <section class="map" id="map-section">
                        <h2>Vị trí trên bản đồ</h2>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1123.8171623444712!2d105.6963742446166!3d18.66151461289158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cde3df58d4a1%3A0xe0981320a4164113!2zTmjDoCBUcuG7jSBYdcOibiBIxrDGoW5n!5e0!3m2!1svi!2s!4v1761227614474!5m2!1svi!2s"
                            width="100%" height="450" style="border: 0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </section>

                </div>

                <aside class="sidebar">

                    <div class="owner-box">
                        <img src="<?php echo $phong['Avatar'] ?>" alt="Ảnh đại diện Lê Bảo Ngọc" class="avatar">
                        <div class="owner-name">
                            <?php echo $phong['HoTen'] ?></div>
                        <p class="owner-role">Người đăng</p>
                        <?php 
                        // Kiểm tra trạng thái phòng - chỉ hiển thị nút nếu phòng còn trống (trạng thái 1)
                        if ($phong['TrangThai'] == 1): 
                        ?>
                        <a href="YeuCauThueTro.php?ID_Phong=<?php echo $phong['id'] ?>&ID_NguoiThue=<?php echo $user['id'] ?>"
                            class="btn btn-rent">
                            <i class="fas fa-key"></i> Đặt Thuê Ngay
                        </a>
                        <?php elseif ($phong['TrangThai'] == 2): ?>
                        <div class="btn btn-rent" style="background: #6b7280; cursor: not-allowed; opacity: 0.7;">
                            <i class="fas fa-lock"></i> Phòng đã được thuê
                        </div>
                        <?php endif; ?>

                        <button class="btn btn-phone">
                            <i class="fas fa-phone"></i> <span>
                                <?php echo $phong['Phone'] ?></span>
                        </button>
                        <button class=" btn btn-zalo">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" style="margin-right: 10px"
                                width="25" height="25" viewBox="0 0 48 48">
                                <path fill="#2962ff"
                                    d="M15,36V6.827l-1.211-0.811C8.64,8.083,5,13.112,5,19v10c0,7.732,6.268,14,14,14h10	c4.722,0,8.883-2.348,11.417-5.931V36H15z">
                                </path>
                                <path fill="#eee"
                                    d="M29,5H19c-1.845,0-3.601,0.366-5.214,1.014C10.453,9.25,8,14.528,8,19	c0,6.771,0.936,10.735,3.712,14.607c0.216,0.301,0.357,0.653,0.376,1.022c0.043,0.835-0.129,2.365-1.634,3.742	c-0.162,0.148-0.059,0.419,0.16,0.428c0.942,0.041,2.843-0.014,4.797-0.877c0.557-0.246,1.191-0.203,1.729,0.083	C20.453,39.764,24.333,40,28,40c4.676,0,9.339-1.04,12.417-2.916C42.038,34.799,43,32.014,43,29V19C43,11.268,36.732,5,29,5z">
                                </path>
                                <path fill="#2962ff"
                                    d="M36.75,27C34.683,27,33,25.317,33,23.25s1.683-3.75,3.75-3.75s3.75,1.683,3.75,3.75	S38.817,27,36.75,27z M36.75,21c-1.24,0-2.25,1.01-2.25,2.25s1.01,2.25,2.25,2.25S39,24.49,39,23.25S37.99,21,36.75,21z">
                                </path>
                                <path fill="#2962ff" d="M31.5,27h-1c-0.276,0-0.5-0.224-0.5-0.5V18h1.5V27z">
                                </path>
                                <path fill="#2962ff"
                                    d="M27,19.75v0.519c-0.629-0.476-1.403-0.769-2.25-0.769c-2.067,0-3.75,1.683-3.75,3.75	S22.683,27,24.75,27c0.847,0,1.621-0.293,2.25-0.769V26.5c0,0.276,0.224,0.5,0.5,0.5h1v-7.25H27z M24.75,25.5	c-1.24,0-2.25-1.01-2.25-2.25S23.51,21,24.75,21S27,22.01,27,23.25S25.99,25.5,24.75,25.5z">
                                </path>
                                <path fill="#2962ff"
                                    d="M21.25,18h-8v1.5h5.321L13,26h0.026c-0.163,0.211-0.276,0.463-0.276,0.75V27h7.5	c0.276,0,0.5-0.224,0.5-0.5v-1h-5.321L21,19h-0.026c0.163-0.211,0.276-0.463,0.276-0.75V18z">
                                </path>
                            </svg>
                            Nhắn tin Zalo
                        </button>
                        <button class="btn btn-save">
                            <i class="far fa-heart"></i> Lưu tin này
                        </button>
                    </div>

                    <div class="post-meta">
                        <p>
                            <i class="fas fa-calendar-alt"></i> Ngày đăng:
                            <strong>
                                <?php echo timeAgo($phong['NgayDang']) ?></strong>
                        </p>
                        <p>
                            <i class="fas fa-eye"></i> Lượt xem: <strong>
                                <?php echo $phong['Luotxem'] ?></strong>
                        </p>
                        <p>
                            <i class="fas fa-hashtag"></i> Mã tin: <strong>
                                <?php echo $phong['id'] ?></strong>
                        </p>
                    </div>

                    <div class="share-report">
                        <div class="share-buttons">
                            <span>Chia sẻ:</span> <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a> <a
                                href="#" class="social-icon"><i class="fab fa-facebook-messenger"></i></a> <a href="#"
                                class="social-icon"><i class="fas fa-link"></i></a>
                        </div>
                        <a href="#" class="report-link"> <i class="fas fa-flag"></i>
                            Báo cáo tin này
                        </a>
                    </div>

                </aside>
            </div>

        </section>


        </div>

    </main>
    <?php require_once "../../template/layouts/footer.php"  ?>
</body>

</html>