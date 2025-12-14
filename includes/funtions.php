<?php
function timeAgo(string $date): string {
    $timestamp = strtotime($date);
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 60) {
        return "Vừa xong";
    } elseif ($diff < 3600) { // dưới 1 giờ
        $minutes = floor($diff / 60);
        return $minutes . " phút trước";
    } elseif ($diff < 86400) { // dưới 1 ngày
        $hours = floor($diff / 3600);
        return $hours . " giờ trước";
    } elseif ($diff < 604800) { // dưới 7 ngày
        $days = floor($diff / 86400);
        return $days . " ngày trước";
    } elseif ($diff < 2592000) { // dưới 30 ngày
        $weeks = floor($diff / 604800);
        return $weeks . " tuần trước";
    } elseif ($diff < 31536000) { // dưới 1 năm
        $months = floor($diff / 2592000);
        return $months . " tháng trước";
    } else {
        $years = floor($diff / 31536000);
        return $years . " năm trước";
    }
}

function formatCurrency($amount): string {
    return number_format($amount, 0, ',', '.'); // 1.500.000
}


function buildUrlClean(array $extra = []): string {
    // Lấy toàn bộ tham số hiện tại
    $query = array_merge($_GET, $extra);

    // Xóa những key rỗng hoặc không cần thiết
    foreach ($query as $k => $v) {
        if ($v === '' || $v === null || $v === [] || $v === false) {
            unset($query[$k]);
        }
    }

    // Ghép lại thành query string
    $qs = http_build_query($query);
    return './TimPhong.php' . ($qs ? '?' . $qs : '');
}


function formatPhoneNumber(string $phone): string {
    // Xóa hết ký tự không phải số
    $digits = preg_replace('/\D/', '', $phone);

    // Nếu số quá ngắn thì trả nguyên
    if (strlen($digits) < 7) return $phone;

    // Giữ 3 số đầu (thường là đầu mạng)
    $prefix = substr($digits, 0, 3);

    // Trả về dạng 08x.xxx.xxx
    return sprintf('%s%s', $prefix, '.xxx.xxx');
}




?>