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

$Utilites = getListUtilities();
$Area = getListArea();
$input = [
  'search'   => $_GET['search']   ?? '',
  'khuVuc'   => $_GET['area_id']   ?? '',
  'mucGia'   => $_GET['price']   ?? '',
  'dienTich' => $_GET['DienTich'] ?? '',
  'tienIch'  => isset($_GET['tienIch']) ? (array)$_GET['tienIch'] : [],
];

$orderBy = $_GET['orderBy'] ?? 'id';
$order   = $_GET['order']   ?? 'ASC';
$page    = (int)($_GET['page'] ?? 1);





// Lấy tổng số phòng
$totalCount = countRoomsWithFilters($input);
// Lấy danh sách phòng
$totalPage  = ceil($totalCount / 6);
$rooms = getListRooms($input, $orderBy, $order, $page);


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
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/TimPhong.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .sort-links {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #3a3a3a;
    }

    .sort-links a {
        text-decoration: none;
        color: #111827;
        background: #fff;
        border: 1.5px solid #1f2937;
        border-radius: 10px;
        padding: 6px 10px;
        transition: all .15s;
    }

    .sort-links a:hover {
        background: #f3f4f6;
    }

    .sort-links a.active {
        background: #007bff;
        border-color: #007bff;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, .15);
    }

    /* --- 1. Container Sidebar Chính --- */
    .filter-sidebar {
        background-color: #fff;
        padding: 25px;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        position: sticky;
        top: 30px;
        height: fit-content;
    }

    .filter-sidebar h3 {
        font-size: 2.3rem;
        font-weight: 700;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
    }

    .filter-group {
        margin-bottom: 25px;
    }

    .filter-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.2rem;
    }

    /* --- 2. CSS cho Form Tìm Kiếm (Giữ nguyên) --- */
    .search-inline {
        display: flex;
    }

    .search-inline input[type="text"] {
        flex-grow: 1;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px 0 0 8px;
        font-size: 1.1rem;
        border-right: none;
        outline: none;
    }

    .search-inline input[type="text"]:focus {
        border-color: var(--primary-color);
    }

    .search-inline button {
        padding: 0 15px;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        background: var(--primary-color);
        border: 1px solid var(--primary-color);
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .search-inline button:hover {
        background: #0056b3;
    }

    /* --- 3. CSS cho các Danh sách Link (Giữ nguyên) --- */
    .filter-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-list a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }

    .filter-list a:hover {
        background-color: #f4f7f6;
        color: #000;
    }

    /* QUAN TRỌNG: Bạn cần dùng code (ví dụ: JSP/JSTL) 
   để thêm class "active" vào link đang được chọn 
*/
    .filter-list a.active {
        background-color: var(--primary-color);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }


    /* --- 4. MỚI: CSS cho Form Tiện ích (Checkboxes) --- */
    .utilities-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        /* Khoảng cách giữa các checkbox */
    }

    .checkbox-item {
        display: flex;
        /* Dùng flex để căn chỉnh */
        align-items: center;
        /* Căn giữa checkbox và chữ */
        font-size: 1.1rem;
        color: #333;
        cursor: pointer;
        padding: 5px;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .checkbox-item:hover {
        background-color: #f4f7f6;
    }

    .checkbox-item input[type="checkbox"] {
        margin-right: 10px;
        /* Làm checkbox lớn hơn và đổi màu */
        transform: scale(1.1);
        accent-color: var(--primary-color);
    }

    /* Nút "Áp dụng" cho form tiện ích */
    .btn-apply {
        width: 100%;
        padding: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        background: var(--primary-color);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
        /* Khoảng cách với checkbox cuối */
        transition: background-color 0.3s;
    }

    .btn-apply:hover {
        background: #0056b3;
    }
    </style>
</head>

<body>
    <header class="header fixed">
        <div class="container">
            <div class="body d-flex justify-content-between align-items-center">
                <a href="" class="logo">
                    <img src="../../template/assets/img/logo.png" alt />
                    <span class="title">TroVinh</span>
                </a>
                <nav class="nav">
                    <ul class="nav-links">
                        <li><a href="home.php">Trang chủ</a></li>
                        <li><a href="GioiThieu.php">Giới thiệu</a></li>
                        <li><a href="LienHe.php">Liên hệ</a></li>
                        <li><a href="TimPhong.php" class="active">Tìm phòng</a></li>
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
        <div class="search-page-layout">

            <aside class="filter-sidebar">
                <h3>Bộ lọc tìm kiếm</h3>

                <!-- 🔍 Tìm kiếm -->
                <div class="filter-group">
                    <label for="keyword">Tìm theo từ khóa</label>
                    <form action="./TimPhong.php" method="get" class="search-inline">
                        <input type="text" id="keyword" name="search" placeholder="Ví dụ: đường Lê Duẩn..." />
                        <button type="submit">Tìm</button>
                    </form>
                </div>

                <!-- 📍 Khu vực -->
                <div class="filter-group">
                    <label>Khu vực</label>
                    <ul class="filter-list">
                        <?php foreach($Area as $ar){ ?>
                        <li><a href="./TimPhong.php?area_id=<?php echo $ar['id'] ?>">
                                <?php echo $ar['name_area'] ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- 💰 Mức giá -->
                <div class="filter-group">
                    <label>Mức giá</label>
                    <ul class="filter-list">
                        <li><a href="./TimPhong.php?price=1000000">≤ 1.000.000 đ</a></li>
                        <li><a href="./TimPhong.php?price=2000000">≤ 2.000.000 đ</a></li>
                        <li><a href="./TimPhong.php?price=3000000">≤ 3.000.000 đ</a></li>
                        <li><a href="./TimPhong.php?price=5000000">≤ 5.000.000 đ</a></li>
                    </ul>
                </div>

                <!-- 📐 Diện tích -->
                <div class="filter-group">
                    <label>Diện tích (m²)</label>
                    <ul class="filter-list">
                        <li><a href="./TimPhong.php?DienTich=15">≤ 15 m²</a></li>
                        <li><a href="./TimPhong.php?DienTich=25">≤ 25 m²</a></li>
                        <li><a href="./TimPhong.php?DienTich=35">≤ 35 m²</a></li>
                        <li><a href="./TimPhong.php?DienTich=50">≤ 50 m²</a></li>
                    </ul>
                </div>

                <!-- 🧩 Tiện ích: nhiều lựa chọn -->
                <div class="filter-group">
                    <label>Tiện ích</label>
                    <form action="./TimPhong.php" method="get" class="utilities-form">
                        <?php foreach($Utilites as $uti){ ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="tienIch[]" value="<?php echo $uti['id'] ?>" />
                            <?php echo $uti['name_uti'] ?> </label>
                        <?php } ?>
                        <button type="submit" class="btn-apply">Áp dụng</button>
                    </form>
                </div>
            </aside>

            <section class="results-area">

                <div class="sort-bar">
                    <div class="results-count">
                        Tìm thấy <strong>
                            <?php echo $totalCount ?></strong> kết quả
                    </div>
                    <div class="sort-links">
                        <span>Sắp xếp:</span>
                        <a href="./TimPhong.php" class="<?= ($orderBy==='id' ? 'active':'') ?>">Mặc định</a>

                        <a href="./TimPhong.php?orderBy=price&order=asc"
                            class="<?= ($orderBy==='price' && strtoupper($order)==='ASC' ? 'active':'') ?>">
                            Giá: Thấp → Cao
                        </a>

                        <a href="./TimPhong.php?orderBy=price&order=desc"
                            class="<?= ($orderBy==='price' && strtoupper($order)==='DESC' ? 'active':'') ?>">
                            Giá: Cao → Thấp
                        </a>
                    </div>
                </div>

                <div class="room-grid">
                    <?php foreach($rooms as $rm){ ?>
                    <a href="ChiTietPhongTro.php?id=<?php echo $rm['id'] ?>" style="text-decoration: none;">
                        <div class="room-card">
                            <div class="card-image">
                                <img src="<?php echo $rm['AnhChinh'] ?>" alt="Phòng trọ mẫu">
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">
                                    <?php echo $rm['title'] ?></h3>

                                <div class="card-info-main">
                                    <p><i class="fas fa-user"></i> Người đăng:
                                        <?php echo $rm['HoTen'] ?></p>
                                    <p><i class="fas fa-ruler-combined"></i> Diện tích:
                                        <?php echo $rm['DienTich'] ?>m²</p>
                                    <p><i class="fas fa-map-marker-alt"></i> Địa chỉ:
                                        <?php echo $rm['DiaChi'] ?></p>
                                    <p class="card-price"><i class="fas fa-dollar-sign"></i> Giá thuê:
                                        <?php echo formatCurrency($rm['price']) ?></p>
                                </div>

                                <div class="card-info-meta">
                                    <p><i class="fas fa-clock"></i>
                                        <?php echo timeAgo($rm['NgayDang']) ?></p>
                                    <p><i class="fas fa-eye"></i> Lượt xem:
                                        <?php echo $rm['Luotxem'] ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php } ?>
                </div>

                <nav class="pagination">
                    <?php for($i=1;$i<= $totalPage;$i++){ ?>
                    <a href="<?php echo buildUrlClean(['page' => $i]) ?>"
                        class="<?php echo ($i == $page ? "active" : "")  ?>">
                        <?php echo $i ?></a>
                    <?php }?>
                </nav>

            </section>
        </div>
    </main>
    <?php require_once "../../template/layouts/footer.php"  ?>
</body>

</html>