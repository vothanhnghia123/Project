-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 02, 2026 lúc 04:27 AM
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
-- Cơ sở dữ liệu: `bansach`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `IDCTDH` int(11) NOT NULL,
  `IDDonHang` int(11) NOT NULL,
  `IDSach` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`IDCTDH`, `IDDonHang`, `IDSach`, `SoLuong`, `DonGia`) VALUES
(1, 1, 3, 1, 120000.00),
(2, 2, 2, 1, 156500.00),
(3, 3, 8, 1, 66500.00),
(4, 6, 12, 1, 58500.00),
(5, 7, 1, 1, 119350.00),
(6, 8, 1, 1, 119350.00),
(7, 9, 1, 3, 119350.00),
(8, 10, 1, 1, 119350.00),
(9, 12, 1, 1, 119350.00),
(10, 13, 11, 1, 37500.00),
(11, 14, 3, 1, 120000.00),
(12, 15, 26, 1, 152000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `IDDanhGia` int(11) NOT NULL,
  `IDSach` int(11) NOT NULL,
  `IDNguoiDung` int(11) NOT NULL,
  `SoSao` int(11) DEFAULT NULL CHECK (`SoSao` between 1 and 5),
  `NoiDung` text DEFAULT NULL,
  `NgayDanhGia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`IDDanhGia`, `IDSach`, `IDNguoiDung`, `SoSao`, `NoiDung`, `NgayDanhGia`) VALUES
(3, 2, 2, 5, 'hay', '2026-03-16 17:14:50'),
(4, 34, 2, 5, 'hay', '2026-03-20 16:15:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `IDDanhMuc` int(11) NOT NULL,
  `TenDanhMuc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`IDDanhMuc`, `TenDanhMuc`) VALUES
(1, 'Văn học'),
(2, 'Thiếu nhi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `IDDonHang` int(11) NOT NULL,
  `IDNguoiDung` int(11) NOT NULL,
  `NgayDat` datetime DEFAULT current_timestamp(),
  `TongTien` decimal(14,2) NOT NULL,
  `TrangThai` varchar(50) DEFAULT NULL,
  `PhuongThucTT` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`IDDonHang`, `IDNguoiDung`, `NgayDat`, `TongTien`, `TrangThai`, `PhuongThucTT`) VALUES
(1, 2, '2026-03-17 15:43:59', 120000.00, 'Đang xử lý', ''),
(2, 2, '2026-03-17 15:45:14', 156500.00, 'Đang xử lý', ''),
(3, 2, '2026-03-17 15:46:09', 66500.00, 'Đang xử lý', ''),
(6, 2, '2026-03-17 16:01:07', 58500.00, 'Đang xử lý', ''),
(7, 2, '2026-03-17 16:12:43', 119350.00, 'Đang xử lý', 'COD'),
(8, 2, '2026-03-17 16:18:45', 119350.00, 'Đang xử lý', 'COD'),
(9, 2, '2026-03-17 16:19:22', 358050.00, 'Đang xử lý', 'COD'),
(10, 2, '2026-03-17 16:26:17', 119350.00, 'Đang xử lý', 'COD'),
(12, 2, '2026-03-18 14:36:53', 119350.00, 'Đang xử lý', 'COD'),
(13, 2, '2026-03-18 14:40:20', 37500.00, 'Đang xử lý', 'COD'),
(14, 2, '2026-03-18 14:44:19', 120000.00, 'Đang xử lý', 'COD'),
(15, 2, '2026-03-18 14:44:44', 152000.00, 'Đang xử lý', 'COD');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `IDNguoiDung` int(11) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `DienThoai` varchar(15) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `IDVaiTro` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`IDNguoiDung`, `HoTen`, `Email`, `MatKhau`, `DienThoai`, `DiaChi`, `IDVaiTro`) VALUES
(2, 'Võ Thành Nghĩa', 'vothanhnghia1503@gmail.com', 'b0baee9d279d34fa1dfd71aadb908c3f', '0337486120', 'An Thới Dông', 2),
(3, 'Admin', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0123456789', 'Admin', 1),
(4, 'nhân', 'nhan@gmail.com', 'b0baee9d279d34fa1dfd71aadb908c3f', NULL, NULL, 2),
(5, 'nhan', 'nguyenlehuunhan1447@gmail.com', '993b0777ada831047ce38c0101899fef', NULL, NULL, 2),
(6, 'Khachhang', 'Khachhanh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhaxuatban`
--

CREATE TABLE `nhaxuatban` (
  `IDNXB` int(11) NOT NULL,
  `TenNXB` varchar(150) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `DienThoai` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhaxuatban`
--

INSERT INTO `nhaxuatban` (`IDNXB`, `TenNXB`, `DiaChi`, `DienThoai`) VALUES
(1, 'Kim Đồng', 'Hà Nội', '0123456789'),
(5, 'Văn Học', '', '0123456780'),
(7, 'NXB Trẻ', '', ''),
(8, 'Dân Trí', '', ''),
(9, 'Thế Giới', '', ''),
(10, 'Hà Nội', '', ''),
(11, 'Phụ Nữ', '', ''),
(12, 'Thanh Niên', '', ''),
(13, 'Hội Nhà Văn', '', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sach`
--

CREATE TABLE `sach` (
  `IDSach` int(11) NOT NULL,
  `TenSach` varchar(200) NOT NULL,
  `IDTheLoai` int(11) NOT NULL,
  `IDNXB` int(11) NOT NULL,
  `IDTacGia` int(11) NOT NULL,
  `GiaBan` decimal(12,2) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `SoTrang` int(11) DEFAULT NULL,
  `NamXB` int(11) DEFAULT NULL,
  `NgayNhap` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sach`
--

INSERT INTO `sach` (`IDSach`, `TenSach`, `IDTheLoai`, `IDNXB`, `IDTacGia`, `GiaBan`, `SoLuong`, `MoTa`, `HinhAnh`, `SoTrang`, `NamXB`, `NgayNhap`) VALUES
(1, 'Hồ Điệp Và Kình Ngư', 1, 5, 1, 119350.00, 10, 'Một câu chuyện cuốn hút ngay từ những trang đầu tiên - Khi tình yêu trở thành sợi dây mong manh giữa sinh tử, phản bội và hy vọng. Khi một nàng hồ điệp nhỏ bé chạm trán với kình ngư mạnh mẽ, liệu đó là định mệnh hay chỉ là một giấc mộng chóng tàn?', 'TT1.jpg', 272, 2024, '2026-03-14 13:56:30'),
(2, 'Hà Thanh Hải Yến - Ngang Qua Ngõ Nhỏ Bình An', 1, 7, 3, 156500.00, 4, 'Bị bố đánh đập dã man, bị bạn học bắt nạt, trong lúc tuyệt vọng cùng quẫn, tôi tìm đến tiệm xăm trong góc ngõ.\r\n\r\nNghe nói ông chủ là một tên côn đồ, rất hung hãn và dữ dằn, người xung quanh đều e sợ anh.\r\n\r\nĐẩy cửa, tôi moi từ trong túi ra một tờ mười tệ nhàu nhĩ, lấy hết dũng khí hỏi:\r\n\r\n“Nghe nói anh thu phí bảo kê, vậy anh... có thể bảo vệ tôi không?”\r\n\r\nGiữa làn khói thuốc lượn lờ, người đàn ông nhếch môi phì cười.\r\n\r\n“Nhóc con nhà ai đây? To gan thật đấy.”\r\n\r\nSau này, anh chỉ vì tờ mười tệ ấy mà bảo vệ tôi suốt mười năm.', 'TT6.jpg', 324, 2024, '2026-03-14 15:08:09'),
(3, 'Người Đàn Ông Mang Tên OVE (Tái Bản)', 1, 7, 4, 120000.00, 4, 'Bạn có tin rằng một ông lão cộc cằn, khó tính lại có thể khiến bạn rơi nước mắt vì xúc động? Bạn đã bao giờ nghĩ rằng lòng nhân ái có thể đến từ những con người tưởng chừng khô khan nhất? Một ông lão cộc cằn, một con mèo hoang, vài người hàng xóm phiền phức - tất cả có thể tạo nên một câu chuyện khiến bạn bật khóc?', 'TT3.jpg', 452, 2022, '2026-03-16 21:23:53'),
(4, ' Nỗi Buồn Chiến Tranh (Tái Bản 2022)', 1, 7, 5, 97500.00, 5, 'Tác phẩm là dòng hồi ức của người lính về chiến tranh và thời tuổi trẻ đã trải qua trong bom đạn. Đólàlòng tiếc thương vô hạn đối với những người cùng thế hệ với mình đã nằm xuống, là ám ảnh về thân phận con người trong thời buổi loạn ly, và thông qua thân phận là sự tái hiện đầy xót xavề quá khứ, những suy tư nghiền ngẫm về con đường dấn thân của cả một thế hệ sinh ra trong chiến tranh. Bao trùm lên tất cả, là nỗi buồn sâu xa gắn với từng mảnh đời riêng. Tác phẩm đã bước ra khỏi lối mòn về lòng tự hào dân tộc cùng những chiến công và vinh quang tập thể để nêu lên thông điệp về sự ghê tởm, về tính chất hủy diệt của chiến tranh đối với con người.\r\n\r\nVào thời điểm ra đời cuối thập niên 1980,“Nỗi buồn chiến tranh” có thểđược xem là tác phẩm văn học Việt Nam hiện đại đầu tiên viết về chiến tranh có cái nhìn khác với quan niệm truyền thống, khẳng định mạnh mẽ vai trò cá nhân trong xã hội, quyền sống, hạnh phúc và đau khổ của con người với tư cách một cá thể độc lập. Tiểu thuyết nhận được giải thưởng Hội Nhà văn Việt Nam năm 1991.', 'TT2.jpg', 352, 2022, '2026-03-16 21:25:45'),
(5, 'Beartown - Thị Trấn Nhỏ, Giấc Mơ Lớn', 1, 7, 4, 157500.00, 5, 'Từ tác giả cuốn sách bán chạy toàn cầu “Người đàn ông mang tên Ove”\r\n\r\nFredrik Backman cuốn hút người đọc vào cuốn tiểu thuyết sâu sắc, quyến rũ về một thị trấn nhỏ mang giấc mơ lớn – và cái giá phải trả để biến giấc mơ thành hiện thực.\r\n\r\nAi cũng nói Beartown vậy là xong rồi. Một cộng đồng nhỏ nép mình sâu trong rừng, và ngày càng nhỏ lại khi cây cối xâm lấn. Nhưng ở đây có một sân băng cũ, và đây là lí do người dân Beartown tin rằng ngày mai sẽ tốt hơn hôm nay. Đội khúc côn cầu trên băng của họ sắp thi đấu ở vòng bán kết quốc gia, và họ hoàn toàn có cơ hội chiến thắng. Tất cả hi vọng và ước mơ của nơi này được đặt lên vai những cậu trai tuổi teen.\r\n\r\nNhưng chính điều đó lại tạo thành một gánh nặng cho các cậu, và đã kích hoạt một hành động bạo lực, cả thị trấn chìm trong hỗn loạn.\r\n\r\n“Beartown” đi sâu vào những hi vọng gắn kết một cộng đồng nhỏ lại với nhau, những bí mật đã chia cắt nó và sự can đảm cần có để một người làm điều khác thường. Trong câu chuyện về thị trấn nhỏ trong rừng này, Fredrik Backman đã tìm được cả thế giới.', 'TT4.jpg', 604, 2023, '2026-03-16 21:27:05'),
(6, 'Lén Nhặt Chuyện Đời', 1, 9, 6, 63500.00, 5, 'Tại vùng ngoại ô xứ Đan Mạch xưa, người thợ kim hoàn Per Enevoldsen đã cho ra mắt một món đồ trang sức lấy ý tưởng từ Pandora - người phụ nữ đầu tiên của nhân loại mang vẻ đẹp như một ngọc nữ phù dung, kiêu sa và bí ẩn trong Thần thoại Hy Lạp. Vòng Pandora được kết hợp từ một sợi dây bằng vàng, bạc hoặc bằng da cùng với những viên charm được chế tác đa dạng, tỉ mỉ. Ý tưởng của ông, mỗi viên charm như một câu chuyện, một kỷ niệm đáng nhớ của người sở hữu chiếc vòng. Khi một viên charm được thêm vào sợi Pandora là cuộc đời lại có thêm một ký ức cần lưu lại để nhớ, để thương, để trân trọng. Lén nhặt chuyện đời ra mắt trong khoảng thời gian chông chênh nhất của bản thân, hay nói cách khác là một cậu bé mới lớn, vừa bước ra khỏi cái vỏ bọc vốn an toàn của mình. Những câu chuyện trong Lén nhặt chuyện đời là những câu chuyện tôi được nghe kể lại, hoặc vô tình bắt gặp, hoặc nhặt nhạnh ở đâu đó trong miền ký ức rời rạc của quá khứ, không theo một trình tự hay một thời gian nào nhất định.', 'TT5.jpg', 213, 2022, '2026-03-16 21:29:06'),
(7, 'Đám Trẻ Ở Đại Dương Đen (Tái Bản 2026)', 2, 1, 7, 95000.00, 5, '“nỗi buồn không rõ hình thù\r\n\r\nta cho nó dáng, ta thu vào lòng\r\n\r\nta ôm mà chẳng đề phòng\r\n\r\nmột ngày nó lớn chất chồng tâm can”\r\n\r\n“kẻ sống muốn đời cạn\r\n\r\nngười chết muốn hồi sinh\r\n\r\ntrần gian bi hài nhỉ?\r\n\r\nta còn muốn bỏ mình?”', 'TN1.jpg', 280, 2026, '2026-03-16 21:33:17'),
(8, 'Mẹ Làm Gì Có Ước Mơ', 2, 5, 1, 66500.00, 5, '“Ước mơ của mẹ là gì?”\r\n\r\n“Thì cho chúng mày ăn học đàng hoàng, đầy đủ để mai sau đỡ khổ.”\r\n\r\n“Không, ước mơ khác cơ.”\r\n\r\n“Mai sau chúng mày lập gia đình, chọn được đúng người, vợ chồng yêu thương nhau.”\r\n\r\n“Ước mơ dành riêng cho bản thân mẹ cơ mà.”', 'TN2.jpg', 208, 2023, '2026-03-16 21:35:27'),
(9, 'Biết Đâu Ngày Mai Có Cầu Vồng', 2, 5, 9, 87000.00, 5, 'Có những ngày mưa\r\n\r\nNgười thì chỉ ướt mặt\r\n\r\nCòn người lại ướt lòng.\r\n\r\nCó những ngày mệt mỏi, những gánh nặng vô hình từ công việc, cuộc sống và những kỳ vọng chồng chất, đẩy bạn đến giới hạn mà chính bạn cũng không nhận ra. Thế rồi, giữa dòng người đông đúc, trong ánh đèn nhấp nháy của thành phố, nước mắt lặng lẽ trào ra. Vừa chạy xe vừa khóc dưới mưa, cảm giác ấy chẳng giống một sự yếu đuối, mà giống như một lần duy nhất cho phép bản thân được thả lỏng, được buông bỏ, dẫu chỉ trong khoảnh khắc ngắn ngủi.', 'TN3.jpg', 232, 2025, '2026-03-16 21:37:07'),
(10, 'Tôi Thích Dáng Vẻ Nỗ Lực Của Chính Mình', 2, 1, 1, 79000.00, 5, 'LILY TRƯƠNG\r\n\r\nLà một người hướng nội INFJ, tác giả tự do, freelance writer và nhà sáng tạo nội dung đa nền tảng. Cô từng theo học ngành luật, hiện tại, cô tập trung nghiên cứu và viết về lĩnh vực tâm lý, giáo dục và sự phát triển cá nhân.\r\n\r\nĐồng thời cô cũng là chủ kênh growthwithlily - kênh chia sẻ những nội dung về học tập, năng suất và phát triển bản thân với hơn 100.000 lượt theo dõi.', 'TN4.jpg', 208, 2024, '2026-03-16 21:38:21'),
(11, 'Chí Phèo', 2, 5, 11, 37500.00, 4, 'Tác phẩm đã xuất bản: Đôi lứa xứng đôi (truyện ngắn, 1941); Nửa đêm (truyện ngắn, 1944); Cười (truyện ngắn, 1946), Ở rừng (nhật ký, 1948); Truyện biên giới (1951); Đôi mắt(truyện ngắn, 1954); Sống mòn (truyện dài, 1956); Chí Phèo (1957); Truyện ngắn Nam Cao (truyện ngắn, 1960); Một đám cưới (truyện ngắn, 1963); Tác phẩm Nam Cao (tuyển, 1964); Nam Cao tác phẩm (tập I: 1976, tập II: 1977); Tuyển tập Nam Cao(tập I: 1987, tập II: 1993); Những cánh hoa tàn (truyện ngắn, 1988); Nam Cao truyện ngắn tuyển chọn (1995); Nam Cao truyện ngắn (chọn lọc, 1996); Nam Cao toàn tập (1999).', 'TN5.jpg', 196, 2022, '2026-03-16 21:41:24'),
(12, 'Bến Xe (Tái Bản 2020)', 3, 5, 12, 58500.00, 10, 'Bến Xe (Tái Bản 2020)\r\n\r\nBến Xe\r\n\r\nThứ tôi có thể cho em trong cuộc đời này\r\n\r\nchỉ là danh dự trong sạch\r\n\r\nvà một tương lai tươi đẹp mà thôi.\r\n\r\nThế nhưng, nếu chúng ta có kiếp sau,\r\n\r\nnếu kiếp sau tôi có đôi mắt sáng,\r\n\r\ntôi sẽ ở bến xe này… đợi em.', 'NT1.jpg', 284, 2020, '2026-03-16 21:44:42'),
(13, 'Ngoảnh Lại Đã Một Đời', 3, 10, 13, 100000.00, 5, '“Đời người trăm năm, qua đi vội vã, bao nhiêu phong cảnh đợi bạn đến chiêm ngưỡng, bao nhiêu câu chuyện đợi bạn đến lấp đầy. Những gặp gỡ, truy cầu, được mất trong hành trình, đều là tu hành. Tuế nguyệt dần dần qua đi, lúc như vô tình, lúc lại như hữu ý, không cố chấp đối với mọi việc, thì sẽ không vui quá hóa nhàm, buồn quá thành tuyệt vọng. Đời người giống như là chèo thuyền trên sóng khơi, nên giữ vững nội tâm, mới có thể thong dong điềm tĩnh, từ gấp gáp đến chậm rãi, từ ồn ã đến yên ả.”', 'NT5.jpg', 316, 2019, '2026-03-16 21:46:05'),
(14, 'Thất Tịch Không Mưa (Tái Bản 2017)', 3, 11, 14, 66220.00, 5, 'Từ nhỏ cô đã thầm yêu anh, như số kiếp không thể thay đổi Tình yêu trong sáng ấy, như lần đầu được nếm mùi vị của quả khế mới chín. Sau đó cô và anh xa nhau, gặp lại đều cách nhau ba năm.\r\n\r\nTình yêu, giống như lần đầu được nếm thử quả khế mới chín.\r\n\r\nChua chua, chát chát, nhưng không kìm được, vẫn muốn nếm thêm lần nữa.\r\n\r\nTrong quả khế chát xanh xanh, nụ cười ngốc nghếch, ngọt ngào của anh, tình đầu thơ ngây, trong sáng của em lặng lẽ nảy mầm.', 'NT2.jpg', 320, 2017, '2026-03-16 21:47:33'),
(15, 'Mãi Mãi Là Bao Xa (Tái Bản)', 3, 12, 15, 103950.00, 5, '\"Em là cây hoa loa kèn hoang dã mãi mãi chỉ vì chính mình mà nở hoa, rời khỏi đất mẹ là cái giá phải trả khi yêu anh.\"\r\n\r\n-------\r\n\r\nBạch Lăng Lăng, nữ sinh khoa Điện khí, trẻ trung, xinh đẹp và rất tự hào khi quen được một người bạn lý tưởng qua mạng, chàng du học sinh của một trường nổi tiếng của Mỹ, người mang biệt danh “nhà khoa học”: Mãi Mãi Là Bao Xa. Qua những cuộc chuyện trò trên QQ, Lăng Lăng đã gắn bó với chàng trai đó lúc nào cô cũng không hay, cảm xúc lớn dần, sự chia sẻ lớn dần và đến một ngày cô phát hiện ra mình đã yêu người con trai “tài giỏi” và không một chút khuyết điểm ấy.', 'NT4.jpg', 590, 2022, '2026-03-16 21:51:02'),
(16, 'Từng Có Người Yêu Tôi Như Sinh Mệnh (Tái Bản 2024)', 3, 5, 16, 92880.00, 5, 'Cô bé của tôi, chúc em một đời bình an vui vẻ. -----', 'NT3.jpg', 464, 2024, '2026-03-16 21:53:04'),
(17, 'Dế Mèn Phiêu Lưu Ký - Tái Bản 2020', 4, 1, 17, 45000.00, 5, 'Ấn bản minh họa màu đặc biệt của Dế Mèn phiêu lưu ký, với phần tranh ruột được in hai màu xanh - đen ấn tượng, gợi không khí đồng thoại.\r\n\r\n“Một con dế đã từ tay ông thả ra chu du thế giới tìm những điều tốt đẹp cho loài người. Và con dế ấy đã mang tên tuổi ông đi cùng trên những chặng đường phiêu lưu đến với cộng đồng những con vật trong văn học thế giới, đến với những xứ sở thiên nhiên và văn hóa của các quốc gia khác. Dế Mèn Tô Hoài đã lại sinh ra Tô Hoài Dế Mèn, một nhà văn trẻ mãi không già trong văn chương...” - Nhà phê bình Phạm Xuân Nguyên\r\n\r\n“Ông rất hiểu tư duy trẻ thơ, kể với chúng theo cách nghĩ của chúng, lí giải sự vật theo lô gích của trẻ. Hơn thế, với biệt tài miêu tả loài vật, Tô Hoài dựng lên một thế giới gần gũi với trẻ thơ. Khi cần, ông biết đem vào chất du ký khiến cho độc giả nhỏ tuổi vừa hồi hộp theo dõi, vừa thích thú khám phá.” - TS Nguyễn Đăng Điệp', 'TTN2.jpg', 0, 2020, '2026-03-17 12:49:18'),
(18, 'Boxset Cá Voi Đêm Bão Và Những Câu Chuyện Khác (Bộ 5 Cuốn) (Tái Bản 2025)', 4, 1, 18, 234000.00, 5, 'Bộ sách “Cá voi đêm bão” bao gồm các tác phẩm của tác giả Benji Davies đã giành được những giải thưởng văn chương thiếu nhi danh giá. Bộ sách gồm những câu chuyện cảm động và đầy ý nghĩa về tình cảm gia đình, tình yêu thiên nhiên… được thể hiện bằng màu sắc và đường nét tuyệt đẹp, có sức lay động mọi trẻ em trên toàn thế giới.', 'TTN1.jpg', 160, 2025, '2026-03-17 12:50:54'),
(19, 'Tác Phẩm Chọn Lọc - Văn Học Pháp - Hoàng Tử Bé (Tái Bản)', 4, 1, 19, 28000.00, 5, '“...Cậu hoàng tử chợp mắt ngủ, tôi bế em lên vòng tay tôi và lại lên đường. Lòng tôi xúc động. Tôi có cảm giác như trên Mặt Đất này không có gì mong manh hơn. Nhờ ánh sáng trăng, tôi nhìn thấy vầng trán nhợt nhạt ấy, đôi mắt nhắm nghiền các lẵng tóc run rẩy trước gió, và tôi nghĩ thầm: \"Cái mà ta nhìn thấy đây chỉ là cái vỏ. Cái quan trọng nhất thì không nhìn thấy được...\" ANTOINE DE SAINT-EXUPÉRY', 'TTN4.jpg', 112, 2022, '2026-03-17 12:52:22'),
(20, 'Diary Of A Wimpy Kid - Nhật Ký Chú Bé Nhút Nhát', 4, 5, 20, 72000.00, 5, '“Nhật ký chú bé nhút nhát – Tác giả: Jeff Kinney” – Bộ sách trụ vững ở vị trí Bestseller của New York Times đến hơn 10 năm (từ năm 2007) với số lượng tiêu thụ lên tới 170 triệu cuốn trên toàn thế giới, xoay quanh cậu bé Greg thông minh, tinh quái hiện đã cực kỳ nổi tiếng với độc giả Việt Nam từ năm 2008 - nay.\r\nBộ sách đã trở thành series dành cho tuổi học trò được ưa chuộng nhất thế giới, vượt qua cả bộ truyện đình đám Harry Potter của nhà văn J. K. Rowling, đưa tác giả Jeff Kinney trở thành một trong 100 người có ảnh hưởng nhất thế giới.\r\nVới mong muốn biến bộ sách không chỉ là một bộ truyện giải trí mà trở thành công cụ hỗ trợ các độc giả, đặc biệt là các độc giả nhí có mong muốn đọc và trau dồi vốn từ vựng, ngữ pháp và khả năng đọc hiểu tiếng Anh, Hà Giang Books đã in song song hai ngôn ngữ Việt-Anh từ tập 1-11, đồng thời cung cấp thêm các từ, cụm từ tiếng Anh thông dụng biến hóa theo tình huống hài hước trong truyện giúp độc giả tiện tra cứu.\r\nBộ truyện Song ngữ được xuất bản năm 2020 với những cập nhật về mặt thiết kế hứa hẹn vẫn sẽ là những cuốn sách khiến bạn đọc thư giãn và quan trọng hơn là tích lũy được lượng từ vựng tiếng Anh phong phú sau khi đọc.', 'TTN3.jpg', 360, 2020, '2026-03-17 12:54:04'),
(21, 'Chuyện Con Mèo Dạy Hải Âu Bay (Tái Bản 2024)', 4, 13, 20, 51000.00, 5, 'Cô hải âu Kengah bị nhấn chìm trong váng dầu – thứ chất thải nguy hiểm mà những con người xấu xa bí mật đổ ra đại dương. Với nỗ lực đầy tuyệt vọng, cô bay vào bến cảng Hamburg và rơi xuống ban công của con mèo mun, to đùng, mập ú Zorba. Trong phút cuối cuộc đời, cô sinh ra một quả trứng và con mèo mun hứa với cô sẽ thực hiện ba lời hứa chừng như không tưởng với loài mèo:\r\n\r\nKhông ăn quả trứng.\r\n\r\nChăm sóc cho tới khi nó nở.\r\n\r\nDạy cho con hải âu bay.', 'TTN5.jpg', 142, 2024, '2026-03-17 13:07:24'),
(22, 'Từ Điển Bằng Tranh - Cờ Các Quốc Gia Trên Thế Giới (Tái Bản 2023)', 5, 8, 22, 92000.00, 5, 'Chúng ta cùng mở cuốn sách ra và khám phá xem có những quốc gia nào nhé !', 'TDTN4.jpg', 28, 2023, '2026-03-17 13:10:03'),
(23, 'Từ Điển Bằng Tranh - Phương Tiện Giao Thông (Tái Bản 2023)', 5, 8, 22, 92000.00, 5, 'Cuốn sách với nhiều hình ảnh sinh động về các loại phương tiện giao thông gần gũi và quen thuộc mà bé bắt gặp hằng ngày hay những phương tiện giao thông mà bé ít được biết đến như: tàu ngầm, xe quân sự, tàu sân bay … giúp bé phát triển khả năng nhận biết. Cuốn sách còn là bộ sưu tập thu nhỏ với nhiều hình ảnh gần gũi quen thuộc và mới lạ giúp bé trở nên thích thú. Bên cạnh đó hình thức song ngữ giúp bé gia tăng vốn từ vựng cả về Tiếng Việt lẫn Tiếng Anh. Cuốn sách cũng là tài liệu học từ vựng cho bất cứ bạn nhỏ nào yêu thích môn Tiếng Anh. Các bạn nhỏ có thể vừa học được từ vựng vừa học được cách phát âm. Bộ sách có thiết kế hợp lý, hình ảnh đẹp, màu sắc bắt mắt giúp bé hào hứng tiếp cận các kiến thức dễ dàng và hiệu quả và đây không còn là những trang sách đầy chữ dày cộm, khô cứng. Cuốn Từ điển bằng tranh theo phương pháp giáo dục sớm GLENN DOMAN cho học sinh là một sự thay đổi bước ngoặt để đưa từ điển đến gần với học sinh, tạo cảm hứng học tập, hứng khởi tìm hiểu, thông qua hình ảnh minh họa để học từ vựng. Sách được in đẹp theo tiêu chuẩn của Anh quốc, giá bìa thấp để có thể đến tay nhiều đối tượng người đọc hơn.', 'TDTN5.jpg', 28, 2023, '2026-03-17 13:10:54'),
(24, 'Từ Điển Hình Ảnh Cho Bé - Động Vật (Tái Bản 2019)', 5, 1, 23, 27000.00, 5, 'Bộ sách gồm 10 cuốn, với các chủ đề: Chữ cái, Số đếm, Màu sắc và hình dạng, Động vật; Hoa, Trái cây, Rau củ, Phương tiện, Đồ dùng, Đồ chơi. Mỗi chủ đề gồm 14 hình ảnh quen thuộc gần gũi với bé và các từ tương ứng với nó (bằng cả tiếng Việt và tiếng Anh) nhằm giúp các bé từ 0-3 tuổi có được vốn từ cần thiết, để bé có những hình dung bước đầu về thế giới xung quanh; đồng thời với vốn từ nho nhỏ này bé sẽ biểu đạt ý nghĩ của mình tốt hơn. Bộ sách có thể coi là những cuốn sách giáo khoa đầu đời dạy bé những bài học đầu tiên đơn giản mà vô cùng ý nghĩa; từ đó bé có thể tự tin trong  giao tiếp.\r\n\r\nSách in trên giấy Ivory, hình ảnh tươi sáng, vui mắt, phần từ ngữ in to dễ đọc, khổ 12,5 x 12,5 cm thích hợp vừa làm sách vừa làm đồ chơi bền bỉ cho các bé 0-3 tuổi.', 'TDTN2.jpg', 12, 2019, '2026-03-17 13:12:22'),
(25, 'Từ Điển Hình Ảnh Cho Bé - Chữ Cái (Tái Bản 2019)', 5, 1, 24, 27000.00, 5, 'Bộ sách gồm 10 cuốn, với các chủ đề: Chữ cái, Số đếm, Màu sắc và hình dạng, Động vật; Hoa, Trái cây, Rau củ, Phương tiện, Đồ dùng, Đồ chơi. Mỗi chủ đề gồm 14 hình ảnh quen thuộc gần gũi với bé và các từ tương ứng với nó (bằng cả tiếng Việt và tiếng Anh) nhằm giúp các bé từ 0-3 tuổi có được vốn từ cần thiết, để bé có những hình dung bước đầu về thế giới xung quanh; đồng thời với vốn từ nho nhỏ này bé sẽ biểu đạt ý nghĩ của mình tốt hơn. Bộ sách có thể coi là những cuốn sách giáo khoa đầu đời dạy bé những bài học đầu tiên đơn giản mà vô cùng ý nghĩa; từ đó bé có thể tự tin trong  giao tiếp.\r\n\r\nSách in trên giấy Ivory, hình ảnh tươi sáng, vui mắt, phần từ ngữ in to dễ đọc, khổ 12,5 x 12,5 cm thích hợp vừa làm sách vừa làm đồ chơi bền bỉ cho các bé 0-3 tuổi.', 'TDTN1.jpg', 12, 2019, '2026-03-17 13:13:45'),
(26, 'Từ Điển Đầu Đời', 5, 9, 25, 152000.00, 4, 'Những năm tháng đầu đời, mỗi từ bé học được không chỉ là một khái niệm mới, mà còn là một “chiếc chìa khóa” mở ra cách con quan sát thế giới, cảm nhận sự vật và giao tiếp với mọi người. Hiểu rõ điều đó, chuyên viên ngôn ngữ trị liệu nhi Nguyễn Thị Thanh Trinh, đồng tác giả của bộ thẻ “Cất tiếng thành lời”, đã dày công biên soạn bộ thẻ “Từ điển đầu đời” như một cuốn từ điển bằng hình dành riêng cho trẻ nhỏ.', 'TDTN3.jpg', 20, 2025, '2026-03-17 13:16:25'),
(27, 'Sách Âm Thanh - 8 Âm Thanh Vui Nhộn - Những Chú Khủng Long Vui Vẻ', 6, 8, 26, 185500.00, 5, 'Bé hãy quay ngược thời gian trở về thời tiền sử và cùng tham gia vào hành trình phiêu lưu khám phá thế giới ồn ào của chú khủng long con nhé! Với 8 âm thanh hài hước cùng hình minh họa ngộ nghĩnh, cuốn sách dễ thương này sẽ mang đến cho bé những giờ phút giải trí vô cùng thú vị.', 'SN1.jpg', 12, 2023, '2026-03-17 13:18:28'),
(28, 'Sách Âm Thanh - Những Loài Vật Quanh Em', 6, 10, 27, 175000.00, 5, 'Ở phiên bản mới nhất, bộ nhớ âm thanh được mở rộng gấp 5 lần so với trước đây, kéo dài thời gian phát âm thanh. Thay vì những âm thanh ngắn vài giây, giờ đây trẻ đã có thể nghe hết một câu chuyện hay một bản nhạc.', 'SN3.jpg', 10, 2022, '2026-03-17 13:20:05'),
(29, 'Sách Âm Thanh - Thỏ Và Rùa', 6, 12, 27, 183000.00, 5, 'Cuộc sống thật vui nhộn khi được gặp lại những bạn động vật quen thuộc, lắng nghe tiếng kêu của các bạn ấy qua cuốn sách âm thanh mới nhất của Đinh Tị Books.\r\n\r\nSách Âm thanh \"made in Việt Nam\" là tuyển tập những câu chuyện kể khai thác chủ đề thân thuộc quanh trẻ. Chuyến khám phá này không chỉ có hình ảnh mà còn có cả âm thanh, qua mắt trẻ thế giới bên ngoài được tái hiện vô cùng chân thực.', 'SN2.jpg', 10, 2021, '2026-03-17 13:21:11'),
(30, 'Sách Âm Thanh - 8 Âm Thanh Ngộ Nghĩnh - Những Âm Thanh Hài Hước', 6, 8, 26, 185500.00, 5, 'Bé hãy cùng hòa mình vào thế giới âm thanh hài hước và vui nhộn khi những loài động vật trong trang trại thức giấc và nô đùa nhé! Với tiếng hắt hơi của những chú gà, tiếng xì xụp của các chú lợn và vô số tiếng ồn ngộ nghĩnh hơn thế nữa, cuốn sách hứa hẹn sẽ mang đến cho bé niềm vui và những tiếng cười thoải mái trong giờ kể chuyện.', 'SN4.jpg', 12, 2023, '2026-03-17 13:22:17'),
(31, 'Sách Âm Thanh - 8 Âm Thanh Sinh Động - Những Từ Vựng Đầu Đời Của Bé', 6, 8, 26, 185500.00, 5, 'Bé đã sẵn sàng khám phá bãi biển, thị trấn, khu vườn và ngôi nhà, nơi có vô số đồ vật tuyệt vời ở khắp mọi nơi chưa nhỉ? Với 8 âm thanh sinh động cùng nhiều từ vựng thú vị mà đơn giản, cuốn sách chính là người bạn hoàn hảo chứa đựng vốn từ vựng phong phú dành cho bé yêu.', 'SN5.jpg', 12, 2023, '2026-03-17 13:23:04'),
(32, 'Còn Chút Gì Để Nhớ (Tái Bản 2022)', 1, 8, 2, 36500.00, 10, 'Đó là những kỷ niệm thời đi học của Chương, lúc mới bước chân vào Sài Gòn và làm quen với cuộc sống đô thị.\r\n\r\nLà những mối quan hệ bạn bè tưởng chừng hời hợt thoảng qua nhưng gắn bó suốt cuộc đời.\r\n\r\nCuộc sống đầy biến động đã xô dạt mỗi người mỗi nơi, nhưng trải qua hàng mấy chục năm, những kỷ niệm ấy vẫn luôn níu kéo Chương về với một thời để nhớ.', '1773822945_TT7.jpg', 220, 2022, '2026-03-18 15:35:45'),
(33, 'Cậu Là Ánh Bình Minh Của Tôi', 1, 5, 28, 121500.00, 10, 'Cậu là ánh bình minh của tôi xoay quanh cô bạn nhỏ Phương Ngọc học giỏi, hiền lành. Vì biến cố gia đình, Phương Ngọc buộc phải chuyển đến một nơi khác cùng mẹ bắt đầu lại cuộc sống mới. Tại đây, Phương Ngọc dần trở nên thân thiết với cô bạn Hà Như, Hoàng Nam; đồng thời quen biết cậu bạn cùng bàn là Giang Nhất, người sau này trở thành ánh bình minh rực rỡ nhất trong năm tháng phổ thông của Phương Ngọc.', '1773906057_TT8.jpg', 270, 2025, '2026-03-19 14:40:57'),
(34, 'Trốn Lên Mái Nhà Để Khóc', 2, 8, 29, 80500.00, 10, '\"Có những ngày chẳng ai hiểu mình, chẳng ai cần mình, chẳng ai thương mình. Và những ngày đó, mái nhà là nơi duy nhất tôi thấy an toàn.\"\r\n\r\n\"Trốn Lên Mái Nhà Để Khóc\" không chỉ là câu chuyện của riêng tác giả, mà còn là những mảnh ghép ký ức của mỗi người. Một cuốn sách dành cho những trái tim nhạy cảm, cho những ai từng giấu nước mắt sau nụ cười, từng thu mình vào một góc chỉ để đối diện với chính mình.', '1773997481_TN7.jpg', 208, 2023, '2026-03-20 16:04:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tacgia`
--

CREATE TABLE `tacgia` (
  `IDTacGia` int(11) NOT NULL,
  `TenTacGia` varchar(150) NOT NULL,
  `TieuSu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tacgia`
--

INSERT INTO `tacgia` (`IDTacGia`, `TenTacGia`, `TieuSu`) VALUES
(1, 'Tuế Kiến', ''),
(2, 'Nguyễn Nhật Ánh', ''),
(3, 'Quất Tử Bất Toan', ''),
(4, 'Fredrik Backman', ''),
(5, 'Bảo Ninh', ''),
(6, 'Mộc Trầm', ''),
(7, 'Châu Sa Đáy Mắt', ''),
(8, 'Hạ Mer', ''),
(9, 'Góc Nhỏ Của Han', ''),
(10, 'Lily Trương', ''),
(11, 'Nam Cao', ''),
(12, 'Thương Thái Vi', ''),
(13, 'Bạch Lạc Mai', ''),
(14, 'Lâu Vũ Tình', ''),
(15, 'Diệp Lạc Vô Tâm', ''),
(16, 'Thư Nghi', ''),
(17, 'Tô Hoài', ''),
(18, 'Benji Davies', ''),
(19, 'Antoine De Saint-Exupéry', ''),
(20, 'Jeff Kinney', ''),
(21, 'Luis Sepúlveda', ''),
(22, 'Phan Minh Đao', ''),
(23, 'Lê Bích Thuỷ, Hiếu Minh', ''),
(24, 'Phạm Huy Thông, Lê Bích Thuỷ', ''),
(25, 'Nguyễn Thị Thanh Trinh', ''),
(26, 'Igloo Books', ''),
(27, 'Khánh Vân, Quỳnh Rùa', ''),
(28, 'Giang Hải Yến', ''),
(29, 'Lam', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `theloai`
--

CREATE TABLE `theloai` (
  `IDTheLoai` int(11) NOT NULL,
  `TenTheLoai` varchar(100) NOT NULL,
  `IDDanhMuc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `theloai`
--

INSERT INTO `theloai` (`IDTheLoai`, `TenTheLoai`, `IDDanhMuc`) VALUES
(1, 'Tiểu thuyết', 1),
(2, 'Truyện ngắn', 1),
(3, 'Ngôn Tình', 1),
(4, 'Truyện Thiếu Nhi', 2),
(5, 'Từ Điển Thiếu Nhi', 2),
(6, 'Sách Nói', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vaitro`
--

CREATE TABLE `vaitro` (
  `IDVaiTro` int(11) NOT NULL,
  `TenVaiTro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vaitro`
--

INSERT INTO `vaitro` (`IDVaiTro`, `TenVaiTro`) VALUES
(1, 'admin'),
(2, 'user');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`IDCTDH`),
  ADD KEY `IDDonHang` (`IDDonHang`),
  ADD KEY `IDSach` (`IDSach`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`IDDanhGia`),
  ADD KEY `IDSach` (`IDSach`),
  ADD KEY `IDNguoiDung` (`IDNguoiDung`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`IDDanhMuc`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`IDDonHang`),
  ADD KEY `IDNguoiDung` (`IDNguoiDung`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`IDNguoiDung`),
  ADD KEY `IDVaiTro` (`IDVaiTro`);

--
-- Chỉ mục cho bảng `nhaxuatban`
--
ALTER TABLE `nhaxuatban`
  ADD PRIMARY KEY (`IDNXB`);

--
-- Chỉ mục cho bảng `sach`
--
ALTER TABLE `sach`
  ADD PRIMARY KEY (`IDSach`),
  ADD KEY `IDTheLoai` (`IDTheLoai`),
  ADD KEY `IDNXB` (`IDNXB`),
  ADD KEY `IDTacGia` (`IDTacGia`);

--
-- Chỉ mục cho bảng `tacgia`
--
ALTER TABLE `tacgia`
  ADD PRIMARY KEY (`IDTacGia`);

--
-- Chỉ mục cho bảng `theloai`
--
ALTER TABLE `theloai`
  ADD PRIMARY KEY (`IDTheLoai`),
  ADD KEY `IDDanhMuc` (`IDDanhMuc`);

--
-- Chỉ mục cho bảng `vaitro`
--
ALTER TABLE `vaitro`
  ADD PRIMARY KEY (`IDVaiTro`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `IDCTDH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `IDDanhGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `IDDanhMuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `IDDonHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `IDNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `nhaxuatban`
--
ALTER TABLE `nhaxuatban`
  MODIFY `IDNXB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `sach`
--
ALTER TABLE `sach`
  MODIFY `IDSach` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `tacgia`
--
ALTER TABLE `tacgia`
  MODIFY `IDTacGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `theloai`
--
ALTER TABLE `theloai`
  MODIFY `IDTheLoai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `vaitro`
--
ALTER TABLE `vaitro`
  MODIFY `IDVaiTro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`IDDonHang`) REFERENCES `donhang` (`IDDonHang`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`IDSach`) REFERENCES `sach` (`IDSach`);

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`IDSach`) REFERENCES `sach` (`IDSach`),
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`IDNguoiDung`) REFERENCES `nguoidung` (`IDNguoiDung`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`IDNguoiDung`) REFERENCES `nguoidung` (`IDNguoiDung`);

--
-- Các ràng buộc cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `nguoidung_ibfk_1` FOREIGN KEY (`IDVaiTro`) REFERENCES `vaitro` (`IDVaiTro`);

--
-- Các ràng buộc cho bảng `sach`
--
ALTER TABLE `sach`
  ADD CONSTRAINT `sach_ibfk_1` FOREIGN KEY (`IDTheLoai`) REFERENCES `theloai` (`IDTheLoai`),
  ADD CONSTRAINT `sach_ibfk_2` FOREIGN KEY (`IDNXB`) REFERENCES `nhaxuatban` (`IDNXB`),
  ADD CONSTRAINT `sach_ibfk_3` FOREIGN KEY (`IDTacGia`) REFERENCES `tacgia` (`IDTacGia`);

--
-- Các ràng buộc cho bảng `theloai`
--
ALTER TABLE `theloai`
  ADD CONSTRAINT `theloai_ibfk_1` FOREIGN KEY (`IDDanhMuc`) REFERENCES `danhmuc` (`IDDanhMuc`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
