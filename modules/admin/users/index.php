<?php
require_once __DIR__."/../giaodien/navbar.php";
require_once __DIR__."/../../../includes/database.php";

$sql = "SELECT id,TenDangNhap,HoTen,Email,Trangthai
        FROM taikhoan WHERE Vaitro=0";
$rs = mysqli_query($conn,$sql);
?>

<h2 style="margin-bottom: 20px; font-size: 28px; color: #1f2937; display: flex; align-items: center; gap: 10px;">
    👤 Quản lý Admin
</h2>

<a href="create.php" style="
    display: inline-block;
    margin-bottom: 20px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transition: transform 0.2s;
">
    ➕ Thêm admin
</a>

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
                <th style="padding: 16px; text-align: left; font-weight: 600;">Username</th>
                <th style="padding: 16px; text-align: left; font-weight: 600;">Họ tên</th>
                <th style="padding: 16px; text-align: left; font-weight: 600;">Email</th>
                <th style="padding: 16px; text-align: center; font-weight: 600;">Trạng thái</th>
                <th style="padding: 16px; text-align: center; font-weight: 600;">Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php while($r=mysqli_fetch_assoc($rs)): ?>
            <tr style="border-top: 1px solid #e5e7eb;">
                <td style="padding: 16px; font-weight: 600; color: #6b7280;">#<?= $r['id'] ?></td>
                <td style="padding: 16px; color: #1f2937; font-weight: 600;"><?= htmlspecialchars($r['TenDangNhap']) ?></td>
                <td style="padding: 16px; color: #374151;"><?= htmlspecialchars($r['HoTen']) ?></td>
                <td style="padding: 16px; color: #374151;"><?= htmlspecialchars($r['Email']) ?></td>
                <td style="padding: 16px; text-align: center;">
                    <?php if ($r['Trangthai']): ?>
                        <span style="
                            padding: 6px 12px;
                            border-radius: 20px;
                            background: #dcfce7;
                            color: #166534;
                            font-weight: 600;
                            font-size: 13px;
                            display: inline-block;
                        ">✅ Hoạt động</span>
                    <?php else: ?>
                        <span style="
                            padding: 6px 12px;
                            border-radius: 20px;
                            background: #fee2e2;
                            color: #991b1b;
                            font-weight: 600;
                            font-size: 13px;
                            display: inline-block;
                        ">🔒 Khóa</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 16px; text-align: center; white-space: nowrap;">
                    <a href="edit.php?id=<?= $r['id'] ?>" style="
                        display: inline-block;
                        padding: 6px 14px;
                        background: #6366f1;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 6px;
                        font-weight: 600;
                        font-size: 13px;
                        margin-right: 6px;
                    ">✏️ Sửa</a>
                    <a href="password.php?id=<?= $r['id'] ?>" style="
                        display: inline-block;
                        padding: 6px 14px;
                        background: #f59e0b;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 6px;
                        font-weight: 600;
                        font-size: 13px;
                    ">🔑 Đổi MK</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div></div></body></html>
