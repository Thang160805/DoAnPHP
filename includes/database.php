<?php
require_once __DIR__ . "/connect.php";
function checkLogin(string $username, string $password): ?array
{
    global $conn; // mysqli connection

    // SQL
    $sql = "SELECT id, TenDangNhap, MatKhau, Trangthai, Vaitro
            FROM TaiKhoan
            WHERE TenDangNhap = ?
            LIMIT 1";

    // Prepare
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        return null;
    }

    // Bind param
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute
    mysqli_stmt_execute($stmt);

    // Get result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result || mysqli_num_rows($result) === 0) {
        return null; // ❌ không tồn tại user
    }

    $user = mysqli_fetch_assoc($result);

    // ❌ tài khoản bị khóa
    if ((int)$user['Trangthai'] === 0) {
        return null;
    }

    // ✅ kiểm tra mật khẩu đã mã hóa
    if (password_verify($password, $user['MatKhau'])) {
        return $user; // ✅ thành công (trả cả Vaitro cho admin/user)
    }

    return null; // ❌ sai mật khẩu
}



function getTotalCountNewRoom(): int
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS cnt
        FROM PhongTro
        WHERE NgayDang >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          AND TrangThai = 1
    ";

    $result = mysqli_query($conn, $sql);
    if (!$result) return 0;

    $row = mysqli_fetch_assoc($result);
    return (int)$row['cnt'];
}



function getListNewRoom($page): array
{
    global $conn;

    $pageSize = 4;
    $start = ($page - 1) * $pageSize;

    $sql = "
        SELECT pt.id, pt.title, tk.HoTen, pt.DienTich, pt.DiaChi,
               pt.NgayDang, pt.price, pt.Luotxem, pt.AnhChinh
        FROM PhongTro pt
        JOIN TaiKhoan tk ON pt.Id_ChuTro = tk.id
        WHERE pt.NgayDang >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          AND pt.TrangThai = 1
        ORDER BY pt.NgayDang DESC
        LIMIT ?, ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $start, $pageSize);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function getTotalCountRoomCheap(): int
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS cnt
        FROM PhongTro
        WHERE price BETWEEN 0 AND 1500000
          AND TrangThai = 1
    ";

    $result = mysqli_query($conn, $sql);
    if (!$result) return 0;

    $row = mysqli_fetch_assoc($result);
    return (int)$row['cnt'];
}


function getListRoomCheap(int $page): array
{
    global $conn;

    $pageSize = 4;
    $start = ($page - 1) * $pageSize;

    $sql = "
        SELECT pt.id, pt.title, tk.HoTen, pt.DienTich, pt.DiaChi,
               pt.NgayDang, pt.price, pt.Luotxem, pt.AnhChinh
        FROM PhongTro pt
        JOIN TaiKhoan tk ON pt.Id_ChuTro = tk.id
        WHERE pt.price BETWEEN 0 AND 1500000
          AND pt.TrangThai = 1
        ORDER BY pt.price ASC
        LIMIT ?, ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $start, $pageSize);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function getTaiKhoan(string $TenDangNhap): ?array
{
    global $conn;

    $sql = "SELECT id,HoTen, Avatar, Email, Phone, DiaChi
            FROM TaiKhoan
            WHERE TenDangNhap = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $TenDangNhap);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result) ?: null;
}


function getListArea(): array
{
    global $conn;

    $sql = "SELECT * FROM area";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

function getListUtilities(): array
{
    global $conn;

    $sql = "SELECT * FROM utilities";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

function buildRoomFilters(array $input): array
{
    $conds  = [];
    $params = [];
    $types  = '';

    /* ✅ CHỈ HIỂN THỊ PHÒNG ĐÃ DUYỆT */
    $conds[] = 'pt.TrangThai = 1';

    /* 🔍 SEARCH: title + DiaChi + HoTen */
    if (!empty($input['search'])) {
        $conds[] = '(
            pt.title LIKE ?
            OR pt.DiaChi LIKE ?
            OR tk.HoTen LIKE ?
        )';

        $kw = '%' . trim($input['search']) . '%';
        $params[] = $kw;
        $params[] = $kw;
        $params[] = $kw;
        $types   .= 'sss';
    }

    /* 📍 KHU VỰC */
    if (!empty($input['khuVuc']) && is_numeric($input['khuVuc'])) {
        $conds[] = 'pt.area_id = ?';
        $params[] = (int)$input['khuVuc'];
        $types .= 'i';
    }

    /* 💰 GIÁ */
    if (!empty($input['mucGia']) && is_numeric($input['mucGia'])) {
        $conds[] = 'pt.price <= ?';
        $params[] = (int)$input['mucGia'];
        $types .= 'i';
    }

    /* 📐 DIỆN TÍCH */
    if (!empty($input['dienTich']) && is_numeric($input['dienTich'])) {
        $conds[] = 'pt.DienTich <= ?';
        $params[] = (int)$input['dienTich'];
        $types .= 'i';
    }

    /* 🧩 TIỆN ÍCH (GIỮ NGUYÊN CODE GỐC CỦA BẠN) */
    if (!empty($input['tienIch']) && is_array($input['tienIch'])) {
        $ids = array_map('intval', $input['tienIch']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $conds[] = "pt.id IN (
            SELECT phongtro_id
            FROM phongtroutilities
            WHERE uti_id IN ($placeholders)
            GROUP BY phongtro_id
            HAVING COUNT(DISTINCT uti_id) = " . count($ids) . "
        )";

        foreach ($ids as $id) {
            $params[] = $id;
            $types .= 'i';
        }
    }

    $where = 'WHERE ' . implode(' AND ', $conds);
    return [$where, $params, $types];
}



function countRoomsWithFilters(array $input): int
{
    global $conn;

    [$where, $params, $types] = buildRoomFilters($input);

    // ép điều kiện trạng thái
    $where .= ($where ? " AND " : " WHERE ") . "pt.TrangThai = 1";

    $sql = "
        SELECT COUNT(DISTINCT pt.id) AS cnt
        FROM PhongTro pt
        JOIN TaiKhoan tk ON pt.Id_ChuTro = tk.id
        LEFT JOIN PhongTroUtilities ptu ON pt.id = ptu.phongtro_id
        LEFT JOIN utilities ut ON ptu.uti_id = ut.id
        $where
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return (int)$row['cnt'];
}


function getListRooms(array $input, ?string $orderBy, ?string $order, int $page): array
{
    global $conn;

    [$where, $params, $types] = buildRoomFilters($input);

    // ép trạng thái
    $where .= ($where ? " AND " : " WHERE ") . "pt.TrangThai = 1";

    $pageSize = 6;
    $start = ($page - 1) * $pageSize;

    $mapOrder = [
        'id' => 'pt.id',
        'price' => 'pt.price',
        'DienTich' => 'pt.DienTich',
        'NgayDang' => 'pt.NgayDang'
    ];

    $orderBy = $mapOrder[$orderBy] ?? 'pt.NgayDang';
    $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

    $sql = "
        SELECT DISTINCT pt.*, tk.HoTen
        FROM PhongTro pt
        JOIN TaiKhoan tk ON pt.Id_ChuTro = tk.id
        LEFT JOIN PhongTroUtilities ptu ON pt.id = ptu.phongtro_id
        LEFT JOIN utilities ut ON ptu.uti_id = ut.id
        $where
        ORDER BY $orderBy $order
        LIMIT ?, ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    $params[] = $start;
    $params[] = $pageSize;
    $types .= 'ii';

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function getTienIchByIDPhong(int $id): array
{
    global $conn;

    $sql = "
        SELECT u.name_uti
        FROM PhongTroUtilities ptu
        JOIN utilities u ON ptu.uti_id = u.id
        WHERE ptu.phongtro_id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row['name_uti'];
    }

    return $data;
}

function getRoomByID(int $id): ?array
{
    global $conn;

    $sql = "
        SELECT pt.*, tk.HoTen
        FROM PhongTro pt
        JOIN TaiKhoan tk ON pt.Id_ChuTro = tk.id
        WHERE pt.id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result) ?: null;
}

// Thêm tài khoản
function insertTaiKhoan(array $data): bool
{
    global $conn;

    if (empty($data)) return false;

    $columns = array_keys($data);
    $placeholders = implode(',', array_fill(0, count($columns), '?'));
    $types = '';
    $values = [];

    foreach ($data as $value) {
        $values[] = $value;
        $types .= is_int($value) ? 'i' : 's';
    }

    $sql = "INSERT INTO TaiKhoan (" . implode(',', $columns) . ") VALUES ($placeholders)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, $types, ...$values);
    return mysqli_stmt_execute($stmt);
}

function checkUserName(string $username): bool
{
    global $conn;

    $sql = "SELECT id FROM TaiKhoan WHERE TenDangNhap = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt)->num_rows > 0;
}

function checkEmail(string $email): bool
{
    global $conn;

    $sql = "SELECT id FROM TaiKhoan WHERE Email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt)->num_rows > 0;
}

function updateTaiKhoan(string $TenDangNhap, array $data): bool
{
    global $conn;

    if (empty($data)) return false;

    $set = [];
    $types = '';
    $values = [];

    foreach ($data as $column => $value) {
        $set[] = "$column = ?";
        $values[] = $value;
        $types .= is_int($value) ? 'i' : 's';
    }

    $values[] = $TenDangNhap;
    $types .= 's';

    $sql = "UPDATE TaiKhoan SET " . implode(', ', $set) . " WHERE TenDangNhap = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, $types, ...$values);
    return mysqli_stmt_execute($stmt);
}

function checkPassword(string $username, string $passwordInput): bool
{
    global $conn;

    $sql = "SELECT MatKhau 
            FROM TaiKhoan 
            WHERE TenDangNhap = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if (!$result || mysqli_num_rows($result) === 0) {
        return false; // user không tồn tại
    }

    $user = mysqli_fetch_assoc($result);

    // So sánh mật khẩu đã hash
    return password_verify($passwordInput, $user['MatKhau']);
}
function updatePassword(string $TenDangNhap, string $newPassword): bool
{
    global $conn;

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE TaiKhoan SET MatKhau = ? WHERE TenDangNhap = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'ss', $newHash, $TenDangNhap);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}

function getPhongById(int $idPhong): ?array
{
    global $conn;

    $sql = "SELECT pt.*,tk.HoTen,tk.Phone,tk.Avatar
            FROM PhongTro pt join TaiKhoan tk on pt.ID_ChuTro=tk.id
            WHERE pt.id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('Prepare failed: ' . mysqli_error($conn));
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'i', $idPhong);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if (!$result || mysqli_num_rows($result) === 0) {
        return null; // ❌ không có phòng
    }

    return mysqli_fetch_assoc($result); // ✅ trả về chi tiết phòng
}

function getTienIchByPhongId(int $idPhong): array
{
    global $conn;

    $sql = "SELECT ti.id, ti.name_uti AS TenTienIch
            FROM PhongTroUtilities pti
            JOIN utilities ti ON pti.uti_id = ti.id
            WHERE pti.phongtro_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log(mysqli_error($conn));
        return [];
    }

    mysqli_stmt_bind_param($stmt, 'i', $idPhong);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        return [];
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function getTienIchIcon(string $tenTienIch): string
{
    $map = [
        'Wi-Fi miễn phí'               => 'fa-solid fa-wifi',
        'Điều hòa'            => 'fas fa-snowflake',
        'Bình nóng lạnh'      => 'fa-solid fa-shower',
        'Máy giặt'            => 'fa-solid fa-soap',
        'Chỗ để xe'           => 'fa-solid fa-motorcycle',
        'Camera an ninh'      => 'fa-solid fa-video',
        'Ban công riêng'            => 'fas fa-door-open',
        'Giường, tủ, bàn ghế'      => 'fas fa-bed',
        'Chỗ nấu ăn'           => 'fas fa-fire',
        'Nhà vệ sinh khép kín'              => 'fas fa-bath',
    ];

    return $map[$tenTienIch] ?? 'fa-solid fa-circle-question';
}

function themYeuCauThue($id_NguoiThue, $ID_Phong, $NgayVao, $ThoiHanThue, $LoiNhan)
{
    global $conn;

    $sql = "
        INSERT INTO YeuCauThueTro
        (phong_id, nguoi_thue_id, ngay_vao, thoi_gian_thue, loi_nhan)
        VALUES (?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('Prepare error: ' . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "iisis",
        $ID_Phong,       
        $id_NguoiThue,    
        $NgayVao,         
        $ThoiHanThue,     
        $LoiNhan          
    );

    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log('Execute error: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    return $ok;
}






?>