DROP DATABASE IF EXISTS `sachkaka`;
CREATE DATABASE `sachkaka` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sachkaka`;

CREATE TABLE `nguoidung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL UNIQUE,
  `matkhau` varchar(255) NOT NULL,
  `hoten` varchar(150) NOT NULL,
  `sodienthoai` varchar(20) DEFAULT NULL,
  `diachi` text DEFAULT NULL,
  `hinhanh` varchar(255) DEFAULT 'default_avatar.png',
  `phanquyen` varchar(20) NOT NULL DEFAULT 'khachhang',
  `trangthai` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `nguoidung` (`email`, `matkhau`, `hoten`, `phanquyen`) VALUES
('admin@sachkaka.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Quản Trị Viên', 'admin');

-- Bảng danh mục cha
CREATE TABLE `danhmuc` (  
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tendanhmuc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `danhmuc` (`id`, `tendanhmuc`) VALUES
(1, 'Sách'),
(2, 'Dụng cụ học sinh'),
(3, 'Đồ Chơi');

-- Bảng danh mục chi tiết
CREATE TABLE `danhmucchitiet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tendanhmuc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `danhmuc_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`danhmuc_id`) REFERENCES `danhmuc` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `danhmucchitiet` (`id`, `tendanhmuc`, `danhmuc_id`) VALUES
(1, 'VĂN HỌC', 1),
(2, 'TÂM LÝ - KỸ NĂNG SÔNG', 1),
(3, 'SÁCH THIẾU NHI', 1),
(4, 'BÚT - VIẾT', 2),
(5, 'DỤNG CỤ HỌC SINH', 2),
(6, 'SẢN PHẨM VỀ GIẤY', 2),
(7, 'BOARD GAME', 3),
(8, 'LEGO', 3),
(9, 'MÔ HÌNH', 3);

-- Bảng mặt hàng
CREATE TABLE `mathang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenmathang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mota` text COLLATE utf8mb4_unicode_ci,
  `giagoc` float NOT NULL DEFAULT 0,
  `giaban` float NOT NULL DEFAULT 0,
  `soluongton` int(11) NOT NULL DEFAULT 0,
  `hinhanh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `danhmucchitiet_id` int(11) NOT NULL,
  `luotxem` int(11) NOT NULL DEFAULT 0,
  `luotmua` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`danhmucchitiet_id`) REFERENCES `danhmucchitiet` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `mathang` (`id`, `tenmathang`, `mota`, `giagoc`, `giaban`, `soluongton`, `hinhanh`, `danhmucchitiet_id`, `luotxem`, `luotmua`) VALUES
(1, 'Máy Tính Văn Phòng Casio SX 100 - W-DP', '<p>Kích thước (Dài × Rộng × Dày) : 110,5 × 91 × 9,4 mm</p><p>Màn hình lớn dễ dàng đọc dữ liệu</p><p>Có 2 nguồn năng lượng: mặt trời &amp; pin</p>', 200000, 180000, 10, 'images/products/m1.jpg', 4, 1, 0),
(2, 'Máy Tính Casio FX580VN X-PK (Màu Hồng)', '<p><strong>Máy Tính Casio FX580VN X-PK (Màu Hồng)</strong> thuộc dòng máy tính khoa học ClassWiz...</p>', 757000, 681000, 20, 'images/products/m2.jpg', 5, 1, 0),
(3, 'Máy Tính CASIO FX-880BTG - Màu Xanh Biển', '<p>Máy tính Casio fx-880BTG thuộc dòng máy tính khoa học...</p>', 820000, 738000, 20, 'images/products/m3.jpg', 5, 0, 0),
(4, 'Máy Tính Khoa Học Thiên Long Flexio Fx680VN Plus - Màu Trắng', '<p>512 + 12 Tính Năng...</p>', 635000, 571500, 30, 'images/products/m4.jpg', 5, 5, 0),
(5, 'Kệ Nhựa 3 Tầng - King Star - Màu Xanh Dương', '<p>Màu sắc trang nhã</p>', 178000, 160200, 25, 'images/products/v1.jpg', 6, 0, 0),
(6, 'Bìa Trình Ký Đôi Toppoint A4 TOP-134A - Xanh Lá', '<p>Sản phẩm được làm từ chất liệu nhựa cứng PP cao cấp.</p>', 52000, 46800, 50, 'images/products/v2.jpg', 6, 0, 0),
(7, 'Khay Cắm Bút Flexoffice FO-PS01', '<p>Sản phẩm làm bằng nhựa cao cấp...</p>', 60000, 54000, 30, 'images/products/v3.jpg', 5, 0, 0),
(8, 'Cắm Bút Moshi 016', '<p>Sản phẩm làm bằng nhựa cao cấp...</p>', 60000, 54000, 20, 'images/products/v4.jpg', 5, 0, 0),
(9, 'Bìa Còng 5P F4 Kokuyo 285B - Màu Xanh', '<p>Có thiết kế khổ F4...</p>', 85000, 76500, 10, 'images/products/v5.jpg', 6, 0, 0),
(10, 'Hộp 24 Kẹp Bướm Màu 41mm - Hồng Hà 6642', '<p>Sản xuất từ chất liệu kim loại cao cấp...</p>', 83000, 74700, 15, 'images/products/v6.jpg', 5, 1, 0),
(11, 'Máy Bấm Giá Hand MX5500 - Màu Xanh', '<p>Sản phẩm là dụng cụ tiện lợi...</p>', 205000, 184500, 10, 'images/products/v7.jpg', 5, 0, 0),
(12, 'Bộ Compa 8 Món - Bút Chì Kim - Yalong 19020', '<p>Compass được làm từ kim loại cứng cáp...</p>', 53000, 53000, 20, 'images/products/h1.jpg', 5, 0, 0),
(13, 'Bóp Viết Vải Polyester Stacom 2 Ngăn Hình Hoa Cúc PB-2011C - Màu Xanh Mint', '<p>Sản phẩm được làm bằng chất liệu vải Polyester...</p>', 70000, 63000, 20, 'images/products/h2.jpg', 5, 3, 0),
(14, 'Bộ 2 Hộp Thực Hành Toán Và Tiếng Việt Lớp 1', '<p>Chất liệu: Nhựa...</p>', 240000, 216000, 20, 'images/products/h3.jpg', 5, 0, 0),
(15, 'Bảng Bộ 2 Mặt A4 - Queen BS-02 - Viền Cam', '<p>Sản phẩm bao gồm 1 bảng 2 mặt...</p>', 54000, 48600, 20, 'images/products/h4.jpg', 5, 0, 0),
(16, 'Bộ Lắp Ghép Mô Hình Kỹ Thuật (Lớp 4, Lớp 5)', '<p>Bộ Lắp Ghép Mô Hình Kỹ Thuật...</p>', 92000, 82800, 20, 'images/products/h5.jpg', 5, 0, 0),
(17, 'Thước Bộ Eke - Keyroad KR971430', '<p>Thước bộ eke là dụng cụ học tập phổ biến...</p>', 19000, 19000, 20, 'images/products/h6.jpg', 5, 3, 0),
(18, 'Giấy Photo A4 70gsm - IK Plus (500 Tờ)', '<p>Giấy in A4 của thương hiệu IK Plus...</p>', 84000, 75600, 20, 'images/products/g1.jpg', 6, 0, 0),
(19, 'Giấy photo Double A A4/80 gsm', '<p>Giấy photo Double A A4/80 gsm...</p>', 108000, 97200, 20, 'images/products/g2.jpg', 6, 0, 0),
(20, 'Tập Doraemon Fly - A5 5 Ô Ly 96 Trang ĐL 120g/m2 - Campus NB-ADFL96 (Màu Ngẫu Nhiên)', '<p>Chất liệu: Giấy ngoại nhập...</p>', 27000, 27000, 200, 'images/products/g3.jpg', 6, 4, 0),
(21, 'Sổ Diary Icon The Sun', '<p>Sản phẩm sử dụng loại giấy láng...</p>', 39000, 35100, 50, 'images/products/g4.jpg', 6, 0, 0);
