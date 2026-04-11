-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 28, 2026 lúc 03:52 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `eoffice_vietmy`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_accounts`
--

CREATE TABLE `hicrm_accounts` (
  `id` int(11) NOT NULL,
  `account_number` varchar(10) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_type` int(2) NOT NULL DEFAULT 1 COMMENT '1 - dư nợ, 2 - dư có, 3 - lưỡng tính',
  `account_name_en` varchar(255) NOT NULL,
  `account_description` text DEFAULT NULL,
  `account_status` int(2) NOT NULL DEFAULT 1 COMMENT '1 - đang sử dụng, 2 - ngưng sử dụng',
  `account_parent` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_accounts`
--

INSERT INTO `hicrm_accounts` (`id`, `account_number`, `account_name`, `account_type`, `account_name_en`, `account_description`, `account_status`, `account_parent`) VALUES
(1, '111', 'Tiền mặt', 1, 'Cash in hand', NULL, 1, 0),
(2, '1111', 'Tiền Việt Nam', 1, 'Vietnam dong', NULL, 1, 1),
(3, '1112', 'Ngoại tệ', 1, 'Foreign currency', NULL, 1, 1),
(4, '112', 'Tiền gửi Ngân hàng', 1, 'Cash in bank', NULL, 1, 0),
(5, '1121', 'Tiền Việt Nam', 1, 'Vietnam dong', NULL, 1, 4),
(6, '1122', 'Ngoại tệ', 1, 'Foreign currency', NULL, 1, 4),
(7, '121', 'Chứng khoán kinh doanh', 1, 'Trading securities', NULL, 1, 4),
(8, '128', 'Đầu tư nắm giữ đến ngày đáo hạn', 1, 'Held to maturity investment', NULL, 1, 0),
(9, '1281', 'Tiền gửi có kỳ hạn', 1, 'Term deposits', NULL, 1, 8),
(10, '1288', 'Các khoản đầu tư khác nắm giữ đến ngày đáo hạn', 1, 'Other held to maturity investments', NULL, 1, 8),
(11, '131', 'Phải thu của khách hàng', 3, 'Receivables from customers', NULL, 1, 8),
(12, '133', 'Thuế GTGT được khấu trừ', 1, 'VAT receivable', NULL, 1, 0),
(13, '1331', 'Thuế GTGT được khấu trừ của hàng hóa, dịch vụ', 1, 'VAT receivable - goods and services', NULL, 1, 12),
(14, '1332', 'Thuế GTGT được khấu trừ của TSCĐ', 1, 'VAT receivable - fixed assets', NULL, 1, 12),
(15, '136', 'Phải thu nội bộ', 1, 'Internal receivables', NULL, 1, 0),
(16, '1361', 'Vốn kinh doanh ở đơn vị trực thuộc', 1, 'Investment in equity of subsidiaries', NULL, 1, 15),
(17, '1368', 'Phải thu nội bộ khác', 1, 'Other internal receivables', NULL, 1, 15),
(18, '138', 'Phải thu khác', 3, 'Other receivable', NULL, 1, 0),
(19, '1381', 'Tài sản thiếu chờ xử lý', 3, 'Shortage of assets awaiting resolution', NULL, 1, 18),
(20, '1386', 'Cầm cố, thế chấp, ký quỹ, ký cược', 1, 'Deposits, mortgages and collateral', NULL, 1, 18),
(21, '1388', 'Phải thu khác', 3, 'Other receivable', NULL, 1, 18),
(22, '141', 'Tạm ứng', 1, 'Advances', NULL, 1, 18),
(23, '151', 'Hàng mua đang đi đường', 1, 'Goods in transit', NULL, 1, 0),
(24, '152', 'Nguyên liệu, vật liệu', 1, 'Raw materials', NULL, 1, 0),
(25, '153', 'Công cụ, dụng cụ', 1, 'Tools and equipments', NULL, 1, 0),
(26, '154', 'Chi phí sản xuất, kinh doanh dở dang', 1, 'Work in progress', NULL, 1, 0),
(27, '155', 'Thành phẩm', 1, 'Finished goods', NULL, 1, 0),
(28, '156', 'Hàng hóa', 1, 'Goods', NULL, 1, 0),
(29, '157', 'Hàng gửi đi bán', 1, 'Goods on consignment', NULL, 1, 0),
(30, '211', 'Tài sản cố định', 1, 'Long-term assets', NULL, 1, 0),
(31, '2111', 'TSCĐ hữu hình', 1, 'House, building', NULL, 1, 30),
(32, '21111', 'Nhà cửa, vật kiến trúc', 1, 'House, building', NULL, 1, 31),
(33, '21112', 'Máy móc thiết bị', 1, 'Equipment & machines', NULL, 1, 31),
(34, '21113', 'Phương tiện vận tải truyền dẫn', 1, 'Means of transport, conveyance equipment', NULL, 1, 31),
(35, '21114', 'Thiết bị dụng cụ quản lý', 1, 'Managerial equipment and instruments', NULL, 1, 31),
(36, '21115', 'Cây lâu năm, súc vật làm việc và cho sản phẩm', 1, 'Plants and livestocks', NULL, 1, 31),
(37, '21116', 'Các TSCĐ là kết cấu hạ tầng, có giá trị lớn do Nhà nước ĐTXD từ NSNN giao cho các tổ chức kinh tế quản lý, khai thác, sử dụng', 1, 'Fixed assets are high value infrastructure funded from State Budget, transferred to business entities to manage, exploit, use', NULL, 1, 31),
(38, '21118', 'TSCĐ khác', 1, 'Other tangible fixed assets', NULL, 1, 31),
(39, '2112', 'TSCĐ thuê tài chính', 1, 'Machinery, equipments', NULL, 1, 30),
(40, '2113', 'TSCĐ vô hình', 1, 'Intangible fixed assets', NULL, 1, 30),
(41, '21131', 'Quyền sử dụng đất', 1, 'Right of land use', NULL, 1, 40),
(42, '21132', 'Quyền phát hành', 1, 'Publishing rights', NULL, 1, 40),
(43, '21133', 'Bản quyền, bằng sáng chế', 1, 'Copyright, patents', NULL, 1, 40),
(44, '21134', 'Nhãn hiệu hàng hóa', 1, 'Trademark', NULL, 1, 40),
(45, '21135', 'Phần mềm máy vi tính', 1, 'Software', NULL, 1, 40),
(46, '21136', 'Giấy phép và giấy chuyển nhượng quyền', 1, 'License and right concession permits', NULL, 1, 40),
(47, '21138', 'TSCĐ vô hình khác', 1, 'Other intangible fixed assets', NULL, 1, 40),
(48, '214', 'Hao mòn TSCĐ', 2, 'Accumulated depreciation - fixed assets', NULL, 1, 0),
(49, '2141', 'Hao mòn TSCĐ hữu hình', 2, 'Accumulated depreciation - tangible fixed assets', NULL, 1, 48),
(50, '2142', 'Hao mòn TSCĐ thuê tài chính', 2, 'Accumulated depreciation - financial leasing fixed assets', NULL, 1, 48),
(51, '2143', 'Hao mòn TSCĐ vô hình', 2, 'Accumulated depreciation - intangible fixed assets', NULL, 1, 48),
(52, '2147', 'Hao mòn bất động sản đầu tư', 2, 'Accumulated depreciation - investment property', NULL, 1, 48),
(53, '217', 'Bất động sản đầu tư', 1, 'Investmet property', NULL, 1, 0),
(54, '228', 'Đầu tư góp vốn vào đơn vị khác', 1, 'Equity investments in other entities', NULL, 1, 0),
(55, '2281', 'Đầu tư vào công ty liên doanh, liên kết', 1, 'Investments in joint ventures and associates', NULL, 1, 54),
(56, '2288', 'Đầu tư khác', 1, 'Other investments', NULL, 1, 54),
(57, '229', 'Dự phòng tổn thất tài sản', 2, 'Provisions for impairment of assets', NULL, 1, 0),
(58, '2291', 'Dự phòng giảm giá chứng khoán kinh doanh', 2, 'Provision for diminution in the value of trading securities ', NULL, 1, 57),
(59, '2292', 'Dự phòng tổn thất đầu tư vào đơn vị khác', 2, 'Provisions for impairment of investments in other entities', NULL, 1, 57),
(60, '2293', 'Dự phòng phải thu khó đòi', 2, 'Provisions for doubtful debts', NULL, 1, 57),
(61, '2294', 'Dự phòng giảm giá hàng tồn kho', 2, 'Provisions for inventories', NULL, 1, 57),
(62, '241', 'Xây dựng cơ bản dở dang', 1, 'Construction in process', NULL, 1, 0),
(63, '2411', 'Mua sắm TSCĐ', 1, 'Fixed assets purchases', NULL, 1, 62),
(64, '2412', 'Xây dựng cơ bản', 1, 'Construction in process', NULL, 1, 62),
(65, '2413', 'Sửa chữa lớn TSCĐ', 1, 'Major repair of fixed assets', NULL, 1, 62),
(66, '242', 'Chi phí trả trước', 1, 'Prepaid expenses', NULL, 1, 0),
(67, '331', 'Phải trả cho người bán', 3, 'Payable to suppliers', NULL, 1, 0),
(68, '333', 'Thuế và các khoản phải nộp Nhà nước', 3, 'Taxes and payable to state budget', NULL, 1, 0),
(69, '3331', 'Thuế giá trị gia tăng phải nộp', 3, 'Value Added Tax payables', NULL, 1, 68),
(70, '33311', 'Thuế GTGT đầu ra', 3, 'VAT output', NULL, 1, 69),
(71, '33312', 'Thuế GTGT hàng nhập khẩu', 3, 'VAT for imported goods', NULL, 1, 69),
(72, '3332', 'Thuế tiêu thụ đặc biệt', 3, 'Special consumption tax', NULL, 1, 68),
(73, '3333', 'Thuế xuất, nhập khẩu', 3, 'Import & export duties', NULL, 1, 68),
(74, '3334', 'Thuế thu nhập doanh nghiệp', 3, 'Company income tax', NULL, 1, 68),
(75, '3335', 'Thuế thu nhập cá nhân', 3, 'Personal income tax', NULL, 1, 68),
(76, '3336', 'Thuế tài nguyên', 3, 'Natural resource tax', NULL, 1, 68),
(77, '3337', 'Thuế nhà đất, tiền thuê đất', 3, 'Land & housing tax, land rental charges', NULL, 1, 68),
(78, '3338', 'Thuế bảo vệ môi trường và các loại thuế khác', 3, 'Environment protection tax and other taxes', NULL, 1, 68),
(79, '33381', 'Thuế bảo vệ môi trường', 3, 'Environment protection tax', NULL, 1, 78),
(80, '33382', 'Các loại thuế khác', 3, 'Other taxes', NULL, 1, 78),
(81, '3339', 'Phí, lệ phí và các khoản phải nộp khác', 3, 'Fee & charge & other payables', NULL, 1, 68),
(82, '334', 'Phải trả người lao động', 2, 'Payable to employees', NULL, 1, 0),
(83, '335', 'Chi phí phải trả', 2, 'Payable expenses', NULL, 1, 0),
(84, '336', 'Phải trả nội bộ', 2, 'Internal payables', NULL, 1, 0),
(85, '3361', 'Phải trả nội bộ về vốn kinh doanh', 2, 'Internal payables for operating capital received', NULL, 1, 84),
(86, '3368', 'Phải trả nội bộ khác', 2, 'Other Internal payables', NULL, 1, 84),
(87, '338', 'Phải trả, phải nộp khác', 3, 'Other payable', NULL, 1, 0),
(88, '3381', 'Tài sản thừa chờ giải quyết', 3, 'Surplus assets awaiting for resolution', NULL, 1, 87),
(89, '3382', 'Kinh phí công đoàn', 3, 'Trade Union fees', NULL, 1, 87),
(90, '3383', 'Bảo hiểm xã hội', 3, 'Social insurance', NULL, 1, 87),
(91, '3384', 'Bảo hiểm y tế', 3, 'Health insurance', NULL, 1, 87),
(92, '3385', 'Bảo hiểm thất nghiệp', 3, 'Unemployment insurance', NULL, 1, 87),
(93, '3386', 'Nhận ký quỹ, ký cược', 3, 'Collaterals, deposits received', NULL, 1, 87),
(94, '3387', 'Doanh thu chưa thực hiện', 3, 'Unrealised revenue', NULL, 1, 87),
(95, '3388', 'Phải trả, phải nộp khác', 3, 'Other payable', NULL, 1, 87),
(96, '341', 'Vay và nợ thuê tài chính', 2, 'Borrowings and financial lease liabilities', NULL, 1, 0),
(97, '3411', 'Các khoản đi vay', 2, 'Borrowing', NULL, 1, 96),
(98, '3412', 'Nợ thuê tài chính', 2, 'Financial lease liabilities', NULL, 1, 96),
(99, '352', 'Dự phòng phải trả', 2, 'Provisions for payables', NULL, 1, 0),
(100, '3521', 'Dự phòng bảo hành sản phẩm hàng hóa', 2, 'Product warranty provisions', NULL, 1, 99),
(101, '3522', 'Dự phòng bảo hành công trình xây dựng', 2, 'Construction warranty provisions', NULL, 1, 99),
(102, '3524', 'Dự phòng phải trả khác', 2, 'Other provisions', NULL, 1, 99),
(103, '353', 'Quỹ khen thưởng, phúc lợi', 2, 'Bonus & welfare funds', NULL, 1, 0),
(104, '3531', 'Quỹ khen thưởng', 2, 'Bonus fund', NULL, 1, 103),
(105, '3532', 'Quỹ phúc lợi', 2, 'Welfare fund', NULL, 1, 103),
(106, '3533', 'Quỹ phúc lợi đã hình thành TSCĐ', 2, 'Welfare fund used to acquire fixed assets', NULL, 1, 103),
(107, '3534', 'Quỹ thưởng ban quản lý điều hành công ty', 2, 'Management bonus fund', NULL, 1, 103),
(108, '356', 'Quỹ phát triển khoa học và công nghệ', 2, 'Science and technology development fund', NULL, 1, 0),
(109, '3561', 'Quỹ phát triển khoa học và công nghệ', 2, 'Science and technology development fund', NULL, 1, 108),
(110, '3562', 'Quỹ phát triển khoa học và công nghệ đã hình thành TSCĐ', 2, 'Science and technology development fund used for fixed asset acquisition', NULL, 1, 108),
(111, '411', 'Vốn đầu tư của chủ sở hữu', 2, 'Contributed legal capital', NULL, 1, 0),
(112, '4111', 'Vốn góp của chủ sở hữu', 2, 'Contributed capital ', NULL, 1, 111),
(113, '4112', 'Thặng dư vốn cổ phần', 2, 'Share capital surplus', NULL, 1, 111),
(114, '4118', 'Vốn khác', 2, 'Other capital', NULL, 1, 111),
(115, '413', 'Chênh lệch tỷ giá hối đoái', 3, 'Foreign exchange differences', NULL, 1, 111),
(116, '418', 'Các quỹ thuộc vốn chủ sở hữu', 2, 'Other funds', NULL, 1, 0),
(117, '419', 'Cổ phiếu quỹ', 1, 'Treasury share', NULL, 1, 0),
(118, '421', 'Lợi nhuận sau thuế chưa phân phối', 3, 'Undistributed earnings', NULL, 1, 0),
(119, '4211', 'Lợi nhuận sau thuế chưa phân phối năm trước', 3, 'Previous year undistributed earnings', NULL, 1, 118),
(120, '4212', 'Lợi nhuận sau thuế chưa phân phối năm nay', 3, 'This year undistributed earnings', NULL, 1, 118),
(121, '511', 'Doanh thu bán hàng và cung cấp dịch vụ', 3, 'Revenue from sales of goods and provision of services', NULL, 1, 0),
(122, '5111', 'Doanh thu bán hàng hóa', 3, 'Revenue from sales of goods', NULL, 1, 121),
(123, '5112', 'Doanh thu bán thành phẩm', 3, 'Revenue from sales of finished goods', NULL, 1, 121),
(124, '5113', 'Doanh thu cung cấp dịch vụ', 3, 'Revenue from provision of services', NULL, 1, 121),
(125, '5118', 'Doanh thu khác', 3, 'Other revenues', NULL, 1, 121),
(126, '515', 'Doanh thu hoạt động tài chính', 3, 'Revenue from financial operations', NULL, 1, 0),
(127, '611', 'Mua hàng', 3, 'Cost of purchases', NULL, 1, 0),
(128, '631', 'Giá thành sản xuất', 3, 'Cost of production', NULL, 1, 0),
(129, '632', 'Giá vốn hàng bán', 3, 'Cost of goods sold', NULL, 1, 0),
(130, '635', 'Chi phí tài chính', 3, 'Financial activities expenses', NULL, 1, 0),
(131, '642', 'Chi phí quản lý kinh doanh', 3, 'Business management expenses', NULL, 1, 0),
(132, '6421', 'Chi phí bán hàng', 3, 'Selling expenses', NULL, 1, 131),
(133, '6422', 'Chi phí quản lý doanh nghiệp', 3, 'General & administration expenses', NULL, 1, 131),
(134, '711', 'Thu nhập khác', 3, 'Other income', NULL, 1, 0),
(135, '811', 'Chi phí khác', 3, 'Other expenses', NULL, 1, 0),
(136, '821', 'Chi phí thuế thu nhập doanh nghiệp', 3, 'Business Income tax charge', NULL, 1, 0),
(137, '911', 'Xác định kết quả kinh doanh', 3, 'Evaluation of business results', NULL, 1, 0),
(138, '0001', 'Tài khoản thử', 2, 'Test Account 1', 'Test account', 99, 2),
(139, '0001112', 'Tài khoản thử', 2, 'Test Account', 'Test account', 99, 2),
(140, '0002112', 'Tài khoản thử', 2, 'Test Account', 'Test account', 1, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_banks`
--

CREATE TABLE `hicrm_banks` (
  `id` int(11) NOT NULL,
  `bank_name` text NOT NULL,
  `bank_name_en` text NOT NULL,
  `bank_code` varchar(30) NOT NULL,
  `bank_logo` text DEFAULT NULL,
  `bank_description` varchar(255) DEFAULT NULL,
  `bank_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_banks`
--

INSERT INTO `hicrm_banks` (`id`, `bank_name`, `bank_name_en`, `bank_code`, `bank_logo`, `bank_description`, `bank_status`) VALUES
(3, 'Ngân hàng TMCP Á Châu', 'Asia Commercial Joint Stock Bank', 'ACB', NULL, NULL, 0),
(4, 'Ngân hàng TMCP Tiên Phong', 'Tien Phong Commercial Joint Stock Bank', 'TPBank', NULL, NULL, 0),
(5, 'Ngân hàng TMCP Đông Á', 'Dong A Commercial Joint Stock Bank', 'DAB', NULL, NULL, 0),
(6, 'Ngân Hàng TMCP Đông Nam Á', 'Southeast Asia Commercial Joint Stock Bank', 'SeABank', NULL, NULL, 0),
(7, 'Ngân hàng TMCP An Bình', 'An Binh Commercial Joint Stock Bank', 'ABBANK', NULL, NULL, 0),
(8, 'Ngân hàng TMCP Bắc Á', 'Bac A Commercial Joint Stock Bank', 'BacABank', NULL, NULL, 0),
(9, 'Ngân hàng TMCP Bản Việt', 'Vietcapital Commercial Joint Stock Bank', 'VietCapitalBank', NULL, NULL, 0),
(10, 'Ngân hàng TMCP Hàng hải Việt Nam', 'Vietnam Maritime Joint – Stock Commercial Bank', 'MSB', NULL, NULL, 0),
(11, 'Ngân hàng TMCP Kỹ Thương Việt Nam', 'VietNam Technological and Commercial Joint Stock Bank', 'TCB', NULL, NULL, 0),
(12, 'Ngân hàng TMCP Kiên Long', 'Kien Long Commercial Joint Stock Bank', 'KienLongBank', NULL, NULL, 0),
(13, 'Ngân hàng TMCP Nam Á', 'Nam A Comercial Join Stock Bank', 'Nam A Bank', NULL, NULL, 0),
(14, 'Ngân hàng TMCP Quốc Dân', 'National Citizen Commercial Joint Stock Bank', 'NCB', NULL, NULL, 0),
(15, 'Ngân hàng TMCP Việt Nam Thịnh Vượng', 'Vietnam Prosperity Joint Stock Commercial Bank', 'VPBank', NULL, NULL, 0),
(16, 'Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh', 'Ho Chi Minh City Housing Development Bank', 'HDBank', NULL, NULL, 0),
(17, 'Ngân hàng TMCP Việt Nam Thịnh Vượng', 'Vietnam Prosperity Joint Stock Commercial Bank', 'VPBank', NULL, NULL, 0),
(18, 'Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh', 'Ho Chi Minh City Housing Development Bank', 'HDBank', NULL, NULL, 0),
(19, 'Ngân hàng TMCP Phương Đông', 'Orient Commercial Joint Stock Bank', 'OCB', NULL, NULL, 0),
(20, 'Ngân hàng TMCP Quân đội', 'Military Commercial Joint Stock Bank', 'MB', NULL, NULL, 0),
(21, 'Ngân hàng TMCP Đại chúng', 'Vietnam Public Joint Stock Commercial Bank', 'PVcombank', NULL, NULL, 0),
(22, 'Ngân hàng TMCP Quốc tế Việt Nam', 'Vietnam International and Commercial Joint Stock Bank', 'VIB', NULL, NULL, 0),
(23, 'Ngân hàng TMCP Sài Gòn', 'Sai Gon Joint Stock Commercial Bank', 'SCB', NULL, NULL, 0),
(24, 'Ngân hàng TMCP Sài Gòn Công Thương', 'Saigon Bank for Industry and Trade', 'SGB', NULL, NULL, 0),
(25, 'Ngân hàng TMCP Sài Gòn – Hà Nội', 'Saigon – Hanoi Commercial Joint Stock Bank', 'SHB', NULL, NULL, 0),
(26, 'Ngân hàng TMCP Sài Gòn Thương Tín', 'Sai Gon Thuong Tin Commercial Joint Stock Bank', 'Sacombank', NULL, NULL, 0),
(27, 'Ngân hàng TMCP Việt Á', 'Vietnam Asia Commercial Joint Stock Bank', 'VietABank', NULL, NULL, 0),
(28, 'Ngân hàng TMCP Bảo Việt', 'Bao Viet Joint Stock Commercial Bank', 'BaoVietBank', NULL, NULL, 0),
(29, 'Ngân hàng TMCP Việt Nam Thương Tín', 'Vietnam Thuong Tin Commercial Joint Stock Bank', 'VietBank', NULL, NULL, 0),
(30, 'Ngân Hàng TMCP Xăng Dầu Petrolimex', 'Joint Stock Commercia Petrolimex Bank', 'PG Bank', NULL, NULL, 0),
(31, 'Ngân Hàng TMCP Xuất Nhập khẩu Việt Nam', 'Vietnam Joint Stock Commercia lVietnam Export Import Bank', 'EIB', NULL, NULL, 0),
(32, 'Ngân Hàng TMCP Bưu điện Liên Việt', 'Joint stock commercial Lien Viet postal bank', 'LPB', NULL, NULL, 0),
(33, 'Ngân Hàng TMCP Ngoại thương Việt Nam', 'JSC Bank for Foreign Trade of Vietnam', 'VCB', NULL, NULL, 0),
(34, 'Ngân Hàng TMCP Công Thương Việt Nam', 'Vietnam Joint Stock Commercial Bank for Industry and Trade', 'VietinBank', NULL, NULL, 0),
(35, 'Ngân Hàng TMCP Đầu tư và Phát triển Việt Nam', 'JSC Bank for Investment and Development of Vietnam', 'BIDV', NULL, NULL, 0),
(36, 'Ngân hàng Chính sách xã hội', 'Vietnam Bank for Social Policies', 'NHCSXH', NULL, NULL, 0),
(37, 'Ngân hàng Phát triển Việt Nam', 'Vietnam Development Bank', 'VDB', NULL, NULL, 0),
(38, 'Ngân hàng Thương mại TNHH MTV Xây dựng Việt Nam', 'Construction Bank', 'CB', NULL, NULL, 0),
(39, 'Ngân hàng Thương mại TNHH MTV Đại Dương', 'Ocean Commercial One Member Limited Liability Bank', 'Oceanbank', NULL, NULL, 0),
(40, 'Ngân hàng Thương mại TNHH MTV Dầu Khí Toàn Cầu', 'Global Petro Commercial Joint Stock Bank', 'GPBank', NULL, NULL, 0),
(41, 'Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam', 'Vietnam Bank for Agriculture and Rural Development', 'Agribank', NULL, NULL, 0),
(42, 'Ngân hàng TNHH MTV ANZ (Việt Nam)', 'Australia And Newzealand Bank', '', NULL, NULL, 0),
(43, 'Deutsche Bank Việt Nam', 'Deutsche Bank AG', '', NULL, NULL, 0),
(44, 'Ngân hàng Citibank Việt Nam', 'Citibank', 'Citibank', NULL, NULL, 0),
(45, 'Ngân hàng TNHH MTV HSBC (Việt Nam)', 'HSBC', '', NULL, NULL, 0),
(46, 'Ngân hàng TNHH MTV Standard Chartered (Việt Nam)', 'Standard Chartered Bank (Vietnam) Limited', 'Standard Chartered', NULL, NULL, 0),
(47, 'Ngân hàng TNHH MTV Shinhan Việt Nam', 'Shinhan Vietnam Bank Limited', 'SHBVN', NULL, NULL, 0),
(48, 'Ngân hàng Hong Leong Việt Nam', 'Hong Leong Bank Vietnam Limited – HLBVN', '', NULL, NULL, 0),
(49, 'Ngân hàng Đầu tư và Phát triển Campuchia', '', 'BIDC', NULL, NULL, 0),
(50, 'Ngân Hàng Mizuho Bank', '', 'Mizuhobank', NULL, NULL, 0),
(51, 'Ngân hàng Tokyo-Mitsubishi UFJ', '', '', NULL, NULL, 0),
(52, 'Ngân hàng Sumitomo Mitsui Bank', '', '', NULL, NULL, 0),
(53, 'Ngân hàng TNHH MTV Public Việt Nam', '', 'PBBVN', NULL, NULL, 0),
(54, 'Ngân hàng Commonwealth Bank Việt Nam', '', '', NULL, NULL, 0),
(55, 'Ngân hàng United Overseas Bank Việt Nam', '', 'UOB', NULL, NULL, 0),
(56, 'Ngân hàng Bank of China', '', '', NULL, NULL, 0),
(57, 'Ngân hàng Maybank Việt Nam', '', '', NULL, NULL, 0),
(58, 'Ngân Hàng Công Thương Trung Quốc (ICBC)', '', ' ICBC', NULL, NULL, 0),
(59, 'Ngân hàng Scotiabank', '', '', NULL, NULL, 0),
(60, 'Ngân hàng Commercial Siam Bank Việt Nam', '', '', NULL, NULL, 0),
(61, 'Ngân Hàng Bnp Paribas', '', '', NULL, NULL, 0),
(62, 'Ngân hàng Bankok bank Việt Nam', '', '', NULL, NULL, 0),
(63, 'Ngân hàng Worldbank Việt Nam', '', '', NULL, NULL, 0),
(64, 'Ngân hàng Woori bank Việt Nam', '', '', NULL, NULL, 0),
(65, 'Ngân hàng RHB (Malaysia) tại Việt Nam', '', '', NULL, NULL, 0),
(66, 'Ngân hàng Intesa Sanpaolo (Italia) tại Việt Nam', '', '', NULL, NULL, 0),
(67, 'Ngân hàng JP Morgan Chase Bank (Mỹ) tại Việt Nam', '', '', NULL, NULL, 0),
(68, 'Ngân hàng Wells Fargo (Mỹ) tại Việt Nam', '', '', NULL, NULL, 0),
(69, 'Ngân hàng BHF – Bank Aktiengesellschaft (Đức) tại Việt Nam', '', '', NULL, NULL, 0),
(70, 'Ngân hàng Unicredit Bank AG (Đức) tại Việt Nam', '', '', NULL, NULL, 0),
(71, 'Ngân hàng Landesbank Baden-Wuerttemberg (Đức) tại Việt Nam', '', '', NULL, NULL, 0),
(72, 'Ngân hàng Commerzbank AG (Đức) tại Việt Nam', '', '', NULL, NULL, 0),
(73, 'Ngân hàng Bank Sinopac (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(74, 'Ngân hàng Chinatrust Commercial Bank (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(75, 'Ngân hàng Union Bank of Taiwan (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(76, 'Ngân hàng Hua Nan Commercial Bank  Ltd (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(77, 'Ngân hàng Cathay United Bank (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(78, 'Ngân hàng Taishin International Bank (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(79, 'Ngân hàng Land Bank of Taiwan (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(80, 'Ngân hàng The Shanghai Commercial and Savings Bank Ltd (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(81, 'Ngân hàng Taiwan Shin Kong Commercial Bank (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(82, 'Ngân hàng E.Sun Commercial Bank (Đài Loan) tại Việt Nam', '', '', NULL, NULL, 0),
(83, 'Ngân hàng Natixis Banque BFCE (Pháp) tại Việt Nam', '', '', NULL, NULL, 0),
(84, 'Ngân hàng Société Générale Bank – tại TP. HCM (Pháp) tại Việt Nam', '', '', NULL, NULL, 0),
(85, 'Ngân hàng Fortis Bank (Bỉ) tại Việt Nam', '', '', NULL, NULL, 0),
(86, 'Ngân hàng RBI (Áo) tại Việt Nam', '', '', NULL, NULL, 0),
(87, 'Ngân hàng Phongsavanh (Lào) tại Việt Nam', '', '', NULL, NULL, 0),
(88, 'Ngân hàng Acom Co., Ltd (Nhật) tại Việt Nam', '', '', NULL, NULL, 0),
(89, 'Ngân hàng Mitsubishi UFJ Lease & Finance Company Limited (Nhật) tại Việt Nam', '', '', NULL, NULL, 0),
(90, 'Ngân hàng Industrial Bank of Korea (Hàn Quốc) tại Việt Nam', '', '', NULL, NULL, 0),
(91, 'Ngân hàng Korea Exchange Bank (Hàn Quốc) tại Việt Nam', '', '', NULL, NULL, 0),
(92, 'Ngân hàng Kookmin Bank (Hàn Quốc) tại Việt Nam', '', '', NULL, NULL, 0),
(93, 'Ngân hàng Hana Bank (Hàn Quốc) tại Việt Nam', '', '', NULL, NULL, 0),
(94, 'Ngân hàng Bank of India (Ấn Độ) tại Việt Nam', '', '', NULL, NULL, 0),
(95, 'Ngân hàng Indian Oversea Bank (Ấn Độ) tại Việt Nam', '', '', NULL, NULL, 0),
(96, 'Ngân hàng Rothschild Limited (Singapore) tại Việt Nam', '', '', NULL, NULL, 0),
(97, 'Ngân hàng The Export-Import Bank of Korea (Hàn Quốc) tại Việt Nam', '', '', NULL, NULL, 0),
(98, 'Ngân hàng Busan – (Hàn Quốc) tại Việt Nam', '', '', NULL, NULL, 0),
(99, 'Ngân hàng Ogaki Kyorítu (Nhật Bản) tại Việt Nam', '', '', NULL, NULL, 0),
(100, 'Ngân hàng Phát triển Hàn Quốc (Hàn Quốc) tại Việt Nam', '', '', NULL, NULL, 0),
(101, 'Ngân hàng Phát triển Châu Á và Việt Nam', '', '', NULL, NULL, 0),
(102, 'Ngân hàng Oversea-Chinese Banking Corporation LTD', '', ' OCBC', NULL, NULL, 0),
(103, 'Ngân hàng TNHH Indovina', '', 'IVB', NULL, NULL, 0),
(104, 'Ngân hàng Liên doanh Việt – Nga', '', 'VRB', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_bank_accounts`
--

CREATE TABLE `hicrm_bank_accounts` (
  `id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `ba_branch_id` int(11) NOT NULL,
  `ba_account` varchar(30) NOT NULL,
  `ba_holder` varchar(255) NOT NULL,
  `ba_branch` text NOT NULL,
  `ba_description` text DEFAULT NULL,
  `ba_status` int(2) NOT NULL,
  `ba_primary` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_bank_accounts`
--

INSERT INTO `hicrm_bank_accounts` (`id`, `bank_id`, `ba_branch_id`, `ba_account`, `ba_holder`, `ba_branch`, `ba_description`, `ba_status`, `ba_primary`) VALUES
(1, 5, 1, '501320555942', 'Vũ Xuân Cương', 'Huyện Kongchro, Tỉnh Gia Lai', '', 1, 0),
(2, 5, 0, '50132055591', 'Vũ Xuân Cương', 'Huyện Kongchro, Tỉnh Gia Lai', 'TEst a', 99, 0),
(3, 5, 0, '501320555943', 'Vũ Xuân Cương', 'Huyện Kongchro, Tỉnh Gia Lai', 'Nhân bản', 2, 0),
(4, 3, 0, '501320555944', 'Vũ Xuân Cương', 'Huyện Kongchro, Tỉnh Gia Lai', 'Nhân bản 2', 99, 0),
(5, 4, 0, '1 1 1111111111111', 'haauj', 'gia lai', '', 99, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_bookings`
--

CREATE TABLE `hicrm_bookings` (
  `id` bigint(30) NOT NULL,
  `booking_person_name` varchar(255) DEFAULT NULL,
  `booking_person_gender` int(11) NOT NULL,
  `booking_person_year` int(11) NOT NULL,
  `booking_person_address` varchar(255) DEFAULT NULL,
  `booking_person_phone` varchar(255) NOT NULL,
  `booking_doctor` int(11) DEFAULT 0,
  `booking_date` date DEFAULT NULL,
  `booking_hour` varchar(255) NOT NULL,
  `booking_title` varchar(255) DEFAULT NULL,
  `booking_description` text DEFAULT NULL,
  `booking_created_date` datetime DEFAULT NULL,
  `booking_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_bookings`
--

INSERT INTO `hicrm_bookings` (`id`, `booking_person_name`, `booking_person_gender`, `booking_person_year`, `booking_person_address`, `booking_person_phone`, `booking_doctor`, `booking_date`, `booking_hour`, `booking_title`, `booking_description`, `booking_created_date`, `booking_status`) VALUES
(1, NULL, 0, 0, '', '0', 1, NULL, '14:21:45', 'Họp tổng kết', NULL, NULL, 2),
(2, '2,3,5', 0, 0, '', '0', 1, NULL, '10:42:00', 'Test', 'Test', NULL, 2),
(3, '2,3,5', 0, 0, '', '0', 1, NULL, '10:42:00', 'Test', 'Test', NULL, 1),
(4, '', 0, 0, '', '0', 1, NULL, '10:48:00', 'Test 2', 'Test 2', NULL, 1),
(5, '1,2', 0, 0, '', '0', 2, NULL, '11:42:00', 'Test', '', NULL, 1),
(6, 'aaaaa', 1, 1998, 'aaaa', '1111231231', 2, '2026-01-03', '08:00', NULL, 'aaaa', NULL, 1),
(7, 'Vũ Xuân Cương', 1, 1998, 'Kon Tum', '0828222833', 2, '2026-01-03', '08:00', NULL, 'aaaa', NULL, 1),
(8, 'Vũ Xuân Cương', 1, 1998, 'Kon Tum', '0828222833', 2, '2026-01-03', '08:00', NULL, 'aaaa', NULL, 1),
(9, '1231231aa', 1, 1111, 'aaaa', 'd123123123', 2, '2026-01-03', '08:00', NULL, '12313123123', NULL, 1),
(10, 'ádasdasdas', 1, 1112, '11123123', 'd112312222', 2, '2026-01-03', '08:00', NULL, '12123123123', NULL, 1),
(11, '1123123123', 1, 1332, '1112312z', '3333333333', 19, '2026-01-02', '14:00', NULL, '112312', NULL, 1),
(12, '123123123', 1, 1123, '111111111111', '1211112312', 2, '2026-01-31', '14:00', NULL, 'aaasdasd', NULL, 1),
(13, '123123123', 1, 3333, 'aaaaaaaaaaaa', '1111111111', 2, '2026-01-03', '07:00', NULL, 'aaaaaaaaaaaa', NULL, 1),
(14, '13123123', 1, 3312, 'aasd', '1111111111', 2, '2026-01-31', '15:00', NULL, 'aaa', NULL, 1),
(15, 'ádasdasdas', 1, 1123, 'ádasdas', 'd123123123', 2, '2026-01-03', '15:00', NULL, 'aaa', NULL, 1),
(16, 'đáasdasdasd', 1, 0, 'ádasdasd', '1231231111', 2, '2026-01-29', '14:00', NULL, 'adasdasdasd', NULL, 1),
(17, 'aasdasd', 1, 1111, 'aaaaa', '1233333333', 2, '2026-01-03', '07:00', NULL, 'aaaaaaaaaaaaaaaa', NULL, 1),
(18, 'qqweqwe', 1, 1213, '1111', '3133333333', 2, '0000-00-00', '', NULL, '', NULL, 1),
(19, 'Vũ Xuân Cương', 1, 1111, 'kon tum', '0822822833', 2, '2026-01-03', '07:00', NULL, 'aaa', '2026-01-03 10:07:27', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_booking_status`
--

CREATE TABLE `hicrm_booking_status` (
  `id` int(11) NOT NULL,
  `bk_status_label` varchar(80) NOT NULL,
  `bk_status_class` varchar(100) DEFAULT NULL,
  `bk_status_icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_booking_status`
--

INSERT INTO `hicrm_booking_status` (`id`, `bk_status_label`, `bk_status_class`, `bk_status_icon`) VALUES
(1, 'Mới', 'primary ', ''),
(2, 'Đã duyệt', 'success', NULL),
(3, 'Đã hủy', 'danger', NULL),
(4, 'Khác', 'warning', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_branchs`
--

CREATE TABLE `hicrm_branchs` (
  `id` int(11) NOT NULL,
  `branch_uid` int(11) NOT NULL,
  `branch_tax_code` varchar(30) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` text NOT NULL,
  `branch_phone` varchar(255) NOT NULL,
  `branch_email` varchar(255) NOT NULL,
  `branch_director` text DEFAULT NULL,
  `branch_type` int(3) NOT NULL,
  `branch_founded_date` datetime DEFAULT NULL,
  `branch_created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_branchs`
--

INSERT INTO `hicrm_branchs` (`id`, `branch_uid`, `branch_tax_code`, `branch_name`, `branch_address`, `branch_phone`, `branch_email`, `branch_director`, `branch_type`, `branch_founded_date`, `branch_created_date`) VALUES
(1, 1, '5901047034', 'An Lộc FSC', '499A Phan Đình Phùng, P. Yên Đõ, TP. Pleiku, Gia Lai', '02693720888', 'info@anlocgroup.vn', 'Nguyễn Khoa Quyền', 1, '2021-08-12 16:44:46', '2021-08-19 16:47:09'),
(2, 1, '5747828318', 'Công ty TNHN VCF MEDIA Gia Lai', '86 - Cách mạng tháng 8 - Hoa Lư - Pleiku - Gia Lai', '0997123123', 'vcf.info@gmail.com', 'Thái Đình Sang', 1, '2019-06-12 00:00:00', '2021-09-10 14:07:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_calendar_works`
--

CREATE TABLE `hicrm_calendar_works` (
  `id` int(11) NOT NULL,
  `calendar_work_name` varchar(255) NOT NULL,
  `calendar_work_content` longtext DEFAULT NULL,
  `calendar_work_file` varchar(255) DEFAULT NULL,
  `calendar_work_from_date` datetime NOT NULL,
  `calendar_work_to_date` datetime NOT NULL,
  `calendar_work_created_date` datetime NOT NULL,
  `calendar_work_user_created` int(2) NOT NULL,
  `calendar_status` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_calendar_works`
--

INSERT INTO `hicrm_calendar_works` (`id`, `calendar_work_name`, `calendar_work_content`, `calendar_work_file`, `calendar_work_from_date`, `calendar_work_to_date`, `calendar_work_created_date`, `calendar_work_user_created`, `calendar_status`) VALUES
(1, 'aaa', '', '8116651572e45c53f12eb849040c04f8-PL7.Bocosdngvttyttiuhao.pdf', '2026-01-28 00:00:00', '2026-01-29 00:00:00', '2026-01-28 20:46:47', 24, 1),
(2, 'Lịch tuần 2', '', '770753b334723db19fbb7a2300a4d669-rp_phieunhapvien_1769486261.pdf', '2026-01-28 00:00:00', '2026-01-30 00:00:00', '2026-01-28 21:44:15', 24, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_caludar_employees`
--

CREATE TABLE `hicrm_caludar_employees` (
  `id` int(11) NOT NULL,
  `caludar_id_employee` int(11) NOT NULL,
  `caludar_time` datetime NOT NULL,
  `caludar_status` int(11) NOT NULL,
  `user_created` int(11) NOT NULL,
  `caludar_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_categories`
--

CREATE TABLE `hicrm_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text DEFAULT NULL,
  `category_parent` int(11) NOT NULL,
  `category_orderby` int(2) DEFAULT 0,
  `category_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_categories`
--

INSERT INTO `hicrm_categories` (`id`, `category_name`, `category_description`, `category_parent`, `category_orderby`, `category_status`) VALUES
(1, 'Tin tức và sự kiện', NULL, 5, NULL, 1),
(2, 'Về chúng tôi', NULL, 1, NULL, 1),
(3, 'Cơ cấu tổ chức', NULL, 1, NULL, 1),
(4, 'Cơ sở hạ tầng', NULL, 1, NULL, 1),
(5, 'Tại sao chọn chúng tôi?', NULL, 1, 0, 1),
(6, 'Chuyên khoa', '', 3, 0, 1),
(7, 'Gói khám', '', 3, 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_category_parent`
--

CREATE TABLE `hicrm_category_parent` (
  `id` int(11) NOT NULL,
  `category_parent_name` varchar(255) NOT NULL,
  `category_parent_status` int(2) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_category_parent`
--

INSERT INTO `hicrm_category_parent` (`id`, `category_parent_name`, `category_parent_status`) VALUES
(1, 'Giới thiệu', 1),
(2, 'Dịch vụ', 99),
(3, 'Chuyên khoa và Gói khám', 1),
(4, 'Nhà thuốc', 1),
(5, 'Tin tức và sự kiện', 1),
(6, 'Bác sĩ', 99);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_configs`
--

CREATE TABLE `hicrm_configs` (
  `id` int(11) NOT NULL,
  `config_key` varchar(255) NOT NULL,
  `config_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_configs`
--

INSERT INTO `hicrm_configs` (`id`, `config_key`, `config_value`) VALUES
(1, 'won_rate', '3765'),
(2, 'website_name', 'Phong kham & nha thuoc cao dang kon tum'),
(3, 'website_description', ''),
(4, 'admin_email', 'tomchen802000@gmail.com'),
(5, 'smtp_server', ''),
(6, 'smtp_port', ''),
(7, 'smtp_protocol', ''),
(8, 'smtp_password', ''),
(9, 'sms_api_key', ''),
(10, 'agency_prefix', 'CMAG'),
(11, 'deposite_prefix', 'CMC'),
(12, 'minimun_fee', '10000'),
(13, 'admin_email.site_email', ''),
(14, 'site_phone', '0909 888 628 - 0949 84 1688'),
(15, 'site_email', 'support@clickmua.net'),
(16, 'site_address', '41 Đường Nguyễn Văn Săng, Phường Tân Sơn Nhì, Quận Tân Phú'),
(17, 'deposite_branch', ''),
(18, 'deposite_bank', 'vcb'),
(19, 'deposite_account', '8 6666 8888 1688'),
(20, 'deposite_holder', 'Tran Tam'),
(21, 'income_prefix', 'ALI'),
(22, 'customer_prefix', 'KH'),
(23, 'employee_prefix', 'BS'),
(24, 'warehouse_prefix', 'MK'),
(25, 'order_prefix', 'DH'),
(26, 'company_name', 'Viet My J.S.C'),
(27, 'company_email', 'info@earthbornholistic.com.vn'),
(28, 'company_phone', '0937180880'),
(29, 'company_tax_id', '0315422622'),
(30, 'company_address', 'Số 63 Đường CN11, Phường Sơn Kỳ, Quận Tân Phú, TPHCM'),
(31, 'QUOTE_PREFIX', 'VMQ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_currencies`
--

CREATE TABLE `hicrm_currencies` (
  `id` int(11) NOT NULL,
  `currency_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `currency_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `currency_rate` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `currency_type` int(11) NOT NULL,
  `currency_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_currencies`
--

INSERT INTO `hicrm_currencies` (`id`, `currency_code`, `currency_name`, `currency_rate`, `currency_type`, `currency_status`) VALUES
(1, 'VND', 'Việt Nam đồng', '1,00', 1, 1),
(2, 'USD', 'Đô la Mỹ', '23.150,00', 1, 1),
(3, 'EUR', 'Đồng tiền chung châu Âu', '25.974,30', 1, 1),
(4, 'JPY', 'Yên Nhật', '212,99', 1, 1),
(5, 'AUD', 'Đô la Úc', '16.242,04', 3, 1),
(6, 'CNY', 'Nhân dân tệ Trung Quốc', '3.326,53', 1, 1),
(7, 'GBP', 'Bảng Anh', '30.692,27', 1, 1),
(8, 'HKD', 'Đô La Hồng Kong', '2.970,81', 1, 1),
(9, 'KHR', 'Riêl Cămpuchia', '5,75', 1, 1),
(10, 'LAK', 'Kíp Lào', '2,61', 1, 1),
(11, 'SGD', 'Đô la Singapore', '17.215,74', 1, 1),
(12, 'THB', 'Bath Thái', '768,85', 1, 1),
(13, 'UAH', 'Grip-na Ucraina', '981,29', 1, 1),
(14, 'AOA', 'Angola', '48,11', 1, 1),
(15, 'RUB', 'Ruble Nga', '373,41', 1, 1),
(16, 'LBP', 'Lebanese Pound', '15,32', 1, 1),
(17, 'MKD', 'Macedonian Denar', '423,13', 1, 1),
(18, 'AWG', 'Aruban Florin', '12.985,42', 1, 1),
(19, 'UGX', 'Ugandan Shilling', '6,32', 1, 1),
(20, 'NZD', 'New Zealand Dollar', '15.589,21', 1, 1),
(21, 'XOF', 'CFA Franc BCEAO', '39,66', 1, 1),
(22, 'MVR', 'Maldivian Rufiyaa', '1.499,38', 1, 1),
(23, 'TZS', 'Tanzanian Shilling', '10,08', 1, 1),
(24, 'RON', 'Romanian Leu', '5.436,05', 1, 1),
(25, 'HRK', 'Croatian Kuna', '3.495,63', 1, 1),
(26, 'LSL', 'Lesotho Loti', '1.650,42', 1, 1),
(27, 'KRW', 'South Korean Won', '20,07', 1, 1),
(28, 'CLF', 'Chilean Unit of Account (UF)', '865.182,10', 1, 1),
(29, 'BGN', 'Bulgarian Lev', '13.302,32', 1, 1),
(30, 'GGP', 'Guernsey Pound', '30.741,66', 1, 1),
(31, 'GYD', 'Guyanaese Dollar', '111,03', 1, 1),
(32, 'NOK', 'Norwegian Krone', '2.640,49', 1, 1),
(33, 'TND', 'Tunisian Dinar', '8.327,09', 1, 1),
(34, 'SRD', 'Surinamese Dollar', '3.119,70', 1, 1),
(35, 'ETB', 'Ethiopian Birr', '725,96', 1, 1),
(36, 'BBD', 'Barbadian Dollar', '11.476,84', 1, 1),
(37, 'BOB', 'Bolivian Boliviano', '3.355,95', 1, 1),
(38, 'SAR', 'Saudi Riyal', '6.183,13', 1, 1),
(39, 'BIF', 'Burundian Franc', '12,33', 1, 1),
(40, 'IMP', 'Manx pound', '30.729,80', 1, 1),
(41, 'GIP', 'Gibraltar Pound', '30.729,80', 1, 1),
(42, 'LVL', 'Latvian Lats', '37.082,06', 1, 1),
(43, 'KZT', 'Kazakhstani Tenge', '60,52', 1, 1),
(44, 'TTD', 'Trinidad and Tobago Dollar', '3.427,07', 1, 1),
(45, 'TOP', 'Tongan Paʻanga', '10.164,28', 1, 1),
(46, 'BTC', 'Bitcoin', '165.373.122,73', 1, 1),
(47, 'MGA', 'Malagasy Ariary', '6,40', 1, 1),
(48, 'CUC', 'Cuban Convertible Peso', '23.244,30', 1, 1),
(49, 'STD', 'São Tomé and Príncipe Dobra', '1,06', 1, 1),
(50, 'XDR', 'Special Drawing Rights', '32.198,00', 1, 1),
(51, 'BDT', 'Bangladeshi Taka', '272,96', 1, 1),
(52, 'GNF', 'Guinean Franc', '2,43', 1, 1),
(53, 'DOP', 'Dominican Peso', '438,21', 1, 1),
(54, 'LRD', 'Liberian Dollar', '123,93', 1, 1),
(55, 'MWK', 'Malawian Kwacha', '31,47', 1, 1),
(56, 'MUR', 'Mauritian Rupee', '637,49', 1, 1),
(57, 'PEN', 'Peruvian Nuevo Sol', '6.987,14', 1, 1),
(58, 'FKP', 'Falkland Islands Pound', '30.706,86', 1, 1),
(59, 'BND', 'Brunei Dollar', '17.224,76', 1, 1),
(60, 'SVC', 'Salvadoran Colón', '2.657,14', 1, 1),
(61, 'PYG', 'Paraguayan Guarani', '3,59', 1, 1),
(62, 'HUF', 'Hungarian Forint', '78,59', 1, 1),
(63, 'MZN', 'Mozambican Metical', '378,99', 1, 1),
(64, 'BTN', 'Bhutanese Ngultrum', '325,90', 1, 1),
(65, 'SEK', 'Swedish Krona', '2.475,96', 1, 1),
(66, 'LKR', 'Sri Lankan Rupee', '127,76', 1, 1),
(67, 'XPF', 'CFP Franc', '218,16', 1, 1),
(68, 'GTQ', 'Guatemalan Quetzal', '3.007,48', 1, 1),
(69, 'QAR', 'Qatari Rial', '6.364,48', 1, 1),
(70, 'PAB', 'Panamanian Balboa', '23.172,43', 1, 1),
(71, 'GEL', 'Georgian Lari', '8.095,78', 1, 1),
(72, 'IQD', 'Iraqi Dinar', '19,41', 1, 1),
(73, 'MMK', 'Myanma Kyat', '15,70', 1, 1),
(74, 'YER', 'Yemeni Rial', '92,69', 1, 1),
(75, 'IRR', 'Iranian Rial', '0,55', 1, 1),
(76, 'SDG', 'Sudanese Pound', '513,96', 1, 1),
(77, 'BWP', 'Botswanan Pula', '2.189,79', 1, 1),
(78, 'KMF', 'Comorian Franc', '52,96', 1, 1),
(79, 'ZWL', 'Zimbabwean Dollar', '64,25', 1, 1),
(80, 'MDL', 'Moldovan Leu', '1.347,25', 1, 1),
(81, 'SLL', 'Sierra Leonean Leone', '2,38', 1, 1),
(82, 'MTL', 'Maltese Lira', '60.687,48', 1, 1),
(83, 'ZAR', 'South African Rand', '1.656,89', 1, 1),
(84, 'LTL', 'Lithuanian Litas', '7.545,51', 1, 1),
(85, 'KPW', 'North Korean Won', '25,74', 1, 1),
(86, 'INR', 'Indian Rupee', '325,04', 1, 1),
(87, 'PHP', 'Philippine Peso', '457,51', 1, 1),
(88, 'PLN', 'Polish Zloty', '6.103,99', 1, 1),
(89, 'SHP', 'Saint Helena Pound', '30.602,59', 1, 1),
(90, 'CZK', 'Czech Republic Koruna', '1.023,55', 1, 1),
(91, 'JOD', 'Jordanian Dinar', '32.681,82', 1, 1),
(92, 'SCR', 'Seychellois Rupee', '1.693,81', 1, 1),
(93, 'GMD', 'Gambian Dalasi', '452,80', 1, 1),
(94, 'TRY', 'Turkish Lira', '3.892,52', 1, 1),
(95, 'DJF', 'Djiboutian Franc', '130,17', 1, 1),
(96, 'ANG', 'Netherlands Antillean Guilder', '13.760,37', 1, 1),
(97, 'PKR', 'Pakistani Rupee', '149,63', 1, 1),
(98, 'MXN', 'Mexican Peso', '1.227,24', 1, 1),
(99, 'AFN', 'Afghan Afghani', '298,12', 1, 1),
(100, 'ISK', 'Icelandic Króna', '191,58', 1, 1),
(101, 'UZS', 'Uzbekistan Som', '2,44', 1, 1),
(102, 'TMT', 'Turkmenistani Manat', '6.630,27', 1, 1),
(103, 'EEK', 'Estonian Kroon', '1.656,82', 1, 1),
(104, 'AMD', 'Armenian Dram', '48,38', 1, 1),
(105, 'JEP', 'Jersey Pound', '30.576,96', 1, 1),
(106, 'CUP', 'Cuban Peso', '23.168,30', 1, 1),
(107, 'MRO', 'Mauritanian Ouguiya', '61,32', 1, 1),
(108, 'BZD', 'Belize Dollar', '11.496,11', 1, 1),
(109, 'BYR', 'Belarusian Ruble', '1,10', 1, 1),
(110, 'SZL', 'Swazi Lilangeni', '1.650,42', 1, 1),
(111, 'XAF', 'CFA Franc BEAC', '39,66', 1, 1),
(112, 'HNL', 'Honduran Lempira', '941,18', 1, 1),
(113, 'ZMW', 'Zambian Kwacha', '1.646,38', 1, 1),
(114, 'ALL', 'Albanian Lek', '213,38', 1, 1),
(115, 'ZMK', 'Zambian Kwacha (pre-2013)', '1,64', 1, 1),
(116, 'BRL', 'Brazilian Real', '5.760,14', 1, 1),
(117, 'IDR', 'Indonesian Rupiah', '1,67', 1, 1),
(118, 'AED', 'United Arab Emirates Dirham', '6.308,62', 1, 1),
(119, 'CDF', 'Congolese Franc', '13,73', 1, 1),
(120, 'KES', 'Kenyan Shilling', '228,93', 1, 1),
(121, 'ERN', 'Eritrean Nakfa', '1.542,67', 1, 1),
(122, 'XAU', 'Gold (troy ounce)', '35.182.224,55', 1, 1),
(123, 'BSD', 'Bahamian Dollar', '23.172,43', 1, 1),
(124, 'BMD', 'Bermudan Dollar', '23.172,43', 1, 1),
(125, 'SYP', 'Syrian Pound', '45,31', 1, 1),
(126, 'UYU', 'Uruguayan Peso', '623,77', 1, 1),
(127, 'OMR', 'Omani Rial', '60.185,06', 1, 1),
(128, 'SBD', 'Solomon Islands Dollar', '2.823,10', 1, 1),
(129, 'ARS', 'Argentine Peso', '386,99', 1, 1),
(130, 'LYD', 'Libyan Dinar', '16.563,53', 1, 1),
(131, 'ILS', 'Israeli New Sheqel', '6.710,86', 1, 1),
(132, 'RSD', 'Serbian Dinar', '221,37', 1, 1),
(133, 'CLP', 'Chilean Peso', '30,76', 1, 1),
(134, 'MAD', 'Moroccan Dirham', '2.422,62', 1, 1),
(135, 'COP', 'Colombian Peso', '7,05', 1, 1),
(136, 'HTG', 'Haitian Gourde', '243,78', 1, 1),
(137, 'DZD', 'Algerian Dinar', '194,84', 1, 1),
(138, 'CVE', 'Cape Verdean Escudo', '235,96', 1, 1),
(139, 'KGS', 'Kyrgystani Som', '333,91', 1, 1),
(140, 'TWD', 'New Taiwan Dollar', '772,80', 1, 1),
(141, 'SOS', 'Somali Shilling', '40,06', 1, 1),
(142, 'CAD', 'Canadian Dollar', '17.401,00', 1, 1),
(143, 'NIO', 'Nicaraguan Córdoba', '686,91', 1, 1),
(144, 'MYR', 'Malaysian Ringgit', '5.669,85', 1, 1),
(145, 'KWD', 'Kuwaiti Dinar', '76.478,54', 1, 1),
(146, 'GHS', 'Ghanaian Cedi', '4.130,63', 1, 1),
(147, 'TJS', 'Tajikistani Somoni', '2.386,83', 1, 1),
(148, 'AZN', 'Azerbaijani Manat', '13.653,18', 1, 1),
(149, 'WST', 'Samoan Tala', '8.811,56', 1, 1),
(150, 'EGP', 'Egyptian Pound', '1.445,61', 1, 1),
(151, 'NPR', 'Nepalese Rupee', '202,89', 1, 1),
(152, 'MOP', 'Macanese Pataca', '2.886,53', 1, 1),
(153, 'MNT', 'Mongolian Tugrik', '8,45', 1, 1),
(154, 'NGN', 'Nigerian Naira', '63,62', 1, 1),
(155, 'RWF', 'Rwandan Franc', '24,44', 1, 1),
(156, 'XAG', 'Silver (troy ounce)', '414.710,76', 1, 1),
(157, 'PGK', 'Papua New Guinean Kina', '6.793,35', 1, 1),
(158, 'CHF', 'Swiss Franc', '23.912,82', 1, 1),
(159, 'FJD', 'Fijian Dollar', '10.805,27', 1, 1),
(160, 'BHD', 'Bahraini Dinar', '61.464,68', 1, 1),
(161, 'KYD', 'Cayman Islands Dollar', '27.805,92', 1, 1),
(162, 'DKK', 'Danish Krone', '3.477,54', 1, 1),
(163, 'VEF', 'Venezuelan Bolívar Fuerte', '2.317,51', 1, 1),
(164, 'CRC', 'Costa Rican Colón', '40,58', 1, 1),
(165, 'VUV', 'Vanuatu Vatu', '201,10', 1, 1),
(166, 'XCD', 'East Caribbean Dollar', '8.589,47', 1, 1),
(167, 'JMD', 'Jamaican Dollar', '174,84', 1, 1),
(168, 'BAM', 'Bosnia-Herzegovina Convertible Mark', '13.302,79', 1, 1),
(169, 'NAD', 'Namibian Dollar', '1.650,42', 1, 1),
(170, 'ACC', 'TEST', '1,3332', 2, 99);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_customers`
--

CREATE TABLE `hicrm_customers` (
  `id` bigint(30) NOT NULL,
  `customer_uid` int(11) NOT NULL,
  `customer_branch_id` int(11) DEFAULT NULL,
  `customer_code` varchar(25) NOT NULL,
  `customer_tax_code` varchar(20) DEFAULT NULL,
  `customer_name` mediumtext DEFAULT NULL,
  `customer_title` varchar(20) DEFAULT NULL,
  `customer_address` mediumtext DEFAULT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_group` int(3) NOT NULL DEFAULT 1,
  `customer_type` int(3) NOT NULL DEFAULT 1,
  `customer_is_vendor` int(1) NOT NULL DEFAULT 0,
  `customer_loyalty_point` int(11) NOT NULL DEFAULT 0,
  `customer_staff` int(11) DEFAULT NULL,
  `customer_note` text DEFAULT NULL,
  `customer_payment_policy` int(11) NOT NULL,
  `customer_debit` int(11) NOT NULL,
  `customer_credit` int(11) NOT NULL,
  `customer_debt` decimal(20,2) NOT NULL DEFAULT 0.00,
  `customer_created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `customer_last_update` datetime DEFAULT NULL,
  `customer_status` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_customers`
--

INSERT INTO `hicrm_customers` (`id`, `customer_uid`, `customer_branch_id`, `customer_code`, `customer_tax_code`, `customer_name`, `customer_title`, `customer_address`, `customer_phone`, `customer_email`, `customer_group`, `customer_type`, `customer_is_vendor`, `customer_loyalty_point`, `customer_staff`, `customer_note`, `customer_payment_policy`, `customer_debit`, `customer_credit`, `customer_debt`, `customer_created_date`, `customer_last_update`, `customer_status`) VALUES
(1, 1, 1, 'KH0000001', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', 'Thái Đình Sang', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, NULL, 1, 1111, 1111, 0.00, '2021-08-18 21:41:08', NULL, 1),
(2, 0, 0, 'KH0000002', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-18 23:33:50', NULL, 99),
(3, 0, 0, 'KH0000003', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-18 23:34:21', NULL, 99),
(4, 1, 0, 'KH0000004', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư Phần mềm', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-18 23:35:06', NULL, 1),
(5, 0, 0, 'KH0000005', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-19 00:05:33', NULL, 1),
(6, 0, 0, 'KH0000006', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-19 00:13:11', NULL, 2),
(7, 0, 0, 'KH0000007', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-19 00:13:15', NULL, 1),
(8, 0, 0, 'KH0000008', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 2, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-19 00:13:21', NULL, 1),
(9, 0, 0, 'KH0000009', '7712312', 'Vcf Media', 'Nguyễn Trần Nhân Hậu', '86, Cách mạng tháng 8, Pleiku', '0997123123', 'Vuxuancuong@gmail.com', 1, 2, 2, 0, 1, 'Ghi chú ghi ở đây', 1, 1111, 1111, 0.00, '2021-08-19 13:57:47', '2021-08-19 13:57:47', 1),
(10, 0, 0, 'KH0000009', '7712312', 'Vcf Media', 'Nguyễn Trần Nhân Hậu', '86, Cách mạng tháng 8, Pleiku', '0997123123', 'Vuxuancuong@gmail.com', 1, 2, 2, 0, 1, 'Ghi chú ghi ở đây', 1, 1111, 1111, 0.00, '2021-08-19 13:58:18', '2021-08-19 13:58:18', 1),
(11, 0, 0, 'KH0000010', '77123122', 'Công ty TNHH Vcf Media Tây Nguyên', 'Nguyễn Trần Nhân Hậu', '86 - CMT8 - Hoa Lư - Pleiku', '0927123123', 'nguyentrannhanhau@gmail.com', 1, 2, 2, 0, 1, 'Nôi dung 1', 1, 1111, 1111, 0.00, '2021-08-19 14:05:32', '2021-08-19 14:05:32', 1),
(12, 0, 0, 'KH0000011', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-20 10:06:18', NULL, 1),
(13, 0, 0, 'KH0000012', '77123122', 'Công ty TNHH Vcf Media Tây Nguyên', 'Nguyễn Trần Nhân Hậu', '86 - CMT8 - Hoa Lư - Pleiku', '0927123123', 'nguyentrannhanhau@gmail.com', 1, 2, 2, 0, 1, 'Nôi dung 1', 1, 1111, 1111, 0.00, '2021-08-21 10:49:21', NULL, 1),
(14, 0, 0, 'KH0000013', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-22 15:32:28', NULL, 1),
(15, 0, 0, 'KH0000014', '7712312', 'Công ty TNHH Công nghệ và Đầu tư VCF', 'Nguyễn Trần Nhân Hậu', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 0, 0, 1, '', 1, 1111, 1111, 0.00, '2021-08-23 10:13:48', '2021-09-07 13:35:39', 1),
(16, 0, 0, 'KH0000015', '5901157710', 'Công ty TNHH Công nghệ và Đầu tư VCF', '', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 1, 0, 1, '', 1, 1111, 1111, 0.00, '2021-09-07 10:15:18', NULL, 99),
(17, 24, NULL, 'KH0000016', '7712312', 'Công ty TNHH Công nghệ và Đầu tư VCF', 'Nguyễn Trần Nhân Hậu', '86 Cách Mạng Tháng Tám, P. Hoa Lư, TP. Pleiku, Gia Lai', '02693883456', 'info@vcfmedia.com', 1, 1, 0, 0, 1, '', 1, 1111, 1111, 0.00, '2025-11-19 22:54:28', NULL, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_customer_banks`
--

CREATE TABLE `hicrm_customer_banks` (
  `id` int(11) NOT NULL,
  `cid` bigint(30) NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `bank_holder` varchar(255) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_branch` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_customer_groups`
--

CREATE TABLE `hicrm_customer_groups` (
  `id` int(11) NOT NULL,
  `group_code` varchar(30) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_color` varchar(20) DEFAULT NULL,
  `group_description` text DEFAULT NULL,
  `group_status` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_customer_groups`
--

INSERT INTO `hicrm_customer_groups` (`id`, `group_code`, `group_name`, `group_color`, `group_description`, `group_status`) VALUES
(1, 'KHTN', 'Khách hàng tiềm năng', 'green', 'Diễn giải', 1),
(2, 'KHTN', 'Khách hàng tiềm năng', 'green', '', 99),
(3, 'KHTT', 'Khách hàng trung thành', '#000080', 'Khách hàng trung thành mua hàng', 2),
(4, 'KHTC', 'Khách hàng tiềm năng', '#C0C0C0', '', 99),
(5, 'KHNN', 'Khách hàng ngẫu nhiên', '#008080', '', 99),
(6, '', '', '', '', 99),
(7, 'KHTC', 'Khách hàng tiềm năng', 'red', '', 99),
(8, 'KHTN', 'Test', 'green', '', 99),
(9, 'T', 'Test', '00991', '', 99),
(10, 'KHTN', 'Khách hàng tiềm năng', '#000000', 'Khách hàng tiềm năng chốt sale', 1),
(11, '', '', '', '', 99),
(12, '', '', '', '', 99),
(13, '', '', '', '', 99),
(14, '', '', '', '', 99),
(15, '', '', '', '', 99),
(16, 'KHTN', 'Khách hàng tiềm năng', '#000080', '', 99),
(17, 'KHTT', 'Khách hàng tiềm năng', '#000080', '', 99),
(18, '00998', '', '', '', 99),
(19, 'TEST', 'Test new module a', '#cccc', 'Test New Module', 99);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_departments`
--

CREATE TABLE `hicrm_departments` (
  `id` int(11) NOT NULL,
  `depart_name` varchar(255) NOT NULL,
  `depart_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_departments`
--

INSERT INTO `hicrm_departments` (`id`, `depart_name`, `depart_status`) VALUES
(1, 'Ban Giám Đốc', 1),
(2, 'Khoa Xét nghiệm', 1),
(15, 'Khoa khám bệnh', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_employees`
--

CREATE TABLE `hicrm_employees` (
  `id` int(11) NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_gender` int(2) NOT NULL,
  `employee_birthday` datetime NOT NULL,
  `employee_branch` int(11) NOT NULL DEFAULT 1,
  `employee_department` int(11) NOT NULL,
  `employee_position` int(3) NOT NULL,
  `employee_national_id` varchar(30) NOT NULL,
  `employee_issue_date` datetime NOT NULL,
  `employee_issue_by` varchar(255) NOT NULL,
  `employee_address` text NOT NULL,
  `employee_phone` varchar(50) NOT NULL,
  `employee_email` varchar(255) NOT NULL,
  `employee_debt` decimal(20,2) NOT NULL DEFAULT 0.00,
  `employee_image` varchar(255) NOT NULL,
  `employee_des` text NOT NULL,
  `employee_status` int(2) NOT NULL,
  `employee_calendar` date DEFAULT NULL,
  `employee_shift` int(11) DEFAULT NULL,
  `employee_created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `employee_last_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_employees`
--

INSERT INTO `hicrm_employees` (`id`, `employee_code`, `employee_name`, `employee_gender`, `employee_birthday`, `employee_branch`, `employee_department`, `employee_position`, `employee_national_id`, `employee_issue_date`, `employee_issue_by`, `employee_address`, `employee_phone`, `employee_email`, `employee_debt`, `employee_image`, `employee_des`, `employee_status`, `employee_calendar`, `employee_shift`, `employee_created_date`, `employee_last_update`) VALUES
(1, 'NV0000001', 'Thái Đình Sang', 1, '2019-08-20 00:00:00', 1, 17, 3, '230802525', '2020-08-20 00:00:00', 'abc', 'abc', '0963719679', 'vuxuancuong@gmail.com', 0.00, '', '', 99, '2025-12-31', 1, '2021-08-19 10:33:50', '2021-08-29 08:09:26'),
(2, 'NV0000002', 'Nguyễn Khoa Quyền', 1, '2003-08-19 00:00:00', 1, 1, 1, '909772133', '2010-08-20 00:00:00', 'Tỉnh Gia Lai', '324 Cách mạng tháng 8, Pleiku, Gia lai', '0997123131', 'haunguyen@gmail.com', 0.00, '', '', 1, '2025-12-31', 1, '2021-08-19 17:38:22', '2021-08-24 17:30:34'),
(18, 'BS003', 'ấdasdasd', 0, '2025-11-23 00:00:00', 0, 1, 0, '11111111111', '2025-11-23 00:00:00', 'aaaaaaaaaaaaaaaaaaa', 'undefined', '11111111111', 'a@ấdasdasxx ', 0.00, '02602714f65ce6f44f6e74622fc4de6c-Screenshot_1.png', 'aaa', 1, '2025-12-31', 1, '2025-11-23 20:39:23', '2025-11-23 20:39:23'),
(19, 'BS004', 'Vũ Xuân Cương', 0, '2025-11-23 00:00:00', 0, 1, 1, '030098013026', '2025-11-23 00:00:00', 'CụC CS', '', '0963719679', 'cuongvx.ktm@vnpt.vn', 0.00, 'c7eef5e7ed7bee5df6f7f585c777b461-Screenshot_1.png', 'Tôi là bác sĩ', 1, '2025-12-31', 1, '2025-11-23 20:48:14', '2025-11-23 22:28:30'),
(20, 'BS005', 'Vũ Xuân Cương 2', 0, '2025-11-23 00:00:00', 0, 1, 0, '030098013027', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '0963719671', 'cuongv2x.ktm@vnpt.vn', 0.00, '', 'aaaaa', 1, '2025-12-31', 1, '2025-11-23 20:56:58', '2025-11-23 20:56:58'),
(21, 'BS006', 'Vũ Xuân Cương 3', 0, '2025-11-23 00:00:00', 0, 1, 0, '030098013026', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '0963719679', 'cuongvx.ktm@vnpt.vn', 0.00, '', 'aaaaaaaaaaaaaaaaax', 1, '2025-12-31', 1, '2025-11-23 21:00:49', '2025-11-23 21:00:49'),
(22, 'BS007', 'Vũ Xuân Cương 12', 0, '2025-11-23 00:00:00', 0, 1, 0, '030098013026', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '0963719279', 'cuongv1x.ktm@vnpt.vn', 0.00, '', 'dx', 99, '2025-12-31', 1, '2025-11-23 21:07:07', '2025-11-23 21:07:07'),
(23, 'BS008', 'Vũ Xuân Cương 33', 1, '2025-11-23 00:00:00', 0, 1, 0, '030098013026', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '0963719679', 'cuongvx.ktm@vnpt.vn', 0.00, '887c768e210d8b199d91017d6611def3-Hnhnh1.png', 'tự giới thiệu bản thân a', 1, '2025-12-29', 1, '2025-12-28 11:59:39', '2025-12-28 11:59:39'),
(24, 'BS0000001', 'Vũ Xuân Cương 33', 1, '2025-11-23 00:00:00', 0, 1, 0, '030098013026', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '0963719679', 'cuongvx.ktm@vnpt.vn', 0.00, '', '', 99, '2025-12-31', 1, '2025-11-23 21:35:04', '2025-11-23 21:35:04'),
(25, 'BS002', 'Vũ Xuân Cương tester', 1, '2025-12-22 00:00:00', 0, 1, 0, '030098013026', '2025-12-09 00:00:00', 'CụC CS', 'undefined', '0963719179', 'cuong1a2x.ktm@vnpt.vn', 0.00, '6d0aa28924a8c2a9e74e1e1f2bd38c5c-The-Gioi-24H.jpg', 'aaa', 1, '2025-12-30', 2, '2025-12-28 12:10:15', '2025-12-28 12:10:15'),
(26, 'BS003', 'Vũ Xuân Cương 331', 1, '2025-12-23 00:00:00', 0, 1, 0, '030098013028', '2025-12-31 00:00:00', 'CụC CS', 'undefined', '0963719672', 'cuongvxax.ktm@vnpt.vn', 0.00, '878c20366d5ae480b11bb72c7cf982f8-_sieu-am-o-bung-3.jpg', 'aaa', 99, '2025-12-31', 1, '2025-12-31 23:20:58', '2025-12-31 23:20:58'),
(27, 'BS004', 'Vũ Xuân Cương test lịch', 1, '2025-12-30 00:00:00', 0, 1, 0, '030098013017', '2025-12-30 00:00:00', 'CụC CS', 'undefined', '0963719621', 'cuongv2zx.ktm@vnpt.vn', 0.00, '', '', 1, NULL, NULL, '2025-12-31 23:30:46', '2025-12-31 23:30:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_employee_banks`
--

CREATE TABLE `hicrm_employee_banks` (
  `id` int(11) NOT NULL,
  `eid` bigint(30) NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `bank_holder` varchar(255) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_branch` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_events`
--

CREATE TABLE `hicrm_events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_description` text NOT NULL,
  `event_content` longtext NOT NULL,
  `event_image` varchar(255) NOT NULL,
  `event_type` int(11) NOT NULL,
  `event_user_created` int(11) NOT NULL,
  `event_status` int(11) NOT NULL,
  `event_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_events`
--

INSERT INTO `hicrm_events` (`id`, `event_name`, `event_description`, `event_content`, `event_image`, `event_type`, `event_user_created`, `event_status`, `event_created_date`) VALUES
(1, 'sk 1', 'Mô tả', '<p>aaaa asd zzz</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 24, 99, '2026-01-06 20:49:23'),
(2, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026', 'Mô tả', '<p>Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 24, 1, '2026-01-06 20:49:23'),
(3, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2027', 'Mô tả', '<p>Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 24, 1, '2026-01-06 20:49:23'),
(4, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2027', 'Mô tả', '<p>Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 24, 1, '2026-01-06 20:49:23'),
(5, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026 1111', 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026', '<p>aaaa asd zzz</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 24, 1, '2026-01-06 20:49:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_expense_items`
--

CREATE TABLE `hicrm_expense_items` (
  `id` int(11) NOT NULL,
  `expense_code` varchar(255) NOT NULL,
  `expense_name` varchar(255) NOT NULL,
  `expense_description` text NOT NULL,
  `expense_parent` int(11) NOT NULL,
  `expense_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_expense_items`
--

INSERT INTO `hicrm_expense_items` (`id`, `expense_code`, `expense_name`, `expense_description`, `expense_parent`, `expense_status`) VALUES
(1, 'CPSX', 'Chi phí sản xuất ', 'Chi phí sản xuất', 0, 1),
(2, 'MTC', 'Chi phí sử dụng máy thi công', '', 0, 99),
(3, 'CPVH', 'Chi phí vạn hành', 'Chi phí vận hành', 1, 1),
(4, 'CPSC', 'Chi phí sửa chữa', 'Chi phí sửa chữa', 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_images`
--

CREATE TABLE `hicrm_images` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_url` text NOT NULL,
  `image_user_created` int(11) NOT NULL,
  `image_created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `image_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_images`
--

INSERT INTO `hicrm_images` (`id`, `image_name`, `image_url`, `image_user_created`, `image_created_date`, `image_status`) VALUES
(1, 'Hình ảnh giới thiệu', 'd2173854c8803807b1835ae91a68629b-Hnhnh1.png', 24, '2025-12-28 12:14:30', 1),
(2, 'Hình ảnh của đơn vị', '358c270047cc7088cc8d0d71aa50e4dc-banner.jpg', 24, '2025-12-28 13:29:48', 1),
(3, 'Cơ cấu tổ chức', '266e4bda8ef073881f1ea868529df577-SDTCR1.png', 24, '2026-01-17 11:30:15', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_incomes`
--

CREATE TABLE `hicrm_incomes` (
  `id` bigint(30) NOT NULL,
  `income_no` varchar(20) NOT NULL,
  `income_type` int(2) NOT NULL,
  `income_created_date` datetime NOT NULL,
  `income_accounting_date` datetime NOT NULL,
  `income_to` bigint(30) NOT NULL,
  `income_note` text DEFAULT NULL,
  `income_staff` int(11) NOT NULL,
  `income_document` int(5) NOT NULL DEFAULT 0,
  `income_status` int(2) NOT NULL DEFAULT 0,
  `income_created_by` int(11) NOT NULL,
  `income_approved_by` int(11) DEFAULT NULL,
  `income_approved_date` datetime DEFAULT NULL,
  `income_approved_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_incomes`
--

INSERT INTO `hicrm_incomes` (`id`, `income_no`, `income_type`, `income_created_date`, `income_accounting_date`, `income_to`, `income_note`, `income_staff`, `income_document`, `income_status`, `income_created_by`, `income_approved_by`, `income_approved_date`, `income_approved_note`) VALUES
(1, 'ALI0000001', 1, '2021-08-23 00:00:00', '2021-08-23 00:00:00', 7, 'Test phiếu', 4, 2, 0, 1, NULL, NULL, NULL),
(3, 'ALI0000002', 1, '2021-08-23 00:00:00', '2021-08-23 00:00:00', 7, 'stess', 3, 2, 0, 1, NULL, NULL, NULL),
(4, 'ALI0000003', 1, '2021-08-23 00:00:00', '2021-08-23 00:00:00', 7, 'Test lý do', 5, 2, 0, 1, NULL, NULL, NULL),
(5, 'ALI0000004', 1, '2021-08-23 00:00:00', '2021-08-23 00:00:00', 7, 'Test', 3, 2, 0, 1, NULL, NULL, NULL),
(6, 'ALI0000005', 1, '2021-08-23 00:00:00', '2021-08-23 00:00:00', 7, 'Test lý do', 3, 1, 0, 1, NULL, NULL, NULL),
(7, 'ALI0000006', 1, '2021-09-14 00:00:00', '2021-09-14 00:00:00', 4, 'ABC', 1, 1, 0, 1, NULL, NULL, NULL),
(8, 'ALI0000006', 1, '2021-09-14 00:00:00', '2021-09-14 00:00:00', 4, 'ABC', 1, 1, 0, 1, NULL, NULL, NULL),
(9, 'ALI0000006', 1, '2021-09-14 00:00:00', '2021-09-14 00:00:00', 4, 'ABC', 1, 1, 0, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_income_details`
--

CREATE TABLE `hicrm_income_details` (
  `id` bigint(30) NOT NULL,
  `income_id` bigint(30) NOT NULL,
  `income_detail` varchar(255) NOT NULL,
  `income_debit` int(11) NOT NULL,
  `income_credit` int(11) NOT NULL,
  `income_amount` decimal(20,2) NOT NULL,
  `income_bank_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_income_details`
--

INSERT INTO `hicrm_income_details` (`id`, `income_id`, `income_detail`, `income_debit`, `income_credit`, `income_amount`, `income_bank_id`) VALUES
(1, 6, 'Nội dung 1', 111, 111, 200000.00, NULL),
(2, 6, 'Nội dung 2', 111, 112, 12345678.00, NULL),
(3, 7, 'Thu tiền', 111, 111, 1000000.00, NULL),
(4, 8, 'Thu tiền', 111, 111, 1000000.00, NULL),
(5, 9, 'Thu tiền', 111, 111, 1000000.00, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_income_types`
--

CREATE TABLE `hicrm_income_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_to` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_income_types`
--

INSERT INTO `hicrm_income_types` (`id`, `type_name`, `type_to`) VALUES
(1, 'Thu tiền Khách hàng', 1),
(2, 'Thu hoàn ứng nhân viên', 3),
(3, 'Rút tiền gửi về nhập quỹ', 0),
(4, 'Thu khác', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_introduce`
--

CREATE TABLE `hicrm_introduce` (
  `id` int(11) NOT NULL,
  `introduce_id_type` int(11) NOT NULL,
  `introduce_content` longtext NOT NULL,
  `introduce_uid` int(11) NOT NULL,
  `introduce_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_introduce`
--

INSERT INTO `hicrm_introduce` (`id`, `introduce_id_type`, `introduce_content`, `introduce_uid`, `introduce_created_date`) VALUES
(1, 2, '<pre style=\"text-align: center; \"><span style=\"background-color: rgb(255, 0, 0);\"><b>PHÒNG KHÁM VÀ NHÀ THUỐC CAO ĐẲNG KON TUM</b></span></pre><ul><li style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 16px; text-align: start; white-space-collapse: preserve;\">Phòng Khám chính thức khai trương và đi vào hoạt động từ ngày 25 tháng 11 năm 2025. Cùng theo đuổi những giá trị cốt lõi “Nâng niu sức khỏe - Giữ trọn niềm tin” và mô hình quản lý dịch vụ y tế chuyên nghiệp theo chuẩn quốc tế, Phòng khám đa khoa Cao đẳng Kon Tum là một làn gió mới góp phần thay đổi tích cực trong việc chăm sóc sức khỏe cho cộng đồng.</span></li><li style=\"text-align: center;\"><img style=\"width: 50%;\" src=\"http://localhost/caodangkontum.edu.vn/uploads/images/358c270047cc7088cc8d0d71aa50e4dc-banner.jpg\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 16px; text-align: start; white-space-collapse: preserve;\"><br></span></li><li style=\"text-align: center;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 16px; text-align: start; white-space-collapse: preserve;\">Hình ảnh bác sĩ của phòng khám</span></li><li style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 16px; text-align: start; white-space-collapse: preserve;\">Là cơ sở y tế trực thuộc trường Cao đẳng Kon Tum, chúng tôi cung cấp dịch vụ khám chữa bệnh ban đầu và tư vấn dược khoa chuyên nghiệp. Với phương châm \'Lấy người bệnh làm trung tâm\', phòng khám cam kết mang lại sự an tâm tuyệt đối qua từng khâu chẩn đoán và điều trị. Đến với chúng tôi để trải nghiệm dịch vụ y tế thân thiện và chuẩn mực ngay tại địa phương.</span></li></ul><p style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 16px; text-align: start; white-space-collapse: preserve;\"><br></span></p><p style=\"text-align: right;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 16px; text-align: start; white-space-collapse: preserve;\">Tác giả: Vũ Xuân Cương</span></p><p style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 16px; text-align: start; white-space-collapse: preserve;\"><br></span></p>', 24, '2026-01-17 10:39:00'),
(2, 4, '<p>Cơ sở hạ tầng vô cùng hiện đại, đầy đủ</p>', 24, '2025-12-28 21:53:46'),
(3, 3, '<h1 style=\"text-align: center; \"><img src=\"http://localhost/caodangkontum.edu.vn/uploads/images/266e4bda8ef073881f1ea868529df577-SDTCR1.png\" style=\"color: inherit; font-family: inherit; width: 1064px;\"></h1>', 24, '2026-01-17 11:31:42'),
(4, 5, '<p>Tại sao chọn chúng tôi.</p><p>Chúng tôi có đội ngũ chuyên gia bác sĩ nhiều năm kinh nghiệm.</p>', 24, '2025-12-28 22:08:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_news`
--

CREATE TABLE `hicrm_news` (
  `id` int(11) NOT NULL,
  `new_name` varchar(255) NOT NULL,
  `new_description` text NOT NULL,
  `new_content` longtext NOT NULL,
  `new_image` varchar(255) NOT NULL,
  `new_type` int(2) DEFAULT 5,
  `new_user_created` int(11) NOT NULL,
  `new_status` int(11) NOT NULL,
  `new_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_news`
--

INSERT INTO `hicrm_news` (`id`, `new_name`, `new_description`, `new_content`, `new_image`, `new_type`, `new_user_created`, `new_status`, `new_created_date`) VALUES
(1, 'aaa', 'aaaa', '<pre style=\"text-align: center; \"><span style=\"background-color: rgb(255, 0, 0);\"><b>PHÒNG KHÁM VÀ NHÀ THUỐC CAO ĐẲNG KON TUM</b></span></pre><ul><li style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; text-align: start; white-space-collapse: preserve;\">Phòng Khám chính thức khai trương và đi vào hoạt động từ ngày 25 tháng 11 năm 2025. Cùng theo đuổi những giá trị cốt lõi “Nâng niu sức khỏe - Giữ trọn niềm tin” và mô hình quản lý dịch vụ y tế chuyên nghiệp theo chuẩn quốc tế, Phòng khám đa khoa Cao đẳng Kon Tum là một làn gió mới góp phần thay đổi tích cực trong việc chăm sóc sức khỏe cho cộng đồng.</span></li><li style=\"text-align: center;\"><img style=\"width: 50%;\" src=\"http://localhost/caodangkontum.edu.vn/uploads/images/358c270047cc7088cc8d0d71aa50e4dc-banner.jpg\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; text-align: start; white-space-collapse: preserve;\"><br></span></li><li style=\"text-align: center;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; text-align: start; white-space-collapse: preserve;\">Hình ảnh bác sĩ của phòng khám</span></li><li style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; text-align: start; white-space-collapse: preserve;\">Là cơ sở y tế trực thuộc trường Cao đẳng Kon Tum, chúng tôi cung cấp dịch vụ khám chữa bệnh ban đầu và tư vấn dược khoa chuyên nghiệp. Với phương châm \'Lấy người bệnh làm trung tâm\', phòng khám cam kết mang lại sự an tâm tuyệt đối qua từng khâu chẩn đoán và điều trị. Đến với chúng tôi để trải nghiệm dịch vụ y tế thân thiện và chuẩn mực ngay tại địa phương.</span></li></ul><p style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; text-align: start; white-space-collapse: preserve;\"><br></span></p><p style=\"text-align: right;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; text-align: start; white-space-collapse: preserve;\">Tác giả: Vũ Xuân Cương</span></p><p style=\"text-align: justify;\"><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; text-align: start; white-space-collapse: preserve;\"><br></span></p>', 'c7103c7b276a7ae3bf5b074688736776-image-removebg-preview10.png', NULL, 24, 1, '2026-01-05 21:35:52'),
(2, 'Tin 1', 'Tin 1 nè', '<p>TIn trong ngày</p><p><img src=\"http://localhost/caodangkontum.edu.vn/uploads/images/d2173854c8803807b1835ae91a68629b-Hnhnh1.png\" style=\"width: 314px;\"><br></p>', 'cec9c2e819fc4e30eef8af1b437ecf9c-image-removebg-preview.png', NULL, 24, 99, '2026-01-05 21:43:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_orders`
--

CREATE TABLE `hicrm_orders` (
  `id` int(11) NOT NULL,
  `order_customer_id` int(11) NOT NULL,
  `order_employee_id` int(11) NOT NULL,
  `order_payment_policy_id` int(11) NOT NULL,
  `order_warehouse_id` int(11) NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `order_name_contact` varchar(255) NOT NULL,
  `order_payment_active` int(11) NOT NULL COMMENT '- Đã thanh toán.  - Chưa thanh toán',
  `order_description` text NOT NULL,
  `order_date` date NOT NULL,
  `order_active` int(2) NOT NULL COMMENT '1. Chưa thực hiện, 2. Đang thực hiện, 3. Hoàn thành, 4. Hủy bỏ ',
  `order_delivery_date` date NOT NULL COMMENT 'Ngày giao hàng',
  `order_create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `order_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_orders`
--

INSERT INTO `hicrm_orders` (`id`, `order_customer_id`, `order_employee_id`, `order_payment_policy_id`, `order_warehouse_id`, `order_code`, `order_name_contact`, `order_payment_active`, `order_description`, `order_date`, `order_active`, `order_delivery_date`, `order_create_date`, `order_status`) VALUES
(1, 1, 2, 6, 2, 'SĐH0000001', 'Vũ Xuân Cương', 2, 'Diễn giải', '2021-09-16', 3, '2021-09-17', '2021-09-16 22:43:08', 1),
(2, 4, 5, 2, 1, 'SĐH0000002', 'Vũ Xuân Cương', 2, 'Cung cấp công cụ dụng cụ cho phòng Marketing', '2021-09-17', 2, '2021-09-18', '2021-09-17 14:21:47', 1),
(3, 4, 5, 2, 1, 'SĐH0000002', 'Vũ Xuân Cương', 2, 'Cung cấp công cụ dụng cụ cho phòng Marketing', '2021-09-17', 2, '2021-09-18', '2021-09-17 14:21:58', 1),
(4, 4, 5, 2, 1, 'SĐH0000002', 'Vũ Xuân Cương', 2, 'Cung cấp công cụ dụng cụ cho phòng Marketing', '2021-09-17', 2, '2021-09-18', '2021-09-17 14:28:23', 1),
(5, 4, 5, 2, 1, 'SĐH0000002', 'Vũ Xuân Cương', 2, 'Cung cấp công cụ dụng cụ cho phòng Marketing', '2021-09-17', 2, '2021-09-18', '2021-09-17 14:28:45', 1),
(6, 1, 3, 2, 2, 'SĐH0000003', 'Vũ Xuân Cương', 2, 'Mua công cụ dụng cụ cho phòng Kế toán', '2021-09-17', 2, '2021-09-19', '2021-09-17 14:38:52', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_order_details`
--

CREATE TABLE `hicrm_order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `order_product_quantity` int(11) NOT NULL,
  `order_product_price` decimal(10,0) NOT NULL,
  `order_product_vat_tax` int(11) NOT NULL,
  `order_product_discount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_order_details`
--

INSERT INTO `hicrm_order_details` (`id`, `order_id`, `order_product_id`, `order_product_quantity`, `order_product_price`, `order_product_vat_tax`, `order_product_discount`) VALUES
(1, 6, 17, 2, 3500000, 10, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_payment_policies`
--

CREATE TABLE `hicrm_payment_policies` (
  `id` int(11) NOT NULL,
  `policy_uid` int(11) NOT NULL,
  `policy_code` varchar(20) NOT NULL,
  `policy_title` varchar(255) NOT NULL,
  `policy_debt_day` int(5) DEFAULT NULL,
  `policy_comission` decimal(20,2) NOT NULL DEFAULT 0.00,
  `policy_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_payment_policies`
--

INSERT INTO `hicrm_payment_policies` (`id`, `policy_uid`, `policy_code`, `policy_title`, `policy_debt_day`, `policy_comission`, `policy_status`) VALUES
(1, 1, 'CS001', 'Chính sách 1', 30, 1.00, 1),
(2, 0, 'CS002', 'Chính sách 2', 60, 0.00, 1),
(3, 0, 'TEST 1', 'TEST 3', 4, 2.00, 99),
(4, 0, 'TEST 1', 'TEST 3', 32, 2.00, 99),
(5, 0, 'CS003', 'Chính sách 1', 30, 1.00, 1),
(6, 1, 'DKMH01', 'Điều khoản mua hàng', 12, 4.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_permissions`
--

CREATE TABLE `hicrm_permissions` (
  `id` int(11) NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `permission_level` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_permissions`
--

INSERT INTO `hicrm_permissions` (`id`, `permission_name`, `permission_level`) VALUES
(1, 'Đơn hàng', 1),
(2, 'Vận đơn', 1),
(3, 'Khách hàng', 1),
(4, 'Kho hàng', 1),
(5, 'Giao dịch', 1),
(6, 'Nhân viên', 1),
(7, 'Đại lý', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_permission_datas`
--

CREATE TABLE `hicrm_permission_datas` (
  `id` int(11) NOT NULL,
  `depart` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_permission_datas`
--

INSERT INTO `hicrm_permission_datas` (`id`, `depart`, `permission_id`) VALUES
(23, 4, 1),
(57, 1, 1),
(58, 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_positions`
--

CREATE TABLE `hicrm_positions` (
  `id` int(3) NOT NULL,
  `position_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_positions`
--

INSERT INTO `hicrm_positions` (`id`, `position_title`) VALUES
(1, 'Bác sĩ'),
(2, 'Phó Giám đốc'),
(3, 'Trưởng phòng'),
(4, 'Phó trưởng phòng'),
(5, 'Nhân viên'),
(6, 'Chuyên viên'),
(7, 'Trợ lý Chủ tịch');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_products`
--

CREATE TABLE `hicrm_products` (
  `id` bigint(30) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_barcode` varchar(20) DEFAULT NULL,
  `product_vat_name` varchar(255) DEFAULT NULL,
  `product_unit` int(3) NOT NULL,
  `product_category` int(11) NOT NULL,
  `product_price` decimal(20,2) NOT NULL,
  `product_discount` decimal(20,2) DEFAULT NULL,
  `product_tax_id` int(11) NOT NULL DEFAULT 0,
  `product_description` text DEFAULT NULL,
  `product_image` text DEFAULT NULL,
  `product_created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `product_status` int(3) NOT NULL DEFAULT 1 COMMENT '1 - Đang bán, 2 - Không bán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_products`
--

INSERT INTO `hicrm_products` (`id`, `product_name`, `product_code`, `product_barcode`, `product_vat_name`, `product_unit`, `product_category`, `product_price`, `product_discount`, `product_tax_id`, `product_description`, `product_image`, `product_created_time`, `product_status`) VALUES
(1, 'Sản phẩm 1', 'SP0001', NULL, '', 2, 1, 650000.00, 2.00, 1, 'hức ăn cho chó mọi độ tuổi, không độn ngũ cốc, không phẩm màu và chất bảo quản nhân tạo.\r\n\r\nTHÀNH PHẦN: Bột thịt gà, Khoai tây, Mỡ gà (được bảo quản với Tocopherols hỗn hợp), Bột thịt cá trắng, Trứng, Cà chua, Đậu Hà Lan, Sợi việt quất, Sợi nam việt quất, Táo, Việt quất, Cà rốt, Rau bina, Nam Việt quất, DL-Methionine, L-Lysine, Taurine, Beta-Carotene, L-Carnitine, Yucca, Cây hương thảo, Vitamin, Khoáng chất , Probiotics.\r\n\r\nBỔ SUNG (TRÊN MỖI KG): Vitamin A 12.000 IU/kg, Vitamin D3 750 IU/kg, Vitamin C 100 mg/kg, Vitamin E (α-tocopherol) 250 IU/kg, Đồng (đồng sunfat) 16 mg/kg, Omega-6 >3,3%, Omega-3 >0,4%, Methionine 1,2%, Lysine 2,1%, Taurine 0,05%, L-Carnitine 50 mg/kg, Beta-Carotene 10 mg/kg, Axit Docosahexaenoic (DHA) >0,05%.\r\n\r\nTHÀNH PHẦN PHÂN TÍCH: Đạm 38%, Chất béo 20%, Tro 10,2%, Chất xơ 2,5%, Độ ẩm 10%, Natri 0,3%, Canxi 1,5%, Phốt pho 1,0%. Kilocalories/kg: 3.800', 'abc.png', '2021-10-30 16:19:28', 1),
(2, 'Kháng sinh', '01', '', '', 1, 2, 1000000.00, 0.00, 0, '<p>ấc</p>', 'ecd99574ee4d8c7ffa4412b6aeb4b771-2908_02_b73c39a223.jpg', '2026-01-14 23:02:57', 1),
(3, 'Tiêu chảy â aa', '002', '', '', 1, 3, 555555.00, 3.00, 0, 'Thuốc điều trị tiêu chảy cấp\r\n\r\n', 'd8535c8a19519afd8bb5b91220665a62-2908_02_b73c39a223.jpg', '2026-01-14 23:07:04', 1),
(4, 'Amocinin', '003', '', '', 1, 3, 90000.00, 5.00, 0, '<p>Thuốc amocinin trị đau họng</p>', '14b0c8d644d55be8208a4ed9793a544f-thuoc-tri-viem-hong-hat-1.jpeg', '2026-01-18 22:20:03', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_products_bk`
--

CREATE TABLE `hicrm_products_bk` (
  `id` int(11) NOT NULL,
  `product_cat_id` int(11) NOT NULL,
  `product_unit_id` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `product_into_money` decimal(10,0) NOT NULL,
  `product_total_money` decimal(10,0) NOT NULL,
  `product_vat_tax` int(11) NOT NULL,
  `product_status` int(2) NOT NULL,
  `product_create_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_product_categories`
--

CREATE TABLE `hicrm_product_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `category_parent` int(11) NOT NULL DEFAULT 0,
  `category_image` text DEFAULT NULL,
  `category_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_product_categories`
--

INSERT INTO `hicrm_product_categories` (`id`, `category_name`, `category_description`, `category_parent`, `category_image`, `category_status`) VALUES
(1, 'Thuốc thường', '', 0, NULL, 1),
(2, 'Thuốc đau đầu', '', 0, NULL, 0),
(3, 'Thuốc hỗ trợ tiêu hóa', '', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_product_warehouses`
--

CREATE TABLE `hicrm_product_warehouses` (
  `id` bigint(30) NOT NULL,
  `pid` bigint(30) NOT NULL,
  `wareid` int(11) NOT NULL,
  `ware_instock` int(11) NOT NULL DEFAULT 0,
  `ware_alert` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_product_warehouses`
--

INSERT INTO `hicrm_product_warehouses` (`id`, `pid`, `wareid`, `ware_instock`, `ware_alert`) VALUES
(1, 1, 1, 20, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_promotions`
--

CREATE TABLE `hicrm_promotions` (
  `id` bigint(30) NOT NULL,
  `promo_type` int(2) NOT NULL DEFAULT 1,
  `promo_name` varchar(255) NOT NULL,
  `promo_code` varchar(30) NOT NULL,
  `promo_discount_type` int(2) NOT NULL DEFAULT 1 COMMENT '1 - theo tiền | 2 - Theo phần trăm',
  `promo_discount_value` decimal(20,2) NOT NULL,
  `promo_qty` int(11) NOT NULL DEFAULT 1 COMMENT 'Số lượng',
  `promo_used` int(11) NOT NULL DEFAULT 0,
  `promo_reuse` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 Không dùng lại | 1 được dùng lại',
  `promo_created_by` int(11) NOT NULL,
  `promo_from` datetime NOT NULL,
  `promo_to` datetime DEFAULT NULL,
  `promo_expried` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 có hết hạn, 2 không bao giờ hết hạn',
  `promo_created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `promo_status` int(3) NOT NULL DEFAULT 1,
  `promo_for` int(2) NOT NULL DEFAULT 1 COMMENT '1 tất cả đơn hàng, 2 đơn hàng theo giá trị, 3 khách hàng, 4 sản phẩm',
  `promo_all_order` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - tất cả | 2 - một số',
  `promo_order_min` decimal(20,2) NOT NULL DEFAULT 0.00,
  `promo_order_max` decimal(20,2) NOT NULL DEFAULT 0.00,
  `promo_customers` text DEFAULT NULL,
  `promo_products` text DEFAULT NULL,
  `promo_max_apply` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_promotions`
--

INSERT INTO `hicrm_promotions` (`id`, `promo_type`, `promo_name`, `promo_code`, `promo_discount_type`, `promo_discount_value`, `promo_qty`, `promo_used`, `promo_reuse`, `promo_created_by`, `promo_from`, `promo_to`, `promo_expried`, `promo_created_time`, `promo_status`, `promo_for`, `promo_all_order`, `promo_order_min`, `promo_order_max`, `promo_customers`, `promo_products`, `promo_max_apply`) VALUES
(1, 1, 'Tri ân năm mới', '', 2, 10.00, 0, 0, 1, 1, '2021-10-30 14:47:48', '2022-01-01 14:47:48', 1, '2021-10-30 14:49:11', 1, 1, 1, 0.00, 0.00, NULL, NULL, 1),
(2, 2, 'Khách hàng mới', 'NEWCUSTOMER', 1, 100000.00, 20, 3, 0, 1, '2021-10-30 15:03:53', NULL, 2, '2021-10-30 15:05:44', 1, 2, 0, 1000000.00, 0.00, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_quotes`
--

CREATE TABLE `hicrm_quotes` (
  `id` bigint(30) NOT NULL,
  `quote_code` varchar(20) NOT NULL,
  `quote_customer` bigint(30) NOT NULL,
  `quote_created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `quote_created_by` int(11) NOT NULL,
  `quote_status` int(2) NOT NULL COMMENT '1 - mới tạo, 2 - khách đồng ý, 3 - khách từ chối, 4 - mới điều chỉnh, 5 đã chuyển qua đơn hàng',
  `quote_promotion` int(11) NOT NULL DEFAULT 0,
  `quote_discount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `quote_reviewed_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_quotes`
--

INSERT INTO `hicrm_quotes` (`id`, `quote_code`, `quote_customer`, `quote_created_time`, `quote_created_by`, `quote_status`, `quote_promotion`, `quote_discount`, `quote_reviewed_time`) VALUES
(1, 'VMQ2310001', 1, '2021-10-30 15:23:01', 1, 3, 1, 0.00, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_quote_details`
--

CREATE TABLE `hicrm_quote_details` (
  `id` bigint(30) NOT NULL,
  `qid` bigint(30) NOT NULL,
  `quote_product_id` bigint(30) NOT NULL,
  `quote_product_qty` int(11) NOT NULL DEFAULT 1,
  `quote_product_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `quote_product_discount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `quote_product_tax_percent` decimal(20,2) NOT NULL DEFAULT 0.00,
  `quote_product_tax` decimal(20,2) NOT NULL DEFAULT 0.00,
  `quote_product_total` decimal(20,2) NOT NULL DEFAULT 0.00,
  `quote_product_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_quote_details`
--

INSERT INTO `hicrm_quote_details` (`id`, `qid`, `quote_product_id`, `quote_product_qty`, `quote_product_price`, `quote_product_discount`, `quote_product_tax_percent`, `quote_product_tax`, `quote_product_total`, `quote_product_note`) VALUES
(1, 1, 1, 2, 250000.00, 50000.00, 10.00, 50000.00, 50000.00, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_request_salary`
--

CREATE TABLE `hicrm_request_salary` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `request_uid` int(11) NOT NULL,
  `request_new_salary` decimal(20,2) NOT NULL,
  `request_new_commission` decimal(20,2) NOT NULL,
  `request_note` text DEFAULT NULL,
  `request_admin_note` text DEFAULT NULL,
  `request_time` datetime NOT NULL DEFAULT current_timestamp(),
  `request_review_time` datetime DEFAULT NULL,
  `request_status` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_request_salary`
--

INSERT INTO `hicrm_request_salary` (`id`, `uid`, `request_uid`, `request_new_salary`, `request_new_commission`, `request_note`, `request_admin_note`, `request_time`, `request_review_time`, `request_status`) VALUES
(1, 20, 1, 7000000.00, 3.00, 'Anh sang có nhiều Khách hàng', '', '2021-05-10 15:33:04', '2021-05-10 16:12:12', 2),
(2, 20, 1, 6000000.00, 2.00, 'Không chịu 7tr thì nâng lên 6tr', 'Không cho nâng rồi', '2021-05-10 16:15:04', '2021-05-10 16:15:14', 2),
(3, 21, 1, 5000000.00, 2.00, 'Tăng lương giảm hoa hồng', NULL, '2021-05-10 16:21:13', '2021-05-10 16:21:23', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_service`
--

CREATE TABLE `hicrm_service` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_description` longtext DEFAULT NULL,
  `service_image` varchar(255) DEFAULT NULL,
  `service_category` int(2) NOT NULL,
  `service_created_date` datetime NOT NULL,
  `service_status` int(2) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_service`
--

INSERT INTO `hicrm_service` (`id`, `service_name`, `service_description`, `service_image`, `service_category`, `service_created_date`, `service_status`) VALUES
(1, 'Gói khám sức khỏe', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', NULL, 7, '2026-01-27 13:33:22', 1),
(2, 'aaaa', 'undefined', 'd25e5a510b4127a0bcb0af41dc049935-Screenshot_10.png', 6, '2026-01-27 20:09:40', 99),
(3, 'CHUYÊN KHOA SIÊU ÂM', 'undefined', '0aba10ab9a03e4df4b9b92369adc5416-tixung.png', 6, '2026-01-27 20:10:45', 99),
(4, 'CHUYÊN KHOA SIÊU ÂM', '<p>aasdasdasd</p>', 'ad4a40eaae9fd0a23b496bfcc9659fc2-PL7.Bocosdngvttyttiuhao.pdf', 6, '2026-01-27 20:18:21', 1),
(5, 'aasdasdasdas', '<p>ấdasdasdasd</p>', '10c35a605065990f54139d0a5c186e39-SDTCR1.png', 7, '2026-01-27 20:20:37', 1),
(6, 'ádasdasd', '<p>âdasdasd</p>', '0b90857b3e07bce8eb2f76531306cf69-SDTCR1.png', 7, '2026-01-27 20:21:49', 1),
(7, 'GÓI KHÁM PHỤ SẢN 1', '<div style=\"color: rgb(0, 0, 0); font-family: Consolas, \" courier=\"\" new\",=\"\" monospace;=\"\" font-size:=\"\" 18px;=\"\" line-height:=\"\" 24px;=\"\" white-space:=\"\" pre;\"=\"\" bis_skin_checked=\"1\"><span style=\"font-family: \" open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" text-align:=\"\" justify;=\"\" white-space:=\"\" normal;\"=\"\">t is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by&nbsp;</span></div>', 'e5881e2f63e45fdb3b73b56eb648daee-Screenshot_5.png', 7, '2026-01-27 20:23:21', 1),
(8, 'aaaa', '<p>sdasdasdasd</p>', '2ae43ec5307c5622dd1ca9e54b95ec68-SDTCR1.png', 6, '2026-01-28 20:03:04', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_spend_collectes`
--

CREATE TABLE `hicrm_spend_collectes` (
  `id` int(11) NOT NULL,
  `spend_collecte_code` int(11) NOT NULL,
  `spend_collecte_name` varchar(255) NOT NULL,
  `spend_collecte_type` int(1) NOT NULL COMMENT '1 - Mục thu. 2- Mục chi',
  `spend_collecte_active` int(11) NOT NULL DEFAULT 0 COMMENT '1-Phát sinh định kỳ. 0-Không phát sinh định kỳ',
  `spend_collecte_parent` int(11) NOT NULL,
  `spend_collecte_description` text DEFAULT NULL,
  `spend_collecte_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_spend_collectes`
--

INSERT INTO `hicrm_spend_collectes` (`id`, `spend_collecte_code`, `spend_collecte_name`, `spend_collecte_type`, `spend_collecte_active`, `spend_collecte_parent`, `spend_collecte_description`, `spend_collecte_status`) VALUES
(1, 100, 'Thu từ bán hàng thu tiền ngay', 1, 1, 3, '', 1),
(2, 101, 'Thu nợ từ bán hàng', 1, 0, 1, '', 1),
(3, 102, 'Thu nợ từ hợp đồng bán', 1, 1, 1, '', 1),
(4, 103, 'Thu tiền góp vốn', 1, 1, 1, '', 1),
(5, 104, 'Thu tiền vay', 1, 1, 1, '', 1),
(6, 105, 'Thu từ lãi tiền gửi ngân hàng, các khoản cho vay', 1, 1, 1, '', 1),
(7, 106, 'Thu lãi từ các khoản đầu tư', 1, 1, 1, '', 1),
(8, 107, 'Thu nhận ký quỹ, ký cược', 1, 1, 1, '', 1),
(9, 108, 'Thu hồi các khoản đi ký cược, ký quỹ', 1, 1, 1, '', 1),
(10, 109, 'Thu từ thanh lý, nhượng bán', 1, 1, 1, '', 1),
(11, 110, 'Thu tiền từ lãi cổ tức', 1, 1, 1, '', 1),
(12, 111, 'Thu khác', 1, 1, 1, '', 1),
(13, 200, 'Chi tiền mua TSCĐ', 2, 1, 1, '', 1),
(14, 201, 'Chi tiền mua CCDC', 2, 1, 1, '', 1),
(15, 202, 'Chi mua hàng hóa thanh toán ngay', 2, 1, 1, '', 1),
(16, 203, 'Chi trả nợ từ mua hàng', 2, 1, 1, '', 1),
(17, 204, 'Chi trả nợ từ hợp đồng mua', 2, 1, 1, '', 1),
(18, 205, 'Chi tiền lương', 2, 1, 1, '', 1),
(19, 206, 'Chi tiền thưởng', 2, 1, 1, '', 1),
(20, 207, 'Chi tiền phụ cấp', 2, 1, 1, '', 1),
(21, 208, 'Chi thanh toán bảo hiểm', 2, 1, 1, '', 1),
(22, 209, 'Chi tiền tạm ứng', 2, 1, 1, '', 1),
(23, 210, 'Chi tiền công tác phí', 2, 1, 1, '', 1),
(24, 211, 'Chi tiền điện', 2, 1, 1, '', 1),
(25, 212, 'Chi tiền nước', 2, 1, 1, '', 1),
(26, 213, 'Chi tiền điện thoại', 2, 1, 1, '', 1),
(27, 214, 'Chi tiền thuê văn phòng, cửa hàng, thuê tài sản', 2, 1, 1, '', 1),
(28, 215, 'Chi tiền bốc dỡ, vận chuyển', 2, 1, 1, '', 1),
(29, 216, 'Chi tiền hoa hồng đại lý', 2, 1, 1, '', 1),
(30, 217, 'Chi tiền bảo trì, bảo dưỡng tài sản', 2, 1, 1, '', 1),
(31, 218, 'Chi tiền mua văn phòng phẩm', 2, 1, 1, '', 1),
(32, 219, 'Chi tiền nộp thuế', 2, 1, 1, '', 1),
(33, 220, 'Chi tiền trả tiền lãi vay, chi phí tài chính', 2, 1, 1, '', 1),
(34, 221, 'Chi tiền đào tạo cán bộ', 2, 1, 1, '', 1),
(35, 222, 'Chi tiền hội nghị', 2, 1, 1, '', 1),
(36, 223, 'Chi tiền tiếp khách', 2, 1, 1, '', 1),
(37, 224, 'Chi tiền tuyển dụng', 2, 1, 1, '', 1),
(38, 225, 'Chi tiền quảng cáo, giới thiệu sản phẩm, hàng hóa', 2, 1, 1, '', 1),
(39, 226, 'Chi tiền cầm cố, ký cược, ký quỹ', 2, 1, 1, '', 1),
(40, 227, 'Chi tiền đầu tư xây dựng cơ bản', 2, 1, 1, '', 1),
(41, 228, 'Chi khác', 2, 1, 1, '', 1),
(42, 44334, 'Test', 1, 1, 2, 'Test', 1),
(43, 1001111, 'Thu từ bán hàng thu tiền ngay', 1, 1, 5, 'AAA', 99),
(44, 100332, 'Thu từ bán hàng thu tiền ngay edit 1', 1, 1, 2, '', 99),
(45, 123, 'TEST', 1, 1, 1, 'TEST', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_status`
--

CREATE TABLE `hicrm_status` (
  `id` int(3) NOT NULL,
  `status_label` varchar(255) NOT NULL,
  `status_class` varchar(255) DEFAULT NULL,
  `status_icon` varchar(255) DEFAULT NULL,
  `status_type` int(2) NOT NULL COMMENT '1 Chung, 2 báo giá, 3 đơn hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_status`
--

INSERT INTO `hicrm_status` (`id`, `status_label`, `status_class`, `status_icon`, `status_type`) VALUES
(1, 'Hoạt động', 'success', NULL, 0),
(2, 'Ngừng hoạt động', 'danger', NULL, 0),
(3, 'Mới tạo', 'info', NULL, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_supplies`
--

CREATE TABLE `hicrm_supplies` (
  `id` int(11) NOT NULL,
  `supplie_code` varchar(10) NOT NULL,
  `supplie_name` varchar(255) NOT NULL,
  `supplie_status` int(2) NOT NULL,
  `supplie_parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_supplies`
--

INSERT INTO `hicrm_supplies` (`id`, `supplie_code`, `supplie_name`, `supplie_status`, `supplie_parent`) VALUES
(1, 'CCDC', 'Công cụ dụng c', 99, 0),
(2, 'MT 3', 'Máy tính', 99, 2),
(3, 'SSSSS', 'Sách 3', 1, 0),
(4, 'SS', 'Sách 2', 1, 1),
(5, 'SS', 'Sách 2', 99, 1),
(6, 'SS', 'Sách1', 99, 1),
(7, 'S', 'Sách', 99, 0),
(8, 'CCDC 2', 'Công cụ dụng cụ', 1, 0),
(9, 'CCDC', 'Công cụ dụng c', 1, 0),
(10, 'CCDC', 'Công cụ dụng c', 99, 0),
(11, 'CCDC 3', 'Công cụ dụng c', 1, 0),
(12, 'MT 34', 'Máy tính', 1, 2),
(13, 'SS55', 'Sách', 1, 3),
(14, 'A', 'a', 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_taxs`
--

CREATE TABLE `hicrm_taxs` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(255) NOT NULL,
  `tax_value` decimal(20,2) NOT NULL DEFAULT 0.00,
  `tax_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_taxs`
--

INSERT INTO `hicrm_taxs` (`id`, `tax_name`, `tax_value`, `tax_description`) VALUES
(1, 'VAT', 10.00, 'Thuế VAT 10%');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_templates`
--

CREATE TABLE `hicrm_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_type` int(2) NOT NULL,
  `template_html` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_templates`
--

INSERT INTO `hicrm_templates` (`id`, `template_name`, `template_type`, `template_html`) VALUES
(1, 'Phiếu thu - Mẫu 01-TT Thông tu 200', 1, '<table style=\"width: 100%; font-size: 13px;\" border=\"0\">\n<tbody>\n<tr>\n<td style=\"width: 40.9339%; text-align: center; font-weight: bold;\">C&Ocirc;NG TY TNHH AN LỘC FSC GIA LAI<br /><span style=\"font-size: 11px;\">ĐC: 499A Phan Đ&igrave;nh Ph&ugrave;ng, P. Y&ecirc;n Đỗ, <br />TP. Pleiku, Gia Lai</span></td>\n<td style=\"width: 44.8793%; text-align: center;\" colspan=\"2\"><strong>Mẫu số 01 - TT</strong><br style=\"text-align: center;\" /><em>(Ban h&agrave;nh theo Th&ocirc;ng tư số 200/2014/TT-BTC<br />Ng&agrave;y 22/12/2014 của Bộ T&agrave;i ch&iacute;nh)</em></td>\n</tr>\n<tr>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n<td style=\"width: 30%;\">Số: {{INCOME_NO}}<br />Nợ: {{DEBIT_ACC}}<br />C&oacute;: {{CREDIT_ACC}}</td>\n</tr>\n<tr>\n<td style=\"text-align: center; font-size: 20px; font-weight: bold;\" colspan=\"3\">PHIẾU THU</td>\n</tr>\n</tbody>\n</table>\n<table style=\"width: 100%; font-size: 13px;\" border=\"0\">\n<tbody>\n<tr>\n<td style=\"width: 30%; font-weight: bold;\">Họ v&agrave; t&ecirc;n người nộp tiền:</td>\n<td><strong>{{INCOME_NAME}}</strong></td>\n</tr>\n<tr>\n<td style=\"width: 30%; font-weight: bold;\">Địa chỉ:</td>\n<td>{{INCOME_ADDRESS}}</td>\n</tr>\n<tr>\n<td style=\"width: 30%; font-weight: bold;\">Số tiền:</td>\n<td>{{INCOME_AMOUNT}} VNĐ</td>\n</tr>\n<tr>\n<td style=\"width: 30%; font-weight: bold;\">Bằng chữ:</td>\n<td style=\"font-style: italic;\">{{INCOME_AMOUNT_TEXT}}</td>\n</tr>\n<tr>\n<td style=\"width: 30%; font-weight: bold;\">L&yacute; do nộp:</td>\n<td>{{INCOME_NOTE}}</td>\n</tr>\n<tr>\n<td style=\"width: 30%; font-weight: bold;\">K&egrave;m theo:</td>\n<td>{{INCOME_DOCUMENT}} <em>chứng từ gốc</em>.</td>\n</tr>\n</tbody>\n</table>\n<table style=\"width: 100%; font-size: 13px;\" border=\"0\">\n<tbody>\n<tr>\n<td style=\"width: 20%;\">&nbsp;</td>\n<td style=\"width: 20%;\">&nbsp;</td>\n<td style=\"width: 20%;\">&nbsp;</td>\n<td style=\"text-align: center;\" colspan=\"2\">Ng&agrave;y {{BILL_DAY}} th&aacute;ng {{BILL_MONTH}} năm {{BILL_YEAR}}</td>\n</tr>\n<tr>\n<td style=\"width: 22%; text-align: center;\"><strong>Gi&aacute;m đốc</strong><br /><em>(K&yacute;, họ t&ecirc;n, đ&oacute;ng dấu)</em></td>\n<td style=\"width: 20%; text-align: center;\"><strong>Kế to&aacute;n trưởng</strong><br /><em>(K&yacute;, họ t&ecirc;n)</em></td>\n<td style=\"width: 18%; text-align: center;\"><strong>Thủ quỹ</strong><br /><em>(K&yacute;, họ t&ecirc;n)</em></td>\n<td style=\"width: 20%; text-align: center;\"><strong>Người lập phiếu</strong><br /><em>(K&yacute;, họ t&ecirc;n)</em></td>\n<td style=\"width: 20%; text-align: center;\"><strong>Người n&ocirc;̣p tiền</strong><br /><em>(K&yacute;, họ t&ecirc;n)</em></td>\n</tr>\n</tbody>\n</table>'),
(2, 'Phiếu chi - Mẫu 01-TT Thông tư 200', 2, '<table style=\"width: 827px; height: 336px;\" border=\"0\" width=\"836\" cellspacing=\"0\" cellpadding=\"0\"><colgroup><col style=\"width: 242px;\" width=\"160\" /><col style=\"width: 0px;\" span=\"2\" width=\"148\" /><col style=\"width: 140px;\" width=\"188\" /><col style=\"width: 218px;\" width=\"134\" /></colgroup>\n<tbody>\n<tr style=\"height: 25px;\">\n<td class=\"xl78\" style=\"height: 25px; width: 342pt; text-align: center;\" colspan=\"3\" width=\"456\" height=\"34\"><strong>C&Ocirc;NG TY TNHH AN LỘC FSC GIA LAI</strong></td>\n<td class=\"xl84\" style=\"width: 286pt; height: 50px; text-align: center;\" colspan=\"2\" rowspan=\"2\" width=\"380\"><strong>Mẫu số 01 &ndash; TT</strong><br /><em>(Ban h&agrave;nh theo Th&ocirc;ng tư số 200/2014/TT-BTC<br />Ng&agrave;y 22/12/2014 của Bộ T&agrave;i ch&iacute;nh)</em></td>\n</tr>\n<tr style=\"height: 25px;\">\n<td class=\"xl78\" style=\"height: 25px; text-align: center;\" colspan=\"3\" height=\"34\"><strong>499A Phan Đ&igrave;nh Ph&ugrave;ng, P. Y&ecirc;n Đỗ, TP. Pleiku, Gia Lai</strong></td>\n</tr>\n<tr style=\"height: 32px;\">\n<td class=\"xl65\" style=\"height: 10px;\" height=\"31\">&nbsp;</td>\n<td class=\"xl64\" style=\"width: 111pt; height: 10px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl64\" style=\"width: 111pt; height: 10px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl64\" style=\"width: 141pt; height: 10px;\" width=\"188\">&nbsp;</td>\n<td class=\"xl82\" style=\"width: 145pt; height: 10px;\" width=\"192\">Số: {{INCOME_NO}}</td>\n</tr>\n<tr style=\"height: 10px;\">\n<td style=\"height: 10px;\">&nbsp;</td>\n<td style=\"width: 111pt; height: 10px;\">&nbsp;</td>\n<td style=\"width: 111pt; height: 10px;\">&nbsp;</td>\n<td style=\"width: 141pt; height: 10px;\">&nbsp;</td>\n<td style=\"width: 145pt; height: 10px;\">Nợ: {{DEBIT_ACC}}</td>\n</tr>\n<tr style=\"height: 43px;\">\n<td class=\"xl83\" style=\"height: 10px; width: 483pt;\" colspan=\"4\" width=\"644\" height=\"58\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>\n<td class=\"xl68\" style=\"height: 10px;\" width=\"134\">C&oacute;: {{CREDIT_ACC}}</td>\n</tr>\n<tr style=\"height: 31px;\">\n<td class=\"xl83\" style=\"height: 31px; text-align: center;\" colspan=\"5\" width=\"644\" height=\"24\"><span style=\"font-size: 14pt;\"><strong>PHIẾU CHI</strong></span></td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl68\" style=\"height: 15px; width: 100pt;\" width=\"100\" height=\"20\">Họ v&agrave; t&ecirc;n người nh&acirc;̣n tiền:</td>\n<td class=\"xl85\" style=\"width: 508pt; height: 15px;\" colspan=\"4\" width=\"676\">{{INCOME_NAME}}</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl68\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">Bộ phận:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n<td class=\"xl68\" style=\"width: 508pt; height: 15px;\" colspan=\"4\" width=\"676\">{{INCOME_DEPARTMENT}}</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl68\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">Địa chỉ:</td>\n<td class=\"xl80\" style=\"width: 508pt; height: 15px;\" colspan=\"4\" width=\"676\">{{INCOME_ADDRESS}}</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl68\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">Số tiền:</td>\n<td class=\"xl86\" style=\"width: 508pt; height: 15px;\" colspan=\"4\" width=\"676\">{{INCOME_AMOUNT}} VND</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl68\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">Bằng chữ:</td>\n<td class=\"xl87\" style=\"width: 508pt; height: 15px;\" colspan=\"4\" width=\"676\">#NAME?</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl68\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">L&yacute; do nộp:</td>\n<td class=\"xl80\" style=\"width: 508pt; height: 15px;\" colspan=\"4\" width=\"676\">{{INCOME_NOTE}}</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl69\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">K&egrave;m theo:</td>\n<td class=\"xl76\" style=\"width: 111pt; height: 15px;\" width=\"148\">{{INCOME_DOCUMENT}}</td>\n<td class=\"xl77\" style=\"width: 111pt; height: 15px;\" align=\"left\" width=\"148\">&nbsp;chứng từ gốc</td>\n<td class=\"xl79\" style=\"width: 286pt; height: 15px;\" colspan=\"2\" width=\"380\">&nbsp;</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl69\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">&nbsp;</td>\n<td class=\"xl70\" style=\"width: 111pt; height: 15px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 111pt; height: 15px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 141pt; height: 15px;\" width=\"188\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 101pt; height: 15px;\" width=\"134\">&nbsp;</td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl68\" style=\"height: 15px; width: 120pt;\" width=\"160\" height=\"20\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 111pt; height: 15px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 111pt; height: 15px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 141pt; height: 15px;\" width=\"188\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 101pt; height: 15px;\" width=\"134\">&nbsp;</td>\n</tr>\n<tr style=\"height: 21px;\">\n<td class=\"xl71\" style=\"height: 21px; width: 120pt;\" width=\"160\" height=\"28\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 111pt; height: 21px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl71\" style=\"width: 111pt; height: 21px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl81\" style=\"width: 286pt; height: 21px; text-align: center;\" colspan=\"2\" width=\"380\"><em>Ng&agrave;y 24 th&aacute;ng 08 năm 2021</em></td>\n</tr>\n<tr style=\"height: 15px;\">\n<td class=\"xl72\" style=\"height: 15px; text-align: center;\" height=\"20\"><strong>Gi&aacute;m đốc</strong></td>\n<td class=\"xl72\" style=\"height: 15px; text-align: center;\"><strong>Kế to&aacute;n trưởng&nbsp;</strong></td>\n<td class=\"xl72\" style=\"height: 15px; text-align: center;\"><strong>Thủ quỹ&nbsp;</strong></td>\n<td class=\"xl72\" style=\"height: 15px; text-align: center;\"><strong>Người lập phiếu</strong></td>\n<td class=\"xl72\" style=\"height: 15px; text-align: center;\"><strong>Người nh&acirc;̣n tiền</strong></td>\n</tr>\n<tr style=\"height: 18px;\">\n<td class=\"xl75\" style=\"height: 18px; text-align: center;\" height=\"25\">(K&yacute;, họ t&ecirc;n, đ&oacute;ng dấu)</td>\n<td class=\"xl75\" style=\"height: 18px; text-align: center;\">(K&yacute;, họ t&ecirc;n)</td>\n<td class=\"xl75\" style=\"height: 18px; text-align: center;\">(K&yacute;, họ t&ecirc;n)</td>\n<td class=\"xl75\" style=\"height: 18px; text-align: center;\">(K&yacute;, họ t&ecirc;n)</td>\n<td class=\"xl75\" style=\"height: 18px; text-align: center;\">(K&yacute;, họ t&ecirc;n)</td>\n</tr>\n<tr style=\"height: 18px;\">\n<td class=\"xl63\" style=\"height: 18px; width: 120pt;\" width=\"160\" height=\"25\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 111pt; height: 18px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 111pt; height: 18px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 141pt; height: 18px;\" width=\"188\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 101pt; height: 18px;\" width=\"134\">&nbsp;</td>\n</tr>\n<tr style=\"height: 18px;\">\n<td class=\"xl63\" style=\"height: 18px; width: 120pt;\" width=\"160\" height=\"25\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 111pt; height: 18px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 111pt; height: 18px;\" width=\"148\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 141pt; height: 18px;\" width=\"188\">&nbsp;</td>\n<td class=\"xl63\" style=\"width: 101pt; height: 18px;\" width=\"134\">&nbsp;</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_template_types`
--

CREATE TABLE `hicrm_template_types` (
  `id` int(11) NOT NULL,
  `template_type_code` varchar(50) NOT NULL,
  `template_type_name` varchar(255) NOT NULL,
  `template_type_description` text DEFAULT NULL,
  `template_type_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_template_types`
--

INSERT INTO `hicrm_template_types` (`id`, `template_type_code`, `template_type_name`, `template_type_description`, `template_type_status`) VALUES
(1, 'BHCTT', 'Bán hàng chưa thanh toán', NULL, 1),
(2, 'CKDV', 'Các khoản đi vay', NULL, 1),
(3, 'CPL', 'Hạch toán chi phí lương', NULL, 1),
(4, 'CTNVK', 'Chứng từ nghiệp vụ khác', NULL, 1),
(5, 'GBC', 'Giấy Báo Có', NULL, 1),
(6, 'GGHB', 'Giảm giá hàng bán', NULL, 1),
(7, 'GTSCĐHH', 'Ghi giảm TSCĐ hữu hình', NULL, 1),
(8, 'GTSCĐVH', 'Ghi giảm TSCĐ vô hình', NULL, 1),
(9, 'HBBTL', 'Hàng bán bị trả lại', NULL, 1),
(10, 'HGBĐL', 'Xuất kho hàng gửi bán đại lý', NULL, 1),
(11, 'KHTSCĐ', 'Khấu hao TSCĐ', NULL, 1),
(12, 'MHCTT', 'Mua hàng hóa, dịch vụ chưa thanh toán', NULL, 1),
(13, 'NK', 'Nhập kho vật tư', NULL, 1),
(14, 'NKCCDC', 'Nhập kho CCDC', NULL, 1),
(15, 'NKHGB', 'Nhập kho hàng gửi bán đại lý', NULL, 1),
(16, 'NKHH', 'Nhập kho hàng hóa', NULL, 1),
(17, 'NKTP', 'Nhập kho thành phẩm', NULL, 1),
(18, 'NT', 'Nghiệm thu công trình, đơn hàng, hợp đồng', NULL, 1),
(19, 'NTTC', 'Nợ thuê tài chính', NULL, 1),
(20, 'PBCCDC', 'Phân bổ CCDC', NULL, 1),
(21, 'PC', 'Phiếu chi', NULL, 1),
(22, 'PT', 'Phiếu thu', NULL, 1),
(23, 'TL', 'Trả lương', NULL, 1),
(24, 'TTKHTG', 'Thu tiền khách hàng bằng tiền gửi ngân hàng', NULL, 1),
(25, 'TTKHTM', 'Thu tiền khách hàng bằng tiền mặt', NULL, 1),
(26, 'TTNCCTG', 'Trả tiền nhà cung cấp bằng tiền gửi ngân hàng', NULL, 1),
(27, 'TTNCCTM', 'Trả tiền nhà cung cấp bằng tiền mặt', NULL, 1),
(28, 'TTSCĐHH', 'Ghi tăng TSCĐ hữu hình', NULL, 1),
(29, 'TTSCĐVH', 'Ghi tăng TSCĐ vô hình', NULL, 1),
(30, 'TƯ', 'Tạm ứng', NULL, 1),
(31, 'UNC', 'Séc/Ủy nhiệm chi', NULL, 1),
(32, 'XKCCDC', 'Xuất kho CCDC', NULL, 1),
(33, 'XKHH', 'Xuất kho hàng hóa', NULL, 1),
(34, 'XKTP', 'Xuất kho thành phẩm', NULL, 1),
(35, 'XKVT', 'Xuất kho vật tư', '', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_toolinstruments`
--

CREATE TABLE `hicrm_toolinstruments` (
  `id` int(11) NOT NULL,
  `tool_warehouse_id` int(11) NOT NULL,
  `tool_category_id` int(11) NOT NULL,
  `tool_voucher_number` varchar(255) NOT NULL COMMENT 'Số chứng từ',
  `tool_date` date NOT NULL COMMENT 'Ngày ghi tăng',
  `tool_allotment_time` int(11) NOT NULL COMMENT 'Số kỳ phân bổ',
  `tool_allotment_money` decimal(10,0) NOT NULL,
  `tool_code` varchar(100) NOT NULL,
  `tool_description` text NOT NULL,
  `tool_name` varchar(255) NOT NULL,
  `tool_quantity` int(11) NOT NULL,
  `tool_unit` int(11) NOT NULL,
  `tool_price` int(11) NOT NULL,
  `tool_total_money` decimal(10,0) NOT NULL,
  `tool_is_stop` int(11) NOT NULL,
  `tool_reason_stop` text DEFAULT NULL,
  `tool_active` int(11) NOT NULL DEFAULT 1,
  `tool_create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `tool_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_transactions`
--

CREATE TABLE `hicrm_transactions` (
  `id` bigint(30) NOT NULL,
  `uid` bigint(30) NOT NULL,
  `trans_code` varchar(20) NOT NULL,
  `trans_type` int(2) NOT NULL,
  `trans_bank` int(3) NOT NULL DEFAULT 1,
  `trans_method` int(2) NOT NULL,
  `trans_amount` decimal(20,2) NOT NULL,
  `trans_time` datetime NOT NULL DEFAULT current_timestamp(),
  `trans_hash` varchar(64) NOT NULL,
  `trans_status` int(1) NOT NULL,
  `trans_note` text DEFAULT NULL,
  `trans_data` text DEFAULT NULL,
  `trans_approved_by` int(11) DEFAULT NULL,
  `trans_approved_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_type`
--

CREATE TABLE `hicrm_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_detail` int(11) NOT NULL COMMENT '1. Giới thiệu\r\n2. Dịch vụ\r\n3. Chuyên khoa\r\n4. Nhà thuốc',
  `type_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_type`
--

INSERT INTO `hicrm_type` (`id`, `type_name`, `type_detail`, `type_status`) VALUES
(1, 'Về chúng tôi', 1, 1),
(2, 'Cơ sở hạ tầng', 1, 1),
(3, 'Cơ cấu tổ chức', 1, 1),
(4, 'Tại sao chọn chúng tôi?', 1, 1),
(5, 'Tin tức', 5, 1),
(6, 'Sự kiện', 5, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_units`
--

CREATE TABLE `hicrm_units` (
  `id` int(11) NOT NULL,
  `unit_code` varchar(30) NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `unit_description` text DEFAULT NULL,
  `unit_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_units`
--

INSERT INTO `hicrm_units` (`id`, `unit_code`, `unit_name`, `unit_description`, `unit_status`) VALUES
(1, 'Chai', 'Chai', NULL, 1),
(2, '', 'Túi', 'Diễn giải', 99),
(3, 'AAAA', 'aa', 'aa', 99),
(4, 'AAAA A', 'aa', 'aa', 99),
(5, 'TEST', 'Test', '', 99),
(6, 'DVTKG', 'KG', 'Đơn vị tính là KG', 99),
(7, 'DVTK', 'g', 'Gam\n', 99),
(8, 'DVTC1', 'Chiếc', 'Chiếc', 99),
(9, 'DVTC2', 'Cái', 'Cái', 99);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_users`
--

CREATE TABLE `hicrm_users` (
  `id` bigint(30) NOT NULL,
  `user_username` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_fullname` varchar(255) DEFAULT NULL,
  `user_phone` varchar(30) DEFAULT NULL,
  `user_group` int(2) NOT NULL DEFAULT 4,
  `user_dept` int(2) NOT NULL DEFAULT 1,
  `user_address` text DEFAULT NULL,
  `user_avatar` text DEFAULT NULL,
  `user_status` int(2) NOT NULL,
  `user_commission` decimal(20,2) NOT NULL DEFAULT 0.00,
  `user_basic_salary` decimal(20,2) NOT NULL DEFAULT 0.00,
  `user_register_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_users`
--

INSERT INTO `hicrm_users` (`id`, `user_username`, `user_password`, `user_email`, `user_fullname`, `user_phone`, `user_group`, `user_dept`, `user_address`, `user_avatar`, `user_status`, `user_commission`, `user_basic_salary`, `user_register_time`) VALUES
(61, 'cuongmedia@gmail.coma', '82f9fa29d86dae71395f7fc9ef23fe5f', 'test@gmail.com', 'Tôi test 2', '', 0, 8, 'askdaosda ', '', 99, 0.00, 0.00, '2025-11-18 22:53:06'),
(24, 'cuongmedia@gmail.com', '635092b43f6daab6e117b2429f5e6236', 'vuxuancuong98gl@gmail.com', 'Vũ Xuân Cương', '0963719679', 1, 1, '27 - Lê đinh chinh', 'aaaa', 1, 0.00, 0.00, '2021-08-17 23:28:18'),
(25, 'nguyenvana', '1234567', 'nguyenvana@gmail.com', 'Nguyễn Văn A', '096388122', 4, 1, 'Cmt8, Hoa Lư, Gia Lai', 'bgr1.jpg', 99, 0.00, 0.00, '2021-08-23 17:24:01'),
(44, 'nhanhau_khoa', '635092b43f6daab6e117b2429f5e6236', 'hau@gmail.com', 'Nguyễn Trần Nhân Hậu 123123', '09971231991111', 2, 1, 'Hẻm 234, CMT8, Hoa Lư, Pleiku', '', 1, 0.00, 0.00, '2021-09-03 14:40:37'),
(69, 'admin888', '82f9fa29d86dae71395f7fc9ef23fe5f', 'test@gmail.com', 'Tôi test 7', '', 0, 1, 'askdaosda ', '', 1, 0.00, 0.00, '2025-11-19 22:41:10'),
(70, 'uuuuu11123', '82f9fa29d86dae71395f7fc9ef23fe5f', 'xcuong@gmail.com', 'Tôi test 9', '', 0, 1, 'kkkkka', '', 1, 0.00, 0.00, '2025-11-19 22:41:56'),
(42, 'xuancuong@gmail.com', '635092b43f6daab6e117b2429f5e6236', 'xuancuong@gmail.com', 'Vũ Xuân Cương', '0987112318', 1, 1, '27, Lê Đình Chinh, Hoa Lư, Gia Lai', 'background-Doan.jpg', 99, 0.00, 0.00, '2021-09-02 17:15:52'),
(43, 'quanly', '635092b43f6daab6e117b2429f5e6236', 'quanly@gmail.com', 'Quản lý', '0987112323', 2, 1, '27, Lê Đình Chinh, Hoa Lư, Gia Lai', '', 99, 0.00, 0.00, '2021-09-02 17:24:25'),
(45, '', 'd41d8cd98f00b204e9800998ecf8427e', '', '', '', 0, 1, '', '', 99, 0.00, 0.00, '2025-11-11 22:55:45'),
(68, 'admin3312312', '82f9fa29d86dae71395f7fc9ef23fe5f', 'qqsatest@gmail.com', 'Tôi test 5', '', 0, 1, '', '', 1, 0.00, 0.00, '2025-11-19 22:40:21'),
(67, 'admin212312', '82f9fa29d86dae71395f7fc9ef23fe5f', 'ddtest@gmail.com', 'Tôi test 5', '', 0, 1, 'askdaosda ', '', 1, 0.00, 0.00, '2025-11-19 22:39:34'),
(48, 'admin', 'd41d8cd98f00b204e9800998ecf8427e', 'admin', '62004_TRANGBT111', '091203123', 1, 1, 'Xuấdasjd', '', 1, 0.00, 0.00, '2025-11-17 21:13:22'),
(49, 'oakancha', 'd41d8cd98f00b204e9800998ecf8427e', 'oakancha', 'sgd_ioc_ktm', '0901231238', 2, 1, 'aosduiqweqn alsdais ', '', 1, 0.00, 0.00, '2025-11-17 21:19:22'),
(50, 'xuancuong1', 'd41d8cd98f00b204e9800998ecf8427e', 'xuancuong1', 'CAODANGKTM.ADMINKTM', '091231239', 1, 1, 'AKSDASDuqw', '', 1, 0.00, 0.00, '2025-11-17 21:23:23'),
(51, 'xuancuong2', 'd41d8cd98f00b204e9800998ecf8427e', 'xuancuong2', 'ádasdasd asd ', '019231237', 1, 1, 'qweqwe aasda s', '', 1, 0.00, 0.00, '2025-11-17 21:27:00'),
(52, 'xuancuong01923', 'd41d8cd98f00b204e9800998ecf8427e', 'xuancuong01923', 'aaaa', '192301293', 2, 1, 'asdasdasda a', '', 1, 0.00, 0.00, '2025-11-17 21:31:10'),
(53, '999023ja', 'd41d8cd98f00b204e9800998ecf8427e', '999023ja', 'asdasdax z', '009123128', 2, 1, 'AUsdalksd ', '', 1, 0.00, 0.00, '2025-11-17 21:32:48'),
(54, 'aasdquq ', 'd41d8cd98f00b204e9800998ecf8427e', 'aasdquq ', 'ádasda', '19283123', 2, 1, 'áasdasd', '', 1, 0.00, 0.00, '2025-11-17 21:33:51'),
(55, '23123919', 'd41d8cd98f00b204e9800998ecf8427e', '23123919', 'aasdasd ', '128312381', 2, 1, 'asdasdnx ', '', 1, 0.00, 0.00, '2025-11-17 21:34:26'),
(71, '09121232', '82f9fa29d86dae71395f7fc9ef23fe5f', '31test@gmail.com', 'Tôi test 109', '', 1, 1, 'KOn tum', '', 1, 0.00, 0.00, '2025-11-20 22:41:05'),
(63, 'cuongmedia@gmail.come', '82f9fa29d86dae71395f7fc9ef23fe5f', 'test@gmail.com', 'Tôi test 3', '', 0, 1, 'askdaosda ', '', 1, 0.00, 0.00, '2025-11-18 22:54:28'),
(64, 'admin123', '82f9fa29d86dae71395f7fc9ef23fe5f', 'test@gmail.com', 'Tôi test 4', '', 0, 1, 'askdaosda ', '', 1, 0.00, 0.00, '2025-11-18 22:55:30'),
(65, 'admin11111', '82f9fa29d86dae71395f7fc9ef23fe5f', 'tesat@gmail.com', 'Tôi test z', '', 0, 1, 'askdaosda ', '', 1, 0.00, 0.00, '2025-11-18 22:57:27'),
(66, 'admin3333', '82f9fa29d86dae71395f7fc9ef23fe5f', 'teast@gmail.com', 'Tôi test 2', '', 0, 1, 'askdaosda ', '', 1, 0.00, 0.00, '2025-11-19 22:16:25'),
(72, 'cuongvx.ktm@vnpt.vn', '82f9fa29d86dae71395f7fc9ef23fe5f', 'cuongvx.ktm@vnpt.vn', 'Vũ Xuân Cương', '0963719679', 2, 1, 'Kon Tum', '', 1, 0.00, 0.00, '2025-11-20 22:45:19'),
(73, '0828282121', '82f9fa29d86dae71395f7fc9ef23fe5f', 'hdnt@gmail.com', 'Phạm trần hương gian', '0828282121', 1, 15, 'Kon tum', '', 1, 0.00, 0.00, '2025-11-20 22:48:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_user_groups`
--

CREATE TABLE `hicrm_user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_class` varchar(255) DEFAULT NULL,
  `group_icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_user_groups`
--

INSERT INTO `hicrm_user_groups` (`id`, `group_name`, `group_class`, `group_icon`) VALUES
(1, 'Quản trị viên', 'primary', NULL),
(2, 'Bác sĩ', 'info', NULL),
(3, 'Biên tập viên', 'warning', NULL),
(4, 'Nhân viên', 'primary', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_warehouses`
--

CREATE TABLE `hicrm_warehouses` (
  `id` int(11) NOT NULL,
  `warehouse_uid` int(11) NOT NULL,
  `warehouse_branch_id` int(11) NOT NULL,
  `warehouse_code` varchar(255) NOT NULL,
  `warehouse_quantity` int(11) DEFAULT NULL,
  `warehouse_name` varchar(255) NOT NULL,
  `warehouse_description` text DEFAULT NULL,
  `warehouse_parent` int(11) NOT NULL,
  `warehouse_create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `warehouse_status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_warehouses`
--

INSERT INTO `hicrm_warehouses` (`id`, `warehouse_uid`, `warehouse_branch_id`, `warehouse_code`, `warehouse_quantity`, `warehouse_name`, `warehouse_description`, `warehouse_parent`, `warehouse_create_date`, `warehouse_status`) VALUES
(1, 1, 1, 'MK0000001', 0, 'Kho Computer', 'Kho tổng hợp thiết bị máy tính', 0, '2021-09-09 13:31:46', 1),
(2, 1, 2, 'MK0000002', NULL, 'Kho thiết bị điện tử', 'Kho tổng hợp các thiết bị điện tử', 0, '2021-09-10 15:05:37', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_otp`
--

CREATE TABLE `system_otp` (
  `id` bigint(30) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `otp_uid` bigint(30) NOT NULL,
  `otp_exp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `system_otp`
--

INSERT INTO `system_otp` (`id`, `otp_code`, `otp_uid`, `otp_exp`) VALUES
(1, '569780', 6, '2021-04-05 12:55:12'),
(2, '364651', 6, '2021-04-05 13:13:13'),
(3, '729103', 6, '2021-04-05 13:13:17'),
(4, '857710', 6, '2021-04-05 13:14:11'),
(5, '114226', 6, '2021-04-05 13:15:03'),
(6, '093488', 6, '2021-04-05 13:36:13'),
(7, '755674', 6, '2021-04-05 13:39:02'),
(8, '276816', 6, '2021-04-05 13:41:20'),
(9, '390663', 6, '2021-04-05 17:03:14'),
(10, '203970', 6, '2021-04-05 17:03:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_page`
--

CREATE TABLE `system_page` (
  `page_type` int(11) NOT NULL,
  `page_content` longtext NOT NULL,
  `page_uid` int(11) NOT NULL,
  `page_status` int(11) NOT NULL,
  `page_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `system_page`
--

INSERT INTO `system_page` (`page_type`, `page_content`, `page_uid`, `page_status`, `page_created_date`) VALUES
(1, '', 0, 0, '2025-12-28 07:58:34'),
(2, '', 1, 1, '2025-12-28 07:58:55');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_accounts`
--
ALTER TABLE `hicrm_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_banks`
--
ALTER TABLE `hicrm_banks`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_bank_accounts`
--
ALTER TABLE `hicrm_bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_bookings`
--
ALTER TABLE `hicrm_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_booking_status`
--
ALTER TABLE `hicrm_booking_status`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_branchs`
--
ALTER TABLE `hicrm_branchs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_calendar_works`
--
ALTER TABLE `hicrm_calendar_works`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_caludar_employees`
--
ALTER TABLE `hicrm_caludar_employees`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_categories`
--
ALTER TABLE `hicrm_categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_category_parent`
--
ALTER TABLE `hicrm_category_parent`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_configs`
--
ALTER TABLE `hicrm_configs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_currencies`
--
ALTER TABLE `hicrm_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_customers`
--
ALTER TABLE `hicrm_customers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_customer_banks`
--
ALTER TABLE `hicrm_customer_banks`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_customer_groups`
--
ALTER TABLE `hicrm_customer_groups`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_departments`
--
ALTER TABLE `hicrm_departments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_employees`
--
ALTER TABLE `hicrm_employees`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_employee_banks`
--
ALTER TABLE `hicrm_employee_banks`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_events`
--
ALTER TABLE `hicrm_events`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_expense_items`
--
ALTER TABLE `hicrm_expense_items`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_images`
--
ALTER TABLE `hicrm_images`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_incomes`
--
ALTER TABLE `hicrm_incomes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_income_details`
--
ALTER TABLE `hicrm_income_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_income_types`
--
ALTER TABLE `hicrm_income_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_introduce`
--
ALTER TABLE `hicrm_introduce`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_news`
--
ALTER TABLE `hicrm_news`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_orders`
--
ALTER TABLE `hicrm_orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_order_details`
--
ALTER TABLE `hicrm_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_payment_policies`
--
ALTER TABLE `hicrm_payment_policies`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_permissions`
--
ALTER TABLE `hicrm_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_permission_datas`
--
ALTER TABLE `hicrm_permission_datas`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_positions`
--
ALTER TABLE `hicrm_positions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_products`
--
ALTER TABLE `hicrm_products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_products_bk`
--
ALTER TABLE `hicrm_products_bk`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_product_categories`
--
ALTER TABLE `hicrm_product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_product_warehouses`
--
ALTER TABLE `hicrm_product_warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_promotions`
--
ALTER TABLE `hicrm_promotions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_quotes`
--
ALTER TABLE `hicrm_quotes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_quote_details`
--
ALTER TABLE `hicrm_quote_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_request_salary`
--
ALTER TABLE `hicrm_request_salary`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_service`
--
ALTER TABLE `hicrm_service`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_spend_collectes`
--
ALTER TABLE `hicrm_spend_collectes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_status`
--
ALTER TABLE `hicrm_status`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_supplies`
--
ALTER TABLE `hicrm_supplies`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_taxs`
--
ALTER TABLE `hicrm_taxs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_templates`
--
ALTER TABLE `hicrm_templates`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_template_types`
--
ALTER TABLE `hicrm_template_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_transactions`
--
ALTER TABLE `hicrm_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_type`
--
ALTER TABLE `hicrm_type`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_units`
--
ALTER TABLE `hicrm_units`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_users`
--
ALTER TABLE `hicrm_users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_user_groups`
--
ALTER TABLE `hicrm_user_groups`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hicrm_warehouses`
--
ALTER TABLE `hicrm_warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `system_otp`
--
ALTER TABLE `system_otp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_accounts`
--
ALTER TABLE `hicrm_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT cho bảng `hicrm_banks`
--
ALTER TABLE `hicrm_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT cho bảng `hicrm_bank_accounts`
--
ALTER TABLE `hicrm_bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hicrm_bookings`
--
ALTER TABLE `hicrm_bookings`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `hicrm_booking_status`
--
ALTER TABLE `hicrm_booking_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_branchs`
--
ALTER TABLE `hicrm_branchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_calendar_works`
--
ALTER TABLE `hicrm_calendar_works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_caludar_employees`
--
ALTER TABLE `hicrm_caludar_employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_categories`
--
ALTER TABLE `hicrm_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `hicrm_category_parent`
--
ALTER TABLE `hicrm_category_parent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_configs`
--
ALTER TABLE `hicrm_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `hicrm_currencies`
--
ALTER TABLE `hicrm_currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT cho bảng `hicrm_customers`
--
ALTER TABLE `hicrm_customers`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `hicrm_customer_banks`
--
ALTER TABLE `hicrm_customer_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_customer_groups`
--
ALTER TABLE `hicrm_customer_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `hicrm_departments`
--
ALTER TABLE `hicrm_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `hicrm_employees`
--
ALTER TABLE `hicrm_employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `hicrm_employee_banks`
--
ALTER TABLE `hicrm_employee_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_events`
--
ALTER TABLE `hicrm_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hicrm_expense_items`
--
ALTER TABLE `hicrm_expense_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_images`
--
ALTER TABLE `hicrm_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_incomes`
--
ALTER TABLE `hicrm_incomes`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `hicrm_income_details`
--
ALTER TABLE `hicrm_income_details`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hicrm_income_types`
--
ALTER TABLE `hicrm_income_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_introduce`
--
ALTER TABLE `hicrm_introduce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_news`
--
ALTER TABLE `hicrm_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_orders`
--
ALTER TABLE `hicrm_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_order_details`
--
ALTER TABLE `hicrm_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_payment_policies`
--
ALTER TABLE `hicrm_payment_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_permissions`
--
ALTER TABLE `hicrm_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `hicrm_permission_datas`
--
ALTER TABLE `hicrm_permission_datas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT cho bảng `hicrm_positions`
--
ALTER TABLE `hicrm_positions`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `hicrm_products`
--
ALTER TABLE `hicrm_products`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_products_bk`
--
ALTER TABLE `hicrm_products_bk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_product_categories`
--
ALTER TABLE `hicrm_product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_product_warehouses`
--
ALTER TABLE `hicrm_product_warehouses`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_promotions`
--
ALTER TABLE `hicrm_promotions`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_quotes`
--
ALTER TABLE `hicrm_quotes`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_quote_details`
--
ALTER TABLE `hicrm_quote_details`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_request_salary`
--
ALTER TABLE `hicrm_request_salary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_service`
--
ALTER TABLE `hicrm_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `hicrm_spend_collectes`
--
ALTER TABLE `hicrm_spend_collectes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `hicrm_status`
--
ALTER TABLE `hicrm_status`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_supplies`
--
ALTER TABLE `hicrm_supplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `hicrm_taxs`
--
ALTER TABLE `hicrm_taxs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_templates`
--
ALTER TABLE `hicrm_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_template_types`
--
ALTER TABLE `hicrm_template_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `hicrm_transactions`
--
ALTER TABLE `hicrm_transactions`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_type`
--
ALTER TABLE `hicrm_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_units`
--
ALTER TABLE `hicrm_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `hicrm_users`
--
ALTER TABLE `hicrm_users`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT cho bảng `hicrm_user_groups`
--
ALTER TABLE `hicrm_user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_warehouses`
--
ALTER TABLE `hicrm_warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `system_otp`
--
ALTER TABLE `system_otp`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
