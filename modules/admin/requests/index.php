<?php

require_once __DIR__ . "/../giaodien/navbar.php";
require_once __DIR__ . "/../../../includes/database.php";

// Lọc theo trạng thái
$statusFilter = isset($_GET['status']) ? (int)$_GET['status'] : null;
$list = getYeuCauThueTro($statusFilter);

// Hiển thị thông báo
if (isset($_SESSION['success'])) {
    echo "<div style='padding: 12px 20px; background: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 20px; font-weight: 600;'>✅ " . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div style='padding: 12px 20px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px; font-weight: 600;'>❌ " . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']);
}
?>

<h2 style="margin-bottom: 20px; font-size: 28px; color: #1f2937; display: flex; align-items: center; gap: 10px;">
    📋 Quản lý yêu cầu thuê trọ
</h2>

<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="?status=0" style="
        padding: 8px 16px;
        background: #fef3c7;
        color: #92400e;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    ">⏳ Chờ duyệt</a>
    <a href="?status=2" style="
        padding: 8px 16px;
        background: #dcfce7;
        color: #166534;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    ">✅ Đã duyệt (Thành công)</a>
    <a href="?status=3" style="
        padding: 8px 16px;
        background: #fee2e2;
        color: #991b1b;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    ">❌ Không thành công</a>
    <a href="?" style="
        padding: 8px 16px;
        background: #e0e7ff;
        color: #3730a3;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    ">📊 Tất cả</a>
</div>

<div style="
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,.05);
">
    <table style="
        width: 100%;
        border-collapse: collapse;
    ">
        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
            <tr>
                <th style="padding: 16px; text-align: left; font-weight: 600;">ID</th>
                <th style="padding: 16px; text-align: left; font-weight: 600;">Phòng trọ</th>
                <th style="padding: 16px; text-align: left; font-weight: 600;">Người thuê</th>
                <th style="padding: 16px; text-align: left; font-weight: 600;">Ngày vào</th>
                <th style="padding: 16px; text-align: left; font-weight: 600;">Thời hạn</th>
                <th style="padding: 16px; text-align: left; font-weight: 600;">Trạng thái</th>
                <th style="padding: 16px; text-align: center; font-weight: 600;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($list)){ ?>
            <?php foreach($list as $r){ ?>
            <tr style="border-top: 1px solid #e5e7eb; transition: background 0.2s;">
                <td style="padding: 16px; font-weight: 600; color: #6b7280;">
                    #<?= $r['id'] ?>
                </td>
                <td style="padding: 16px;">
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                        <? htmlspecialchars($r['ten_phong']) ?>
                    </div>
                    <div style="font-size: 13px; color: #6b7280;">
                        💰 <?= number_format($r['gia_phong']) ?> đ/tháng
                    </div>
                    <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">
                        📍 <?= htmlspecialchars($r['dia_chi_phong']) ?>
                    </div>
                </td>
                <td style="padding: 16px;">
                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                        <?= htmlspecialchars($r['ten_nguoi_thue']) ?>
                    </div>
                    <div style="font-size: 13px; color: #6b7280;">
                        📞 <?= htmlspecialchars($r['sdt_nguoi_thue'] ?? 'N/A') ?>
                    </div>
                    <div style="font-size: 12px; color: #9ca3af;">
                        ✉️ <?= htmlspecialchars($r['email_nguoi_thue']) ?>
                    </div>
                </td>
                <td style="padding: 16px; color: #374151;">
                    <?= date('d/m/Y', strtotime($r['ngay_vao'])) ?>
                </td>
                <td style="padding: 16px; color: #374151;">
                    <?= $r['thoi_gian_thue'] ?> tháng
                </td>
                <td style="padding: 16px;">
                    <?php
                        $status = (int)$r['trang_thai'];
                        if ($status == 0) {
                            echo "<span style='
                                padding: 6px 12px;
                                border-radius: 20px;
                                background: #fef3c7;
                                color: #92400e;
                                font-weight: 600;
                                font-size: 13px;
                                display: inline-block;
                            '>⏳ Chờ duyệt</span>";
                        } elseif ($status == 2) {
                            echo "<span style='
                                padding: 6px 12px;
                                border-radius: 20px;
                                background: #dcfce7;
                                color: #166534;
                                font-weight: 600;
                                font-size: 13px;
                                display: inline-block;
                            '>✅ Thành công</span>";
                        } elseif ($status == 3) {
                            echo "<span style='
                                padding: 6px 12px;
                                border-radius: 20px;
                                background: #fee2e2;
                                color: #991b1b;
                                font-weight: 600;
                                font-size: 13px;
                                display: inline-block;
                            '>❌ Không thành công</span>";
                        } elseif ($status == 4) {
                            echo "<span style='
                                padding: 6px 12px;
                                border-radius: 20px;
                                background: #e0e7ff;
                                color: #3730a3;
                                font-weight: 600;
                                font-size: 13px;
                                display: inline-block;
                            '>📋 Đã từng thuê</span>";
                        } else {
                            echo "<span style='
                                padding: 6px 12px;
                                border-radius: 20px;
                                background: #f3f4f6;
                                color: #6b7280;
                                font-weight: 600;
                                font-size: 13px;
                                display: inline-block;
                            '>❓ Khác</span>";
                        }
                        ?>
                </td>
                <td style="padding: 16px; text-align: center; white-space: nowrap;">
                    <?php if ($status == 0){ ?>
                    <a href="approve.php?id=<?= $r['id'] ?>"
                        onclick="return confirm('Bạn có chắc chắn muốn duyệt yêu cầu này?');" style="
                                   display: inline-block;
                                   padding: 6px 14px;
                                   background: #22c55e;
                                   color: #fff;
                                   text-decoration: none;
                                   border-radius: 6px;
                                   font-weight: 600;
                                   font-size: 13px;
                                   margin-right: 6px;
                               ">✅ Duyệt</a>
                    <a href="reject.php?id=<?= $r['id'] ?>"
                        onclick="return confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?');" style="
                                   display: inline-block;
                                   padding: 6px 14px;
                                   background: #ef4444;
                                   color: #fff;
                                   text-decoration: none;
                                   border-radius: 6px;
                                   font-weight: 600;
                                   font-size: 13px;
                               ">❌ Từ chối</a>
                    <?php }else{ ?>
                    <span style="color: #9ca3af; font-size: 13px;">-</span>
                    <?php } ?>
                    <br>
                    <a href="detail.php?id=<?= $r['id'] ?>" style="
                               display: inline-block;
                               margin-top: 6px;
                               padding: 4px 10px;
                               background: #e0e7ff;
                               color: #3730a3;
                               text-decoration: none;
                               border-radius: 6px;
                               font-size: 12px;
                           ">👁️ Chi tiết</a>
                </td>
            </tr>
            <?php if (!empty($r['loi_nhan'])){ ?>
            <tr>
                <td colspan="7" style="padding: 0 16px 16px 16px;">
                    <div style="
                            background: #f9fafb;
                            padding: 12px;
                            border-radius: 8px;
                            border-left: 3px solid #6366f1;
                            font-size: 13px;
                            color: #4b5563;
                        ">
                        <strong>💬 Lời nhắn:</strong> <?= htmlspecialchars($r['loi_nhan']) ?>
                    </div>
                </td>
            </tr>
            <?php
            }
        }
            } else {
             ?>
            <tr>
                <td colspan="7" style="padding: 40px; text-align: center; color: #9ca3af;">
                    <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
                    <div style="font-size: 18px; font-weight: 600;">Không có yêu cầu nào</div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div>
</div>
</body>

</html>