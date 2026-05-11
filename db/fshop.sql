-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 11, 2026 lúc 08:32 PM
-- Phiên bản máy phục vụ: 10.4.11-MariaDB
-- Phiên bản PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `fshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `idchitietdh` int(11) NOT NULL,
  `iddonhang` int(11) NOT NULL,
  `idsanpham` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `gia` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`idchitietdh`, `iddonhang`, `idsanpham`, `soluong`, `gia`) VALUES
(1, 3, 1, 10, 1231),
(2, 3, 2, 3, 13),
(3, 4, 1, 1, 1231),
(4, 5, 1, 60, 1231),
(5, 6, 1, 6, 200000),
(6, 6, 2, 2, 1300000),
(7, 7, 2, 1, 1300000),
(8, 8, 1, 1, 200000),
(9, 9, 2, 10, 1300000),
(10, 10, 2, 1, 1300000),
(11, 11, 1, 1, 200000),
(12, 11, 2, 1, 1300000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmucsanpham`
--

CREATE TABLE `danhmucsanpham` (
  `id_DanhMuc` int(10) NOT NULL,
  `Ten_DanhMuc` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmucsanpham`
--

INSERT INTO `danhmucsanpham` (`id_DanhMuc`, `Ten_DanhMuc`) VALUES
(1, 'Nam'),
(2, 'Nữ'),
(3, 'Trẻ Em');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `idDonHang` int(10) NOT NULL,
  `idKhach` int(10) NOT NULL,
  `ngaydathang` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangthai` int(10) NOT NULL,
  `tongtien` int(200) NOT NULL,
  `hoten` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sodienthoai` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diachi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`idDonHang`, `idKhach`, `ngaydathang`, `trangthai`, `tongtien`, `hoten`, `email`, `sodienthoai`, `diachi`) VALUES
(3, 2, '2025-04-25 07:57:58', 0, 12349, NULL, NULL, NULL, NULL),
(4, 2, '2025-04-25 08:09:38', 0, 1231, NULL, NULL, NULL, NULL),
(5, 2, '2025-04-25 08:58:55', 0, 73860, NULL, NULL, NULL, NULL),
(6, 4, '2026-05-11 12:42:38', 0, 3800000, 'nam', 'quangdoptcn@gmail.com', '0987654321', 'nhà 04 tổ 16h khu châu phong'),
(7, 4, '2026-05-11 12:43:53', 0, 1300000, 'nam', 'quangdoptcn@gmail.com', '98765432', 'hà nội'),
(8, 4, '2026-05-11 12:49:20', 0, 200000, 'vũ', 'qtemobush@gmail.com', '0967734974', 'tp ho chi minh'),
(9, 4, '2026-05-11 12:53:20', 0, 13000000, 'hoa', 'hoanguyen@gmail.com', '0987654321', 'Đà Nẵng'),
(10, 5, '2026-05-11 16:08:08', 0, 1300000, 'vũ', 'quangdoptcn@gmail.com', '1234567890', 'Việt trì'),
(11, 5, '2026-05-11 16:11:38', 0, 1500000, 'nam', 'quangdoptcn@gmail.com', '0987654321', 'Hà Nội');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
--

CREATE TABLE `giohang` (
  `id` int(10) NOT NULL,
  `iduser` int(10) NOT NULL,
  `idsanpham` int(10) NOT NULL,
  `soluong` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`id`, `iduser`, `idsanpham`, `soluong`) VALUES
(24, 2, 2, 3),
(31, 4, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role`
--

CREATE TABLE `role` (
  `roleId` int(10) NOT NULL,
  `Ten` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role`
--

INSERT INTO `role` (`roleId`, `Ten`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Operater');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `id` int(10) NOT NULL,
  `Ten` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `MoTa` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `Gia` float NOT NULL,
  `soluong` int(200) NOT NULL,
  `Anh` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `id_DanhMuc` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`id`, `Ten`, `MoTa`, `Gia`, `soluong`, `Anh`, `id_DanhMuc`) VALUES
(1, 'thời trang nam', 'San pham dep', 200000, 123, 'cat-item1.jpg', 1),
(2, 'túi xách', 'San pham dep', 1300000, 117, 'cat-item3.jpg', 2),
(3, 'Áo thun nam', 'Áo thun cotton cao cấp', 250000, 50, 'ao-thun-nam.jpg', 1),
(4, 'Quần jean nam', 'Quần jean form slim fit', 450000, 40, 'quan-jean-nam.jpg', 1),
(5, 'Áo khoác nữ', 'Áo khoác thời trang mùa đông', 650000, 30, 'ao-khoac-nu.jpg', 1),
(6, 'Váy nữ', 'Váy thiết kế thanh lịch', 550000, 25, 'vay-nu.jpg', 1),
(7, 'Túi xách da', 'Túi xách nữ da cao cấp', 1200000, 15, 'tui-xach-da.jpg', 2),
(8, 'Balo thời trang', 'Balo chống nước tiện lợi', 700000, 35, 'balo-thoi-trang.jpg', 2),
(9, 'Ví nam', 'Ví da nam nhỏ gọn', 350000, 60, 'vi-nam.jpg', 2),
(10, 'Giày sneaker', 'Giày sneaker trẻ trung', 900000, 20, 'giay-sneaker.jpg', 3),
(11, 'Dép sandal', 'Dép sandal đi biển', 280000, 45, 'dep-sandal.jpg', 3),
(12, 'Mũ lưỡi trai', 'Mũ thời trang unisex', 180000, 70, 'mu-luoi-trai.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `idtk` int(10) NOT NULL,
  `username` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `roleId` int(10) NOT NULL,
  `trangthai` int(10) NOT NULL DEFAULT 1,
  `thoigiantao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`idtk`, `username`, `password`, `roleId`, `trangthai`, `thoigiantao`) VALUES
(1, 'Admin', '12', 1, 1, '2025-03-27 05:32:27'),
(2, 'oper', '234', 2, 1, '2025-03-27 05:32:27'),
(3, 'custommer', '123', 3, 1, '2025-03-27 05:32:27'),
(4, 'quang', '2301', 2, 1, '2026-05-11 06:35:55'),
(5, 'Hao', '$2y$10$a5vUuXaW.urMYHIMg90QRe4XhY52OzbbWU9QtgaLGPKl4/m1Zo0T2', 2, 1, '2026-05-11 06:45:32'),
(6, 'quang2', '123', 2, 1, '2026-05-11 15:15:36'),
(7, 'ThànhNam', '1234', 2, 1, '2026-05-11 17:06:04'),
(8, 'dunglt', '12334566', 2, 1, '2026-05-11 17:20:40'),
(9, 'Vietdung', '123456789', 2, 1, '2026-05-11 17:22:01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `iduser` int(10) NOT NULL,
  `idtk` int(10) NOT NULL,
  `Ten_user` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `Anh_user` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sdt` int(20) NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `diachi` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ngaysinh` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`iduser`, `idtk`, `Ten_user`, `Anh_user`, `sdt`, `email`, `diachi`, `ngaysinh`) VALUES
(1, 1, 'Nguyễn Văn A', 'giay.jpg', 98765432, 'admin@gmail.com', 'Hà Nội', '2015-03-03'),
(2, 2, 'Nguyễn Văn B', 'ely.jpg', 98765432, 'b@gmail.com', 'Ha Noi', '2015-03-03'),
(3, 4, 'quang', 'user.jpg', 987654321, 'qtemobush@gmail.com', 'Hà Nội', '2000-05-01'),
(4, 5, 'Hao', 'user.jpg', 1234567890, 'def66519@gmail.com', 'Hà Nội', '2008-08-15'),
(5, 6, 'quang2', 'user.jpg', 987654321, 'quangdoptcn@gmail.com', 'Hà Nội', '2026-05-16'),
(6, 7, 'ThànhNam', 'user.jpg', 969456962, 'duongthanhnam12tin@gmail.com', 'Mộ Thượng - Bạch Hạc - Việt Trì', '2002-09-03'),
(7, 8, 'dunglt', 'user.jpg', 966286739, 'duongvietdung@gmail.com', 'Lang Đài - Bạch Hạc - Việt Trì', '2002-11-12'),
(8, 9, 'Dương Việt Dũng', 'Untitled design (1).png', 966286739, 'duongvietdugn@gmail.com', 'Lang Đài - Bạch Hạc', '2002-11-12');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`idchitietdh`),
  ADD KEY `iddonhang` (`iddonhang`),
  ADD KEY `idsanpham` (`idsanpham`);

--
-- Chỉ mục cho bảng `danhmucsanpham`
--
ALTER TABLE `danhmucsanpham`
  ADD PRIMARY KEY (`id_DanhMuc`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`idDonHang`),
  ADD KEY `idKhach` (`idKhach`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser` (`iduser`),
  ADD KEY `idsanpham` (`idsanpham`);

--
-- Chỉ mục cho bảng `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleId`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_DanhMuc` (`id_DanhMuc`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`idtk`),
  ADD KEY `roleId` (`roleId`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`),
  ADD KEY `idtk` (`idtk`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `idchitietdh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `danhmucsanpham`
--
ALTER TABLE `danhmucsanpham`
  MODIFY `id_DanhMuc` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `idDonHang` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `giohang`
--
ALTER TABLE `giohang`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `role`
--
ALTER TABLE `role`
  MODIFY `roleId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `idtk` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`iddonhang`) REFERENCES `donhang` (`idDonHang`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`id`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`idKhach`) REFERENCES `users` (`iduser`);

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`id`),
  ADD CONSTRAINT `giohang_ibfk_3` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`id_DanhMuc`) REFERENCES `danhmucsanpham` (`id_DanhMuc`);

--
-- Các ràng buộc cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `taikhoan_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `role` (`roleId`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`idtk`) REFERENCES `taikhoan` (`idtk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
