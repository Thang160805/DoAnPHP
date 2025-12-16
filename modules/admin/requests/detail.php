<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
if (!isset($_SESSION['username'])) {
  header('Location: ../../modules/auth/login.php'); exit;
}
require_once __DIR__ . "/../giaodien/navbar.php";
require_once __DIR__ . "/../../../includes/database.php";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Lấy chi tiết yêu cầu
$sql = "
    SELECT 
        yct.*,
        pt.title AS ten_phong,
        pt.price AS gia_phong,
        pt.DienTich,
        pt.DiaChi AS dia_chi_phong,
        pt.description AS mo_ta_phong,
        pt.AnhChinh,
        tk_nguoi_thue.HoTen AS ten_nguoi_thue,
        tk_nguoi_thue.Phone AS sdt_nguoi_thue,
        tk_nguoi_thue.Email AS email_nguoi_thue,
        tk_nguoi_thue.DiaChi AS dia_chi_nguoi_thue,
        tk_chu_tro.HoTen AS ten_chu_tro,
        tk_chu_tro.Phone AS sdt_chu_tro,
        tk_chu_tro.Email AS email_chu_tro
    FROM yeucauthuetro yct
    JOIN phongtro pt ON yct.phong_id = pt.id
    JOIN taikhoan tk_nguoi_thue ON yct.nguoi_thue_id = tk_nguoi_thue.id
    JOIN taikhoan tk_chu_tro ON pt.Id_ChuTro = tk_chu_tro.id
    WHERE yct.id = ?
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$r = mysqli_fetch_assoc($result);

if (!$r) {
    header("Location: index.php");
    exit;
}
?>

<h2 style="margin-bottom: 20px; font-size: 28px; color: #1f2937;">
    📋 Chi tiết yêu cầu thuê trọ #<?= $r['id'] ?>
</h2>

<a href="index.php" style="
    display: inline-block;
    margin-bottom: 20px;
    padding: 8px 16px;
    background: #e0e7ff;
    color: #3730a3;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
">← Quay lại</a>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">

    <!-- Thông tin phòng trọ -->
    <div style="
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,.05);
    ">
        <h3
            style="margin-bottom: 20px; color: #1f2937; font-size: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
            🏠 Thông tin phòng trọ
        </h3>

        <?php if ($r['AnhChinh']): ?>
        <img src="<?= htmlspecialchars($r['AnhChinh']) ?>" alt="Phòng trọ" style="
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 16px;
        ">
        <?php endif; ?>

        <div style="margin-bottom: 12px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Tiêu đề:</strong>
            <span
                style="color: #1f2937; font-weight: 600; font-size: 18px;"><?= htmlspecialchars($r['ten_phong']) ?></span>
        </div>

        <div style="margin-bottom: 12px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Địa chỉ:</strong>
            <span style="color: #374151;">📍 <?= htmlspecialchars($r['dia_chi_phong']) ?></span>
        </div>

        <div style="margin-bottom: 12px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Diện tích:</strong>
            <span style="color: #374151;"><?= $r['DienTich'] ?> m²</span>
        </div>

        <div style="margin-bottom: 12px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Giá thuê:</strong>
            <span style="color: #22c55e; font-weight: 600; font-size: 20px;"><?= number_format($r['gia_phong']) ?>
                đ/tháng</span>
        </div>

        <?php if ($r['mo_ta_phong']): ?>
        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Mô tả:</strong>
            <p style="color: #4b5563; line-height: 1.6;"><?= nl2br(htmlspecialchars($r['mo_ta_phong'])) ?></p>
        </div>
        <?php endif; ?>

        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <strong style="color: #6b7280; display: block; margin-bottom: 8px;">Chủ trọ:</strong>
            <div style="color: #374151;">👤 <?= htmlspecialchars($r['ten_chu_tro']) ?></div>
            <div style="color: #6b7280; font-size: 14px; margin-top: 4px;">📞
                <?= htmlspecialchars($r['sdt_chu_tro'] ?? 'N/A') ?></div>
            <div style="color: #6b7280; font-size: 14px;">✉️ <?= htmlspecialchars($r['email_chu_tro']) ?></div>
        </div>
    </div>

    <!-- Thông tin người thuê -->
    <div style="
        background: #fff;
        padding: 24px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,.05);
    ">
        <h3
            style="margin-bottom: 20px; color: #1f2937; font-size: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
            👤 Thông tin người thuê
        </h3>

        <div style="margin-bottom: 16px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Họ và tên:</strong>
            <span
                style="color: #1f2937; font-weight: 600; font-size: 18px;"><?= htmlspecialchars($r['ten_nguoi_thue']) ?></span>
        </div>

        <div style="margin-bottom: 16px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Số điện thoại:</strong>
            <span style="color: #374151;">📞 <?= htmlspecialchars($r['sdt_nguoi_thue'] ?? 'N/A') ?></span>
        </div>

        <div style="margin-bottom: 16px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Email:</strong>
            <span style="color: #374151;">✉️ <?= htmlspecialchars($r['email_nguoi_thue']) ?></span>
        </div>

        <?php if ($r['dia_chi_nguoi_thue']): ?>
        <div style="margin-bottom: 16px;">
            <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Địa chỉ:</strong>
            <span style="color: #374151;">📍 <?= htmlspecialchars($r['dia_chi_nguoi_thue']) ?></span>
        </div>
        <?php endif; ?>

        <div style="margin-top: 24px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
            <h4 style="margin-bottom: 16px; color: #1f2937; font-size: 18px;">📅 Chi tiết yêu cầu</h4>

            <div style="margin-bottom: 12px;">
                <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Ngày dọn vào:</strong>
                <span style="color: #374151; font-weight: 600;"><?= date('d/m/Y', strtotime($r['ngay_vao'])) ?></span>
            </div>

            <div style="margin-bottom: 12px;">
                <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Thời hạn thuê:</strong>
                <span style="color: #374151; font-weight: 600;"><?= $r['thoi_gian_thue'] ?> tháng</span>
            </div>

            <div style="margin-bottom: 12px;">
                <strong style="color: #6b7280; display: block; margin-bottom: 4px;">Ngày gửi yêu cầu:</strong>
                <span style="color: #374151;"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></span>
            </div>

            <div style="margin-top: 16px;">
                <strong style="color: #6b7280; display: block; margin-bottom: 8px;">Trạng thái:</strong>
                <?php
                $status = (int)$r['trang_thai'];
                if ($status == 0) {
                    echo "<span style='
                        padding: 8px 16px;
                        border-radius: 20px;
                        background: #fef3c7;
                        color: #92400e;
                        font-weight: 600;
                        display: inline-block;
                    '>⏳ Chờ duyệt</span>";
                } elseif ($status == 2) {
                    echo "<span style='
                        padding: 8px 16px;
                        border-radius: 20px;
                        background: #dcfce7;
                        color: #166534;
                        font-weight: 600;
                        display: inline-block;
                    '>✅ Thành công</span>";
                } elseif ($status == 3) {
                    echo "<span style='
                        padding: 8px 16px;
                        border-radius: 20px;
                        background: #fee2e2;
                        color: #991b1b;
                        font-weight: 600;
                        display: inline-block;
                    '>❌ Không thành công</span>";
                } elseif ($status == 4) {
                    echo "<span style='
                        padding: 8px 16px;
                        border-radius: 20px;
                        background: #e0e7ff;
                        color: #3730a3;
                        font-weight: 600;
                        display: inline-block;
                    '>📋 Đã từng thuê</span>";
                } else {
                    echo "<span style='
                        padding: 8px 16px;
                        border-radius: 20px;
                        background: #f3f4f6;
                        color: #6b7280;
                        font-weight: 600;
                        display: inline-block;
                    '>❓ Khác</span>";
                }
                ?>
            </div>
        </div>

        <?php if ($r['loi_nhan']): ?>
        <div
            style="margin-top: 20px; padding: 16px; background: #f9fafb; border-radius: 8px; border-left: 4px solid #6366f1;">
            <strong style="color: #6b7280; display: block; margin-bottom: 8px;">💬 Lời nhắn từ người thuê:</strong>
            <p style="color: #4b5563; line-height: 1.6; margin: 0;"><?= nl2br(htmlspecialchars($r['loi_nhan'])) ?></p>
        </div>
        <?php endif; ?>

        <?php if ($status == 0): ?>
        <div style="margin-top: 24px; display: flex; gap: 10px;">
            <a href="approve.php?id=<?= $r['id'] ?>"
                onclick="return confirm('Bạn có chắc chắn muốn duyệt yêu cầu này?');" style="
                   flex: 1;
                   padding: 12px;
                   background: #22c55e;
                   color: #fff;
                   text-decoration: none;
                   border-radius: 8px;
                   font-weight: 600;
                   text-align: center;
               ">✅ Duyệt yêu cầu</a>
            <a href="reject.php?id=<?= $r['id'] ?>"
                onclick="return confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?');" style="
                   flex: 1;
                   padding: 12px;
                   background: #ef4444;
                   color: #fff;
                   text-decoration: none;
                   border-radius: 8px;
                   font-weight: 600;
                   text-align: center;
               ">❌ Từ chối</a>
        </div>
        <?php endif; ?>
    </div>
</div>

</div>
</div>
</body>

</html>