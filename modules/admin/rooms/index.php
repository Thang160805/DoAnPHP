<?php
require_once __DIR__ . "/../giaodien/navbar.php";
require_once __DIR__ . "/../../../includes/database.php";

/* LẤY DANH SÁCH PHÒNG */
$sql = "
    SELECT pt.id, pt.title, pt.price, pt.TrangThai, tk.HoTen
    FROM phongtro pt
    JOIN taikhoan tk ON pt.Id_ChuTro = tk.id
    ORDER BY pt.id DESC
";
$rs = mysqli_query($conn, $sql);
?>

<h2 style="margin-bottom:15px">🏘 Quản lý phòng</h2>

<a href="create.php" style="
    display:inline-block;
    margin-bottom:15px;
    padding:10px 18px;
    background:#6366f1;
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    font-weight:600;
">
➕ Thêm phòng
</a>

<table style="
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
">
    <thead style="background:#f3f4f6">
        <tr>
            <th style="padding:14px;text-align:center">ID</th>
            <th>Tiêu đề</th>
            <th>Chủ trọ</th>
            <th style="text-align:right;padding-right:20px">Giá</th>
            <th style="text-align:center">Trạng thái</th>
            <th style="text-align:center">Hành động</th>
        </tr>
    </thead>

    <tbody>
    <?php while ($r = mysqli_fetch_assoc($rs)): ?>
        <tr style="border-top:1px solid #e5e7eb">
            <td style="padding:12px;text-align:center">
                <?= $r['id'] ?>
            </td>

            <td style="padding:12px">
                <?= htmlspecialchars($r['title']) ?>
            </td>

            <td><?= htmlspecialchars($r['HoTen']) ?></td>

            <td style="text-align:right;padding-right:20px">
                <?= number_format($r['price']) ?> đ
            </td>

            <!-- TRẠNG THÁI -->
            <td style="text-align:center">
                <?php
                if ($r['TrangThai'] == 1) {
                    echo "<span style='
                        padding:4px 10px;
                        border-radius:999px;
                        background:#dcfce7;
                        color:#166534;
                        font-weight:600;
                    '>✔ Đã duyệt</span>";
                } elseif ($r['TrangThai'] == 0) {
                    echo "<span style='
                        padding:4px 10px;
                        border-radius:999px;
                        background:#fef9c3;
                        color:#854d0e;
                        font-weight:600;
                    '>⏳ Chờ duyệt</span>";
                } else {
                    echo "<span style='
                        padding:4px 10px;
                        border-radius:999px;
                        background:#fee2e2;
                        color:#991b1b;
                        font-weight:600;
                    '>🚫 Đã ẩn</span>";
                }
                ?>
            </td>

            <!-- HÀNH ĐỘNG -->
            <td style="text-align:center;white-space:nowrap">
                <a href="edit.php?id=<?= $r['id'] ?>">✏️ Sửa</a> |

                <?php if ($r['TrangThai'] == 1): ?>
                    <a href="toggle.php?id=<?= $r['id'] ?>&action=hide"
                       onclick="return confirm('Ẩn phòng này khỏi trang người dùng?');">
                       👁️‍🗨️ Ẩn
                    </a>
                <?php else: ?>
                    <a href="toggle.php?id=<?= $r['id'] ?>&action=show"
                       onclick="return confirm('Hiện lại phòng này?');">
                       👁️ Hiện
                    </a>
                <?php endif; ?>

                | <a href="delete.php?id=<?= $r['id'] ?>"
                     onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này không?');"
                     style="color:#dc2626;font-weight:600">
                     ❌ Xóa
                  </a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</div></div></body></html>
