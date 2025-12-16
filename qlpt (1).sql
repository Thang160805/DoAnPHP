-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th12 14, 2025 lúc 05:00 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlpt`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `name_area` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `area`
--

INSERT INTO `area` (`id`, `name_area`) VALUES
(1, 'Phường Trung Đô'),
(2, 'Phường Lê Lợi'),
(3, 'Phường Hưng Bình'),
(4, 'Phường Hưng Dũng'),
(5, 'Phường Quang Trung'),
(6, 'Phường Bến Thủy'),
(7, 'Phường Trường Thi'),
(8, 'Phường Hưng Phúc'),
(9, 'Phường Hà Huy Tập'),
(10, 'Phường Cửa Nam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongtro`
--

CREATE TABLE `phongtro` (
  `id` int(11) NOT NULL,
  `Id_ChuTro` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `DienTich` float DEFAULT NULL,
  `DiaChi` text DEFAULT NULL,
  `area_id` int(11) NOT NULL,
  `TrangThai` int(11) DEFAULT NULL CHECK (`TrangThai` in (0,1,2)),
  `NgayDang` datetime DEFAULT current_timestamp(),
  `Luotxem` int(11) DEFAULT 0,
  `AnhChinh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phongtro`
--

INSERT INTO `phongtro` (`id`, `Id_ChuTro`, `title`, `description`, `price`, `DienTich`, `DiaChi`, `area_id`, `TrangThai`, `NgayDang`, `Luotxem`, `AnhChinh`) VALUES
(21, 1, 'Phòng trọ khép kín gần Đại học Vinh', 'Phòng 25m², có điều hòa, vệ sinh riêng, chỗ để xe, cách cổng chính 200m.', 1500000, 25, 'Ngõ 66 Nguyễn Thái Học, TP. Vinh', 2, 1, '2025-11-11 00:00:00', 120, 'https://img.iproperty.com.my/angel/750x1000-fit/wp-content/uploads/sites/7/2023/12/d857bad5a73f58e0789cd16b94b4baa0.jpg'),
(22, 2, 'Phòng trọ sinh viên đầy đủ tiện nghi', 'Phòng 20m², có wifi miễn phí, giường, bàn học, gần chợ và trường Đại học Vinh.', 1200000, 20, 'Số 10, đường Trần Phú, TP. Vinh', 2, 1, '2025-11-11 00:00:00', 95, 'https://img.iproperty.com.my/angel/750x1000-fit/wp-content/uploads/sites/7/2023/12/nha-tro-gac-lung-1-1.jpg'),
(25, 1, 'Phòng trọ giá rẻ có gác lửng', 'Phòng khép kín, có gác lửng, WC riêng, phù hợp sinh viên. Giờ giấc tự do.', 1000000, 18, 'Đường Lê Duẩn, TP. Vinh', 3, 1, '2025-10-11 00:00:00', 80, 'https://img.iproperty.com.my/angel/750x1000-fit/wp-content/uploads/sites/7/2023/12/nha-tro-gac-lung-3.jpg'),
(26, 2, 'Phòng cao cấp cho sinh viên nữ', 'Phòng mới xây 30m², có điều hòa, tủ lạnh, camera 24/7. Cách trường 5 phút.', 2000000, 30, 'Đường Nguyễn Văn Cừ, TP. Vinh', 3, 1, '2025-10-11 00:00:00', 150, 'https://img.iproperty.com.my/angel/750x1000-fit/wp-content/uploads/sites/7/2023/12/nha-tro-gac-lung-4.jpg'),
(27, 2, 'Phòng trọ có ban công, view đẹp', 'Phòng 28m², có ban công, thoáng mát, đầy đủ nội thất, gần trường Đại học Vinh.', 1800000, 28, 'Đường Nguyễn Thị Minh Khai, TP. Vinh', 1, 1, '2025-11-11 22:43:18', 70, 'https://s-housing.vn/wp-content/uploads/2022/09/thiet-ke-phong-tro-dep-7.jpg'),
(28, 1, 'Phòng trọ rẻ cho nhóm 2 người', 'Phòng rộng 35m², có 2 giường, phù hợp nhóm sinh viên, cách trường 5 phút đi bộ.', 1700000, 35, 'Số 3, đường Phạm Hồng Thái, TP. Vinh', 1, 1, '2025-11-11 22:43:18', 110, 'https://s-housing.vn/wp-content/uploads/2022/09/thiet-ke-phong-tro-dep-58.jpg'),
(29, 2, 'Phòng trọ sinh viên giá tốt', 'Phòng mới sửa, 22m², có chỗ nấu ăn, để xe rộng rãi, gần Đại học Vinh.', 1300000, 22, 'Ngõ 20, đường Lê Lợi, TP. Vinh', 1, 1, '2025-11-11 22:43:18', 90, 'https://s-housing.vn/wp-content/uploads/2022/09/thiet-ke-phong-tro-dep-25.jpg'),
(30, 1, 'Phòng trọ gần cổng chính Đại học Vinh', 'Phòng khép kín, có gác lửng, bếp riêng, đi bộ 2 phút tới trường.', 1600000, 25, 'Đường Nguyễn Thị Minh Khai, đối diện Đại học Vinh', 1, 1, '2025-11-11 22:43:18', 200, 'https://s-housing.vn/wp-content/uploads/2022/09/thiet-ke-phong-tro-dep-26.jpg'),
(31, 2, 'Phòng trọ khang trang cho sinh viên', 'Phòng 26m², có điều hòa, wifi miễn phí, vệ sinh riêng, môi trường yên tĩnh.', 1500000, 27, 'Hẻm 19, đường Nguyễn Thái Học, TP. Vinh', 1, 1, '2025-11-11 22:43:18', 85, 'https://s-housing.vn/wp-content/uploads/2022/09/thiet-ke-phong-tro-dep-14.jpg'),
(43, 8, 'phòng đẹp', 'dfsdfsdsffdsddd', 1500000, 25, 's', 2, 1, '2025-12-14 22:34:13', 0, '/ThuNghiem/template/assets/img/phongtro_1765726453_693ed8f59aeea.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongtroutilities`
--

CREATE TABLE `phongtroutilities` (
  `id` int(11) NOT NULL,
  `uti_id` int(11) DEFAULT NULL,
  `phongtro_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phongtroutilities`
--

INSERT INTO `phongtroutilities` (`id`, `uti_id`, `phongtro_id`) VALUES
(1, 1, 21),
(2, 2, 21),
(3, 4, 21),
(4, 7, 21),
(5, 2, 22),
(6, 5, 22),
(7, 6, 22),
(8, 1, 25),
(9, 3, 25),
(10, 9, 25),
(11, 10, 25),
(12, 4, 26),
(13, 8, 26),
(14, 7, 26),
(15, 1, 27),
(16, 2, 27),
(17, 5, 27),
(18, 10, 27),
(19, 3, 28),
(20, 4, 28),
(21, 8, 28),
(22, 9, 28),
(23, 1, 29),
(24, 6, 29),
(25, 7, 29),
(26, 2, 30),
(27, 5, 30),
(28, 8, 30),
(29, 1, 31),
(30, 3, 31),
(31, 4, 31),
(32, 9, 31),
(33, 10, 31),
(34, 1, 43),
(35, 2, 43),
(36, 3, 43);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `id` int(11) NOT NULL,
  `TenDangNhap` varchar(255) DEFAULT NULL,
  `MatKhau` varchar(255) DEFAULT NULL,
  `HoTen` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Phone` varchar(100) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `Vaitro` int(11) DEFAULT NULL CHECK (`Vaitro` in (0,1,2)),
  `Trangthai` bit(1) DEFAULT b'1',
  `NgayTao` datetime DEFAULT current_timestamp(),
  `Avatar` varchar(255) DEFAULT 'https://i.pinimg.com/1200x/b3/c2/77/b3c2779d6b6195793b72bf73e284b3e8.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `TenDangNhap`, `MatKhau`, `HoTen`, `Email`, `Phone`, `DiaChi`, `Vaitro`, `Trangthai`, `NgayTao`, `Avatar`) VALUES
(1, 'chutro1', '123456', 'Nguyễn Văn A', 'chutro1@example.com', '0912345678', 'Hà Nội', 1, b'1', '2025-11-11 08:32:41', 'Avatar_1765574628.png'),
(2, 'nguoidung1', '123456', 'Trần Thị B', 'nguoidung1@example.com', '0987654321', 'Hồ Chí Minh', 2, b'1', '2025-11-11 08:32:41', 'https://i.pinimg.com/1200x/b3/c2/77/b3c2779d6b6195793b72bf73e284b3e8.jpg'),
(4, 'Thang160805', '$2y$10$ZU4fbS24cgaljpOUxbM9.uQG5ObCYvp2sWc3r81SsS.F7.lAG4Fsa', 'Hoàng Minh Thắng', 'minhthang160805@gmail.com', '0829028846', 'Nghi Đức, Vinh, Nghệ An', 2, b'1', '2025-11-12 22:21:36', 'avatar_Thang160805_1765677608.jpg'),
(8, 'admin', '$2y$10$dpkfkEw9hWFnuyH/Odpahe/gSpbg73hwemSb88mEuMZ/RN2QBoLlm', 'Quản trị viên', 'admin@gmail.com', '0123456789', 'Nghi Lộc', 0, b'1', '2025-12-13 16:09:24', 'https://i.pinimg.com/1200x/b3/c2/77/b3c2779d6b6195793b72bf73e284b3e8.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `utilities`
--

CREATE TABLE `utilities` (
  `id` int(11) NOT NULL,
  `name_uti` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `utilities`
--

INSERT INTO `utilities` (`id`, `name_uti`) VALUES
(1, 'Điều hòa'),
(2, 'Wi-Fi miễn phí'),
(3, 'Máy giặt'),
(4, 'Chỗ để xe'),
(5, 'Bình nóng lạnh'),
(6, 'Giường, tủ, bàn ghế'),
(7, 'Nhà vệ sinh khép kín'),
(8, 'Camera an ninh'),
(9, 'Ban công riêng'),
(10, 'Chỗ nấu ăn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `yeucauthuetro`
--

CREATE TABLE `yeucauthuetro` (
  `id` int(11) NOT NULL,
  `phong_id` int(11) NOT NULL,
  `nguoi_thue_id` int(11) NOT NULL,
  `ngay_vao` date NOT NULL,
  `thoi_gian_thue` int(11) NOT NULL,
  `loi_nhan` text DEFAULT NULL,
  `trang_thai` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `yeucauthuetro`
--

INSERT INTO `yeucauthuetro` (`id`, `phong_id`, `nguoi_thue_id`, `ngay_vao`, `thoi_gian_thue`, `loi_nhan`, `trang_thai`, `created_at`) VALUES
(5, 43, 4, '2025-12-14', 6, NULL, 3, '2025-12-14 22:47:54'),
(6, 43, 4, '2025-12-14', 6, 'dffddf', 4, '2025-12-14 22:50:14');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `phongtro`
--
ALTER TABLE `phongtro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_ChuTro` (`Id_ChuTro`),
  ADD KEY `area_id` (`area_id`);

--
-- Chỉ mục cho bảng `phongtroutilities`
--
ALTER TABLE `phongtroutilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uti_id` (`uti_id`),
  ADD KEY `phongtro_id` (`phongtro_id`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `utilities`
--
ALTER TABLE `utilities`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `yeucauthuetro`
--
ALTER TABLE `yeucauthuetro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phong_id` (`phong_id`),
  ADD KEY `nguoi_thue_id` (`nguoi_thue_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `phongtro`
--
ALTER TABLE `phongtro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `phongtroutilities`
--
ALTER TABLE `phongtroutilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `utilities`
--
ALTER TABLE `utilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `yeucauthuetro`
--
ALTER TABLE `yeucauthuetro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `phongtro`
--
ALTER TABLE `phongtro`
  ADD CONSTRAINT `phongtro_ibfk_1` FOREIGN KEY (`Id_ChuTro`) REFERENCES `taikhoan` (`id`),
  ADD CONSTRAINT `phongtro_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`);

--
-- Các ràng buộc cho bảng `phongtroutilities`
--
ALTER TABLE `phongtroutilities`
  ADD CONSTRAINT `phongtroutilities_ibfk_1` FOREIGN KEY (`uti_id`) REFERENCES `utilities` (`id`),
  ADD CONSTRAINT `phongtroutilities_ibfk_2` FOREIGN KEY (`phongtro_id`) REFERENCES `phongtro` (`id`);

--
-- Các ràng buộc cho bảng `yeucauthuetro`
--
ALTER TABLE `yeucauthuetro`
  ADD CONSTRAINT `yeucauthuetro_ibfk_1` FOREIGN KEY (`phong_id`) REFERENCES `phongtro` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yeucauthuetro_ibfk_2` FOREIGN KEY (`nguoi_thue_id`) REFERENCES `taikhoan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
