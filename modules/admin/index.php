<?php
require_once __DIR__ . "/giaodien/navbar.php";
require_once __DIR__ . "/../../includes/database.php";

// Đếm số liệu
$totalRooms   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM phongtro"))['c'];
$pendingRooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM phongtro WHERE TrangThai = 0"))['c'];
$totalNguoiThue   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM taikhoan WHERE Vaitro = 2"))['c'];
$totalChutro   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM taikhoan WHERE Vaitro = 1"))['c'];
$monthPosts   = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS c FROM phongtro 
    WHERE MONTH(NgayDang) = MONTH(NOW()) AND YEAR(NgayDang)=YEAR(NOW())
"))['c'];
$pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM yeucauthuetro WHERE trang_thai = 0"))['c'];
$totalRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM yeucauthuetro"))['c'];
?>

<h1 style="
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 30px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
">
    📊 ADMIN DASHBOARD
</h1>

<div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="stat-icon">🏠</div>
        <div class="stat-content">
            <div class="stat-label">Tổng phòng</div>
            <div class="stat-value"><?= $totalRooms ?></div>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">⏳</div>
        <div class="stat-content">
            <div class="stat-label">Chờ duyệt phòng</div>
            <div class="stat-value"><?= $pendingRooms ?></div>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="stat-icon">👤</div>
        <div class="stat-content">
            <div class="stat-label">Người thuê</div>
            <div class="stat-value"><?= $totalNguoiThue ?></div>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="stat-icon">👤</div>
        <div class="stat-content">
            <div class="stat-label">Chủ trọ</div>
            <div class="stat-value"><?= $totalChutro ?></div>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="stat-icon">📋</div>
        <div class="stat-content">
            <div class="stat-label">Yêu cầu thuê trọ</div>
            <div class="stat-value"><?= $totalRequests ?></div>
            <?php if ($pendingRequests > 0): ?>
            <div class="stat-badge"><?= $pendingRequests ?> chờ duyệt</div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
        <div class="stat-icon">📅</div>
        <div class="stat-content">
            <div class="stat-label">Tin tháng này</div>
            <div class="stat-value"><?= $monthPosts ?></div>
        </div>
    </div>
</div>

<div style="
    background: #fff;
    padding: 24px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,.05);
    margin-top: 20px;
">
    <h2 style="margin-bottom: 20px; color: #1f2937; font-size: 22px;">⚡ Hành động nhanh</h2>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="rooms/index.php" style="
            padding: 12px 20px;
            background: #6366f1;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        ">🏘 Quản lý phòng</a>
        <a href="requests/index.php" style="
            padding: 12px 20px;
            background: #22c55e;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        ">📋 Yêu cầu thuê trọ
            <?php if ($pendingRequests > 0): ?>
            <span style="
                background: rgba(255,255,255,0.3);
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 12px;
            "><?= $pendingRequests ?></span>
            <?php endif; ?>
        </a>
        <a href="users/index.php" style="
            padding: 12px 20px;
            background: #f59e0b;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        ">👤 Quản lý admin</a>
    </div>
</div>

<style>
.stat-card {
    padding: 24px;
    border-radius: 14px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0,0,0,.2);
}

.stat-icon {
    font-size: 40px;
    opacity: 0.9;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 6px;
    font-weight: 500;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    line-height: 1;
}

.stat-badge {
    font-size: 11px;
    background: rgba(255,255,255,0.25);
    padding: 4px 10px;
    border-radius: 12px;
    margin-top: 8px;
    display: inline-block;
    font-weight: 600;
}
</style>

</div></div></body></html>
