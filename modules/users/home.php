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
$pageStr = $_GET['pageNR'] ?? 1;
$pageSize = 4;

$totalCount = getTotalCountNewRoom(); 
$totalPage = ceil($totalCount / $pageSize);

$page = (int)$pageStr;
if ($page < 1 || $page > $totalPage) {
    $page = 1;
}

$rooms = getListNewRoom($page);


$pageStr1 = $_GET['pageRC'] ?? 1;
$pageSize1 = 4;

$totalCount1 = getTotalCountRoomCheap(); 
$totalPage1 = ceil($totalCount1 / $pageSize1);

$page1 = (int)$pageStr1;
if ($page1 < 1 || $page1 > $totalPage1) {
    $page1 = 1;
}

$rooms1 = getListRoomCheap($page1);

$TenDangNhap = $_SESSION['username'];
$user = getTaiKhoan($TenDangNhap);
$avatar = $user['Avatar'];

if (filter_var($avatar, FILTER_VALIDATE_URL)) {
    $src = $avatar;
} else {
    $src = '/ThucHanhPHP/template/assets/img/' . $avatar;
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
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/homepage.css">
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
                        <li><a href="home.php" class="active">Trang chủ</a></li>
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
    <section class=" container panel hero-banner">
        <div class="banner-overlay">
            <h1>Tìm Phòng Trọ Đại Học Vinh</h1>
            <p>Uy tín - An ninh - Giá tốt nhất cho sinh viên.</p>
        </div>
    </section>

    <section class="container new-room">
        <div class="body">
            <h1>Phòng trọ mới đăng nhất</h1>
            <div class="grid-room">
                <?php foreach($rooms as $room){ ?>
                <div class="room-card">
                    <a href="ChiTietPhongTro.php?id=<?php echo $room['id'] ?>" class="d-flex">
                        <!-- left -->
                        <div class="img-room">
                            <img src="<?php echo $room['AnhChinh']  ?>" alt>
                        </div>
                        <!-- right -->
                        <div class="info-room">
                            <h3 class="card-title">
                                <?php echo $room['title'] ?></h3>
                            <div class="row" style="margin-top: 20px;">
                                <div class="card-content">

                                    <div class="card-info-main">
                                        <p><i class="fas fa-user"></i> Người đăng:
                                            <?php echo $room['HoTen'] ?></p>
                                        <p><i class="fas fa-ruler-combined"></i> Diện tích:
                                            <?php echo $room['DienTich'] ?>m²</p>
                                        <p><i class="fas fa-map-marker-alt"></i> Địa chỉ:
                                            <?php echo $room['DiaChi'] ?></p>
                                        <p class="card-price"><i class="fas fa-dollar-sign"></i> Giá
                                            thuê:
                                            <?php echo formatCurrency($room['price']) ?></p>
                                    </div>

                                    <div class="card-info-meta">
                                        <p><i class="fas fa-clock"></i>
                                            <?php echo timeAgo($room['NgayDang']) ?></p>
                                        <p><i class="fas fa-eye"></i> Lượt xem:
                                            <?php echo $room['Luotxem'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>
            <nav class="pagination">
                <?php for($i=1;$i<= $totalPage;$i++){ ?>
                <a href="./home.php?pageNR=<?php echo $i ?>" class="<?php echo ($i == $page ? "active" : "")  ?>">
                    <?php echo $i ?></a>
                <?php }?>
            </nav>
        </div>

    </section>

    <section class="container new-room">
        <div class="body">
            <h1>Phòng trọ giá rẻ</h1>
            <div class="grid-room">
                <?php foreach($rooms1 as $room1){ ?>
                <div class="room-card">
                    <a href="ChiTietPhongTro.php?id=<?php echo $room1['id'] ?>" class="d-flex">
                        <!-- left -->
                        <div class="img-room">
                            <img src="<?php echo $room1['AnhChinh']  ?>" alt>
                        </div>
                        <!-- right -->
                        <div class="info-room">
                            <h3 class="card-title">
                                <?php echo $room1['title'] ?></h3>
                            <div class="row" style="margin-top: 20px;">
                                <div class="card-content">

                                    <div class="card-info-main">
                                        <p><i class="fas fa-user"></i> Người đăng:
                                            <?php echo $room1['HoTen'] ?></p>
                                        <p><i class="fas fa-ruler-combined"></i> Diện tích:
                                            <?php echo $room1['DienTich'] ?>m²</p>
                                        <p><i class="fas fa-map-marker-alt"></i> Địa chỉ:
                                            <?php echo $room1['DiaChi'] ?></p>
                                        <p class="card-price"><i class="fas fa-dollar-sign"></i> Giá
                                            thuê:
                                            <?php echo formatCurrency($room1['price']) ?></p>
                                    </div>

                                    <div class="card-info-meta">
                                        <p><i class="fas fa-clock"></i>
                                            <?php echo timeAgo($room1['NgayDang']) ?></p>
                                        <p><i class="fas fa-eye"></i> Lượt xem:
                                            <?php echo $room1['Luotxem'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>
            <nav class="pagination">
                <?php for($i=1;$i<= $totalPage1;$i++){ ?>
                <a href="./home.php?pageRC=<?php echo $i ?>" class="<?php echo ($i == $page1 ? "active" : "")  ?>">
                    <?php echo $i ?></a>
                <?php }?>
            </nav>
        </div>

    </section>
    <?php require_once "../../template/layouts/footer.php"  ?>
    <script>
    // Danh sách ảnh banner (đặt trong thư mục /images/)
    const images = [
        " ../../template/assets/img/anh1.jpg", "./template/assets/img/anh2.jpg",
        "../../template/assets/img/anh3.jpg",
        "../../template/assets/img/anh4.jpg", "./template/assets/img/anh5.jpg"
    ];
    const
        banner = document.querySelector('.hero-banner');
    let currentIndex = 0; // Ảnh đầu tiên
    banner.style.backgroundImage = `url(${images[currentIndex]})`; // Tự đổi ảnh sau mỗi 10 giây
    setInterval(() => {
        currentIndex = (currentIndex + 1) % images.length;
        banner.style.backgroundImage =
            `url(${images[currentIndex]})`;
    }, 10000);
    </script>
</body>

</html>