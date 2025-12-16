<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
if (!isset($_SESSION['username'])) {
  header('Location: ../../modules/auth/login.php'); exit;
}
/* CHẶN USER THƯỜNG – CHỈ ADMIN */
require_once __DIR__ . "/../auth.php";

/* GIAO DIỆN + CSDL */
require_once __DIR__ . "/../giaodien/navbar.php";
require_once __DIR__ . "/../../../includes/database.php";

$error = "";
$success = "";

// Lấy ID admin đang đăng nhập (tất cả admin đều là chủ trọ)
$username = $_SESSION['username'] ?? '';
$sqlAdmin = "SELECT id FROM taikhoan WHERE TenDangNhap = ? AND Vaitro = 0 LIMIT 1";
$stmtAdmin = mysqli_prepare($conn, $sqlAdmin);
mysqli_stmt_bind_param($stmtAdmin, "s", $username);
mysqli_stmt_execute($stmtAdmin);
$resultAdmin = mysqli_stmt_get_result($stmtAdmin);
$adminInfo = mysqli_fetch_assoc($resultAdmin);
$id_chutro = $adminInfo['id'] ?? 0;
mysqli_stmt_close($stmtAdmin);

/* LẤY DANH SÁCH KHU VỰC */
$sqlArea = "SELECT id, name_area FROM area";
$resultArea = mysqli_query($conn, $sqlArea);

/* LẤY DANH SÁCH TIỆN ÍCH */
$sqlUtilities = "SELECT id, name_uti FROM utilities ORDER BY id";
$resultUtilities = mysqli_query($conn, $sqlUtilities);

/* XỬ LÝ FORM */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']);
    $price       = (int)$_POST['price'];
    $dientich    = (float)$_POST['dientich'];
    $diachi      = trim($_POST['diachi']);
    $description = trim($_POST['description']);
    $area_id     = (int)$_POST['area_id'];
    $trangthai   = (int)$_POST['trangthai'];
    $utilities   = isset($_POST['utilities']) ? $_POST['utilities'] : [];

    if ($title === "" || $price <= 0 || $id_chutro <= 0 || $area_id <= 0) {
        $error = "❌ Vui lòng nhập đầy đủ thông tin bắt buộc!";
    } else {
        
        // Xử lý upload ảnh
        $anhChinh = null;
        $uploadDir = __DIR__ . '/../../../template/assets/img/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['anhchinh']) && $_FILES['anhchinh']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['anhchinh']['tmp_name'];
            $fileSize = $_FILES['anhchinh']['size'];
            $ext = strtolower(pathinfo($_FILES['anhchinh']['name'], PATHINFO_EXTENSION));
            
            // Kiểm tra file ảnh
            if (getimagesize($tmpName)) {
                $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($ext, $allowedExt)) {
                    if ($fileSize <= 5 * 1024 * 1024) { // 5MB
                        $newFileName = 'phongtro_' . time() . '_' . uniqid() . '.' . $ext;
                        $uploadPath = $uploadDir . $newFileName;
                        
                        if (move_uploaded_file($tmpName, $uploadPath)) {
                            // Lưu đường dẫn đầy đủ hoặc chỉ tên file tùy vào cách hiển thị
                            $anhChinh = '/CaseStudy/template/assets/img/' . $newFileName;
                        } else {
                            $error = "❌ Upload ảnh thất bại!";
                        }
                    } else {
                        $error = "❌ Ảnh quá lớn (tối đa 5MB)!";
                    }
                } else {
                    $error = "❌ Chỉ cho phép ảnh jpg, jpeg, png, gif, webp!";
                }
            } else {
                $error = "❌ File không phải là ảnh!";
            }
        }

        if (empty($error)) {
            $sql = "
                INSERT INTO phongtro
                (title, price, DienTich, DiaChi, description, Id_ChuTro, area_id, TrangThai, NgayDang, AnhChinh)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
            ";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "sddssiiss",
                $title,
                $price,
                $dientich,
                $diachi,
                $description,
                $id_chutro,
                $area_id,
                $trangthai,
                $anhChinh
            );

            if (mysqli_stmt_execute($stmt)) {
                $phongId = mysqli_insert_id($conn);
                
                // Lưu tiện ích
                if (!empty($utilities) && is_array($utilities)) {
                    foreach ($utilities as $utiId) {
                        $utiId = (int)$utiId;
                        if ($utiId > 0) {
                            $sqlUti = "INSERT INTO phongtroutilities (phongtro_id, uti_id) VALUES (?, ?)";
                            $stmtUti = mysqli_prepare($conn, $sqlUti);
                            mysqli_stmt_bind_param($stmtUti, "ii", $phongId, $utiId);
                            mysqli_stmt_execute($stmtUti);
                            mysqli_stmt_close($stmtUti);
                        }
                    }
                }
                
                $success = "✅ Thêm phòng trọ thành công!";
                // Reset form sau 2 giây
                header("Refresh:2;url=create.php");
            } else {
                $error = "❌ Lỗi khi thêm phòng: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm phòng trọ mới</title>
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
    }

    .image-preview-container {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .img-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    /* Style cho checkbox tiện ích đẹp hơn */
    .utility-checkbox .form-check-input:checked+.form-check-label {
        color: #0d6efd;
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h2 class="text-primary"><i class="fa-solid fa-house-chimney-medical me-2"></i>Thêm phòng
                            trọ mới</h2>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm"><i
                                class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                    </div>

                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Tiêu đề phòng <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="VD: Phòng trọ cao cấp gần ĐH..." required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Giá thuê (VNĐ) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="price" class="form-control" placeholder="0"
                                                required>
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Diện tích (m²)</label>
                                        <div class="input-group">
                                            <input type="number" step="0.1" name="dientich" class="form-control"
                                                placeholder="0">
                                            <span class="input-group-text">m²</span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Địa chỉ chi tiết</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i
                                                    class="fa-solid fa-location-dot"></i></span>
                                            <input type="text" name="diachi" class="form-control"
                                                placeholder="Số nhà, tên đường...">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Khu vực <span class="text-danger">*</span></label>
                                        <select name="area_id" class="form-select" required>
                                            <option value="">-- Chọn khu vực --</option>
                                            <?php 
                                        // Reset pointer data nếu cần thiết
                                        if(isset($resultArea) && mysqli_num_rows($resultArea) > 0) {
                                            mysqli_data_seek($resultArea, 0); 
                                            while ($a = mysqli_fetch_assoc($resultArea)): ?>
                                            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name_area']) ?>
                                            </option>
                                            <?php endwhile; 
                                        } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Trạng thái hiển thị</label>
                                        <select name="trangthai" class="form-select">
                                            <option value="1" class="text-success">✔️ Hiển thị ngay</option>
                                            <option value="0" class="text-warning">⏳ Chờ duyệt / Ẩn</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Mô tả chi tiết</label>
                                        <textarea name="description" class="form-control" rows="4"
                                            placeholder="Mô tả về phòng, giờ giấc, an ninh..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">📷 Ảnh chính phòng</label>
                                    <input type="file" name="anhchinh" id="imgInput" class="form-control"
                                        accept="image/*">
                                    <div class="form-text text-muted">Chọn ảnh (JPG, PNG, GIF, WEBP - tối đa 5MB)</div>
                                    <div id="preview-area" class="image-preview-container"></div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label d-block">⚙️ Tiện ích đi kèm</label>
                                    <div class="bg-white border rounded p-3"
                                        style="max-height: 400px; overflow-y: auto;">
                                        <?php 
                                        if(isset($resultUtilities) && mysqli_num_rows($resultUtilities) > 0) {
                                            mysqli_data_seek($resultUtilities, 0);
                                            while ($uti = mysqli_fetch_assoc($resultUtilities)): 
                                        ?>
                                        <div class="form-check utility-checkbox mb-2">
                                            <input class="form-check-input" type="checkbox" name="utilities[]"
                                                value="<?= $uti['id'] ?>" id="util_<?= $uti['id'] ?>">
                                            <label class="form-check-label" for="util_<?= $uti['id'] ?>">
                                                <?= htmlspecialchars($uti['name_uti']) ?>
                                            </label>
                                        </div>
                                        <?php 
                                            endwhile;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="index.php" class="btn btn-light me-md-2">Hủy bỏ</a>
                            <button type="submit" name="btn_submit" class="btn btn-primary px-5">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu phòng trọ
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('imgInput').addEventListener('change', function(e) {
        var preview = document.getElementById('preview-area');
        preview.innerHTML = ''; // Xóa preview cũ

        if (this.files && this.files[0]) {
            var file = this.files[0];

            // Kiểm tra kích thước (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ảnh quá lớn! Vui lòng chọn ảnh nhỏ hơn 5MB.');
                this.value = '';
                return;
            }

            // Kiểm tra định dạng
            if (!/\.(jpe?g|png|gif|webp)$/i.test(file.name)) {
                alert(file.name + " không phải là file ảnh hợp lệ (chỉ JPG, PNG, GIF, WEBP)");
                this.value = '';
                return;
            }

            var reader = new FileReader();
            reader.addEventListener("load", function() {
                var img = document.createElement('img');
                img.title = file.name;
                img.src = this.result;
                img.className = "img-preview";
                img.style.width = '100%';
                img.style.maxWidth = '300px';
                img.style.height = 'auto';
                img.style.borderRadius = '8px';
                img.style.boxShadow = '0 4px 6px rgba(0,0,0,.1)';
                img.style.marginTop = '10px';
                preview.appendChild(img);
            });
            reader.readAsDataURL(file);
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>