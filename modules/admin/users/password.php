<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../../../includes/database.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Lấy thông tin admin để hiển thị
$sql = "SELECT TenDangNhap, HoTen FROM taikhoan WHERE id=? AND Vaitro=0";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$admin) {
    header("Location: index.php");
    exit;
}

$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPass = trim($_POST['oldpass'] ?? '');
    $pass1 = trim($_POST['pass1'] ?? '');
    $pass2 = trim($_POST['pass2'] ?? '');

    if ($oldPass === "" || $pass1 === "" || $pass2 === "") {
        $error = "❌ Không được để trống!";
    } else {
        // Kiểm tra mật khẩu cũ
        $sqlCheck = "SELECT MatKhau FROM taikhoan WHERE id = ? AND Vaitro = 0";
        $stmtCheck = mysqli_prepare($conn, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "i", $id);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $adminData = mysqli_fetch_assoc($resultCheck);
        mysqli_stmt_close($stmtCheck);

        if (!$adminData || !password_verify($oldPass, $adminData['MatKhau'])) {
            $error = "❌ Mật khẩu cũ không đúng!";
        } elseif (strlen($pass1) < 6) {
            $error = "❌ Mật khẩu mới phải có ít nhất 6 ký tự!";
        } elseif ($pass1 !== $pass2) {
            $error = "❌ Mật khẩu mới không khớp!";
        } else {
            $hash = password_hash($pass1, PASSWORD_DEFAULT);

            $sql = "UPDATE taikhoan SET MatKhau=? WHERE id=? AND Vaitro=0";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hash, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "✅ Đổi mật khẩu thành công!";
                // Reset form sau 2 giây
                header("Refresh:2;url=password.php?id=" . $id);
            } else {
                $error = "❌ Lỗi khi đổi mật khẩu: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

require_once __DIR__ . "/../giaodien/navbar.php";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu Admin</title>
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
    .password-strength {
        height: 4px;
        border-radius: 2px;
        margin-top: 8px;
        transition: all 0.3s;
    }
    .strength-weak { background: #ef4444; width: 33%; }
    .strength-medium { background: #f59e0b; width: 66%; }
    .strength-strong { background: #22c55e; width: 100%; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h2 class="text-primary"><i class="fa-solid fa-key me-2"></i>Đổi mật khẩu Admin</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin</label>
                    <div class="info-badge">
                        <i class="fa-solid fa-user me-2"></i><?= htmlspecialchars($admin['HoTen']) ?> 
                        <span class="text-muted">(<?= htmlspecialchars($admin['TenDangNhap']) ?>)</span>
                    </div>
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

                <form method="post" id="passwordForm">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu cũ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="oldpass" id="oldpass" class="form-control" 
                                   placeholder="Nhập mật khẩu cũ" required autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('oldpass', 'toggleIcon0')">
                                <i class="fa-solid fa-eye" id="toggleIcon0"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="pass1" id="pass1" class="form-control" 
                                   placeholder="Nhập mật khẩu mới" required minlength="6" autocomplete="new-password">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('pass1', 'toggleIcon1')">
                                <i class="fa-solid fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="strengthBar"></div>
                        <small class="text-muted">Tối thiểu 6 ký tự</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="pass2" id="pass2" class="form-control" 
                                   placeholder="Nhập lại mật khẩu" required minlength="6" autocomplete="new-password">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('pass2', 'toggleIcon2')">
                                <i class="fa-solid fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                        <div id="matchMessage" class="mt-2"></div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="index.php" class="btn btn-light me-md-2">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa-solid fa-key me-2"></i>Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
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

// Kiểm tra mật khẩu cũ
document.getElementById('oldpass').addEventListener('blur', function() {
    const oldPass = this.value;
    if (oldPass.length > 0) {
        // Có thể thêm AJAX check ở đây nếu cần
    }
});

// Kiểm tra độ mạnh mật khẩu
document.getElementById('pass1').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strengthBar');
    
    if (password.length === 0) {
        strengthBar.className = 'password-strength';
        strengthBar.style.width = '0%';
        return;
    }
    
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    strengthBar.className = 'password-strength';
    if (strength <= 2) {
        strengthBar.classList.add('strength-weak');
    } else if (strength <= 3) {
        strengthBar.classList.add('strength-medium');
    } else {
        strengthBar.classList.add('strength-strong');
    }
});

// Kiểm tra mật khẩu khớp
document.getElementById('pass2').addEventListener('input', function() {
    const pass1 = document.getElementById('pass1').value;
    const pass2 = this.value;
    const matchMessage = document.getElementById('matchMessage');
    
    if (pass2.length === 0) {
        matchMessage.innerHTML = '';
        return;
    }
    
    if (pass1 === pass2) {
        matchMessage.innerHTML = '<small class="text-success"><i class="fa-solid fa-check-circle me-1"></i>Mật khẩu khớp</small>';
    } else {
        matchMessage.innerHTML = '<small class="text-danger"><i class="fa-solid fa-times-circle me-1"></i>Mật khẩu không khớp</small>';
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
