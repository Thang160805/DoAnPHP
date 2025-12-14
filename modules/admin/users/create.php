<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../giaodien/navbar.php";
require_once __DIR__ . "/../../../includes/database.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    $hoten = trim($_POST['hoten'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($u === "" || $p === "") {
        $error = "❌ Vui lòng nhập đầy đủ username và password!";
    } elseif (strlen($p) < 6) {
        $error = "❌ Mật khẩu phải có ít nhất 6 ký tự!";
    } else {
        // Kiểm tra username đã tồn tại chưa
        $sqlCheck = "SELECT id FROM taikhoan WHERE TenDangNhap = ? LIMIT 1";
        $stmtCheck = mysqli_prepare($conn, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $u);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        
        if (mysqli_num_rows($resultCheck) > 0) {
            $error = "❌ Username đã tồn tại!";
        } else {
            $pHash = password_hash($p, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO taikhoan(TenDangNhap, MatKhau, HoTen, Email, Vaitro, Trangthai, NgayTao) VALUES (?, ?, ?, ?, 0, 1, NOW())";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $u, $pHash, $hoten, $email);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "✅ Thêm admin thành công!";
                // Reset form sau 2 giây
                header("Refresh:2;url=create.php");
            } else {
                $error = "❌ Lỗi khi thêm admin: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($stmtCheck);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Admin</title>
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
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        transition: border-color 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h2 class="text-primary"><i class="fa-solid fa-user-plus me-2"></i>Thêm Admin mới</h2>
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
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="username" class="form-control" 
                                       placeholder="Nhập username" required autocomplete="off">
                            </div>
                            <small class="text-muted">Username dùng để đăng nhập</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" 
                                       placeholder="Nhập mật khẩu" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                    <i class="fa-solid fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                <input type="text" name="hoten" class="form-control" 
                                       placeholder="Nhập họ và tên">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="example@email.com">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="index.php" class="btn btn-light me-md-2">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Lưu Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
