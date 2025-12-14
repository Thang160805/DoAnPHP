<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../../../includes/database.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

/* LẤY THÔNG TIN ADMIN */
$sql = "SELECT TenDangNhap, HoTen, Email FROM taikhoan WHERE id=? AND Vaitro=0";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$admin) {
    header("Location: index.php");
    exit;
}

$error = $success = "";

/* SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoten = trim($_POST['hoten'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($hoten === "" || $email === "") {
        $error = "❌ Không được để trống!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Email không hợp lệ!";
    } else {
        $sql = "UPDATE taikhoan SET HoTen=?, Email=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $hoten, $email, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "✅ Cập nhật thành công!";
            // Load lại dữ liệu
            header("Refresh:2;url=edit.php?id=" . $id);
        } else {
            $error = "❌ Lỗi cập nhật: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

require_once __DIR__ . "/../giaodien/navbar.php";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
    }
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .info-badge {
        background: #e0e7ff;
        color: #3730a3;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-block;
    }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h2 class="text-primary"><i class="fa-solid fa-user-edit me-2"></i>Sửa thông tin Admin</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-4">
                        <label class="form-label">Username</label>
                        <div class="info-badge">
                            <i class="fa-solid fa-user me-2"></i><?= htmlspecialchars($admin['TenDangNhap']) ?>
                        </div>
                        <small class="text-muted d-block mt-2">Username không thể thay đổi</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                <input type="text" name="hoten" class="form-control" 
                                       value="<?= htmlspecialchars($admin['HoTen']) ?>" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" 
                                       value="<?= htmlspecialchars($admin['Email']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="index.php" class="btn btn-light me-md-2">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
