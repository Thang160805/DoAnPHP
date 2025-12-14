<?php
require_once __DIR__ . "/../auth.php";
require_once __DIR__ . "/../giaodien/navbar.php";
require_once __DIR__ . "/../../../includes/database.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

/* LẤY THÔNG TIN PHÒNG */
$sql = "
    SELECT pt.*, tk.HoTen
    FROM phongtro pt
    JOIN taikhoan tk ON pt.Id_ChuTro = tk.id
    WHERE pt.id = ?
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$room = mysqli_fetch_assoc($result);

if (!$room) {
    echo "<p>❌ Phòng không tồn tại!</p>";
    exit;
}

/* LẤY DANH SÁCH TIỆN ÍCH */
$sqlUtilities = "SELECT id, name_uti FROM utilities ORDER BY id";
$resultUtilities = mysqli_query($conn, $sqlUtilities);

/* LẤY TIỆN ÍCH HIỆN TẠI CỦA PHÒNG */
$sqlCurrentUti = "SELECT uti_id FROM phongtroutilities WHERE phongtro_id = ?";
$stmtCurrentUti = mysqli_prepare($conn, $sqlCurrentUti);
mysqli_stmt_bind_param($stmtCurrentUti, "i", $id);
mysqli_stmt_execute($stmtCurrentUti);
$resultCurrentUti = mysqli_stmt_get_result($stmtCurrentUti);
$currentUtilities = [];
while ($row = mysqli_fetch_assoc($resultCurrentUti)) {
    $currentUtilities[] = $row['uti_id'];
}

$error = "";
$success = "";

/* XỬ LÝ SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title     = trim($_POST['title']);
    $price     = (int)$_POST['price'];
    $dientich  = (float)$_POST['dientich'];
    $diachi    = trim($_POST['diachi']);
    $desc      = trim($_POST['description']);
    $trangthai = (int)$_POST['trangthai'];
    $utilities = isset($_POST['utilities']) ? $_POST['utilities'] : [];

    if ($title === "" || $price <= 0) {
        $error = "❌ Dữ liệu không hợp lệ!";
    } else {
        
        // Xử lý upload ảnh
        $anhChinh = $room['AnhChinh']; // Giữ ảnh cũ nếu không upload mới
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
                            // Xóa ảnh cũ nếu có
                            if (!empty($room['AnhChinh']) && strpos($room['AnhChinh'], 'http') === false) {
                                $oldImagePath = __DIR__ . '/../../../template/assets/img/' . basename($room['AnhChinh']);
                                if (file_exists($oldImagePath)) {
                                    unlink($oldImagePath);
                                }
                            }
                            $anhChinh = '/ThuNghiem/template/assets/img/' . $newFileName;
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
            $sqlUpdate = "
                UPDATE phongtro
                SET title = ?, price = ?, DienTich = ?, DiaChi = ?, description = ?, TrangThai = ?, AnhChinh = ?
                WHERE id = ?
            ";

            $stmt = mysqli_prepare($conn, $sqlUpdate);
            mysqli_stmt_bind_param(
                $stmt,
                "sddssissi",
                $title,
                $price,
                $dientich,
                $diachi,
                $desc,
                $trangthai,
                $anhChinh,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                // Xóa tiện ích cũ
                $sqlDeleteUti = "DELETE FROM phongtroutilities WHERE phongtro_id = ?";
                $stmtDeleteUti = mysqli_prepare($conn, $sqlDeleteUti);
                mysqli_stmt_bind_param($stmtDeleteUti, "i", $id);
                mysqli_stmt_execute($stmtDeleteUti);
                mysqli_stmt_close($stmtDeleteUti);
                
                // Thêm tiện ích mới
                if (!empty($utilities) && is_array($utilities)) {
                    foreach ($utilities as $utiId) {
                        $utiId = (int)$utiId;
                        if ($utiId > 0) {
                            $sqlUti = "INSERT INTO phongtroutilities (phongtro_id, uti_id) VALUES (?, ?)";
                            $stmtUti = mysqli_prepare($conn, $sqlUti);
                            mysqli_stmt_bind_param($stmtUti, "ii", $id, $utiId);
                            mysqli_stmt_execute($stmtUti);
                            mysqli_stmt_close($stmtUti);
                        }
                    }
                }
                
                $success = "✅ Cập nhật phòng thành công!";
                // load lại dữ liệu
                header("Refresh:2;url=edit.php?id=" . $id);
            } else {
                $error = "❌ Lỗi cập nhật!";
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
    <title>Sửa phòng trọ</title>
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
    .img-preview {
        max-width: 300px;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        margin-top: 10px;
    }
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
                    <h2 class="text-primary"><i class="fa-solid fa-edit me-2"></i>Sửa / Duyệt phòng</h2>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
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
                                    <label class="form-label">Tiêu đề phòng <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($room['title']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Giá thuê (VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="price" class="form-control" value="<?= $room['price'] ?>" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Diện tích (m²)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" name="dientich" class="form-control" value="<?= $room['DienTich'] ?>">
                                        <span class="input-group-text">m²</span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Địa chỉ chi tiết</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                        <input type="text" name="diachi" class="form-control" value="<?= htmlspecialchars($room['DiaChi']) ?>">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Mô tả chi tiết</label>
                                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($room['description']) ?></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Trạng thái hiển thị</label>
                                    <select name="trangthai" class="form-select">
                                        <option value="1" <?= $room['TrangThai']==1?'selected':'' ?>>✔️ Đã duyệt</option>
                                        <option value="0" <?= $room['TrangThai']==0?'selected':'' ?>>⏳ Chờ duyệt</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label">📷 Ảnh chính phòng</label>
                                
                                <?php if (!empty($room['AnhChinh'])): ?>
                                <div class="mb-2">
                                    <label class="text-muted small">Ảnh hiện tại:</label>
                                    <div>
                                        <?php 
                                        $imgSrc = $room['AnhChinh'];
                                        // Nếu là URL đầy đủ thì dùng trực tiếp, nếu không thì thêm đường dẫn
                                        if (strpos($imgSrc, 'http') === 0) {
                                            $displayImg = $imgSrc;
                                        } else {
                                            // Kiểm tra xem có phải đường dẫn tuyệt đối không
                                            if (strpos($imgSrc, '/') === 0) {
                                                $displayImg = $imgSrc;
                                            } else {
                                                $displayImg = '/ThuNghiem/template/assets/img/' . $imgSrc;
                                            }
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($displayImg) ?>" alt="Ảnh phòng" class="img-preview" id="currentImage" 
                                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'300\' height=\'200\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EKhông có ảnh%3C/text%3E%3C/svg%3E';">
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <input type="file" name="anhchinh" id="imgInput" class="form-control" accept="image/*">
                                <div class="form-text text-muted">Chọn ảnh mới (JPG, PNG, GIF, WEBP - tối đa 5MB)</div>
                                <div id="preview-area" class="image-preview-container"></div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label d-block">⚙️ Tiện ích đi kèm</label>
                                <div class="bg-white border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                    <?php 
                                    if(isset($resultUtilities) && mysqli_num_rows($resultUtilities) > 0) {
                                        mysqli_data_seek($resultUtilities, 0);
                                        while ($uti = mysqli_fetch_assoc($resultUtilities)): 
                                            $checked = in_array($uti['id'], $currentUtilities) ? 'checked' : '';
                                    ?>
                                    <div class="form-check utility-checkbox mb-2">
                                        <input class="form-check-input" type="checkbox" name="utilities[]"
                                            value="<?= $uti['id'] ?>" id="util_<?= $uti['id'] ?>" <?= $checked ?>>
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
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
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
    var currentImg = document.getElementById('currentImage');
    
    // Ẩn ảnh hiện tại khi chọn ảnh mới
    if (currentImg) {
        currentImg.style.display = 'none';
    }
    
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
            preview.appendChild(img);
        });
        reader.readAsDataURL(file);
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>