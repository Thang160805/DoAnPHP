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

$idPhong = (int)$_GET['ID_Phong'];
$idNguoiThue = (int)$_GET['ID_NguoiThue'];
$phong = getPhongById($idPhong);
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_NguoiThue = (int)($_POST['ID_NguoiThue'] ?? 0);
    $ID_Phong     = (int)($_POST['ID_Phong'] ?? 0);
    $NgayVao      = trim($_POST['NgayDonVao'] ?? '');
    $ThoiHanThue  = trim($_POST['ThoiHanThue'] ?? '');
    $LoiNhan      = trim($_POST['LoiNhan'] ?? '');

 

    if ($id_NguoiThue <= 0 || $ID_Phong <= 0) {
        $message = 'Dữ liệu không hợp lệ!';
        $message_type = 'error';
    }
    else if ($NgayVao === '') {
        $message = 'Vui lòng chọn ngày dọn vào!';
        $message_type = 'error';
    }
    else if (strtotime($NgayVao) < strtotime(date('Y-m-d'))) {
        $message = 'Ngày dọn vào không được nhỏ hơn hôm nay!';
        $message_type = 'error';
    }
    else if (!in_array($ThoiHanThue, ['3', '6', '12'])) {
        $message = 'Thời hạn thuê không hợp lệ!';
        $message_type = 'error';
    }
    else {

       
        if ($LoiNhan === '') {
            $LoiNhan = null;
        }

        $ok = themYeuCauThue($id_NguoiThue,$ID_Phong,$NgayVao,$ThoiHanThue,$LoiNhan);

        if ($ok) {
            $message = 'Đã gửi yêu cầu thuê, vui lòng chờ chủ trọ duyệt!';
            $message_type = 'success';
        } else {
            $message = 'Gửi yêu cầu thuê thất bại, vui lòng thử lại!';
            $message_type = 'error';
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
    <link rel=" stylesheet" href="/ThucHanhPHP/template/assets/css/reset.css">
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/style.css">
    <link rel="stylesheet" href="/ThucHanhPHP/template/assets/css/YeuCauThueTro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header class="simple-header">
        <div class="container header-content">
            <a href="#" class="logo">TroVinh</a>
            <div class="header-secure">
                <i class="fa-solid fa-shield-halved"></i> Gửi yêu cầu an toàn & miễn phí
            </div>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="container">

            <div style="margin-bottom: 20px; font-size: 14px; color: var(--text-secondary);">
                <a href="ChiTietPhongTro.php?id=<?php echo $phong['id'] ?>"
                    style="text-decoration: none; color: var(--text-secondary);"><i class="fa-solid fa-arrow-left"></i>
                    Quay lại chi tiết phòng</a>
            </div>

            <div class="grid-layout">

                <div class="form-section">
                    <?php if (!empty($message)) { ?>
                    <div class="alert <?php echo ($message_type === 'success') ? 'alert-success' : 'alert-danger'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                        <?php if ($message_type === 'success') { ?>
                        <div style="margin-top:8px;font-size:13px;">
                            Tự động quay về trang chủ sau 3 giây...
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <form
                        action="YeuCauThueTro.php?ID_Phong=<?php echo $phong['id'] ?>&ID_NguoiThue=<?php echo $user['id'] ?>"
                        method="POST" id="bookingForm">

                        <div class="section-title">
                            <span class="step-number">1</span> Thông tin của bạn
                        </div>
                        <div class="form-grid">
                            <input type="hidden" name="ID_NguoiThue" value="<?php echo $user['id'] ?>">
                            <input type="hidden" name="ID_Phong" value="<?php echo $phong['id'] ?>">
                            <div class=" form-group">
                                <label>Họ và tên</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-user input-icon"></i>
                                    <input type="text" value="<?php echo $user['HoTen'] ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-phone input-icon"></i>
                                    <input type="text" value="<?php echo $user['Phone'] ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group full">
                                <label>Email liên hệ</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-envelope input-icon"></i>
                                    <input type="email" value="<?php echo $user['Email'] ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="section-title" style="margin-top: 25px;">
                            <span class="step-number">2</span> Chi tiết thuê
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Ngày dọn vào dự kiến</label>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-calendar input-icon"></i>
                                    <input type="date" name="NgayDonVao" id="startDate">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Thời gian thuê</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-clock input-icon"></i>
                                    <select name="ThoiHanThue">
                                        <option value="3">3 Tháng</option>
                                        <option value="6" selected>6 Tháng</option>
                                        <option value="12">12 Tháng (1 Năm)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group full">
                                <label>Lời nhắn cho chủ trọ (Tùy chọn)</label>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-comment-dots input-icon" style="top: 20px;"></i>
                                    <textarea name="LoiNhan" rows="3"
                                        placeholder="Ví dụ: Em là sinh viên năm 3, muốn thuê ở lâu dài..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 30px;">
                            <button type="submit" class="btn-submit" id="btnSubmit">
                                GỬI YÊU CẦU THUÊ NGAY <i class="fa-solid fa-paper-plane"></i>
                            </button>
                            <p
                                style="text-align: center; font-size: 13px; color: var(--text-secondary); margin-top: 15px;">
                                Bằng việc gửi yêu cầu, bạn đồng ý chờ chủ trọ duyệt hồ sơ trong vòng 24h.
                            </p>
                        </div>
                    </form>

                    <div id="successState" class="success-state">
                        <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                        <h2 style="font-size: 24px; color: var(--text-main); margin-bottom: 10px;">Gửi yêu cầu thành
                            công!</h2>
                        <p style="color: var(--text-secondary); margin-bottom: 30px;">
                            Yêu cầu thuê phòng <strong>P102</strong> của bạn đã được gửi đến chủ trọ. <br>
                            Vui lòng chú ý điện thoại, chủ trọ sẽ liên hệ sớm.
                        </p>
                        <div style="display: flex; gap: 15px; justify-content: center;">
                            <a href="#"
                                style="text-decoration: none; padding: 12px 24px; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-weight: 600;">Về
                                trang chủ</a>
                            <a href="#"
                                style="text-decoration: none; padding: 12px 24px; background: var(--primary); border-radius: 8px; color: white; font-weight: 600;">Quản
                                lý yêu cầu</a>
                        </div>
                    </div>

                </div>

                <div class="summary-card">
                    <img src="<?php echo $phong['AnhChinh'] ?>" alt="Room" class="room-img">
                    <div class="summary-content">
                        <span class="room-tag">CHO THUÊ</span>
                        <h3 class="room-name">
                            <?php echo $phong['title'] ?></h3>
                        <div class="room-address">
                            <i class="fa-solid fa-location-dot" style="margin-top: 2px;"></i>
                            <span>
                                <?php echo $phong['DiaChi'] ?></span>
                        </div>

                        <div class="divider"></div>

                        <div
                            style="display: flex; flex-direction: column; gap: 10px; font-size: 14px; color: var(--text-main);">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-secondary);">Diện tích:</span> <span>
                                    <?php echo $phong['DienTich'] ?>m²</span>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="price-row">
                            <span class="price-label">Giá thuê:</span>
                            <span class="price-value">
                                <?php echo formatCurrency($phong['price']) ?>đ<small
                                    style="font-size: 14px; font-weight: 400; color: var(--text-secondary);">
                                    /tháng</small>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <?php if ($message_type === 'success') { ?>
    <script>
    setTimeout(function() {
        window.location.href = "home.php";
    }, 3000); // 3 giây
    </script>
    <?php } ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('startDate');
        const form = document.getElementById('bookingForm');
        const btnSubmit = document.getElementById('btnSubmit');

        // set ngày mặc định = hôm nay (chỉ khi chưa có)
        if (startDateInput && !startDateInput.value) {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            startDateInput.value = `${yyyy}-${mm}-${dd}`;
        }

        form.addEventListener('submit', function(e) {
            const ngayVao = startDateInput ? startDateInput.value : '';
            const thoiHanEl = document.querySelector('select[name="ThoiHanThue"]');
            const thoiHan = thoiHanEl ? thoiHanEl.value.trim() : '';

            if (!ngayVao) {
                e.preventDefault();
                alert('Vui lòng chọn ngày dọn vào!');
                return;
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(ngayVao);

            if (selectedDate < today) {
                e.preventDefault();
                alert('Ngày dọn vào không được nhỏ hơn hôm nay!');
                return;
            }

            if (!['3', '6', '12'].includes(thoiHan)) {
                e.preventDefault();
                alert('Thời gian thuê không hợp lệ!');
                return;
            }

            // hợp lệ -> cho submit, disable nút
            btnSubmit.disabled = true;
            btnSubmit.innerHTML =
                '<i class="fa-solid fa-circle-notch fa-spin"></i> Đang gửi yêu cầu...';
        });
    });
    </script>



</body>

</html>