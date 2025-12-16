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

$userId = $user['id'];
$sqlLichSu = "
    SELECT 
        yct.id,
        yct.phong_id,
        yct.ngay_vao,
        yct.thoi_gian_thue,
        yct.trang_thai,
        yct.created_at,
        pt.title,
        pt.price,
        pt.AnhChinh,
        pt.DiaChi,
        pt.DienTich,
        pt.TrangThai AS trang_thai_phong
    FROM yeucauthuetro yct
    JOIN phongtro pt ON yct.phong_id = pt.id
    WHERE yct.nguoi_thue_id = ? AND yct.trang_thai IN (2, 4)
    ORDER BY yct.created_at DESC
";
$stmtLichSu = mysqli_prepare($conn, $sqlLichSu);
mysqli_stmt_bind_param($stmtLichSu, "i", $userId);
mysqli_stmt_execute($stmtLichSu);
$resultLichSu = mysqli_stmt_get_result($stmtLichSu);
$lichSuThue = [];
while ($row = mysqli_fetch_assoc($resultLichSu)) {
    $lichSuThue[] = $row;
}
mysqli_stmt_close($stmtLichSu);




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
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/Profile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .alert {
        padding: 12px 16px;
        margin-bottom: 20px;
        border-radius: 6px;
        font-weight: 500;
    }

    .alert.success {
        background-color: #e6fffa;
        color: #047857;
        border: 1px solid #99f6e4;
    }

    .alert.error {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    </style>
</head>

<body>
    <header class="header fixed">
        <div class="container">
            <div class="body d-flex justify-content-between align-items-center"><a href="home.php" class="logo"><img
                        src="../../template/assets/img/logo.png" alt /><span class="title">TroVinh</span></a>
                <nav class="nav">
                    <ul class="nav-links">
                        <li><a href="home.php">Trang chủ</a></li>
                        <li><a href="GioiThieu.php">Giới thiệu</a></li>
                        <li><a href="LienHe.php">Liên hệ</a></li>
                        <li><a href="TimPhong.php">Tìm phòng</a></li>
                    </ul>
                </nav><a href="Profile.php" class="profile"><?php if ( !empty($user)) {
        ?><img class="avatar" src="<?php echo $src ?>" alt=""><span><?php echo $user['HoTen'] ?></span><?php
    }

    ?></a>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="profile-container">
                <aside class="profile-sidebar">
                    <div class="sidebar-user"><?php if ( !empty($user)) {
        ?><img src="<?php echo $src ?>" alt="Avatar lớn">
                        <h4><?php echo $user['HoTen'] ?></h4>
                        <p><?php echo $user['Email'] ?></p><?php
    }

    ?>
                    </div>
                    <nav class="sidebar-nav">
                        <ul>
                            <li class="active"><a data-target="thong-tin"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>Thông tin tài khoản </a></li>
                            <li><a data-target="lich-su-thue"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>Lịch sử thuê trọ </a></li>
                            <li><a data-target="doi-mat-khau"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>Đổi mật khẩu </a></li>
                            <li class="logout"><a href="../auth/Logout.php"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>Đăng xuất </a></li>
                        </ul>
                    </nav>
                </aside>
                <div class="profile-content"><?php if (isset($_SESSION['message'])): ?><div
                        class="alert <?= $_SESSION['message_type'] ?? 'success' ?>">
                        <?=htmlspecialchars($_SESSION['message']) ?></div><?php unset($_SESSION['message'], $_SESSION['message_type']);
    ?><?php endif;
    ?><section id="thong-tin" class="content-section active">
                        <h2>Thông tin tài khoản</h2>
                        <form action="./UpdateProfile.php" method="POST" class="info-form"
                            enctype="multipart/form-data"><?php if ( !empty($user)) {
        ?><div class="form-group"><label for="ho-ten">Họ và tên</label><input type="text" name="HoTen" id="ho-ten"
                                    value="<?php echo (isset($user['HoTen']) ? $user['HoTen'] : '') ?>"></div>
                            <div class="form-group"><label for="email">Email</label><input type="email" name="Email"
                                    value="<?php echo (isset($user['Email']) ? $user['Email'] : '') ?>" disabled></div>
                            <div class="form-group"><label for="sdt">Số điện thoại</label><input type="tel" name="Phone"
                                    id="sdt" value="<?php echo (isset($user['Phone']) ? $user['Phone'] : '') ?>"></div>
                            <div class="form-group"><label for="sdt">Địa chỉ</label><input type="text" name="DiaChi"
                                    id="dc" value="<?php echo (isset($user['DiaChi']) ? $user['DiaChi'] : '') ?>"></div>
                            <div class="form-group"><label for="avatar">Avatar</label><input type="file" name="Avatar"
                                    id="avatar" value="Chọn ảnh đại diện" accept="image/*"></div><button type="submit"
                                class="btn-submit">Cập nhật thông tin</button><?php
    }

    ?>
                        </form>
                    </section>
                    <section id="doi-mat-khau" class="content-section">
                        <h2>Đổi mật khẩu</h2>
                        <form action="ChangePass.php" method="POST" class=" info-form" style="max-width: 500px;">
                            <div class="form-group full-width"><label for="oldpass">Mật khẩu cũ</label><input
                                    type="password" name="oldpass" class="form-control"></div>
                            <div class="form-group full-width"><label for="newpass">Mật khẩu mới</label><input
                                    class="form-control" type="password" name="newpass"></div>
                            <div class="form-group full-width"><label for="confirm-pass">Xác nhận mật khẩu
                                    mới</label><input class="form-control" type="password" name="confirmpass"></div>
                            <button type="submit" class="btn-submit">Đổi Mật Khẩu</button>
                        </form>
                    </section>
                    <section id="lich-su-thue" class="content-section">
                        <h2>Lịch sử thuê trọ</h2>
                        <?php if (empty($lichSuThue)): ?>
                        <div style="text-align: center; padding: 40px; color: #6b7280;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <p style="font-size: 16px;">Bạn chưa có lịch sử thuê trọ nào.</p>
                        </div>
                        <?php else: ?>
                        <?php foreach ($lichSuThue as $item): 
                                $imgSrc = $item['AnhChinh'];
                                // Xử lý đường dẫn ảnh
                                if (strpos($imgSrc, 'http') === 0) {
                                    $displayImg = $imgSrc;
                                } else {
                                    if (strpos($imgSrc, '/') === 0) {
                                        $displayImg = $imgSrc;
                                    } else {
                                        $displayImg = '/ThuNghiem/template/assets/img/' . $imgSrc;
                                    }
                                }
                                
                                // Tính ngày trả phòng dự kiến
                                $ngayVao = new DateTime($item['ngay_vao']);
                                $thoiHan = (int)$item['thoi_gian_thue'];
                                $ngayTra = clone $ngayVao;
                                $ngayTra->modify("+{$thoiHan} months");
                                
                                $trangThai = (int)$item['trang_thai'];
                                $isDangThue = ($trangThai == 2); // 2 = đang thuê, 4 = đã trả phòng
                            ?>
                        <div class="listing-card">
                            <img src="<?= htmlspecialchars($displayImg) ?>"
                                alt="<?= htmlspecialchars($item['title']) ?>"
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'150\'%3E%3Crect fill=\'%23ddd\' width=\'200\' height=\'150\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EKhông có ảnh%3C/text%3E%3C/svg%3E';">
                            <div class="listing-info">
                                <h4><?= htmlspecialchars($item['title']) ?></h4>
                                <div class="price"><?= number_format($item['price']) ?> VNĐ/tháng</div>
                                <div style="margin-top: 8px; font-size: 14px; color: #6b7280;">
                                    <p><i class="fas fa-ruler-combined"></i> Diện tích: <?= $item['DienTich'] ?>m²</p>
                                    <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($item['DiaChi']) ?>
                                    </p>
                                </div>
                                <div class="rental-date">
                                    <strong>Ngày thuê:</strong> <?= date('d/m/Y', strtotime($item['ngay_vao'])) ?><br>
                                    <strong>Thời hạn:</strong> <?= $thoiHan ?> tháng<br>
                                    <strong>Ngày trả phòng dự kiến:</strong>
                                    <?php if ($isDangThue): ?>
                                    <span style="color: #22c55e; font-weight: 600;"><?= $ngayTra->format('d/m/Y') ?>
                                        (Đang thuê)</span>
                                    <?php else: ?>
                                    <span style="color: #6b7280;"><?= $ngayTra->format('d/m/Y') ?> (Đã trả phòng)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="listing-actions">
                                <a href="ChiTietPhongTro.php?id=<?= $item['phong_id'] ?>" class="btn-action review">
                                    <?= $isDangThue ? 'Xem chi tiết' : 'Xem lại' ?>
                                </a>
                                <?php if ($isDangThue): ?>
                                <a href="TraPhong.php?yeucau_id=<?= $item['id'] ?>&phong_id=<?= $item['phong_id'] ?>"
                                    class="btn-action review" style="background: #ef4444; margin-top: 8px;"
                                    onclick="return confirm('Bạn có chắc chắn muốn trả phòng này?');">
                                    <i class="fas fa-door-open me-1"></i> Trả phòng
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </div>
    </main><?php require_once "../../template/layouts/footer.php"?><script>
    const navLinks = document.querySelectorAll('.sidebar-nav li a[data-target]');
    const contentSections = document.querySelectorAll('.content-section');
    const navItems = document.querySelectorAll('.sidebar-nav li');

    navLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);

                    contentSections.forEach(section => section.classList.remove('active'));
                    navItems.forEach(item => item.classList.remove('active'));

                    if (targetSection) {
                        targetSection.classList.add('active');
                    }

                    this.parentElement.classList.add('active');
                }

            );
        }

    );
    </script>
</body>

</html>