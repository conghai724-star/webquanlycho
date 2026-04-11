-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th4 04, 2026 lúc 02:50 PM
-- Phiên bản máy phục vụ: 8.0.37
-- Phiên bản PHP: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phongkha697b_phongkhamcdkontum`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_accounts`
--

CREATE TABLE `hicrm_accounts` (
  `id` int NOT NULL,
  `account_number` varchar(10) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_type` int NOT NULL DEFAULT '1' COMMENT '1 - dư nợ, 2 - dư có, 3 - lưỡng tính',
  `account_name_en` varchar(255) NOT NULL,
  `account_description` text,
  `account_status` int NOT NULL DEFAULT '1' COMMENT '1 - đang sử dụng, 2 - ngưng sử dụng',
  `account_parent` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `bank_name` text NOT NULL,
  `bank_name_en` text NOT NULL,
  `bank_code` varchar(30) NOT NULL,
  `bank_logo` text,
  `bank_description` varchar(255) DEFAULT NULL,
  `bank_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `bank_id` int NOT NULL,
  `ba_branch_id` int NOT NULL,
  `ba_account` varchar(30) NOT NULL,
  `ba_holder` varchar(255) NOT NULL,
  `ba_branch` text NOT NULL,
  `ba_description` text,
  `ba_status` int NOT NULL,
  `ba_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` bigint NOT NULL,
  `booking_person_name` varchar(255) DEFAULT NULL,
  `booking_person_gender` int NOT NULL,
  `booking_person_year` int NOT NULL,
  `booking_person_address` varchar(255) DEFAULT NULL,
  `booking_person_phone` varchar(255) NOT NULL,
  `booking_doctor` int DEFAULT '0',
  `booking_date` date DEFAULT NULL,
  `booking_hour` varchar(255) NOT NULL,
  `booking_title` varchar(255) DEFAULT NULL,
  `booking_description` text,
  `booking_created_date` datetime DEFAULT NULL,
  `booking_status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_booking_status`
--

CREATE TABLE `hicrm_booking_status` (
  `id` int NOT NULL,
  `bk_status_label` varchar(80) NOT NULL,
  `bk_status_class` varchar(100) DEFAULT NULL,
  `bk_status_icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `branch_uid` int NOT NULL,
  `branch_tax_code` varchar(30) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` text NOT NULL,
  `branch_phone` varchar(255) NOT NULL,
  `branch_email` varchar(255) NOT NULL,
  `branch_director` text,
  `branch_type` int NOT NULL,
  `branch_founded_date` datetime DEFAULT NULL,
  `branch_created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `calendar_work_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `calendar_work_content` longtext COLLATE utf8mb4_general_ci,
  `calendar_work_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `calendar_work_from_date` datetime NOT NULL,
  `calendar_work_to_date` datetime NOT NULL,
  `calendar_work_created_date` datetime NOT NULL,
  `calendar_work_user_created` int NOT NULL,
  `calendar_status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_calendar_works`
--

INSERT INTO `hicrm_calendar_works` (`id`, `calendar_work_name`, `calendar_work_content`, `calendar_work_file`, `calendar_work_from_date`, `calendar_work_to_date`, `calendar_work_created_date`, `calendar_work_user_created`, `calendar_status`) VALUES
(3, 'Bảng giá dịch vụ', '', '73fefa1a1f095e7bd5916841d496c5ca-Phlc-GiKBCBbanhnhkmtheoQ.pdf', '2026-03-03 00:00:00', '2026-03-03 00:00:00', '2026-03-03 16:07:28', 24, 99);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_caludar_employees`
--

CREATE TABLE `hicrm_caludar_employees` (
  `id` int NOT NULL,
  `caludar_id_employee` int NOT NULL,
  `caludar_time` datetime NOT NULL,
  `caludar_status` int NOT NULL,
  `user_created` int NOT NULL,
  `caludar_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_categories`
--

CREATE TABLE `hicrm_categories` (
  `id` int NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text,
  `category_parent` int NOT NULL,
  `category_orderby` int DEFAULT '0',
  `category_status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_categories`
--

INSERT INTO `hicrm_categories` (`id`, `category_name`, `category_description`, `category_parent`, `category_orderby`, `category_status`) VALUES
(1, 'Tin tức và sự kiện', NULL, 5, NULL, 1),
(2, 'Về chúng tôi', NULL, 1, NULL, 1),
(3, 'Cơ cấu tổ chức', NULL, 1, NULL, 1),
(4, 'Cơ sở hạ tầng', NULL, 1, NULL, 1),
(5, 'Tại sao chọn chúng tôi?', NULL, 1, 0, 1),
(6, ' Khám chữa bệnh dịch vụ', '', 3, 0, 1),
(7, 'Khám chữa bệnh BHYT', '', 3, 0, 1),
(8, 'Giới thiệu nhà thuốc', NULL, 4, 0, 1),
(9, 'Chính sách bán hàng', NULL, 4, 0, 1),
(10, 'Đo chỉ số cân nặng - Chiều cao (BMI) Online', NULL, 0, 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_category_parent`
--

CREATE TABLE `hicrm_category_parent` (
  `id` int NOT NULL,
  `category_parent_name` varchar(255) NOT NULL,
  `category_parent_status` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `config_key` varchar(255) NOT NULL,
  `config_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_configs`
--

INSERT INTO `hicrm_configs` (`id`, `config_key`, `config_value`) VALUES
(1, 'won_rate', '3765'),
(2, 'website_name', 'Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum'),
(3, 'website_description', 'Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum là đơn vị y tế trực thuộc Trường Cao đẳng Kon Tum, cung cấp dịch vụ khám chữa bệnh, cấp phát thuốc và chăm sóc sức khỏe uy tín, chất lượng cho cán bộ, học sinh – sinh viên và người dân trên địa bàn'),
(4, 'admin_email', 'vuxuancuong98gl@gmail.com'),
(5, 'smtp_server', ''),
(6, 'smtp_port', ''),
(7, 'smtp_protocol', ''),
(8, 'smtp_password', ''),
(9, 'sms_api_key', ''),
(10, 'agency_prefix', 'CMAG'),
(11, 'deposite_prefix', 'CMC'),
(12, 'minimun_fee', '10000'),
(13, 'admin_email.site_email', ''),
(14, 'site_phone', '02606 558 568'),
(15, 'site_email', 'phongkhamdakhoavanhathuoccdkt@gmail.com'),
(16, 'site_address', 'số 347 Bà Triệu, phường Kon Tum, tỉnh Quảng Ngãi'),
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
(28, 'site_hotline', 'Phòng khám Đa khoa: 02606 558 568 <br> Nhà thuốc: 083 999 5775'),
(29, 'company_tax_id', '0315422622'),
(30, 'company_address', 'Số 63 Đường CN11, Phường Sơn Kỳ, Quận Tân Phú, TPHCM'),
(31, 'QUOTE_PREFIX', 'VMQ'),
(32, 'site_facebook', 'https://www.facebook.com/profile.php?id=61583478166588'),
(33, 'site_phonezalo', '0839995775');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_currencies`
--

CREATE TABLE `hicrm_currencies` (
  `id` int NOT NULL,
  `currency_code` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `currency_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `currency_rate` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `currency_type` int NOT NULL,
  `currency_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `id` bigint NOT NULL,
  `customer_uid` int NOT NULL,
  `customer_branch_id` int DEFAULT NULL,
  `customer_code` varchar(25) NOT NULL,
  `customer_tax_code` varchar(20) DEFAULT NULL,
  `customer_name` mediumtext,
  `customer_title` varchar(20) DEFAULT NULL,
  `customer_address` mediumtext,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_group` int NOT NULL DEFAULT '1',
  `customer_type` int NOT NULL DEFAULT '1',
  `customer_is_vendor` int NOT NULL DEFAULT '0',
  `customer_loyalty_point` int NOT NULL DEFAULT '0',
  `customer_staff` int DEFAULT NULL,
  `customer_note` text,
  `customer_payment_policy` int NOT NULL,
  `customer_debit` int NOT NULL,
  `customer_credit` int NOT NULL,
  `customer_debt` decimal(20,2) NOT NULL DEFAULT '0.00',
  `customer_created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_last_update` datetime DEFAULT NULL,
  `customer_status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `cid` bigint NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `bank_holder` varchar(255) NOT NULL,
  `bank_id` int NOT NULL,
  `bank_branch` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_customer_feedback`
--

CREATE TABLE `hicrm_customer_feedback` (
  `id` int NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `customer_phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `customer_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` int NOT NULL COMMENT '0: Chờ duyệt, 1: Đã duyệt, 2: Không duyệt 99: Ẩn',
  `rating` int DEFAULT '0' COMMENT '1-5 sao',
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_customer_feedback`
--

INSERT INTO `hicrm_customer_feedback` (`id`, `customer_name`, `customer_phone`, `customer_email`, `customer_address`, `content`, `status`, `rating`, `create_date`) VALUES
(1, 'vũ Xuân CƯơng', '0929981213', 'asb_a@gmail.com', 'aasdasd ', '0', 0, 0, '2026-03-01 22:18:05'),
(2, 'Vũ Xuân Cương', '0828228339', 'vuxuancuong98gl@gmail.com', 'Kon Tum, Quảng Ngãi', '0', 0, 0, '2026-03-04 23:17:38'),
(3, 'NNT', '0963719679', 'vuxuancuong98gl@gmail.com', 'Quảng Ngãi', 'Tôi rất hài lòng', 0, 0, '2026-03-04 23:21:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_customer_groups`
--

CREATE TABLE `hicrm_customer_groups` (
  `id` int NOT NULL,
  `group_code` varchar(30) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_color` varchar(20) DEFAULT NULL,
  `group_description` text,
  `group_status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `depart_name` varchar(255) NOT NULL,
  `depart_image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `depart_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_departments`
--

INSERT INTO `hicrm_departments` (`id`, `depart_name`, `depart_image`, `depart_status`) VALUES
(1, 'Nội khoa', 'noikhoa.png', 1),
(2, 'Sản khoa', 'sankhoa.png', 1),
(15, 'Cận lâm Sàng', 'cls1.png', 1),
(19, 'Ngoại khoa', 'ngoaikhoa.png', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_employees`
--

CREATE TABLE `hicrm_employees` (
  `id` int NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_gender` int NOT NULL,
  `employee_birthday` datetime NOT NULL,
  `employee_branch` int NOT NULL DEFAULT '1',
  `employee_department` int NOT NULL,
  `employee_position` int NOT NULL,
  `employee_national_id` varchar(30) NOT NULL,
  `employee_issue_date` datetime NOT NULL,
  `employee_issue_by` varchar(255) NOT NULL,
  `employee_address` text NOT NULL,
  `employee_phone` varchar(50) NOT NULL,
  `employee_email` varchar(255) NOT NULL,
  `employee_debt` decimal(20,2) NOT NULL DEFAULT '0.00',
  `employee_image` varchar(255) NOT NULL,
  `employee_des` text NOT NULL,
  `employee_status` int NOT NULL,
  `employee_calendar` date DEFAULT NULL,
  `employee_shift` int DEFAULT NULL,
  `employee_created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `employee_last_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_employees`
--

INSERT INTO `hicrm_employees` (`id`, `employee_code`, `employee_name`, `employee_gender`, `employee_birthday`, `employee_branch`, `employee_department`, `employee_position`, `employee_national_id`, `employee_issue_date`, `employee_issue_by`, `employee_address`, `employee_phone`, `employee_email`, `employee_debt`, `employee_image`, `employee_des`, `employee_status`, `employee_calendar`, `employee_shift`, `employee_created_date`, `employee_last_update`) VALUES
(19, 'BS004', 'Nguyễn Trung Hiếu', 1, '1977-01-29 00:00:00', 0, 15, 1, '030098013026', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '02606 558 568', 'phongkhamdakhoavanhathuoccdkt@gmail.com', 0.00, 'ad291bc4d4ea28ed320e61a4b55d2769-BSHiu.jpg', 'Khám bệnh, chữa bệnh chuyên khoa Ngoại, thực hiện các kỹ thuật chẩn đoán hình ảnh', 1, '2025-12-31', 1, '2026-02-25 08:34:38', '2026-02-25 08:34:38'),
(21, 'BS006', 'Lê Thành Vinh', 1, '1977-11-12 00:00:00', 0, 1, 0, '030098013026', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '02606 558 568', 'phongkhamdakhoavanhathuoccdkt@gmail.com', 0.00, 'cce140d37d135d6bded62e924ba465fa-BSVinh.jpg', 'aaaaaaaaaaaaaaaaax', 1, '2025-12-31', 1, '2026-02-12 12:04:05', '2026-02-12 12:04:05'),
(23, 'BS008', 'Lê Thị Ý', 1, '1982-07-17 00:00:00', 0, 2, 0, '030098013026', '2025-11-23 00:00:00', 'CụC CS', 'undefined', '02606 558 568', 'phongkhamdakhoavanhathuoccdkt@gmail.com', 0.00, '7f8115b0d30efa74944ed44af22a28aa-LTY.jpg', 'Khám bệnh, chữa bệnh chuyên khoa Phụ sản', 1, '2025-12-29', 1, '2026-02-25 08:33:45', '2026-02-25 08:33:45'),
(25, 'BS002', 'Nguyễn Thế Vinh', 1, '1967-03-01 00:00:00', 0, 19, 0, '034067007759', '2021-04-28 00:00:00', 'CụC CS', 'undefined', '02606 558 568', 'phongkhamdakhoavanhathuoccdkt@gmail.com', 0.00, '32f4b96277f4162a0455c725ff526572-NTV.jpg', 'Khám bệnh, chữa bệnh chuyên khoa Ngoại chung', 1, '2025-12-30', 2, '2026-02-25 08:33:15', '2026-02-25 08:33:15'),
(27, 'BS004', 'Cao Văn Ngọc', 1, '1964-05-01 00:00:00', 0, 1, 0, '030098013017', '2025-12-30 00:00:00', 'CụC CS', 'undefined', '02606 558 568', 'phongkhamdakhoavanhathuoccdkt@gmail.com', 0.00, '5b1412cefd765d30977be2c01af04538-CVN2.jpg', 'Khám bệnh, chữa bệnh chuyên khoa Nội chung', 1, NULL, NULL, '2026-02-25 08:37:04', '2026-02-25 08:37:04'),
(28, 'BS005', 'Đinh Quang Thuận', 1, '1971-07-09 00:00:00', 0, 19, 0, '066071003441', '2026-02-08 00:00:00', 'CụC CS', 'undefined', '02606 558 568', 'phongkhamdakhoavanhathuoccdkt@gmail.com', 0.00, '978d28d2dc8a7970876b6d99d294c87d-QT.jpg', 'Khám bệnh, chữa bệnh đa khoa', 1, NULL, NULL, '2026-02-24 16:42:54', '2026-02-24 16:42:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_employee_banks`
--

CREATE TABLE `hicrm_employee_banks` (
  `id` int NOT NULL,
  `eid` bigint NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `bank_holder` varchar(255) NOT NULL,
  `bank_id` int NOT NULL,
  `bank_branch` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_events`
--

CREATE TABLE `hicrm_events` (
  `id` int NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `event_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `event_content` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `event_image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `event_type` int NOT NULL,
  `event_hot` int NOT NULL DEFAULT '0',
  `event_user_created` int NOT NULL,
  `event_status` int NOT NULL,
  `event_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hicrm_events`
--

INSERT INTO `hicrm_events` (`id`, `event_name`, `event_description`, `event_content`, `event_image`, `event_type`, `event_hot`, `event_user_created`, `event_status`, `event_created_date`) VALUES
(1, 'sk 1', 'Mô tả', '<p>aaaa asd zzz</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 0, 24, 99, '2026-01-06 20:49:23'),
(2, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026', 'Mô tả', '<p>Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 0, 24, 99, '2026-01-06 20:49:23'),
(3, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2027', 'Mô tả', '<p>Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 0, 24, 99, '2026-01-06 20:49:23'),
(4, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2027', 'Mô tả', '<p>Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 0, 24, 99, '2026-01-06 20:49:23'),
(5, 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026 1111', 'Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026', '<p>aaaa asd zzz</p>', '8ea1c0411ad93af2550011d1504fddb8-NDThe.png', 0, 0, 24, 99, '2026-01-06 20:49:23'),
(6, 'Khám chữa bệnh Bảo hiểm y tế tại Phòng khám Đa khoa Trường Cao đẳng Kon Tum – Lựa chọn thông minh!', 'Nhiều người nghĩ phải đi khám bệnh tại các bệnh viện mới được hưởng quyền lợi bảo hiểm y tế (BHYT) đầy đủ, nhưng thực tế đi khám bệnh tại cơ sở khám chữa bệnh (KCB) ban đầu lại là cách thông minh nhất để tiết kiệm thời gian, chi phí, được phát hiện sớm các vấn đề sức khỏe và được chăm sóc liên tục.', '<p style=\"text-align: left;\"></p><p class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><b><span style=\"font-size:16.0pt;line-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Phòng\r\nkhám Đa khoa Trường Cao đẳng Kon Tum</span></b><span style=\"font-size:16.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align: center;\"><b><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">– Uy tín từ chuyên môn,\r\nan tâm từ dịch vụ</span></b><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Nhiều người nghĩ phải đi\r\nkhám bệnh tại các bệnh viện mới được hưởng quyền lợi bảo hiểm y tế (BHYT) đầy đủ,\r\nnhưng thực tế đi khám bệnh tại cơ sở khám chữa bệnh (KCB) ban đầu lại là cách\r\nthông minh nhất để tiết kiệm thời gian, chi phí, được phát hiện sớm các vấn đề\r\nsức khỏe và được chăm sóc liên tục.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><b><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Phòng khám đa khoa trường\r\ncao đẳng kon tum</span></b><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;là cơ sở&nbsp;<b>KCB ban đầu</b>&nbsp;tiếp\r\nnhận người bệnh BHYT&nbsp;theo quy định, giúp người dân tiếp cận dịch vụ nhanh\r\nchóng, thuận lợi và hiệu quả ngay tại địa phương.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✨</span><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;<b>Những lợi ích nổi bật khi KCB\r\nBHYT tại Phòng khám Đa khoa Trường Cao đẳng Kon Tum:</b><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><b><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✅</span></b><b><span style=\"font-size:14.0pt;line-height:\r\n115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;Không cần giấy chuyển cơ sở KCB\r\n- Thủ tục đơn giản, nhanh gọn, vẫn được hưởng đầy đủ quyền lợi BHYT.&nbsp;</span></b><span style=\"font-size:14.0pt;line-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Người\r\nbệnh thuộc mọi đối tượng tham gia BHYT sinh sống ở bất cứ nơi đâu, không phân\r\nbiệt địa giới hành chính đến KCB BHYT tại Phòng khám Đa khoa Trường Cao đẳng\r\nKon Tum đều được hưởng đầy đủ quyền lợi trong phạm vi về KCB BHYT.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;<b>Mức cùng chi trả thấp – tiết kiệm\r\nchi phí tối đa</b><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">- Hưởng 100% chi phí: Người\r\ncó công với cách mạng, cựu chiến binh, trẻ em &lt; 6 tuổi, người thuộc hộ\r\nnghèo, hộ cận nghèo, người tham gia BHYT thuộc hộ gia đình cận nghèo, người cao\r\ntuổi từ đủ 75 tuổi trở lên đang hưởng trợ cấp hưu trí xã hội&nbsp;<i>(nâng mức\r\nhưởng từ 01/01/2026 theo NQ 261 của Quốc hội)</i>, người dân tộc thiểu số vùng\r\nkhó khăn/đặc biệt khó khăn, người hưởng trợ cấp xã hội hằng tháng, người từ đủ\r\n75 tuổi đang hưởng trợ cấp tuất hằng tháng, trường hợp tổng chi phí một lần KCB\r\nthấp hơn 15% mức lương cơ sở (với mức lượng cơ sở hiện nay là 2.340.000 đồng,\r\n15% mức lương cơ sở tương đương 351.000 đồng), người tham gia BHYT từ 5 năm\r\nliên tục trở lên và có số tiền cùng chi trả trong năm lớn hơn 6 tháng lương cơ\r\nsở.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">- Hưởng 95% chi phí: Người\r\nhưởng lương hưu, trợ cấp mất sức lao động hàng tháng, thân nhân người có công với\r\ncách mạng.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">- Hưởng 80% chi phí: Áp dụng\r\ncho các đối tượng còn lại (như cán bộ, công chức, viên chức, người lao động, học\r\nsinh, sinh viên, người tham gia BHYT hộ gia đình).<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;Khám đúng cơ sở KCB\r\nban đầu giúp người bệnh được áp dụng mức hưởng cao nhất.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;<b>Thông tuyến thuận lợi theo quy định:</b>&nbsp;Khi\r\ncần thiết, bác sĩ sẽ hỗ trợ chuyển cơ sở KCB đúng quy định để người bệnh tiếp tục\r\nđược hưởng quyền lợi cao nhất.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;<b>Vị trí ngay trung tâm phường Kon\r\nTum, tỉnh Quảng Ngãi và rất thuận tiện đi lại</b><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;<b>Quy trình khám bệnh nhanh chóng –\r\nchuyên nghiệp, không phải chờ đợi, chen chúc như ở các cơ sở y tế tuyến trên</b><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;<b>Đội ngũ bác sĩ giỏi, người bệnh\r\nkhông chỉ được khám bệnh mà còn được tư vấn phòng bệnh, theo dõi hồ sơ sức khỏe\r\nvà hỗ trợ điều trị phù hợp ngay từ giai đoạn sớm.</b><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;mso-bidi-font-family:=\"\" \"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;line-height:115%;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;<b>Trang thiết bị siêu âm, xét nghiệm\r\nhiện đại, được đầu tư mới</b><o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align:justify\"><span style=\"font-size:14.0pt;\r\nline-height:115%;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Xu hướng y tế hiện nay\r\nkhuyến khích người dân chăm sóc sức khỏe từ tuyến ban đầu – nơi giúp tiết kiệm\r\nchi phí, tiếp cận dịch vụ nhanh và quản lý sức khỏe hiệu quả.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;line-height:115%;font-family:\r\n\" times=\"\" new=\"\" roman\",serif\"=\"\">Khi đi khám, vui lòng mang theo: Thẻ BHYT còn hiệu lực\r\nhoặc thẻ Căn cước/CCCD gắn chip hoặc ứng dụng VNeID (định danh mức 2) trên điện\r\nthoại.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;line-height:115%;font-family:\r\n\" times=\"\" new=\"\" roman\",serif\"=\"\">* Liên hệ ngay&nbsp;<b><u><span style=\"color:#EE0000\">02606.558.568</span></u></b><span style=\"color:#EE0000\">&nbsp;</span>để được hướng dẫn khám BHYT nhanh chóng!<o:p></o:p></span></p><p class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p class=\"MsoNormal\"><o:p>&nbsp;</o:p></p><p class=\"MsoNormal\" style=\"text-align: justify; margin-bottom: 6pt; line-height: 115%;\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/5967db3f7fcede80bb973c5fc3ef348a-40337fa7d5b75be902a6.jpg\" style=\"width: 939.988px; float: left;\" class=\"note-float-left\"><br></p>', 'f8061fc3b2bf9670cb56a2f04a765fc5-40337fa7d5b75be902a6.jpg', 0, 0, 24, 1, '2026-02-09 09:04:56'),
(7, 'Thông báo chính thức triển khai khám bệnh, chữa bệnh Bảo hiểm y tế tại Phòng khám Đa khoa Trường Cao đẳng Kon Tum', 'Nhằm mở rộng quyền lợi và tạo điều kiện thuận lợi cho người dân trong công tác chăm sóc sức khỏe, bên cạnh các dịch vụ khám chữa bệnh theo yêu cầu hiện có, Phòng khám Đa khoa Trường Cao đẳng Kon Tum chính thức triển khai khám, chữa bệnh Bảo hiểm y tế cho mọi người dân có Bảo hiểm y tế.', '<p></p><div style=\"text-align: justify;\"><span style=\"white-space-collapse: preserve;\">Nhằm mở rộng quyền lợi và tạo điều kiện thuận lợi cho người dân trong công tác chăm sóc sức khỏe, bên cạnh các dịch vụ khám chữa bệnh theo yêu cầu hiện có, Phòng khám Đa khoa Trường Cao đẳng Kon Tum chính thức triển khai khám, chữa bệnh Bảo hiểm y tế cho mọi người dân có Bảo hiểm y tế.</span></div><div style=\"text-align: justify;\"><b><span style=\"white-space-collapse: preserve;\"><br></span></b></div><div style=\"text-align: justify;\"><b><span style=\"white-space-collapse: preserve;\">Thời gian áp dụng: từ ngày 16/03/2026</span></b></div><b><span style=\"white-space-collapse: preserve;\"><div style=\"text-align: justify;\"><b>Địa điểm: Phòng khám Đa khoa Trường Cao đẳng Kon Tum</b></div></span><span style=\"white-space-collapse: preserve;\"><div style=\"text-align: justify;\"><b>Địa chỉ: số 347 Bà Triệu, phường Kon Tum, tỉnh Quảng Ngãi</b></div></span></b><div style=\"text-align: justify;\"><span style=\"font-size: 14px;\"><br></span></div><span style=\"white-space-collapse: preserve;\"><div style=\"text-align: justify;\">Với đội ngũ Y - Bác sĩ tận tâm, cơ sở vật chất đảm bảo đạt chuẩn,  Phòng khám cam kết mang đến dịch vụ khám, chữa bệnh Chất lượng – An toàn – Đúng quy định, góp phần chăm sóc và bảo vệ sức khỏe cộng đồng một các hiệu quả.</div></span><br><span style=\"white-space-collapse: preserve;\">⚜PHÒNG KHÁM ĐA KHOA TRƯỜNG CAO ĐẲNG KON TUM – “UY TÍN TỪ CHUYÊN MÔN – AN TÂM TỪ DỊCH VỤ”⚜</span><br><p></p>', '6e1c9fa97d13a425bcedd3285c887f44-39531ac7b0d73e8967c6.jpg', 0, 0, 24, 1, '2026-02-09 09:40:57'),
(8, 'CẢNH GIÁC VIRUS NIPAH: NGUY CƠ DỊCH BỆNH MỚI', 'Thời gian gần đây, một số quốc gia châu Á ghi nhận các ca nhiễm virus Nipah — loại virus có khả năng gây bệnh nặng với tỷ lệ tử vong cao. Theo các cơ quan y tế quốc tế, virus này lây truyền từ động vật sang người và có thể gây viêm não cấp tính.', '<p class=\"MsoNormal\" style=\"text-align: justify; \"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Virus\r\nNipah là gì?<o:p></o:p></font></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Virus\r\nNipah (Nipah virus – NiV) là một loại virus thuộc họ Paramyxoviridae, lần đầu\r\ntiên được phát hiện năm 1998 tại Malaysia. Đây là bệnh truyền nhiễm nguy hiểm\r\nlây từ động vật sang người (zoonotic disease), chủ yếu liên quan đến loài dơi\r\năn quả (fruit bats).<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify; \"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Theo\r\nTổ chức Y tế Thế giới (WHO), Nipah được xếp vào nhóm tác nhân gây bệnh có nguy\r\ncơ bùng phát dịch lớn do:<o:p></o:p></font></span></p><p>\r\n </p><ul><li style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">&nbsp;Tỷ\r\n     lệ tử vong cao<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">&nbsp;Chưa\r\n     có thuốc điều trị đặc hiệu<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">&nbsp;Chưa\r\n     có vaccine được cấp phép sử dụng rộng rãi<o:p></o:p></font></span></li>\r\n</ul><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Vì\r\nsao virus Nipah đang được cảnh báo trở lại?</font></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"color: rgb(0, 0, 255); font-size: 14pt;\">Trong\r\nnhững năm gần đây, nhiều ca bệnh Nipah được ghi nhận rải rác tại Nam Á và Đông\r\nNam Á, đặc biệt là tại Ấn Độ và Bangladesh. Các chuyên gia y tế lo ngại nguy cơ\r\nbùng phát dịch do:</span></p><ul>\r\n <li style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">&nbsp;Khả\r\n     năng lây truyền từ người sang người<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">&nbsp;Tỷ\r\n     lệ tử vong cao (40% – 75%)<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">&nbsp;Diễn\r\n     tiến bệnh nhanh và nguy hiểm<o:p></o:p></font></span></li>\r\n</ul><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Virus\r\nNipah lây truyền như thế nào?<o:p></o:p></font></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Virus\r\ncó thể lây qua nhiều con đường:<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">1️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" segoe=\"\" ui=\"\" symbol\",sans-serif;mso-bidi-font-family:\"segoe=\"\" symbol\"\"=\"\">⃣</span><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Từ động vật sang\r\nngười: Tiếp xúc với dơi nhiễm bệnh hoặc dịch tiết của dơi; Ăn trái cây bị dơi cắn;\r\nUống nước hoặc thực phẩm nhiễm virus<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">2️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" segoe=\"\" ui=\"\" symbol\",sans-serif;mso-bidi-font-family:\"segoe=\"\" symbol\"\"=\"\">⃣</span><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Từ động vật nuôi\r\nsang người: Lợn từng là nguồn lây lớn trong các đợt dịch trước đây.<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">3️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" segoe=\"\" ui=\"\" symbol\",sans-serif;mso-bidi-font-family:\"segoe=\"\" symbol\"\"=\"\">⃣</span><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Lây từ người sang\r\nngười: Qua dịch tiết đường hô hấp; Tiếp xúc gần với người bệnh; Môi trường chăm\r\nsóc y tế không đảm bảo kiểm soát nhiễm khuẩn<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Triệu\r\nchứng nhận biết nhiễm virus Nipah<o:p></o:p></font></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Thời\r\ngian ủ bệnh thường từ 4 – 14 ngày.<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Các\r\ntriệu chứng có thể bao gồm: Sốt cao đột ngột; Đau đầu, đau cơ; Ho, khó thở; Buồn\r\nnôn, mệt mỏi.<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Ở\r\ngiai đoạn nặng:<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">⚠️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Viêm não cấp<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">⚠️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Rối loạn ý thức<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">⚠️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Co giật<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">⚠️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Hôn mê<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Bệnh\r\ncó thể tiến triển nhanh và gây tử vong.<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Vì\r\nsao Nipah nguy hiểm?<o:p></o:p></font></span></b></p><ul style=\"margin-top:0cm\" type=\"disc\">\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Chưa\r\n     có thuốc đặc trị.<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Điều\r\n     trị chủ yếu là hỗ trợ triệu chứng.<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Một\r\n     số bệnh nhân sống sót có thể gặp biến chứng thần kinh lâu dài.<o:p></o:p></font></span></li>\r\n</ul><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">WHO\r\nđánh giá Nipah là một trong những virus cần ưu tiên nghiên cứu vaccine do nguy\r\ncơ đại dịch trong tương lai.<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Người\r\ndân cần làm gì để phòng ngừa?<o:p></o:p></font></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Phòng\r\nkhám Đa khoa Trường Cao đẳng Kon Tum khuyến cáo người dân thực hiện các biện\r\npháp sau:<o:p></o:p></font></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Không ăn trái cây bị dơi cắn hoặc rơi xuống\r\nđất<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Rửa sạch và gọt vỏ trái cây trước khi ăn<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Tránh tiếp xúc động vật hoang dã<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Rửa tay thường xuyên bằng xà phòng<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Đeo khẩu trang khi có triệu chứng hô hấp<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><font color=\"#0000ff\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Đến cơ sở y tế khi có dấu hiệu nghi ngờ\r\nsau khi đi vùng dịch<o:p></o:p></span></font></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Khi\r\nnào cần đi khám ngay?<o:p></o:p></font></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Người\r\ndân nên đến cơ sở y tế nếu:<o:p></o:p></font></span></p><ul style=\"margin-top:0cm\" type=\"disc\">\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Sốt\r\n     cao kéo dài không rõ nguyên nhân<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Có\r\n     triệu chứng thần kinh (lú lẫn, đau đầu dữ dội)<o:p></o:p></font></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Tiếp\r\n     xúc người nghi nhiễm hoặc từng đi vùng có cảnh báo dịch<o:p></o:p></font></span></li>\r\n</ul><p>\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p class=\"MsoNormal\" style=\"text-align: justify; \"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Virus\r\nNipah tuy chưa phổ biến tại Việt Nam nhưng nguy cơ dịch bệnh mới nổi luôn tồn tại.\r\nChủ động cập nhật thông tin chính thống và thực hiện các biện pháp phòng ngừa\r\nlà cách tốt nhất để bảo vệ sức khỏe cá nhân và cộng đồng.</font></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify; \"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><font color=\"#0000ff\">Nguồn tham khảo:&nbsp;</font></span></b><a href=\"https://www.who.int/news-room/fact-sheets/detail/nipah-virus\" target=\"_blank\" style=\"background-color: rgb(255, 255, 255);\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Nipah virus – World Health Organization</span></a>;&nbsp;<a href=\"https://www.cdc.gov/nipah-virus/hcp/clinical-overview/index.html?utm_source=chatgpt.com\" target=\"_blank\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">About Nipah Virus – CDC</span></a></p><p class=\"MsoNormal\" style=\"text-align: justify; \"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p></o:p></span></b></p>', '6fb350927c21473d4e3bdda30bfd15de-Gemini_Generated_Image_inwnv5inwnv5inwn.png', 0, 0, 24, 1, '2026-02-12 16:28:09'),
(9, 'XU HƯỚNG Y TẾ HIỆN ĐẠI: CHĂM SÓC SỨC KHỎE TỪ TUYẾN BAN ĐẦU – GIẢI PHÁP BỀN VỮNG CHO CỘNG ĐỒNG', 'Trong những năm gần đây, hệ thống y tế trên thế giới và tại Việt Nam đang chuyển dịch mạnh mẽ sang mô hình chăm sóc sức khỏe ban đầu (Primary Health Care) — nơi người dân được tiếp cận dịch vụ y tế ngay từ tuyến cơ sở, giúp phòng bệnh, phát hiện sớm và quản lý sức khỏe hiệu quả hơn.  Theo Tổ chức Y tế Thế giới (WHO), chăm sóc sức khỏe ban đầu là nền tảng quan trọng để xây dựng hệ thống y tế bền vững, bảo đảm mọi người dân được chăm sóc sức khỏe toàn diện, liên tục và công bằng.', '<p class=\"MsoNormal\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/df4f0d9068cf3e14d568761bc243116d-ChatGPTImageFeb12202604_38_30PM.png\" style=\"width: 1255px;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><br></span></b></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Vì\r\nsao chăm sóc sức khỏe từ tuyến ban đầu trở thành xu hướng?<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Trước\r\nđây, nhiều người có xu hướng đến bệnh viện tuyến trên ngay khi có vấn đề sức khỏe.\r\nTuy nhiên, mô hình này dẫn đến:<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpFirst\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l2 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Quá tải bệnh viện\r\nlớn<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l2 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Thời gian chờ đợi\r\nkéo dài<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpLast\" style=\"margin-left:54.0pt;mso-add-space:auto;\r\ntext-indent:-18.0pt;mso-list:l2 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Chi phí khám chữa\r\nbệnh tăng cao<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Chăm\r\nsóc sức khỏe ban đầu giúp giải quyết các vấn đề này bằng cách:<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Phát hiện bệnh sớm ngay từ giai đoạn nhẹ<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Điều trị kịp thời, giảm biến chứng<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Theo dõi sức khỏe lâu dài và liên tục<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Giảm chi phí y tế cho người bệnh<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Vai\r\ntrò của cơ sở khám chữa bệnh ban đầu<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Các\r\ncơ sở khám chữa bệnh ban đầu như phòng khám đa khoa đóng vai trò “cửa ngõ” của\r\nhệ thống y tế.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Người\r\ndân có thể:<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpFirst\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l0 level1 lfo2\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Khám các bệnh\r\nthông thường và bệnh mạn tính<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l0 level1 lfo2\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Được tư vấn phòng\r\nbệnh và chăm sóc sức khỏe cá nhân<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l0 level1 lfo2\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Thực hiện xét nghiệm,\r\nchẩn đoán ban đầu<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpLast\" style=\"margin-left:54.0pt;mso-add-space:auto;\r\ntext-indent:-18.0pt;mso-list:l0 level1 lfo2\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Được chuyển tuyến\r\nđúng quy định khi cần điều trị chuyên sâu<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Tại\r\nViệt Nam, chính sách bảo hiểm y tế cũng khuyến khích người dân khám chữa bệnh tại\r\ntuyến ban đầu nhằm nâng cao hiệu quả sử dụng nguồn lực y tế.<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Lợi\r\ních thiết thực đối với người dân<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">????</span><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Khám nhanh – giảm thời gian chờ: Người\r\nbệnh không phải di chuyển xa hoặc chờ đợi tại bệnh viện lớn.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">????</span><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Quản lý sức khỏe lâu dài: Bác sĩ\r\ntuyến ban đầu theo dõi hồ sơ sức khỏe, giúp phát hiện sớm các nguy cơ bệnh lý.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">????</span><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Tiết kiệm chi phí: Khám đúng tuyến\r\nban đầu giúp người dân được hưởng quyền lợi bảo hiểm y tế tối ưu theo quy định.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">????</span><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Tiếp cận dịch vụ y tế gần gũi: Cơ\r\nsở y tế địa phương giúp người dân dễ dàng tiếp cận chăm sóc sức khỏe định kỳ.<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Vai\r\ntrò của Phòng khám Đa khoa Trường Cao đẳng Kon Tum<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Với\r\nđịnh hướng phát triển theo mô hình chăm sóc sức khỏe ban đầu hiện đại, Phòng\r\nkhám Đa khoa Trường Cao đẳng Kon Tum mang đến cho người dân:<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Dịch vụ khám chữa bệnh bảo hiểm y tế thuận\r\ntiện<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Đội ngũ bác sĩ tận tâm, chuyên môn vững<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Trang thiết bị hỗ trợ chẩn đoán hiện đại<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Quy trình khám nhanh, hỗ trợ thủ tục BHYT\r\nđầy đủ<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Phòng\r\nkhám không chỉ điều trị bệnh mà còn đồng hành cùng người dân trong việc quản lý\r\nsức khỏe lâu dài.<o:p></o:p></span></p><p class=\"MsoNormal\"><b><i><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Chăm\r\nsóc sức khỏe hiệu quả không bắt đầu từ khi bệnh nặng, mà từ việc chủ động khám\r\nvà theo dõi sức khỏe ngay từ tuyến ban đầu. Lựa chọn đúng cơ sở khám chữa bệnh\r\nban đầu giúp người dân tiết kiệm thời gian, chi phí và bảo vệ sức khỏe bền vững.<o:p></o:p></span></i></b></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Nguồn tham khảo<span style=\"font-family: &quot;Times New Roman&quot;; font-size: 18px;\">:&nbsp;</span></span></b><a href=\"https://www.who.int/health-topics/primary-health-care\" style=\"text-indent: -18pt; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 18px;\">WHO: Primary Health Care</span></a>;<span style=\"font-family: &quot;Times New Roman&quot;; font-size: 18px;\">&nbsp;&nbsp;</span><a href=\"https://moh.gov.vn\" style=\"text-indent: -18pt; background-color: rgb(255, 255, 255);\"><span style=\"font-family: &quot;Times New Roman&quot;; font-size: 18px;\">Bộ Y tế Việt nam: Định hướng phát triển chăm sóc sức khỏe ban đầu</span></a></p>', '94d90e27f9924c8e5d9b12f4458c5b66-ChatGPTImageFeb12202604_38_30PM.png', 0, 0, 24, 1, '2026-02-12 16:40:02');
INSERT INTO `hicrm_events` (`id`, `event_name`, `event_description`, `event_content`, `event_image`, `event_type`, `event_hot`, `event_user_created`, `event_status`, `event_created_date`) VALUES
(10, 'VÌ SAO NÊN KHÁM SỨC KHỎE ĐỊNH KỲ? CHỦ ĐỘNG HÔM NAY – AN TÂM NGÀY MAI', 'Trong cuộc sống hiện đại, nhiều người chỉ đi khám khi cơ thể đã xuất hiện triệu chứng rõ ràng. Tuy nhiên, thực tế cho thấy phần lớn bệnh lý nguy hiểm như tim mạch, tiểu đường, ung thư hay rối loạn chuyển hóa thường tiến triển âm thầm trong thời gian dài.  Khám sức khỏe định kỳ chính là giải pháp giúp phát hiện sớm các nguy cơ, từ đó bảo vệ sức khỏe một cách chủ động và hiệu quả.', '<p data-start=\"267\" data-end=\"521\"></p><p class=\"MsoNormal\" style=\"text-align: center; \"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/b2c97b52d22e1f0b3771ed1aa668109a-ChatGPTImageFeb12202604_49_54PM.png\" style=\"width: 1255px;\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\"><br></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Trong\r\ncuộc sống hiện đại, nhiều người chỉ đi khám khi cơ thể đã xuất hiện triệu chứng\r\nrõ ràng. Tuy nhiên, thực tế cho thấy phần lớn bệnh lý nguy hiểm như tim mạch,\r\ntiểu đường, ung thư hay rối loạn chuyển hóa thường tiến triển âm thầm trong thời\r\ngian dài.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Khám\r\nsức khỏe định kỳ chính là giải pháp giúp phát hiện sớm các nguy cơ, từ đó bảo vệ\r\nsức khỏe một cách chủ động và hiệu quả.<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Khám\r\nsức khỏe định kỳ là gì?<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Khám\r\nsức khỏe định kỳ là việc kiểm tra tổng thể tình trạng sức khỏe theo kế hoạch định\r\nsẵn (6 tháng hoặc 12 tháng/lần), bao gồm:<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpFirst\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l0 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Khám lâm sàng với\r\nbác sĩ<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l0 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Đo các chỉ số cơ bản\r\n(huyết áp, nhịp tim, BMI…)<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l0 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Xét nghiệm máu, nước\r\ntiểu<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l0 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Siêu âm, chẩn đoán\r\nhình ảnh khi cần thiết<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpLast\" style=\"margin-left:54.0pt;mso-add-space:auto;\r\ntext-indent:-18.0pt;mso-list:l0 level1 lfo1\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Tư vấn chế độ dinh\r\ndưỡng và phòng bệnh<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Lợi\r\ních quan trọng của khám sức khỏe định kỳ<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">????</span><span style=\"font-size:\r\n14.0pt;font-family:&quot;Times New Roman&quot;,serif\"> Phát hiện bệnh sớm – tăng cơ hội\r\nđiều trị hiệu quả<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Nhiều\r\nbệnh lý nguy hiểm không có triệu chứng ở giai đoạn đầu. Khám định kỳ giúp phát\r\nhiện sớm để điều trị kịp thời, giảm biến chứng và chi phí điều trị.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">????</span><span style=\"font-size:\r\n14.0pt;font-family:&quot;Times New Roman&quot;,serif\"> Tiết kiệm chi phí y tế về lâu dài<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Phòng\r\nbệnh và phát hiện sớm luôn tiết kiệm hơn rất nhiều so với điều trị khi bệnh đã\r\nnặng.<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">❤️</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Quản lý sức khỏe cá nhân khoa học<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Thông\r\nqua hồ sơ theo dõi sức khỏe, bác sĩ có thể:<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpFirst\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l1 level1 lfo2\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Nhận diện nguy cơ\r\nbệnh lý<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left:54.0pt;mso-add-space:\r\nauto;text-indent:-18.0pt;mso-list:l1 level1 lfo2\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Điều chỉnh lối sống\r\nphù hợp<o:p></o:p></span></p><p class=\"MsoListParagraphCxSpLast\" style=\"margin-left:54.0pt;mso-add-space:auto;\r\ntext-indent:-18.0pt;mso-list:l1 level1 lfo2\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;font-family:Symbol;mso-fareast-font-family:Symbol;\r\nmso-bidi-font-family:Symbol\">·<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Theo dõi các bệnh\r\nmạn tính hiệu quả<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">????</span><span style=\"font-size:\r\n14.0pt;font-family:&quot;Times New Roman&quot;,serif\"> Giảm lo lắng, nâng cao chất lượng\r\ncuộc sống<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Biết\r\nrõ tình trạng sức khỏe giúp người bệnh an tâm hơn, chủ động chăm sóc bản thân\r\nvà gia đình.<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Ai\r\nnên khám sức khỏe định kỳ?<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Người trưởng thành từ 18 tuổi trở lên<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Người làm việc áp lực, ít vận động<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Người có bệnh nền hoặc tiền sử gia đình mắc\r\nbệnh mạn tính<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Người trên 40 tuổi nên kiểm tra sức khỏe\r\ntoàn diện mỗi năm<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Khám\r\nsức khỏe định kỳ tại Phòng khám Đa khoa Trường Cao đẳng Kon Tum<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Với\r\nđịnh hướng chăm sóc sức khỏe từ tuyến ban đầu, Phòng khám mang đến:<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Quy trình khám nhanh chóng, thuận tiện<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Đội ngũ bác sĩ tận tâm, tư vấn kỹ lưỡng<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Trang thiết bị hỗ trợ chẩn đoán hiện đại<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">✅</span><span style=\"font-size:14.0pt;\r\nfont-family:&quot;Times New Roman&quot;,serif\"> Hỗ trợ khám bảo hiểm y tế theo quy định<o:p></o:p></span></p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Times New Roman&quot;,serif\">Chăm\r\nsóc sức khỏe không nên bắt đầu khi cơ thể lên tiếng cảnh báo, mà nên bắt đầu từ\r\nviệc chủ động kiểm tra định kỳ.<o:p></o:p></span></p><p>\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:&quot;Segoe UI Emoji&quot;,sans-serif;\r\nmso-bidi-font-family:&quot;Segoe UI Emoji&quot;\">????</span><span style=\"font-size:\r\n14.0pt;font-family:&quot;Times New Roman&quot;,serif\"> <b><i>Khám định kỳ không chỉ là kiểm\r\ntra bệnh — mà là đầu tư cho tương lai khỏe mạnh của chính bạn và những người\r\nthân yêu.</i></b></span></p><p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:&quot;Times New Roman&quot;,serif\"><b>Hãy gọi ngay đến Hotline 02606 558 568 để được tư vấn tận tình!</b></span></p><p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:&quot;Times New Roman&quot;,serif\"><b><i><o:p></o:p></i></b></span></p>', '3098c71a4ec69e495f96e522c250c198-ChatGPTImageFeb12202604_49_54PM.png', 0, 0, 24, 1, '2026-02-12 16:53:03'),
(11, 'CHÚC MỪNG NGÀY THẦY THUỐC VIỆT NAM 27/02', 'Nhân ngày truyền thống của ngành Y, Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum xin gửi lời tri ân sâu sắc đến đội ngũ bác sĩ, dược sĩ và nhân viên y tế đã luôn tận tâm chăm sóc, bảo vệ sức khỏe cộng đồng.', '<p data-start=\"164\" data-end=\"404\">Nhân Ngày Thầy thuốc Việt Nam 27/02, Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum trân trọng gửi lời tri ân sâu sắc đến đội ngũ bác sĩ, dược sĩ và nhân viên y tế – những người đã và đang lặng thầm cống hiến vì sức khỏe cộng đồng.</p><p data-start=\"406\" data-end=\"641\">Nghề y không chỉ là một nghề nghiệp, mà còn là sứ mệnh cao quý. Mỗi sự tận tâm trong tư vấn, mỗi nỗ lực trong điều trị, mỗi lần lắng nghe và thấu hiểu người bệnh… đều góp phần thắp sáng niềm tin và lan tỏa giá trị nhân văn của ngành Y.</p><p data-start=\"643\" data-end=\"798\">Kính chúc quý Thầy thuốc luôn mạnh khỏe, giữ vững y đức, tiếp tục vững tâm với nghề và đồng hành bền bỉ trên hành trình chăm sóc, bảo vệ sức khỏe nhân dân.</p><p>\r\n\r\n\r\n</p><p data-start=\"800\" data-end=\"836\">Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum - Hết lòng vì sức khỏe nhân dân!!!</p><p data-start=\"800\" data-end=\"836\" style=\"text-align: center;\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/09df68cdf9283a01573d71cf95192795-ChatGPTImageFeb27202609_05_13AM.png\" style=\"width: 1536px;\"><br></p>', '937820a6c8878f696cffa4be5e1e706b-ChatGPTImageFeb27202609_05_36AM.png', 0, 0, 24, 1, '2026-02-27 09:25:34'),
(12, 'Một số hiểu lầm phổ biến về khám chữa bệnh Bảo hiểm y tế (BHYT)', 'một số hiểu lầm phổ biến về khám chữa bệnh bằng Bảo hiểm y tế (BHYT)', '<h2 data-section-id=\"g9oo6k\" data-start=\"248\" data-end=\"290\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/58e5ed254820b4412e4bc3a1183b0140-HiulmKCBBHYT.jpg\" style=\"width: 1360.17px;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">1. Nghĩ rằng khám BHYT phải chờ rất lâu</span></h2><p data-start=\"291\" data-end=\"372\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Nhiều người cho rằng khám bằng BHYT sẽ phải xếp hàng lâu hơn so với khám dịch vụ.</span></p><p data-start=\"374\" data-end=\"569\"><strong data-start=\"374\" data-end=\"386\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Thực tế:</span></strong><br data-start=\"386\" data-end=\"389\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\nTại nhiều cơ sở y tế hiện nay, quy trình khám BHYT đã được </span><strong data-start=\"448\" data-end=\"470\">cải tiến và số hóa</strong><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">, thời gian khám không chênh lệch nhiều so với khám dịch vụ nếu người bệnh chuẩn bị đầy đủ giấy tờ.</span></p><hr data-start=\"571\" data-end=\"574\"><h2 data-section-id=\"evwcnv\" data-start=\"576\" data-end=\"620\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">2. Cho rằng khám BHYT thì thuốc không tốt</span></h2><p data-start=\"621\" data-end=\"698\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Một số người nghĩ rằng thuốc được cấp theo BHYT là thuốc rẻ, chất lượng thấp.</span></p><p data-start=\"700\" data-end=\"868\"><strong data-start=\"700\" data-end=\"712\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Thực tế:</span></strong><br data-start=\"712\" data-end=\"715\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\nThuốc BHYT được cấp </span><strong data-start=\"735\" data-end=\"772\">theo danh mục do Bộ Y tế quy định</strong><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">, phải đảm bảo tiêu chuẩn chất lượng, nguồn gốc rõ ràng và được sử dụng rộng rãi trong điều trị.</span></p><hr data-start=\"870\" data-end=\"873\"><h2 data-section-id=\"zzs795\" data-start=\"875\" data-end=\"924\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">3. Nghĩ rằng khám BHYT là “miễn phí hoàn toàn”</span></h2><p data-start=\"925\" data-end=\"1002\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Nhiều người hiểu nhầm rằng có BHYT thì </span><strong data-start=\"964\" data-end=\"1001\"><span style=\"font-size: 18px;\">không phải trả bất kỳ chi phí nào</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">.</span></p><p data-start=\"1004\" data-end=\"1066\"><strong data-start=\"1004\" data-end=\"1016\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Thực tế:</span></strong><br data-start=\"1016\" data-end=\"1019\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\">BHYT </span></span><strong data-start=\"1024\" data-end=\"1054\"><span style=\"font-size: 18px;\">chỉ chi trả theo mức hưởng</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">, thường là:</span></p><ul data-start=\"1067\" data-end=\"1170\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"e6khdn\" data-start=\"1067\" data-end=\"1097\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"1069\" data-end=\"1097\">80% chi phí khám chữa bệnh</p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"1kaykeh\" data-start=\"1098\" data-end=\"1130\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"1100\" data-end=\"1130\">95% đối với một số đối tượng</p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"16tfjgq\" data-start=\"1131\" data-end=\"1170\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"1133\" data-end=\"1170\">100% đối với các đối tượng đặc biệt</p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></ul><p data-start=\"1172\" data-end=\"1287\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Người bệnh có thể vẫn phải </span><strong data-start=\"1199\" data-end=\"1232\"><span style=\"font-size: 18px;\">đồng chi trả một phần chi phí</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\"> hoặc trả thêm nếu sử dụng dịch vụ ngoài danh mục BHYT.</span></p><hr data-start=\"1289\" data-end=\"1292\"><h2 data-section-id=\"1pwdorv\" data-start=\"1294\" data-end=\"1350\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">4. Nghĩ rằng khám trái tuyến sẽ không được thanh toán</span></h2><p data-start=\"1351\" data-end=\"1437\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Nhiều người lo rằng đi khám không đúng nơi đăng ký ban đầu thì </span><strong data-start=\"1414\" data-end=\"1436\"><span style=\"font-size: 18px;\">BHYT không chi trả</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">.</span></p><p data-start=\"1439\" data-end=\"1477\"><strong data-start=\"1439\" data-end=\"1451\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Thực tế:</span></strong><br data-start=\"1451\" data-end=\"1454\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\">Theo quy định hiện nay:</span></span></p><ul data-start=\"1478\" data-end=\"1625\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"v23ie2\" data-start=\"1478\" data-end=\"1529\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"1480\" data-end=\"1529\">Khám <strong data-start=\"1485\" data-end=\"1499\">đúng tuyến</strong>: được hưởng đầy đủ quyền lợi.</p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"9ht7qy\" data-start=\"1530\" data-end=\"1625\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"1532\" data-end=\"1625\">Khám <strong data-start=\"1537\" data-end=\"1551\">trái tuyến</strong>: vẫn được BHYT thanh toán <strong data-start=\"1578\" data-end=\"1598\">một phần chi phí</strong>, tùy theo tuyến bệnh viện.</p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></ul><hr data-start=\"1627\" data-end=\"1630\"><h2 data-section-id=\"1beef3x\" data-start=\"1632\" data-end=\"1681\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">5. Nghĩ rằng phải nằm viện mới được hưởng BHYT</span></h2><p data-start=\"1682\" data-end=\"1738\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Một số người nghĩ BHYT chỉ áp dụng khi điều trị nội trú.</span></p><p data-start=\"1740\" data-end=\"1872\"><strong data-start=\"1740\" data-end=\"1752\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Thực tế:</span></strong><br data-start=\"1752\" data-end=\"1755\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\">BHYT chi trả cho </span></span><strong data-start=\"1772\" data-end=\"1813\"><span style=\"font-size: 18px;\">cả khám ngoại trú và điều trị nội trú</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">, miễn là dịch vụ nằm trong danh mục được bảo hiểm chi trả.</span></p><hr data-start=\"1874\" data-end=\"1877\"><h2 data-section-id=\"gtv3wq\" data-start=\"1879\" data-end=\"1925\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">6. Nghĩ rằng thủ tục khám BHYT rất phức tạp</span></h2><p data-start=\"1926\" data-end=\"1975\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Nhiều người ngại khám BHYT vì sợ thủ tục rườm rà.</span></p><p data-start=\"1977\" data-end=\"2042\"><strong data-start=\"1977\" data-end=\"1989\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Thực tế:</span></strong><br data-start=\"1989\" data-end=\"1992\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\">Hiện nay thủ tục rất đơn giản, người bệnh chỉ cần:</span></span></p><ul data-start=\"2043\" data-end=\"2136\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"1h27vcr\" data-start=\"2043\" data-end=\"2082\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"2045\" data-end=\"2082\"><strong data-start=\"2045\" data-end=\"2080\"><span style=\"font-size: 18px;\">Thẻ BHYT (hoặc mã BHYT điện tử)</span></strong></p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"1b8jgwi\" data-start=\"2083\" data-end=\"2136\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"2085\" data-end=\"2136\"><strong data-start=\"2085\" data-end=\"2112\"><span style=\"font-size: 18px;\">Giấy tờ tùy thân có ảnh</span></strong><span style=\"font-size: 18px;\"> (CCCD hoặc tương đương)</span></p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></ul><hr data-start=\"2138\" data-end=\"2141\"><h2 data-section-id=\"o65ykr\" data-start=\"2143\" data-end=\"2195\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">7. Nghĩ rằng khám BHYT thì không được chọn bác sĩ</span></h2><p data-start=\"2196\" data-end=\"2255\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Một số người cho rằng khám BHYT không được lựa chọn bác sĩ.</span></p><p><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</span></p><p data-start=\"2257\" data-end=\"2404\"><strong data-start=\"2257\" data-end=\"2269\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Thực tế:</span></strong><br data-start=\"2269\" data-end=\"2272\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\">Tùy quy định của từng cơ sở y tế, người bệnh </span></span><strong data-start=\"2317\" data-end=\"2356\"><span style=\"font-size: 18px;\">vẫn được khám bởi bác sĩ chuyên môn</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">, đảm bảo quy trình chuyên môn như khám dịch vụ.</span></p>', '152ebc14155da633e7e3949e6e92d983-HiulmKCBBHYT.jpg', 0, 0, 24, 1, '2026-03-09 19:22:44');
INSERT INTO `hicrm_events` (`id`, `event_name`, `event_description`, `event_content`, `event_image`, `event_type`, `event_hot`, `event_user_created`, `event_status`, `event_created_date`) VALUES
(13, 'CẢM CÚM, HO, NGHẸT MŨI MÙA CHUYỂN MÙA: NHẬN BIẾT SỚM VÀ XỬ LÝ ĐÚNG CÁCH', 'Thời điểm giao mùa, thời tiết thay đổi thất thường là lúc các bệnh cảm cúm, ho, nghẹt mũi, đau đầu, sổ mũi xuất hiện nhiều hơn. Đây là những bệnh lý thường gặp ở cả người lớn và trẻ em, gây khó chịu, ảnh hưởng đến sinh hoạt và công việc hàng ngày.', '<p><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/58f2b8cf8f47b02967950db166f711b6-camcum1.jpg\" style=\"width: 50%;\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/e6294e656e75572f011a58880617022f-camcum2.jpg\" style=\"width: 50%;\"></p><p><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"><span style=\"font-size: 18px;\">Thời điểm giao mùa, thời tiết thay đổi thất thường là lúc các bệnh</span><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"> </span></span><strong data-start=\"185\" data-end=\"228\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">cảm cúm, ho, nghẹt mũi, đau đầu, sổ mũ<span style=\"font-size: 18px;\">i</span></span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"> xuất hiện nhiều hơn. Đây là những bệnh lý thường gặp ở cả người lớn và trẻ em, gây khó chịu, ảnh hưởng đến sinh hoạt và công việc hàng ngày.</span></span></p><p><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Việc </span><span data-start=\"376\" data-end=\"428\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">nhận biết sớm các triệu chứng và xử lý đúng cách</span></span><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"> <span style=\"font-size: 18px;\">sẽ giúp người bệnh nhanh chóng hồi phục và hạn chế biến chứng.</span></span></p><h2 data-section-id=\"16pzrjt\" data-start=\"498\" data-end=\"535\"><span style=\"font-size: 24px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 24px;\"=\"\" 18px;\"=\"\">1. Dấu hiệu thường gặp của cảm cúm</span></h2><p data-start=\"537\" data-end=\"625\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Khi bị cảm cúm hoặc cảm lạnh thông thường, người bệnh có thể gặp một số triệu chứng như:</span></p><ul data-start=\"627\" data-end=\"768\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"s1x1fs\" data-start=\"627\" data-end=\"660\"><p data-start=\"629\" data-end=\"660\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Hắt hơi, sổ mũi, nghẹt mũi</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"cqt0i4\" data-start=\"661\" data-end=\"690\"><p data-start=\"663\" data-end=\"690\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Ho khan hoặc ho có đờm</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"8w9itd\" data-start=\"691\" data-end=\"715\"><p data-start=\"693\" data-end=\"715\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Đau đầu, đau họng</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"14ywdlg\" data-start=\"716\" data-end=\"748\"><p data-start=\"718\" data-end=\"748\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Cơ thể mệt mỏi, u</span><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">ể oải</span></p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1jdq7yr\" data-start=\"749\" data-end=\"768\"><span style=\"font-size: 18px;\">\r\n</span><p data-start=\"751\" data-end=\"768\"><span style=\"font-size: 18px;\">Có thể sốt nhẹ</span></p><span style=\"font-size: 18px;\">\r\n</span></li><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span></ul><p data-start=\"770\" data-end=\"958\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"><span style=\"font-size: 18px;\">Thông thường, các triệu chứng này</span> </span><strong data-start=\"804\" data-end=\"860\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">xuất hiện sau khi cơ thể bị nhiễm virus đường hô hấp</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">, đặc biệt khi sức đề kháng suy giảm do thời tiết lạnh, mưa nhiều hoặc thay đổi nhiệt độ đột ngột.</span></p><hr data-start=\"960\" data-end=\"963\"><h2 data-section-id=\"lq6t16\" data-start=\"965\" data-end=\"1007\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 24px;\"=\"\" 18px;\"=\"\">2. Vì sao mùa chuyển mùa dễ bị cảm cúm?</span></h2><p data-start=\"1009\" data-end=\"1073\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Có nhiều nguyên nhân khiến cảm cúm gia tăng trong thời điểm này:</span></p><ul data-start=\"1075\" data-end=\"1350\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"iu251q\" data-start=\"1075\" data-end=\"1144\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"1077\" data-end=\"1144\"><strong data-start=\"1077\" data-end=\"1111\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Thời tiết thay đổi thất thường</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"> làm cơ thể chưa kịp thích nghi</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"68e4jc\" data-start=\"1145\" data-end=\"1215\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"1147\" data-end=\"1215\"><strong data-start=\"1147\" data-end=\"1172\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Sức đề kháng suy giảm</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"> do thiếu ngủ, căng thẳng, dinh dưỡng kém</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1i6frcf\" data-start=\"1216\" data-end=\"1285\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"1218\" data-end=\"1285\"><strong data-start=\"1218\" data-end=\"1243\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Môi trường đông người</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"> khiến virus dễ lây lan qua đường hô hấp</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1elgny8\" data-start=\"1286\" data-end=\"1350\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"1288\" data-end=\"1350\"><strong data-start=\"1288\" data-end=\"1323\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Thói quen sinh hoạt chưa hợp lý</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">, ít vận động, uống ít nước</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span></ul><p data-start=\"1352\" data-end=\"1436\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Do đó, việc </span><strong data-start=\"1364\" data-end=\"1417\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">chủ động bảo vệ sức khỏe trong giai đoạn giao mùa</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"> là rất quan trọng.</span></p><hr data-start=\"1438\" data-end=\"1441\"><h2 data-section-id=\"19xsbq8\" data-start=\"1443\" data-end=\"1486\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 24px;\"=\"\" 18px;\"=\"\">3. Cách xử lý khi có triệu chứng cảm cúm</span></h2><p data-start=\"1488\" data-end=\"1543\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">K</span><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">hi xuất hiện các dấu hiệu cảm cúm nhẹ, người bệnh nên:</span></span></p><p data-start=\"1545\" data-end=\"1620\"><strong data-start=\"1545\" data-end=\"1565\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Nghỉ ngơi hợp lý</span></strong><br data-start=\"1565\" data-end=\"1568\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">Cơ thể cần thời gian để phục hồi và chống lại virus.</span></span></p><p data-start=\"1622\" data-end=\"1718\"><strong data-start=\"1622\" data-end=\"1641\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">Uống nhiều nước</span></strong><br data-start=\"1641\" data-end=\"1644\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Nước ấm giúp làm dịu cổ họng, giảm nghẹt mũi và hỗ trợ quá trình hồi phục.</span></span></p><p data-start=\"1720\" data-end=\"1778\"><strong data-start=\"1720\" data-end=\"1737\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">Giữ ấm cơ thể</span></strong><br data-start=\"1737\" data-end=\"1740\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Đặc biệt là vùng cổ, ngực và bàn chân.</span></span></p><p data-start=\"1780\" data-end=\"1861\"><strong data-start=\"1780\" data-end=\"1812\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">Sử dụng thuốc đúng hướng dẫn</span></strong><br data-start=\"1812\" data-end=\"1815\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n<span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Một số thuốc có thể giúp giảm triệu chứng như:</span></span></p><ul data-start=\"1863\" data-end=\"1989\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"aocso6\" data-start=\"1863\" data-end=\"1893\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><p data-start=\"1865\" data-end=\"1893\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">Thuốc hạ sốt, giảm đau đầu</span></p><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"lysl4l\" data-start=\"1894\" data-end=\"1926\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"1896\" data-end=\"1926\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Thuốc giảm nghẹt mũi, sổ mũi</span></p><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"yvcglq\" data-start=\"1927\" data-end=\"1954\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"1929\" data-end=\"1954\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Thuốc giảm ho, dịu họng</span></p><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span><li data-section-id=\"3phftc\" data-start=\"1955\" data-end=\"1989\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"1957\" data-end=\"1989\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Vitamin giúp tăng sức đề kháng</span></p><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></ul><p data-start=\"1991\" data-end=\"2093\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">Việc sử dụng thuốc nên </span><strong data-start=\"2014\" data-end=\"2061\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\">được tư vấn bởi dược sĩ hoặc nhân viên y tế</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\" roman\";\"=\"\"> để đảm bảo an toàn và hiệu quả.</span></p><hr data-start=\"2095\" data-end=\"2098\"><h2 data-section-id=\"193wj3q\" data-start=\"2100\" data-end=\"2126\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 24px;\"=\"\">4. Khi nào cần đi khám?</span></h2><p data-start=\"2128\" data-end=\"2182\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Người bệnh nên đến cơ sở y tế khi có các dấu hiệu sau:</span></p><ul data-start=\"2184\" data-end=\"2287\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"13txofn\" data-start=\"2184\" data-end=\"2203\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2186\" data-end=\"2203\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Sốt cao kéo dài</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"8lod6z\" data-start=\"2204\" data-end=\"2225\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2206\" data-end=\"2225\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Ho nhiều, khó thở</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1iy69qo\" data-start=\"2226\" data-end=\"2244\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2228\" data-end=\"2244\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Đau đầu dữ dội</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"nvv81p\" data-start=\"2245\" data-end=\"2287\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2247\" data-end=\"2287\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Triệu chứng không cải thiện sau vài ngày</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span></ul><p data-start=\"2289\" data-end=\"2384\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Việc thăm khám sớm giúp bác sĩ đánh giá chính xác tình trạng bệnh và có hướng điều trị phù hợp.</span></p><hr data-start=\"2386\" data-end=\"2389\"><h2 data-section-id=\"wutvgq\" data-start=\"2391\" data-end=\"2424\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 24px;\"=\"\">5. Chủ động phòng ngừa cảm cúm</span></h2><p data-start=\"2426\" data-end=\"2490\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Để hạn chế nguy cơ mắc bệnh trong mùa chuyển mùa, mỗi người nên:</span></p><ul data-start=\"2492\" data-end=\"2677\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1b6hdpk\" data-start=\"2492\" data-end=\"2536\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2494\" data-end=\"2536\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Tăng cường dinh dưỡng và bổ sung vitamin</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1xu0wz8\" data-start=\"2537\" data-end=\"2565\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2539\" data-end=\"2565\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Tập thể dục thường xuyên</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1vk5ns\" data-start=\"2566\" data-end=\"2600\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2568\" data-end=\"2600\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Rửa tay và giữ vệ sinh cá nhân</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"j7xru6\" data-start=\"2601\" data-end=\"2642\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2603\" data-end=\"2642\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Đeo khẩu trang khi đến nơi đông người</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span><li data-section-id=\"1abxpur\" data-start=\"2643\" data-end=\"2677\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span><p data-start=\"2645\" data-end=\"2677\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Giữ ấm cơ thể khi thời tiết lạnh</span></p><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">\r\n</span></li><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">\r\n</span></ul><hr data-start=\"2679\" data-end=\"2682\"><p data-start=\"2684\" data-end=\"2848\"><strong data-start=\"2687\" data-end=\"2724\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";\"=\"\">Nhà thuốc Trường Cao đẳng Kon Tum</span></strong><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 24px;\"=\"\" 18px;\"=\"\"> luôn sẵn sàng tư vấn các giải pháp chăm sóc sức khỏe và hỗ trợ người dân lựa chọn thuốc phù hợp khi có triệu chứng cảm cúm.</span></p><p data-start=\"2850\" data-end=\"2947\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\"><span style=\"font-size: 18px; font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Địa chỉ:347 Bà Triệu, phường Kon Tum, tỉnh Quảng Ngãi.</span></span></span><br data-start=\"2895\" data-end=\"2898\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><span style=\"font-size: 18px;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\">Liên hệ: 0839 995 775</span></span></span></p><p data-start=\"2850\" data-end=\"2947\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\";=\"\" font-size:=\"\" 18px;\"=\"\">Nếu có các dấu hiệu cảm cúm, ho, nghẹt mũi hoặc cần tư vấn thuốc, hãy liên hệ với dược sĩ để được hỗ trợ kịp thời.</span></p>', 'dccc7912387667588f9c24f2a5a4256c-camcum1.jpg', 0, 0, 24, 1, '2026-03-16 20:37:04'),
(14, 'CHÚC MỪNG NGÀY QUỐC TẾ HẠNH PHÚC 20/3', 'Hạnh phúc lớn nhất là sự bình an trong tâm hồn và một cơ thể khỏe mạnh.', '<p data-path-to-node=\"3\" style=\"text-align: center; \"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/d0edcce1b6fdc5cf2f83c8a3b976b76b-Gemini_Generated_Image_7nrimo7nrimo7nri.png\" style=\"width: 1079.78px;\"><b data-path-to-node=\"3\" data-index-in-node=\"0\"><br></b></p><p data-path-to-node=\"3\" style=\"text-align: center; \"><b data-path-to-node=\"3\" data-index-in-node=\"0\">\"Hạnh phúc lớn nhất là sự bình an trong tâm hồn và một cơ thể khỏe mạnh.\"</b></p><p data-path-to-node=\"4\">✨ Nhân ngày Quốc tế Hạnh phúc 20/3, <b data-path-to-node=\"4\" data-index-in-node=\"36\">Phòng khám Đa khoa &amp; Nhà thuốc Trường Cao đẳng Kon Tum</b> xin gửi đến quý khách hàng, đội ngũ y bác sĩ, các em sinh viên và toàn thể cộng đồng những lời chúc tốt đẹp nhất!</p><p data-path-to-node=\"5\">Hạnh phúc không đâu xa xôi, nó nằm ở nụ cười của người thân, ở sự an tâm khi sức khỏe được chăm sóc tận tình. Tại đây, chúng tôi luôn nỗ lực mỗi ngày để mang lại sự an tâm đó cho bạn thông qua:</p><ul data-path-to-node=\"6\"><li><p data-path-to-node=\"6,0,0\">❤️ Dịch vụ thăm khám tận tâm, chuyên nghiệp.</p></li><li><p data-path-to-node=\"6,1,0\">❤️ Nguồn thuốc chất lượng, tư vấn kỹ lưỡng.</p></li><li><p data-path-to-node=\"6,2,0\">❤️ Sự thấu hiểu và sẻ chia như người thân trong gia đình.</p></li></ul><p data-path-to-node=\"7\">Hãy cùng chúng tôi lan tỏa thông điệp yêu thương bằng cách dành thời gian chăm sóc sức khỏe cho bản thân và những người thân yêu trong ngày hôm nay nhé!</p><hr data-path-to-node=\"8\"><p data-path-to-node=\"9\"><b data-path-to-node=\"9\" data-index-in-node=\"3\">PHÒNG KHÁM ĐA KHOA &amp; NHÀ THUỐC TRƯỜNG CAO ĐẲNG KON TUM</b></p><p data-path-to-node=\"9\"><b data-path-to-node=\"9\" data-index-in-node=\"61\">Địa chỉ:</b> 347 Bà Triệu, phường Kon Tum, tỉnh Quảng Ngãi</p><p data-path-to-node=\"9\"><b data-path-to-node=\"9\" data-index-in-node=\"119\">Hotline:</b> 0839 995 775</p><p data-path-to-node=\"9\"><b data-path-to-node=\"9\" data-index-in-node=\"144\">Website:</b> <response-element class=\"\" ng-version=\"0.0.0-PLACEHOLDER\"><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><link-block _nghost-ng-c2319073353=\"\" class=\"ng-star-inserted\"><!----><!----><a _ngcontent-ng-c2319073353=\"\" target=\"_blank\" rel=\"noopener\" externallink=\"\" _nghost-ng-c2809470641=\"\" jslog=\"197247;track:generic_click,impression,attention;BardVeMetadataKey:[[\" r_7156d25ee294bf5a\",\"c_0aa8653590bdced4\",null,\"rc_cc550950d444ed97\",null,null,\"vi\",null,1,null,null,1,0]]\"=\"\" href=\"https://www.google.com/search?q=http://phongkhamdakhoacdkontum.com.vn\" class=\"ng-star-inserted\" data-hveid=\"0\" decode-data-ved=\"1\" data-ved=\"0CAAQ_4QMahgKEwiDyK7Lo62TAxUAAAAAHQAAAAAQ9gE\">phongkhamdakhoacdkontum.com.vn</a><!----></link-block><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----><!----></response-element></p><p data-path-to-node=\"10\">#NgayQuocTeHanhPhuc #InternationalDayOfHappiness #20Thang3 #PhongKhamDaKhoa #NhaThuoc #CaoDangKonTum #SucKhoeLaHanhPhuc</p>', '49a49dff8a2fd2c1653e32d7586e3eac-Gemini_Generated_Image_uliwrluliwrluliw.png', 0, 0, 24, 1, '2026-03-20 08:33:49'),
(15, '⚠️CẢNH BÁO DỊCH VIÊM NÃO MÔ CẦU – CHỦ ĐỘNG PHÒNG NGỪA ĐỂ BẢO VỆ SỨC KHỎE', 'Viêm não mô cầu là bệnh truyền nhiễm cấp tính nguy hiểm, có thể tiến triển nhanh và gây biến chứng nặng nếu không được phát hiện, điều trị kịp thời. Trong bối cảnh thời tiết giao mùa, nguy cơ lây lan bệnh có xu hướng gia tăng, người dân cần nâng cao ý thức phòng ngừa.', '<ul data-start=\"725\" data-end=\"808\"><li data-section-id=\"1nqajmw\" data-start=\"765\" data-end=\"808\"><p class=\"MsoNormal\"><span style=\"font-size: 18px;\"><i>Viêm não mô cầu là bệnh truyền nhiễm cấp tính nguy hiểm, có\r\nthể tiến triển nhanh và gây biến chứng nặng nếu không được phát hiện, điều trị\r\nkịp thời. Trong bối cảnh thời tiết giao mùa, nguy cơ lây lan bệnh có xu hướng\r\ngia tăng, người dân cần nâng cao ý thức phòng ngừa.</i></span><o:p></o:p></p><div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">\r\n\r\n<hr size=\"1\" width=\"100%\" align=\"center\">\r\n\r\n</div><p class=\"MsoNormal\"><span style=\"font-size: 18px;\"><b>Viêm não mô cầu là gì?</b></span><o:p></o:p></p><p class=\"MsoNormal\">Viêm não mô cầu là bệnh do vi khuẩn&nbsp;<i>Neisseria\r\nmeningitidis</i>&nbsp;gây ra, lây truyền qua đường hô hấp, tiếp xúc gần với người\r\nbệnh hoặc người lành mang vi khuẩn.<o:p></o:p></p><p class=\"MsoNormal\">Bệnh có thể gây:<o:p></o:p></p><p class=\"MsoNormal\">- Viêm màng não<o:p></o:p></p><p class=\"MsoNormal\">- Nhiễm khuẩn huyết<o:p></o:p></p><p class=\"MsoNormal\">- Sốc nhiễm trùng nguy hiểm đến tính mạng<o:p></o:p></p><div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><div style=\"text-align: center;\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/9a8746131de835ce0b16180875a024d4-vikhun3.jpg\" style=\"width: 25%;\">&nbsp;&nbsp;<img src=\"https://phongkhamcdkontum.com.vn/uploads/images/5d4aac80c823dc1d5d9288a2acfef670-vikhun2.jpg\" style=\"width: 25%;\"></div>\r\n\r\n<hr size=\"1\" width=\"100%\" align=\"center\">\r\n\r\n</div><p class=\"MsoNormal\"><span style=\"font-size: 18px;\"><b>Dấu hiệu nhận biết\r\nsớm</b></span><o:p></o:p></p><p class=\"MsoNormal\">Người bệnh có thể xuất hiện các triệu chứng:<o:p></o:p></p><p class=\"MsoNormal\">- Sốt cao đột ngột<o:p></o:p></p><p class=\"MsoNormal\">- Đau đầu dữ dội<o:p></o:p></p><p class=\"MsoNormal\">- Buồn nôn, nôn<o:p></o:p></p><p class=\"MsoNormal\">- Cứng cổ, khó cúi đầu<o:p></o:p></p><p class=\"MsoNormal\">- Xuất hiện ban xuất huyết trên da<o:p></o:p></p><p class=\"MsoNormal\">- Trẻ nhỏ có thể quấy khóc, bỏ bú, ngủ li bì<o:p></o:p></p><p class=\"MsoNormal\">Khi có các dấu hiệu\r\ntrên, cần đến cơ sở y tế ngay để được thăm khám và xử trí kịp thời.<o:p></o:p></p><div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">\r\n\r\n<img src=\"https://phongkhamcdkontum.com.vn/uploads/images/2220e830c8741b1493b5c485186ae9b3-bieuhien2.jpg\" style=\"width: 50%;\"><hr size=\"1\" width=\"100%\" align=\"center\">\r\n\r\n</div><p class=\"MsoNormal\"><b><span style=\"font-family: \" segoe=\"\" ui=\"\" emoji\",=\"\" sans-serif;=\"\" font-size:=\"\" 18px;\"=\"\">⚠️</span><span style=\"font-size: 18px;\"> Đối tượng nguy cơ cao</span></b><o:p></o:p></p><p class=\"MsoNormal\">-&nbsp;Trẻ em, học sinh, sinh viên sống tập thể<o:p></o:p></p><p class=\"MsoNormal\">-&nbsp;Người có hệ miễn dịch yếu<o:p></o:p></p><p class=\"MsoNormal\">-&nbsp;Người sống trong môi trường đông người, kín<o:p></o:p></p><p class=\"MsoNormal\">-&nbsp;Người chưa được tiêm vắc xin phòng bệnh<o:p></o:p></p><div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">\r\n\r\n<hr size=\"1\" width=\"100%\" align=\"center\">\r\n\r\n</div><p class=\"MsoNormal\"><span style=\"font-size: 18px;\"><b>Cách phòng ngừa hiệu quả</b></span><o:p></o:p></p><p class=\"MsoNormal\">- Tiêm vắc xin phòng viêm não mô cầu theo khuyến cáo<o:p></o:p></p><p class=\"MsoNormal\">- Đeo khẩu trang khi đến nơi đông người<o:p></o:p></p><p class=\"MsoNormal\">- Rửa tay thường xuyên bằng xà phòng hoặc dung dịch sát khuẩn<o:p></o:p></p><p class=\"MsoNormal\">- Giữ vệ sinh cá nhân, môi trường sống<o:p></o:p></p><p class=\"MsoNormal\">- Hạn chế tiếp xúc gần với người có dấu hiệu nghi nhiễm bệnh<o:p></o:p></p><div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">\r\n\r\n<img src=\"https://phongkhamcdkontum.com.vn/uploads/images/195016a4612f890ed6e73ac9b28cef8c-ratay1.jpg\" style=\"width: 25%;\">&nbsp;<img src=\"https://phongkhamcdkontum.com.vn/uploads/images/6e3cf1a3790fa234cc19a9834134c4dd-tiemvacxin1.jpg\" style=\"width: 25%;\"><hr size=\"1\" width=\"100%\" align=\"center\">\r\n\r\n</div><p class=\"MsoNormal\"><span style=\"font-size: 18px;\"><b>Thăm khám và tư vấn tại\r\nPhòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum:</b></span><o:p></o:p></p><p class=\"MsoNormal\">- Khám, tư vấn các bệnh truyền nhiễm<o:p></o:p></p><p class=\"MsoNormal\">- Hướng dẫn phòng ngừa và theo dõi sức khỏe<o:p></o:p></p><p class=\"MsoNormal\">- Hỗ trợ xét nghiệm, chẩn đoán khi cần thiết<o:p></o:p></p><p class=\"MsoNormal\">- Cung cấp thuốc điều trị theo đúng chỉ định<o:p></o:p></p><div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\">\r\n\r\n<hr size=\"1\" width=\"100%\" align=\"center\">\r\n\r\n</div><p class=\"MsoNormal\"><b>Liên hệ ngay khi cần hỗ\r\ntrợ</b><o:p></o:p></p><p class=\"MsoNormal\"><b>Phòng khám Đa khoa và\r\nNhà thuốc Trường Cao đẳng Kon Tum</b><o:p></o:p></p><p class=\"MsoNormal\">Hotline:&nbsp;<b>0839\r\n995 775</b><o:p></o:p></p><p class=\"MsoNormal\"><b>Chủ động phòng\r\nbệnh – Bảo vệ sức khỏe bản thân và cộng đồng</b><o:p></o:p></p><p data-start=\"245\" data-end=\"513\">\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p class=\"MsoNormal\"><o:p>Nguồn tham khảo:&nbsp;</o:p><a href=\"https://vncdc.gov.vn\" target=\"_blank\">https://vncdc.gov.vn</a>;<a data-start=\"408\" data-end=\"428\" rel=\"noopener\" target=\"_new\" class=\"decorated-link\" href=\"https://vncdc.gov.vn\" style=\"background-color: rgb(255, 255, 255);\"></a>&nbsp;<a href=\"https://moh.gov.vn\" target=\"_blank\">https://moh.gov.vn</a>;&nbsp;Meningococcal meningitis</p></li></ul>', 'd0b742bc3f896f8b5854537551ffdd64-vikhun2.jpg', 0, 0, 24, 1, '2026-03-20 09:23:21'),
(16, 'NGÀY LÀM VIỆC ĐẦU TIÊN KHÁM CHỮA BỆNH BẢO HIỂM Y TẾ TẠI PHÒNG KHÁM ĐA KHOA TRƯỜNG CAO ĐẲNG KON TUM', 'Ngày 16/3/2026, Phòng khám Đa khoa Trường Cao đẳng Kon Tum chính thức triển khai dịch vụ khám chữa bệnh bảo hiểm y tế (BHYT), đánh dấu một bước phát triển quan trọng trong việc mở rộng hoạt động chăm sóc sức khỏe cho người dân trên địa bàn.', '<div dir=\"auto\" style=\"font-family: \" segoe=\"\" ui=\"\" historic\",=\"\" \"segoe=\"\" ui\",=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" color:=\"\" rgb(8,=\"\" 8,=\"\" 9);=\"\" font-size:=\"\" 15px;=\"\" white-space-collapse:=\"\" preserve;\"=\"\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\" style=\"text-align: inherit; padding-inline: 0px; margin-inline: 1px; overflow-wrap: break-word; display: inline-flex; vertical-align: middle; padding-bottom: 0px; width: 16px; margin-bottom: 0px; margin-top: 0px; padding-top: 0px; height: 16px; font-family: inherit;\"><p class=\"MsoNormal\" style=\"text-align: justify; \"><br></p></span></div><table class=\"table table-bordered\" style=\"text-align: justify;\"><tbody><tr><td><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Dấu mốc quan trọng\r\ntrong hành trình phục vụ cộng đồng<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Ngày\r\n16/3/2026, Phòng khám Đa khoa Trường Cao đẳng Kon Tum chính thức triển khai <b>dịch\r\nvụ khám chữa bệnh bảo hiểm y tế (BHYT)</b>, đánh dấu một bước phát triển quan\r\ntrọng trong việc mở rộng hoạt động chăm sóc sức khỏe cho người dân trên địa\r\nbàn.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Đây\r\nkhông chỉ là sự kiện mang ý nghĩa chuyên môn, mà còn thể hiện <b>cam kết của\r\nNhà trường và Phòng khám trong việc nâng cao chất lượng dịch vụ y tế, hướng tới\r\nphục vụ cộng đồng một cách toàn diện và bền vững</b>.</span></p><p class=\"MsoNormal\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/9f873a73f648fe49e2dc172ec751c2da-745954385089608562-Copy.jpg\" style=\"width: 939.5px;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p></o:p></span></p>\r\n\r\n<div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"center\">\r\n\r\n</span></div>\r\n\r\n<p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Không khí ngày đầu:\r\nKhẩn trương – chuyên nghiệp – tận tâm<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Ngay\r\ntừ sáng sớm, Phòng khám đã đón tiếp những bệnh nhân đầu tiên đến khám bằng thẻ\r\nBHYT.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Đội\r\nngũ y bác sĩ, điều dưỡng và nhân viên y tế:<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Chủ động hướng dẫn thủ tục<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Hỗ trợ người bệnh tiếp cận quy trình khám\r\nchữa bệnh<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\">✔️</span><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> Đảm bảo các khâu tiếp đón – khám – chỉ định\r\ncận lâm sàng được thực hiện thông suốt<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Dù là ngày đầu triển khai, mọi hoạt\r\nđộng diễn ra <b>ổn định, trật tự và chuyên nghiệp</b>, tạo sự yên tâm cho người\r\nbệnh.</span></p><p class=\"MsoNormal\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/32faa31a74d42eeb6129e93c106e2014-3289119611843084288.jpg\" style=\"width: 939.5px;\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><br></span></p><p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p></o:p></span></p>\r\n\r\n<div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n</span></div><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Quy trình rõ ràng\r\n– tạo thuận lợi cho người dân<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Người\r\nbệnh đến khám BHYT được hướng dẫn theo quy trình:<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"margin-left:36.0pt;text-indent:-18.0pt;mso-list:l1 level1 lfo1;\r\ntab-stops:list 36.0pt\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif;mso-fareast-font-family:\"times=\"\" roman\"\"=\"\">1.<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;\r\n</span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Tiếp\r\nnhận – kiểm tra thẻ BHYT và giấy tờ liên quan <o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"margin-left:36.0pt;text-indent:-18.0pt;mso-list:l1 level1 lfo1;\r\ntab-stops:list 36.0pt\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif;mso-fareast-font-family:\"times=\"\" roman\"\"=\"\">2.<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;\r\n</span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Khám\r\nlâm sàng với bác sĩ <o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"margin-left:36.0pt;text-indent:-18.0pt;mso-list:l1 level1 lfo1;\r\ntab-stops:list 36.0pt\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif;mso-fareast-font-family:\"times=\"\" roman\"\"=\"\">3.<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;\r\n</span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Thực\r\nhiện các chỉ định (nếu có) <o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"margin-left:36.0pt;text-indent:-18.0pt;mso-list:l1 level1 lfo1;\r\ntab-stops:list 36.0pt\"><!--[if !supportLists]--><span style=\"font-size:14.0pt;\r\nfont-family:\" times=\"\" new=\"\" roman\",serif;mso-fareast-font-family:\"times=\"\" roman\"\"=\"\">4.<span style=\"font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-language-override: normal; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 7pt; line-height: normal; font-family: \" times=\"\" new=\"\" roman\";\"=\"\">&nbsp;&nbsp;&nbsp;\r\n</span></span><!--[endif]--><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Nhận\r\nthuốc và tư vấn điều trị <o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Mỗi bước đều có nhân viên hỗ trợ,\r\ngiúp người dân, đặc biệt là người lớn tuổi, dễ dàng tiếp cận dịch vụ.</span></p><p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p><br></o:p></span></p><p class=\"MsoNormal\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/cb8174a08b489c535869011d78663518-32891196118430842881.jpg\" style=\"width: 939.5px;\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p><br></o:p></span></p>\r\n\r\n<div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n</span></div><p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Lan tỏa giá trị\r\nnhân văn của chính sách BHYT<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Việc\r\ntriển khai khám chữa bệnh BHYT tại Phòng khám không chỉ giúp:&nbsp;</span><span style=\"text-indent: -18pt; background-color: rgba(0, 0, 0, 0);\"><span style=\"font-size: 14pt;\" times=\"\" new=\"\" roman\",serif\"=\"\">Giảm\r\n     chi phí khám chữa bệnh cho người dân; tăng k</span></span><span style=\"font-size: 14pt; text-indent: -18pt; background-color: rgba(0, 0, 0, 0);\">hả năng tiếp cận dịch vụ y tế chất lượng; g</span><span style=\"font-size: 14pt; text-indent: -18pt; background-color: rgba(0, 0, 0, 0);\">óp\r\n     phần thực hiện hiệu quả chính sách an sinh xã hội; m</span><span style=\"font-size: 14pt; background-color: rgba(0, 0, 0, 0);\">à\r\ncòn khẳng định vai trò của Phòng khám trong việc </span><b style=\"font-size: 14pt; background-color: rgba(0, 0, 0, 0);\">đồng hành cùng hệ thống y tế,\r\nphục vụ sức khỏe cộng đồng tại địa phương</b><span style=\"font-size: 14pt; background-color: rgba(0, 0, 0, 0);\">.</span></p><p class=\"MsoNormal\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/2b74a25f2524da4e1e9903513e6d7a51-44627983600582019271.jpg\" style=\"width: 939.5px;\"><br></p>\r\n\r\n<div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"center\">\r\n\r\n</span></div>\r\n\r\n<p class=\"MsoNormal\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Hướng tới phát\r\ntriển bền vững<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Trong\r\nthời gian tới, Phòng khám Đa khoa Trường Cao đẳng Kon Tum sẽ tiếp tục:</span></p><p class=\"MsoNormal\"><span style=\"font-size: 14pt; text-indent: -18pt; background-color: rgba(0, 0, 0, 0);\">- Nâng\r\n     cao chất lượng chuyên môn</span></p><p class=\"MsoNormal\"><span style=\"font-size: 14pt; text-indent: -18pt; background-color: rgba(0, 0, 0, 0);\">- Hoàn\r\n     thiện quy trình phục vụ</span></p><p class=\"MsoNormal\"><span style=\"font-size: 14pt; text-indent: -18pt; background-color: rgba(0, 0, 0, 0);\">- Đầu\r\n     tư trang thiết bị</span></p><p class=\"MsoNormal\"><span style=\"font-size: 14pt; text-indent: -18pt; background-color: rgba(0, 0, 0, 0);\">- Mở\r\n     rộng các dịch vụ khám chữa bệnh</span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Nhằm đáp ứng ngày càng tốt hơn nhu\r\ncầu chăm sóc sức khỏe của người dân.</span></p><p class=\"MsoNormal\"></p><div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n</span></div>\r\n\r\n<p class=\"MsoNormal\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/2ff812833c68389209d7487e57e8759d-168354482137514171-Copy.jpg\" style=\"width: 939.5px;\"><span style=\"font-size:14.0pt;font-family:\" segoe=\"\" ui=\"\" emoji\",sans-serif;=\"\" mso-bidi-font-family:\"segoe=\"\" emoji\"\"=\"\"><br></span></p><p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Phòng khám Đa khoa Trường Cao đẳng\r\nKon Tum<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Hotline: 02606 558 568<o:p></o:p></span></p>\r\n\r\n<div class=\"MsoNormal\" align=\"center\" style=\"text-align:center\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"center\">\r\n\r\n</span></div>\r\n\r\n<p class=\"MsoNormal\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><i>Ngày làm việc đầu tiên chỉ là\r\nkhởi đầu – hành trình chăm sóc sức khỏe cộng đồng sẽ còn tiếp tục với nhiều nỗ\r\nlực và trách nhiệm hơn nữa.</i><o:p></o:p></span></p></td></tr></tbody></table><div dir=\"auto\" style=\"font-family: \" segoe=\"\" ui=\"\" historic\",=\"\" \"segoe=\"\" ui\",=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" color:=\"\" rgb(8,=\"\" 8,=\"\" 9);=\"\" font-size:=\"\" 15px;=\"\" white-space-collapse:=\"\" preserve;\"=\"\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\" style=\"text-align: inherit; padding-inline: 0px; margin-inline: 1px; overflow-wrap: break-word; display: inline-flex; vertical-align: middle; padding-bottom: 0px; width: 16px; margin-bottom: 0px; margin-top: 0px; padding-top: 0px; height: 16px; font-family: inherit;\"><p class=\"MsoNormal\" style=\"text-align: justify;\"><br></p></span></div><div dir=\"auto\" style=\"font-family: \" segoe=\"\" ui=\"\" historic\",=\"\" \"segoe=\"\" ui\",=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" color:=\"\" rgb(8,=\"\" 8,=\"\" 9);=\"\" font-size:=\"\" 15px;=\"\" white-space-collapse:=\"\" preserve;\"=\"\"></div>', '62977b39ba4fd2f2c617909b1fecac22-4462798360058201927.jpg', 0, 0, 24, 1, '2026-03-24 17:03:04');
INSERT INTO `hicrm_events` (`id`, `event_name`, `event_description`, `event_content`, `event_image`, `event_type`, `event_hot`, `event_user_created`, `event_status`, `event_created_date`) VALUES
(17, 'BIẾN THỂ COVID-19 BA.3.2 (“VE SẦU”): CÓ ĐÁNG LO NGẠI?', 'Trong bối cảnh COVID-19 vẫn diễn biến phức tạp trên thế giới, sự xuất hiện của biến thể phụ BA.3.2 – còn được gọi với tên không chính thức là “ve sầu” – đang thu hút sự quan tâm của giới chuyên môn và cộng đồng.', '<p class=\"MsoNormal\" style=\"text-align: justify; \"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Trong\r\nbối cảnh COVID-19 vẫn diễn biến phức tạp trên thế giới, sự xuất hiện của biến\r\nthể phụ BA.3.2 – còn được gọi với tên không chính thức là “ve sầu” – đang thu\r\nhút sự quan tâm của giới chuyên môn và cộng đồng.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align: justify; \"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">BA.3.2\r\nlà biến thể gì?<o:p></o:p></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">BA.3.2\r\nlà một biến thể phụ của dòng Omicron. Theo các chuyên gia, biến thể này mang <b>hơn\r\n70 đột biến</b>, trong đó có nhiều đột biến liên quan đến khả năng né miễn dịch.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Điều\r\nnày khiến BA.3.2 có thể <b>lây lan trong cộng đồng đã có miễn dịch</b>, bao gồm\r\ncả những người đã tiêm vaccine hoặc từng mắc COVID-19 trước đó.</span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/0ac925f6cda96e72fc13114dd2ddf10a-covid-hom-nay.jpeg\" style=\"width: 800px;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><o:p></o:p></span></p><div class=\"MsoNormal\" align=\"left\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"left\" style=\"text-align: justify;\">\r\n\r\n</span></div><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Triệu\r\nchứng thường gặp<o:p></o:p></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Các\r\ntriệu chứng của BA.3.2 hiện <b>không khác biệt rõ rệt</b> so với các biến thể\r\nOmicron trước đây, bao gồm:<o:p></o:p></span></p><ul style=\"margin-top:0cm\" type=\"disc\">\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Sốt\r\n     hoặc ớn lạnh <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Đau\r\n     họng <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Ho\r\n     khan hoặc ho kéo dài <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Mệt\r\n     mỏi <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Đau\r\n     đầu, đau nhức cơ thể <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Nghẹt\r\n     mũi hoặc chảy mũi <o:p></o:p></span></li>\r\n</ul><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Trong\r\nmột số trường hợp, người bệnh có thể gặp triệu chứng tiêu hóa nhẹ.<o:p></o:p></span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><b><i>* Các chuyên gia lưu ý</i></b>: Triệu chứng\r\nthường <b>nhẹ đến trung bình</b>, nhưng vẫn cần theo dõi sát, đặc biệt ở người\r\ncao tuổi, người có bệnh nền.<o:p></o:p></span></p><div class=\"MsoNormal\" align=\"left\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"left\" style=\"text-align: justify;\">\r\n\r\n</span></div><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Mức\r\nđộ nguy hiểm: Chưa có dấu hiệu tăng nặng<o:p></o:p></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Dù\r\ncó khả năng né miễn dịch, nhưng đến thời điểm hiện tại:<o:p></o:p></span></p><ul style=\"margin-top:0cm\" type=\"disc\">\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Chưa\r\n     có bằng chứng cho thấy BA.3.2 gây bệnh nặng hơn <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Tỷ\r\n     lệ nhập viện và tử vong không tăng đột biến <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Vaccine\r\n     vẫn giúp giảm nguy cơ chuyển nặng và tử vong <o:p></o:p></span></li>\r\n</ul><div class=\"MsoNormal\" align=\"left\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"left\" style=\"text-align: justify;\">\r\n\r\n</span></div><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Xu\r\nhướng lây lan<o:p></o:p></span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Biến\r\nthể BA.3.2 đã được ghi nhận tại một số quốc gia, tuy nhiên:<o:p></o:p></span></p><ul style=\"margin-top:0cm\" type=\"disc\">\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Chưa\r\n     trở thành biến thể chiếm ưu thế toàn cầu</span></b><span style=\"font-size:\r\n     14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"> <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Tốc\r\n     độ lây lan ở mức theo dõi, chưa đáng báo động <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- COVID-19\r\n     hiện có xu hướng trở thành <b>bệnh lưu hành theo mùa</b></span></li><li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><img src=\"https://phongkhamcdkontum.com.vn/uploads/images/47160df24ea1eeb0531e6ddd52614080-Gemini_Generated_Image_hbv86nhbv86nhbv8.png\" style=\"width: 939.5px;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\"><b><br></b> <o:p></o:p></span></li>\r\n</ul><div class=\"MsoNormal\" align=\"left\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"left\" style=\"text-align: justify;\">\r\n\r\n</span></div><p class=\"MsoNormal\" style=\"text-align: justify;\"><b><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Khuyến\r\ncáo phòng bệnh</span></b></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size: 18px;\" times=\"\" new=\"\" roman\",=\"\" serif;=\"\" font-size:=\"\" 14pt;\"=\"\">Trước\r\nsự xuất hiện của biến thể mới, các chuyên gia khuyến nghị:</span></p><ul style=\"margin-top:0cm\" type=\"disc\">\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Đeo\r\n     khẩu trang nơi đông người hoặc khi có triệu chứng <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Rửa\r\n     tay, sát khuẩn thường xuyên <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Theo\r\n     dõi sức khỏe, đi khám khi có dấu hiệu bất thường <o:p></o:p></span></li>\r\n <li class=\"MsoNormal\" style=\"text-align: justify; text-indent: -18pt;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">&nbsp;- Tiêm\r\n     vaccine đầy đủ theo hướng dẫn của Bộ Y tế <o:p></o:p></span></li>\r\n</ul><div class=\"MsoNormal\" align=\"left\"><span style=\"font-size:\r\n14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">\r\n\r\n<hr size=\"2\" width=\"100%\" align=\"left\" style=\"text-align: justify;\">\r\n\r\n</span></div><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Biến\r\nthể BA.3.2 (“ve sầu”) cho thấy khả năng thích nghi và né miễn dịch của virus\r\nSARS-CoV-2 vẫn đang tiếp diễn. Tuy nhiên, với các dữ liệu hiện có, <b>nguy cơ\r\nchưa tăng cao</b>.<o:p></o:p></span></p><p>\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p class=\"MsoNormal\" style=\"text-align: justify;\"><span style=\"font-size:14.0pt;font-family:\" times=\"\" new=\"\" roman\",serif\"=\"\">Điều\r\nquan trọng nhất lúc này là <b>giữ tâm lý bình tĩnh, không hoang mang nhưng cũng\r\nkhông chủ quan</b>, tiếp tục thực hiện các biện pháp phòng bệnh cá nhân.</span></p><p class=\"MsoNormal\" style=\"text-align: justify;\"><a href=\"https://vov.vn/xa-hoi/bien-the-covid-19-ba32-ve-sau-co-phai-chung-moi-chuyen-gia-noi-gi-post1280058.vov\" target=\"_blank\" style=\"background-color: rgb(255, 255, 255);\">bien-the-covid-19-ba32</a></p>', 'ee2024be024dd64578338bb6588c6b77-covid-hom-nay.jpeg', 0, 0, 24, 1, '2026-04-01 14:54:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_expense_items`
--

CREATE TABLE `hicrm_expense_items` (
  `id` int NOT NULL,
  `expense_code` varchar(255) NOT NULL,
  `expense_name` varchar(255) NOT NULL,
  `expense_description` text NOT NULL,
  `expense_parent` int NOT NULL,
  `expense_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_url` text NOT NULL,
  `image_category` int NOT NULL DEFAULT '0' COMMENT '0 - Mặc định 1 - Ưu đãi hấp dẫn 2 - Không gian phòng khám 3 - Slider\r\n4 - Giới thiệu	',
  `image_device` int NOT NULL DEFAULT '0' COMMENT '0 - DESKTOP\r\n1 - MOBILE',
  `image_user_created` int NOT NULL,
  `image_created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_images`
--

INSERT INTO `hicrm_images` (`id`, `image_name`, `image_url`, `image_category`, `image_device`, `image_user_created`, `image_created_date`, `image_status`) VALUES
(1, 'Hình ảnh giới thiệu', 'd2173854c8803807b1835ae91a68629b-Hnhnh1.png', 0, 0, 24, '2025-12-28 12:14:30', 1),
(2, 'Hình ảnh của đơn vị', '358c270047cc7088cc8d0d71aa50e4dc-banner.jpg', 0, 0, 24, '2025-12-28 13:29:48', 1),
(3, 'Cơ cấu tổ chức', '266e4bda8ef073881f1ea868529df577-SDTCR1.png', 0, 0, 24, '2026-01-17 11:30:15', 1),
(4, 'sự kiện 1', 'd1054600d3d5525aed0cc240b0eb758e-ChatGPTImageFeb8202601_26_30PM.png', 0, 0, 24, '2026-02-09 08:04:35', 1),
(5, 'BHYT', '5967db3f7fcede80bb973c5fc3ef348a-40337fa7d5b75be902a6.jpg', 0, 0, 24, '2026-02-09 09:03:21', 1),
(6, 'Thong bao', 'b6e465078b2a956411ca8ffdc2047165-23.02.jpg', 0, 0, 24, '2026-02-09 09:40:16', 1),
(7, 'Slider phòng khám', 'acec4f944ef8e514ef57351789977ee3-SLIDERWEB.png', 0, 0, 24, '2026-02-11 11:41:51', 1),
(8, 'Hình ảnh giới thiệu 1', '65783aa2647ea0823e1c4e498ab0896f-slider1920-600-.png', 0, 0, 24, '2026-02-11 11:56:23', 1),
(9, 'gioi thieu', '41e6e836b05d372273c7579bea43f5dd-86e551eaddcc53920add.jpg', 0, 0, 24, '2026-02-12 13:34:03', 1),
(10, 'gioi thieu 2', 'd78868c569dd3f3e07e488edfb35f38a-Gemini_Generated_Image_x5tyd0x5tyd0x5ty.png', 0, 0, 24, '2026-02-12 13:52:52', 99),
(11, 'gioi thieu 2', '5596d3b1f7dfc578b7beb2555beed28f-Gemini_Generated_Image_x5tyd0x5tyd0x5ty.png', 0, 0, 24, '2026-02-12 13:52:53', 99),
(12, 'gioi thieu 2', '5596d3b1f7dfc578b7beb2555beed28f-Gemini_Generated_Image_x5tyd0x5tyd0x5ty.png', 0, 0, 24, '2026-02-12 13:52:53', 99),
(13, 'gioi thieu 2', '393c9c1c67cbba5b9b2d5e227b76f110-Gemini_Generated_Image_h7x150h7x150h7x1.png', 0, 0, 24, '2026-02-12 13:54:39', 1),
(14, 'Hình ảnh chào xuân năm mới', 'ceeb121c7534d310750122668c671d5a-621849350_122142024387115938_3368117025345007350_n.png', 0, 0, 24, '2026-02-12 14:20:44', 1),
(15, 'CSYT ban đầu', 'df4f0d9068cf3e14d568761bc243116d-ChatGPTImageFeb12202604_38_30PM.png', 0, 0, 24, '2026-02-12 16:39:26', 1),
(16, 'Kham SK dinh ky', 'dc7f9fb3b5234acce095ce7d98195f62-ChatGPTImageFeb12202604_49_54PM.png', 0, 0, 24, '2026-02-12 16:51:48', 1),
(17, '2', 'b2c97b52d22e1f0b3771ed1aa668109a-ChatGPTImageFeb12202604_49_54PM.png', 0, 0, 24, '2026-02-12 16:52:32', 1),
(18, 'Khung 1', 'ce363cec165502b42dcc6f2f7d0e1bf2-1.png', 0, 0, 24, '2026-02-22 10:20:26', 1),
(19, 'Khung 2', 'ced581b3ab6a63e9776af11a72740128-2.png', 0, 0, 24, '2026-02-22 10:20:38', 1),
(20, 'Khung 3', 'cd3c1e398c352dce4b0b64debe65d35d-3.png', 0, 0, 24, '2026-02-22 10:20:49', 1),
(21, 'Hình ảnh banner Mobile', '2f0b7216bf0e505c6a52bd0d6e52fb79-banner_mb.png', 0, 0, 24, '2026-02-25 17:22:17', 1),
(22, 'banner1', '', 0, 0, 24, '2026-02-27 09:06:58', 99),
(23, 'banner2', '67144a887e387337852ee3b44e7118a9-adb3e1d7a71c2942700d1.jpg', 0, 0, 24, '2026-02-27 09:07:44', 1),
(24, 'banner3', '603229ba5ddf552bfd8eda063b6ccd96-adb3e1d7a71c2942700d1.jpg', 0, 0, 24, '2026-02-27 09:08:01', 1),
(25, 'banner4', '09df68cdf9283a01573d71cf95192795-ChatGPTImageFeb27202609_05_13AM.png', 3, 1, 24, '2026-02-27 09:08:26', 1),
(26, 'banner5', '5a4ea2adbd1759c5c07b0fc03ec97ca4-ChatGPTImageFeb27202609_05_36AM.png', 0, 0, 24, '2026-02-27 09:08:41', 1),
(27, 'banner6', '5cc49a76443b87ba5459a9c334a60d19-de2a458d3e46b018e9571.jpg', 0, 0, 24, '2026-02-27 09:09:00', 1),
(28, 'Banner desktop nhà thầy thuốc', '5fdd9ee3e21b1f7d03d4ada266794a37-banner_thaythuoc_desktop.png', 3, 0, 24, '2026-02-27 09:50:09', 1),
(29, 'Bác sĩ giàu kinh nghiệm phòng khám đa khoa và nhà thuốc cao đẳng kon tum', '874fcf68dcbdc78675ba1469b6a3b7de-photo_2026-02-28_14-57-04.jpg', 4, 0, 24, '2026-02-28 14:58:07', 1),
(30, 'banner trang chủ desktop', '021da860fb2310cc36e06c262f5a6c8a-e72653c2a820267e7f31.jpg', 0, 0, 24, '2026-03-04 14:27:11', 1),
(31, 'banner trang chủ 8-3 mobile', 'bb3452b8dfb5ab9f81b1a56945b55f1d-d0c83632a0d02e8e77c1.jpg', 3, 1, 24, '2026-03-04 14:53:12', 1),
(32, 'Banner Trang chủ 8-3 Deskop - 1', '541eb0d23300dd624c3ae014f33422e7-banner_8.3.png', 3, 0, 24, '2026-03-04 21:14:07', 1),
(33, 'Banner Trang chủ 8-3 mobile- 1', '47929e4b6e84075450d68531462c2c85-banner_8.3_mb.png', 0, 1, 24, '2026-03-04 21:14:24', 1),
(34, 'Chỉ số BMI', 'f0050efbf25d8e63d2c5be66418a17f1-Screenshot_1.png', 0, 0, 24, '2026-03-07 13:30:03', 1),
(35, 'quy doi diem thuong', '7345b9b6e0a2f866a40059efbafc7999-Screenshot2026-03-09100340.png', 0, 0, 24, '2026-03-09 10:08:12', 1),
(36, 'Hieu lam KCB BHYT', '58e5ed254820b4412e4bc3a1183b0140-HiulmKCBBHYT.jpg', 0, 0, 24, '2026-03-09 19:21:13', 1),
(37, 'chuyen mua', 'e6294e656e75572f011a58880617022f-camcum2.jpg', 0, 0, 24, '2026-03-16 20:28:59', 1),
(38, 'chuyen mua 1', '58f2b8cf8f47b02967950db166f711b6-camcum1.jpg', 0, 0, 24, '2026-03-16 20:29:23', 1),
(39, 'Quốc tế hạnh phúc', 'd0edcce1b6fdc5cf2f83c8a3b976b76b-Gemini_Generated_Image_7nrimo7nrimo7nri.png', 0, 0, 24, '2026-03-20 08:30:49', 1),
(40, 'viemnaomocau1', '410207b5de359a6c3edadf1500be5ef8-bieuhien1.webp', 0, 0, 24, '2026-03-20 09:13:59', 1),
(41, 'viemnaomocau2', '2220e830c8741b1493b5c485186ae9b3-bieuhien2.jpg', 0, 0, 24, '2026-03-20 09:14:16', 1),
(42, 'viemnaomocau3', '195016a4612f890ed6e73ac9b28cef8c-ratay1.jpg', 0, 0, 24, '2026-03-20 09:14:38', 1),
(43, 'viemnaomocau4', 'c9be61dfffb74fb788f3a9b479ee1fc5-thamkham1.avif', 0, 0, 24, '2026-03-20 09:14:59', 1),
(44, 'viemnaomocau5', '6e3cf1a3790fa234cc19a9834134c4dd-tiemvacxin1.jpg', 0, 0, 24, '2026-03-20 09:15:18', 1),
(45, 'viemnaomocau6', '344ab79e51144d4a1eb7ca1b770bf1bd-tiemvacxin2.jpg', 0, 0, 24, '2026-03-20 09:15:49', 1),
(46, 'viemnaomocau7', 'ccefc64add1d643d512781ecc789143e-vikhun1.jpg', 0, 0, 24, '2026-03-20 09:16:11', 1),
(47, 'viemnaomocau8', '5d4aac80c823dc1d5d9288a2acfef670-vikhun2.jpg', 0, 0, 24, '2026-03-20 09:16:39', 1),
(48, 'viemnaomocau9', '9a8746131de835ce0b16180875a024d4-vikhun3.jpg', 0, 0, 24, '2026-03-20 09:16:59', 1),
(49, 'Ngaylamviec1', '2ff812833c68389209d7487e57e8759d-168354482137514171-Copy.jpg', 0, 0, 24, '2026-03-24 16:31:08', 1),
(50, 'Ngaylamviec2', '9f873a73f648fe49e2dc172ec751c2da-745954385089608562-Copy.jpg', 0, 0, 24, '2026-03-24 16:31:42', 1),
(51, 'Ngaylamviec3', 'cb8174a08b489c535869011d78663518-32891196118430842881.jpg', 0, 0, 24, '2026-03-24 16:32:05', 1),
(52, 'Ngaylamviec4', '10b4f5ce0eb38da6c9db98c7669a07e2-32891196118430842882.jpg', 0, 0, 24, '2026-03-24 16:32:28', 1),
(53, 'Ngaylamviec5', '32faa31a74d42eeb6129e93c106e2014-3289119611843084288.jpg', 0, 0, 24, '2026-03-24 16:32:49', 1),
(54, 'Ngaylamviec6', '2b74a25f2524da4e1e9903513e6d7a51-44627983600582019271.jpg', 0, 0, 24, '2026-03-24 16:33:13', 1),
(55, 'Ngaylamviec7', '3aae102ca96019d3475078ee87ac99bb-4462798360058201927.jpg', 0, 0, 24, '2026-03-24 16:33:34', 1),
(56, 'slider1', 'f219fbe785133adf1eab548816cfd40e-Gemini_Generated_Image_ef2z1lef2z1lef2z.png', 3, 0, 24, '2026-03-26 15:29:01', 1),
(57, 'bienthevesau1', '47160df24ea1eeb0531e6ddd52614080-Gemini_Generated_Image_hbv86nhbv86nhbv8.png', 0, 0, 24, '2026-04-01 14:15:50', 1),
(58, 'bienthevesau2', '64b8b862920c451476a096040ca17a76-bien-the.webp', 0, 0, 24, '2026-04-01 14:20:38', 1),
(59, 'bienthevesau3', '0ac925f6cda96e72fc13114dd2ddf10a-covid-hom-nay.jpeg', 0, 0, 24, '2026-04-01 14:53:13', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_incomes`
--

CREATE TABLE `hicrm_incomes` (
  `id` bigint NOT NULL,
  `income_no` varchar(20) NOT NULL,
  `income_type` int NOT NULL,
  `income_created_date` datetime NOT NULL,
  `income_accounting_date` datetime NOT NULL,
  `income_to` bigint NOT NULL,
  `income_note` text,
  `income_staff` int NOT NULL,
  `income_document` int NOT NULL DEFAULT '0',
  `income_status` int NOT NULL DEFAULT '0',
  `income_created_by` int NOT NULL,
  `income_approved_by` int DEFAULT NULL,
  `income_approved_date` datetime DEFAULT NULL,
  `income_approved_note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` bigint NOT NULL,
  `income_id` bigint NOT NULL,
  `income_detail` varchar(255) NOT NULL,
  `income_debit` int NOT NULL,
  `income_credit` int NOT NULL,
  `income_amount` decimal(20,2) NOT NULL,
  `income_bank_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_to` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `introduce_category` int NOT NULL,
  `introduce_name` varchar(255) NOT NULL,
  `introduce_content` longtext NOT NULL,
  `introduce_uid` int NOT NULL,
  `introduce_orderby` int NOT NULL,
  `introduce_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_introduce`
--

INSERT INTO `hicrm_introduce` (`id`, `introduce_category`, `introduce_name`, `introduce_content`, `introduce_uid`, `introduce_orderby`, `introduce_created_date`) VALUES
(1, 2, 'Về chúng tôi', '<figure class=\"image\"><img style=\"aspect-ratio:1472/704;\" src=\"https://phongkhamcdkontum.com.vn/uploads/images/393c9c1c67cbba5b9b2d5e227b76f110-Gemini_Generated_Image_h7x150h7x150h7x1.png\" width=\"1472\" height=\"704\"></figure><p style=\"margin-left:40px;text-align:center;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>PHÒNG KHÁM ĐA KHOA VÀ NHÀ THUỐC TRƯỜNG CAO ĐẲNG KON TUM&nbsp;</strong></span></p><p style=\"margin-left:40px;text-align:center;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><i><u>Uy tín từ chuyên môn - An tâm từ dịch vụ</u></i></span></p><p style=\"margin-left:40px;text-align:center;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Nơi chăm sóc sức khỏe bắt đầu từ sự thấu hiểu</strong></span></p><p style=\"margin-left:40px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Mỗi hành trình chăm sóc sức khỏe đều bắt đầu từ một nhu cầu rất đơn giản: Được lắng nghe, được thấu hiểu và được điều trị đúng cách. Xuất phát từ định hướng đó, Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum ra đời với mong muốn mang đến cho người dân một địa chỉ y tế đáng tin cậy – nơi chất lượng chuyên môn song hành cùng sự tận tâm trong phục vụ.</span></p><p style=\"margin-left:40px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Là đơn vị trực thuộc Khoa Y – Dược, Trường Cao đẳng Kon Tum, Phòng khám và Nhà thuốc được xây dựng trên nền tảng kết hợp giữa hoạt động khám chữa bệnh thực tế và môi trường đào tạo chuyên ngành y – dược. Điều này tạo nên một mô hình đặc thù: Vừa đảm bảo tính chuyên nghiệp trong cung cấp dịch vụ y tế, vừa thúc đẩy tinh thần học hỏi, đổi mới và nâng cao năng lực chuyên môn liên tục.</span></p><p style=\"margin-left:40px;text-align:center;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Đồng hành cùng sức khỏe cộng đồng</strong></span></p><p style=\"margin-left:40px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Phòng khám Đa khoa cung cấp các dịch vụ khám chữa bệnh đa chuyên khoa, bao gồm nội khoa, ngoại khoa, sản khoa, cận lâm sàng và tư vấn chăm sóc sức khỏe ban đầu. Quy trình khám chữa bệnh được thiết kế theo hướng thuận tiện, giảm thời gian chờ đợi, giúp người bệnh tiếp cận dịch vụ nhanh chóng và hiệu quả.</span></p><p style=\"margin-left:40px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Song song đó, Nhà thuốc đảm nhiệm vai trò cung ứng thuốc và tư vấn sử dụng thuốc an toàn, hợp lý, góp phần hoàn thiện chuỗi chăm sóc sức khỏe toàn diện từ khám bệnh đến điều trị.</span></p><p style=\"margin-left:40px;text-align:center;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Lấy người bệnh làm trung tâm</strong></span></p><p style=\"margin-left:40px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Chúng tôi tin rằng chăm sóc sức khỏe không chỉ là điều trị bệnh mà còn là xây dựng sự tin tưởng lâu dài. Vì vậy, mỗi hoạt động tại Phòng khám và Nhà thuốc đều hướng đến trải nghiệm của người bệnh: Rõ ràng trong thông tin, minh bạch trong chi phí, tận tâm trong phục vụ và chuẩn mực trong chuyên môn.</span></p><p style=\"margin-left:40px;text-align:center;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Hướng tới mô hình y tế hiện đại</strong></span></p><p style=\"margin-left:40px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Trong bối cảnh ngành y tế ngày càng phát triển theo hướng chăm sóc sức khỏe ban đầu và quản lý sức khỏe bền vững, Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum định hướng trở thành điểm đến y tế thân thiện, hiệu quả và gần gũi với cộng đồng. Chúng tôi không ngừng cải tiến chất lượng dịch vụ, ứng dụng công nghệ và nâng cao năng lực đội ngũ để đáp ứng tốt hơn nhu cầu chăm sóc sức khỏe của người dân.</span></p><p style=\"margin-left:40px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum – nơi chuyên môn tạo nên niềm tin, và sự tận tâm tạo nên khác biệt.</span></p><p><br>&nbsp;</p>', 24, 0, '2026-03-05 10:22:00'),
(2, 4, 'Cơ sở hạ tầng', '<p>Cơ sở hạ tầng vô cùng hiện đại, đầy đủ</p>', 24, 0, '2025-12-28 21:53:46'),
(3, 3, 'Cơ cấu tổ chức', '<p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\"><strong>Phòng khám Đa khoa gồm:</strong></span></p><p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\">- Trưởng Phòng khám</span></p><p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\">- Các Phó Trưởng phòng khám</span></p><p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\">- Các bộ phận chuyên môn như: Nội khoa, Ngoại khoa, Sản khoa, Cận lâm sàng và bộ phận tiếp đón.</span></p><p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\"><strong>Nhà thuốc gồm:</strong></span></p><p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\">- Trưởng Nhà thuốc</span></p><p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\">- Đội ngũ dược sĩ chuyên môn.</span></p><p style=\"text-align:justify;\"><span style=\"color:#0000ff;font-size:18px;\"><i><strong>Hai đơn vị hoạt động thống nhất trong hệ thống tổ chức của Khoa Y – Dược, bảo đảm sự phối hợp chặt chẽ giữa khám bệnh, chẩn đoán, tư vấn điều trị và cung ứng thuốc an toàn, hợp lý.</strong></i></span></p>', 24, 0, '2026-03-03 16:41:01'),
(4, 5, 'Tại sao chọn chúng tôi ?', '<p class=\"isSelectedEnd\" style=\"text-align: justify; \"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">Chăm sóc sức khỏe không chỉ là điều trị khi bệnh mà còn là lựa chọn đúng nơi để được tư vấn, theo dõi và đồng hành lâu dài. Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum mang đến giải pháp chăm sóc sức khỏe toàn diện – nhanh chóng, thuận tiện và tối ưu chi phí cho người dân.</font></span></p><h3 style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">✔ Khám chữa bệnh bảo hiểm y tế thuận lợi – tối đa quyền lợi</font></span></h3><p class=\"isSelectedEnd\" style=\"text-align: justify; \"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">Bạn có thể khám trực tiếp tại cơ sở khám chữa bệnh ban đầu để được hưởng đầy đủ quyền lợi bảo hiểm y tế theo quy định. Quy trình tiếp đón và hướng dẫn rõ ràng giúp người bệnh tiết kiệm thời gian, hạn chế thủ tục phức tạp và an tâm khi sử dụng dịch vụ.</font></span></p><h3 style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">✔ Khám nhanh – không phải chờ đợi lâu</font></span></h3><p class=\"isSelectedEnd\" style=\"text-align: justify; \"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">Không cần di chuyển xa hoặc chen chúc tại bệnh viện đông người. Quy trình khám được tối ưu hóa giúp người bệnh được tiếp nhận nhanh chóng, khám đúng chuyên khoa và nhận kết quả trong thời gian hợp lý.</font></span></p><h3 style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">✔ Bác sĩ tận tâm – tư vấn rõ ràng, dễ hiểu</font></span></h3><p class=\"isSelectedEnd\" style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">Đội ngũ bác sĩ và nhân viên y tế luôn lắng nghe, giải thích cặn kẽ tình trạng sức khỏe và phương án điều trị. Người bệnh được tư vấn đầy đủ để chủ động chăm sóc sức khỏe ngay từ giai đoạn sớm.</font></span></p><h3 style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">✔ Mô hình tích hợp khám – xét nghiệm – cấp thuốc tiện lợi</font></span></h3><p class=\"isSelectedEnd\" style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">Phòng khám và Nhà thuốc hoạt động đồng bộ giúp người bệnh hoàn thành quy trình khám chữa bệnh nhanh chóng trong cùng một địa điểm, giảm thời gian di chuyển và bảo đảm tính liên tục trong điều trị.</font></span></p><h3 style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">✔ Chi phí hợp lý – minh bạch</font></span></h3><p class=\"isSelectedEnd\" style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">Các dịch vụ được thực hiện theo quy định hiện hành, chi phí rõ ràng, phù hợp với nhiều đối tượng. Đặc biệt, người tham gia bảo hiểm y tế được hỗ trợ tối đa theo mức hưởng quy định.</font></span></p><h3 style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">✔ Đồng hành chăm sóc sức khỏe lâu dài</font></span></h3><p class=\"isSelectedEnd\" style=\"text-align: justify;\"><span style=\"font-family: \" times=\"\" new=\"\" roman\";\"=\"\"><font color=\"#0000ff\">Chúng tôi không chỉ khám và điều trị, mà còn theo dõi hồ sơ sức khỏe, tư vấn phòng bệnh và hỗ trợ người bệnh duy trì lối sống khỏe mạnh.</font></span></p><p class=\"isSelectedEnd\"></p>', 24, 0, '2026-02-12 14:35:41'),
(5, 8, 'Giới thiệu nhà thuốc', '<h2 style=\"text-align:justify;\"><span style=\"color:hsl(0, 75%, 60%);font-family:\'Times New Roman\', Times, serif;\">NHÀ THUỐC TRƯỜNG CAO ĐẲNG KON TUM</span></h2><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><strong>Đạt chuẩn GPP – Chuyên nghiệp – Tận tâm vì sức khỏe cộng đồng</strong></span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Nhà thuốc Trường Cao đẳng Kon Tum được thành lập với mục tiêu cung ứng thuốc và các sản phẩm chăm sóc sức khỏe bảo đảm chất lượng, an toàn và đúng quy định chuyên môn. Là đơn vị trực thuộc Trường Cao đẳng Kon Tum, nhà thuốc hoạt động theo định hướng gắn kết giữa đào tạo và thực tiễn, góp phần phục vụ hiệu quả công tác chăm sóc sức khỏe cho cán bộ, giảng viên, học sinh – sinh viên và nhân dân trên địa bàn.</span></p><h2 style=\"text-align:justify;\"><span style=\"color:hsl(0, 75%, 60%);font-family:\'Times New Roman\', Times, serif;\">ĐẠT CHUẨN GPP THEO QUY ĐỊNH CỦA BỘ Y TẾ</span></h2><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Nhà thuốc được công nhận đạt tiêu chuẩn <strong>GPP (Good Pharmacy Practice – Thực hành tốt nhà thuốc)</strong>, đáp ứng đầy đủ các yêu cầu về:</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Cơ sở vật chất và trang thiết bị bảo quản thuốc đạt chuẩn</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Quy trình nhập – xuất – lưu trữ thuốc chặt chẽ</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Kiểm soát nguồn gốc, hạn sử dụng và điều kiện bảo quản</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Dược sĩ có chứng chỉ hành nghề theo quy định</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Tư vấn sử dụng thuốc đúng chuyên môn, an toàn và hợp lý</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Việc tuân thủ nghiêm túc tiêu chuẩn GPP là cam kết về chất lượng và trách nhiệm của nhà thuốc đối với người sử dụng.</span></p><h2 style=\"text-align:justify;\"><span style=\"color:hsl(0, 75%, 60%);font-family:\'Times New Roman\', Times, serif;\">DANH MỤC SẢN PHẨM ĐA DẠNG – NGUỒN GỐC RÕ RÀNG</span></h2><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Nhà thuốc cung cấp đầy đủ các nhóm sản phẩm phục vụ nhu cầu chăm sóc sức khỏe:</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Thuốc kê đơn và không kê đơn</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Thực phẩm chức năng</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Dược mỹ phẩm</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Thiết bị y tế gia đình</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Vật tư y tế</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Tất cả sản phẩm đều được nhập từ các đơn vị phân phối hợp pháp, có hóa đơn chứng từ đầy đủ và được bảo quản theo đúng quy định của Bộ Y tế.</span></p><h2 style=\"text-align:justify;\"><span style=\"color:hsl(0, 75%, 60%);font-family:\'Times New Roman\', Times, serif;\">ĐỘI NGŨ DƯỢC SĨ CHUYÊN MÔN VỮNG VÀNG</span></h2><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Đội ngũ dược sĩ của nhà thuốc được đào tạo bài bản, có kinh nghiệm thực tiễn và luôn đề cao y đức trong hành nghề.</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Nhà thuốc chú trọng:</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Tư vấn sử dụng thuốc an toàn, đúng liều lượng</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Hướng dẫn cách dùng thuốc phù hợp với từng đối tượng</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Cảnh báo tương tác thuốc khi cần thiết</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Hỗ trợ giải đáp thông tin liên quan đến chăm sóc sức khỏe</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Chúng tôi không chỉ cung cấp thuốc, mà còn đồng hành cùng người dân trong việc sử dụng thuốc hợp lý và hiệu quả.</span></p><h2 style=\"text-align:justify;\"><span style=\"color:hsl(0, 75%, 60%);font-family:\'Times New Roman\', Times, serif;\">CAM KẾT CỦA NHÀ THUỐC</span></h2><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Hoạt động theo tiêu chuẩn GPP</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Thuốc chính hãng, nguồn gốc rõ ràng</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Giá cả hợp lý, công khai minh bạch</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Bảo mật thông tin khách hàng</span></p><p style=\"text-align:justify;\"><span style=\"color:hsl(240,75%,60%);font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Phục vụ chuyên nghiệp, tận tâm</span></p><p>&nbsp;</p>', 24, 0, '2026-03-03 16:38:39'),
(6, 9, 'Chính sách giá bán', '<p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><strong>NGUYÊN TẮC BÁN HÀNG</strong></span></p><ul style=\"list-style-type:disc;\"><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Chỉ kinh doanh và bán các loại thuốc, vật tư y tế và sản phẩm chăm sóc sức khỏe được phép lưu hành theo quy định của pháp luật.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Thuốc kê đơn được bán đúng theo đơn của bác sĩ hoặc người có thẩm quyền kê đơn.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Thực hiện đầy đủ các quy định về quản lý, bảo quản, lưu trữ và truy xuất nguồn gốc thuốc theo quy định của Bộ Y tế.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Tất cả thuốc và sản phẩm tại Nhà thuốc đều được niêm yết giá công khai, rõ ràng.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Giá bán được xây dựng hợp lý, phù hợp với mặt bằng thị trường và bảo đảm quyền lợi của người tiêu dùng.</span></li></ul><ul><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Bán đúng thuốc theo đơn hoặc theo nhu cầu sử dụng hợp lý của khách hàng.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Không kinh doanh và bán các loại thuốc giả, thuốc kém chất lượng, thuốc không rõ nguồn gốc.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Không bán thuốc quá hạn sử dụng hoặc không bảo đảm điều kiện bảo quản.</span></li></ul><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><strong>CHÍNH SÁCH ƯU ĐÃI</strong></span></p><ul><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Viên chức, người lao động và học sinh, sinh viên Trường Cao đẳng Kon Tum&nbsp;</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Khách hàng thân thiết</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><i>Các hình thức ưu đãi có thể bao gồm:</i> <i>Giảm giá trực tiếp trên sản phẩm; Tích điểm khách hàng.</i></span></li></ul><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><strong>CHÍNH SÁCH CHĂM SÓC KHÁCH HÀNG</strong></span></p><ul><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Nhà thuốc thực hiện tư vấn miễn phí cho khách hàng về: Hướng dẫn sử dụng thuốc đúng cách; Tư vấn phòng bệnh và chăm sóc sức khỏe; Hướng dẫn theo dõi các dấu hiệu bất thường hoặc tác dụng phụ khi sử dụng thuốc.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Kiểm tra và đối chiếu đơn thuốc khi khách hàng có nhu cầu.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Hướng dẫn khách hàng cách bảo quản thuốc đúng quy định tại gia đình.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Hỗ trợ nhắc lịch sử dụng thuốc đối với các trường hợp điều trị dài ngày.</span></li></ul><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><strong>CHÍNH SÁCH ĐỔI TRẢ</strong></span></p><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Nhà thuốc thực hiện đổi trả sản phẩm trong các trường hợp sau:</span></p><ul><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><i><strong>Trường hợp được đổi:</strong></i></span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Thuốc hoặc sản phẩm bị lỗi do nhà sản xuất.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Giao nhầm thuốc hoặc sản phẩm so với yêu cầu của khách hàng.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Sản phẩm bị hư hỏng, rách bao bì hoặc biến dạng khi chưa sử dụng.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><i><strong>Trường hợp không áp dụng đổi trả:</strong></i></span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Thuốc hoặc sản phẩm đã mở bao bì, đã sử dụng.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Thuốc bị hư hỏng do khách hàng bảo quản không đúng quy định sau khi mua.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Thuốc kê đơn đã bán đúng theo đơn thuốc của bác sĩ.</span></li></ul><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><strong>CHÍNH SÁCH GIAO HÀNG</strong></span></p><ul><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Nhà thuốc miễn phí giao hàng đối với các đơn hàng có giá trị từ 300.000 đồng trở lên trong phạm vi nội thị và bán kính không quá 3 km.</span></li><li style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Đối với các đơn hàng có khoảng cách trên 3 km, phí giao hàng được tính theo mức phí của đơn vị vận chuyển hoặc theo mặt bằng giá thị trường tại thời điểm giao hàng.</span></li></ul><p style=\"text-align:justify;\"><span style=\"font-size:18px;\"><strong>THỂ LỆ CHƯƠNG TRÌNH TÍCH ĐIỂM NHẬN ĐẶC QUYỀN</strong></span></p><p style=\"text-align:justify;\"><span style=\"font-size:18px;\">Nhằm<strong> </strong>tri ân khách hàng đã tin tưởng và sử dụng sản phẩm, dịch vụ của Nhà thuốc và xây dựng mối quan hệ lâu dài giữa Nhà thuốc và khách hàng. Nhà thuốc Trường Cao đẳng Kon Tum áp dụng Chương trình tích điểm nhận đặc quyền đối với:</span></p><p style=\"text-align:justify;\"><span style=\"font-size:18px;\">- Tất cả khách hàng mua thuốc và các sản phẩm chăm sóc sức khỏe tại Nhà thuốc.</span></p><p style=\"text-align:justify;\"><span style=\"font-size:18px;\">- Khách hàng cần <strong>đăng ký thông tin cơ bản (tên, số điện thoại)</strong> để tham gia chương trình tích điểm.</span></p><p style=\"text-align:justify;\"><span style=\"font-size:18px;\">*</span><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"> Nguyên tắc tích điểm:</span></p><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Mỗi <strong>1000 đồng giá trị mua hàng = 1 điểm tích lũy</strong>.</span></p><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Điểm được tích lũy <strong>trên tổng giá trị hóa đơn thanh toán</strong>.</span></p><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Điểm tích lũy được <strong>cộng trực tiếp vào tài khoản khách hàng theo số điện thoại đăng ký</strong>.</span></p><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Ví dụ: Hóa đơn 100.000 đồng → tích 100 điểm; Hóa đơn 350.000 đồng → tích 350 điểm</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">* Quy đổi điểm thưởng:</span></p><p style=\"text-align:justify;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><img class=\"image_resized\" style=\"aspect-ratio:649/399;width:39.57%;\" src=\"https://phongkhamcdkontum.com.vn/uploads/images/7345b9b6e0a2f866a40059efbafc7999-Screenshot2026-03-09100340.png\" width=\"649\" height=\"399\"></span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">Khách hàng có thể sử dụng điểm để <strong>trừ trực tiếp vào hóa đơn khi thanh toán</strong>.</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">* Thời hạn sử dụng điểm:</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Điểm tích lũy có <strong>thời hạn sử dụng 12 tháng</strong> kể từ ngày phát sinh.</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Sau thời gian trên, nếu khách hàng chưa sử dụng, điểm sẽ <strong>tự động hết hiệu lực</strong>.</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">* Quy định sử dụng điểm:</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Điểm tích lũy chỉ áp dụng cho chính khách hàng đã đăng ký.</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Không quy đổi điểm thành tiền mặt.</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Không chuyển nhượng điểm cho người khác (trừ trường hợp cùng số điện thoại đăng ký).</span></p><p><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\">- Nhà thuốc có quyền điều chỉnh thể lệ chương trình khi cần thiết và thông báo đến khách hàng.</span></p><p style=\"text-align:justify;\">&nbsp;</p>', 24, 0, '2026-03-09 10:16:47'),
(7, 10, 'Đo chỉ số cân nặng - Chiều cao (BMI) Online', '<hr><h2><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:18px;\"><strong>ĐO CHỈ SỐ CÂN NẶNG - CHIỀU CAO (BMI) ONLINE&nbsp;</strong></span></h2><p style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>BMI không đo lường trực tiếp mỡ của cơ thể nhưng các nghiên cứu đã chứng minh rằng BMI tương quan với đo mỡ trực tiếp. BMI là phương pháp không tốn kém và dễ thực hiện để tầm soát vấn đề sức khoẻ.</strong></span></p><h2><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Sử dụng BMI như thế nào?</strong></span></h2><p style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">BMI được sử dụng như là một công cụ tầm soát để xác định trọng lượng thích hợp cho người lớn. Tuy nhiên, BMI không phải là công cụ chẩn đoán. Ví dụ, một người có chỉ số BMI cao, để xác định trọng lượng có phải là một nguy cơ cho sức khoẻ không thì các bác sĩ cần thực hiện thêm các đánh giá khác. Những đánh giá này gồm đo độ dày nếp da, đánh giá chế độ ăn, hoạt động thể lực, tiền sử gia đình và các sàng lọc sức khoẻ khác.</span></p><h3 style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Tại sao Cơ quan kiểm soát bệnh tật Hoa Kỳ - CDC sử dụng BMI để xác định sự thừa cân và béo phì?</strong></span></h3><p style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Tính chỉ số BMI là một phương pháp tốt nhất để đánh giá thừa cân và béo phì cho một quần thể dân chúng. Để tính chỉ số BMI, người ta chỉ yêu cầu đo chiều cao và cân nặng, không tốn kém và dễ thực hiện. Sử dụng chỉ số BMI cho phép người ta so sánh tình trạng cân nặng của họ với quần thể nói chung. Công thức tính BMI theo đơn vị kilograms và mét (xem cách tính dưới đây)</span></p><h3 style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Cách tính và đánh giá chỉ số BMI như thế nào?</strong></span></h3><figure class=\"image\"><img style=\"aspect-ratio:692/477;\" src=\"https://phongkhamcdkontum.com.vn/uploads/images/f0050efbf25d8e63d2c5be66418a17f1-Screenshot_1.png\" width=\"692\" height=\"477\"></figure><p style=\"margin-left:0px;text-align:center;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Cách đánh giá chỉ số BMI</strong></span></p><p style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Đối với người lớn từ 20 tuổi trở lên, Sử dụng bảng phân loại chuẩn cho cả nam và nữ để đánh giá chỉ số BMI.</strong></span></p><ul><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">BMI &lt;16: Gầy độ III</span></li><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">16 ≤ BMI &lt;17: Gầy độ II</span></li><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">17 ≤ BMI &lt;18.5: Gầy độ I</span></li><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">18.5 ≤ BMI &lt;25: Bình thường</span></li><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">25 ≤ BMI &lt;30: Thừa cân</span></li><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">30 ≤ BMI 35: Béo phì độ 1</span></li><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">35 ≤ BMI &lt;40: Béo phì độ II</span></li><li><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">BMI &gt;40: Béo phì độ III</span></li></ul><h3 style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Nguy cơ khi chỉ số BMI thấp</strong></span></h3><p style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Kết quả test BMI thấp hơn 18,5 cho thấy cơ thể đang trong tình trạng gầy, thiếu cân, suy dinh dưỡng, loãng xương, miễn dịch suy yếu…</span></p><h3 style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\"><strong>Nguy cơ khi chỉ số BMI cao</strong></span></h3><p style=\"margin-left:0px;\"><span style=\"font-family:\'Times New Roman\', Times, serif;font-size:14px;\">Chỉ số BMI cao hơn 25 (tức là thừa cân-béo phì) làm tăng nguy cơ mắc các bệnh tim mạch, đường huyết, huyết áp, đột quỵ, một số bệnh ung thư…</span></p>', 24, 0, '2026-03-07 14:19:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_news`
--

CREATE TABLE `hicrm_news` (
  `id` int NOT NULL,
  `new_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `new_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `new_content` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `new_image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `new_type` int DEFAULT '5',
  `new_user_created` int NOT NULL,
  `new_status` int NOT NULL,
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
  `id` int NOT NULL,
  `order_customer_id` int NOT NULL,
  `order_employee_id` int NOT NULL,
  `order_payment_policy_id` int NOT NULL,
  `order_warehouse_id` int NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `order_name_contact` varchar(255) NOT NULL,
  `order_payment_active` int NOT NULL COMMENT '- Đã thanh toán.  - Chưa thanh toán',
  `order_description` text NOT NULL,
  `order_date` date NOT NULL,
  `order_active` int NOT NULL COMMENT '1. Chưa thực hiện, 2. Đang thực hiện, 3. Hoàn thành, 4. Hủy bỏ ',
  `order_delivery_date` date NOT NULL COMMENT 'Ngày giao hàng',
  `order_create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `order_product_id` int NOT NULL,
  `order_product_quantity` int NOT NULL,
  `order_product_price` decimal(10,0) NOT NULL,
  `order_product_vat_tax` int NOT NULL,
  `order_product_discount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `policy_uid` int NOT NULL,
  `policy_code` varchar(20) NOT NULL,
  `policy_title` varchar(255) NOT NULL,
  `policy_debt_day` int DEFAULT NULL,
  `policy_comission` decimal(20,2) NOT NULL DEFAULT '0.00',
  `policy_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `permission_level` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `depart` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `position_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` bigint NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_spec` varchar(255) DEFAULT NULL,
  `product_code` varchar(50) NOT NULL,
  `product_barcode` varchar(20) DEFAULT NULL,
  `product_vat_name` varchar(255) DEFAULT NULL,
  `product_unit` int NOT NULL,
  `product_category` int NOT NULL,
  `product_price` decimal(20,2) NOT NULL,
  `product_discount` decimal(20,2) DEFAULT NULL,
  `product_tax_id` int NOT NULL DEFAULT '0',
  `product_description` text,
  `product_image` text,
  `product_created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_status` int NOT NULL DEFAULT '1' COMMENT '1 - Đang bán, 2 - Không bán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_products`
--

INSERT INTO `hicrm_products` (`id`, `product_name`, `product_spec`, `product_code`, `product_barcode`, `product_vat_name`, `product_unit`, `product_category`, `product_price`, `product_discount`, `product_tax_id`, `product_description`, `product_image`, `product_created_time`, `product_status`) VALUES
(1, 'Sản phẩm 1', NULL, 'SP0001', NULL, '', 2, 1, 650000.00, 2.00, 1, 'hức ăn cho chó mọi độ tuổi, không độn ngũ cốc, không phẩm màu và chất bảo quản nhân tạo.\r\n\r\nTHÀNH PHẦN: Bột thịt gà, Khoai tây, Mỡ gà (được bảo quản với Tocopherols hỗn hợp), Bột thịt cá trắng, Trứng, Cà chua, Đậu Hà Lan, Sợi việt quất, Sợi nam việt quất, Táo, Việt quất, Cà rốt, Rau bina, Nam Việt quất, DL-Methionine, L-Lysine, Taurine, Beta-Carotene, L-Carnitine, Yucca, Cây hương thảo, Vitamin, Khoáng chất , Probiotics.\r\n\r\nBỔ SUNG (TRÊN MỖI KG): Vitamin A 12.000 IU/kg, Vitamin D3 750 IU/kg, Vitamin C 100 mg/kg, Vitamin E (α-tocopherol) 250 IU/kg, Đồng (đồng sunfat) 16 mg/kg, Omega-6 >3,3%, Omega-3 >0,4%, Methionine 1,2%, Lysine 2,1%, Taurine 0,05%, L-Carnitine 50 mg/kg, Beta-Carotene 10 mg/kg, Axit Docosahexaenoic (DHA) >0,05%.\r\n\r\nTHÀNH PHẦN PHÂN TÍCH: Đạm 38%, Chất béo 20%, Tro 10,2%, Chất xơ 2,5%, Độ ẩm 10%, Natri 0,3%, Canxi 1,5%, Phốt pho 1,0%. Kilocalories/kg: 3.800', 'abc.png', '2021-10-30 16:19:28', 99),
(2, 'Kháng sinh', NULL, '01', '', '', 1, 2, 1000000.00, 0.00, 0, '<p>ấc</p>', 'ecd99574ee4d8c7ffa4412b6aeb4b771-2908_02_b73c39a223.jpg', '2026-01-14 23:02:57', 1),
(3, 'Tiêu chảy â aa', NULL, '002', '', '', 1, 3, 555555.00, 3.00, 0, 'Thuốc điều trị tiêu chảy cấp\r\n\r\n', 'd8535c8a19519afd8bb5b91220665a62-2908_02_b73c39a223.jpg', '2026-01-14 23:07:04', 1),
(4, 'Amocinin', NULL, '003', '', '', 1, 3, 90000.00, 5.00, 0, '<p>Thuốc amocinin trị đau họng</p>', '14b0c8d644d55be8208a4ed9793a544f-thuoc-tri-viem-hong-hat-1.jpeg', '2026-01-18 22:20:03', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_products_bk`
--

CREATE TABLE `hicrm_products_bk` (
  `id` int NOT NULL,
  `product_cat_id` int NOT NULL,
  `product_unit_id` int NOT NULL,
  `product_quantity` int NOT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `product_into_money` decimal(10,0) NOT NULL,
  `product_total_money` decimal(10,0) NOT NULL,
  `product_vat_tax` int NOT NULL,
  `product_status` int NOT NULL,
  `product_create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_product_categories`
--

CREATE TABLE `hicrm_product_categories` (
  `id` int NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `category_parent` int NOT NULL DEFAULT '0',
  `category_icon` varchar(255) DEFAULT NULL,
  `category_image` text,
  `category_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_product_categories`
--

INSERT INTO `hicrm_product_categories` (`id`, `category_name`, `category_description`, `category_parent`, `category_icon`, `category_image`, `category_status`) VALUES
(1, 'Thuốc', '', 0, 'fa-solid fa-pump-medical', NULL, 1),
(2, 'Thực phẩm chức năng', '', 0, 'fa-solid fa-capsules', NULL, 1),
(3, 'Vật tư - Thiết bị y tế', '', 0, 'fa-solid fa-stethoscope', NULL, 1),
(4, 'Chăm sóc cá nhân', '', 0, 'fa-solid fa-hand-holding-medical', NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_product_warehouses`
--

CREATE TABLE `hicrm_product_warehouses` (
  `id` bigint NOT NULL,
  `pid` bigint NOT NULL,
  `wareid` int NOT NULL,
  `ware_instock` int NOT NULL DEFAULT '0',
  `ware_alert` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `id` bigint NOT NULL,
  `promo_type` int NOT NULL DEFAULT '1',
  `promo_name` varchar(255) NOT NULL,
  `promo_code` varchar(30) NOT NULL,
  `promo_discount_type` int NOT NULL DEFAULT '1' COMMENT '1 - theo tiền | 2 - Theo phần trăm',
  `promo_discount_value` decimal(20,2) NOT NULL,
  `promo_qty` int NOT NULL DEFAULT '1' COMMENT 'Số lượng',
  `promo_used` int NOT NULL DEFAULT '0',
  `promo_reuse` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 Không dùng lại | 1 được dùng lại',
  `promo_created_by` int NOT NULL,
  `promo_from` datetime NOT NULL,
  `promo_to` datetime DEFAULT NULL,
  `promo_expried` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 có hết hạn, 2 không bao giờ hết hạn',
  `promo_created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `promo_status` int NOT NULL DEFAULT '1',
  `promo_for` int NOT NULL DEFAULT '1' COMMENT '1 tất cả đơn hàng, 2 đơn hàng theo giá trị, 3 khách hàng, 4 sản phẩm',
  `promo_all_order` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - tất cả | 2 - một số',
  `promo_order_min` decimal(20,2) NOT NULL DEFAULT '0.00',
  `promo_order_max` decimal(20,2) NOT NULL DEFAULT '0.00',
  `promo_customers` text,
  `promo_products` text,
  `promo_max_apply` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` bigint NOT NULL,
  `quote_code` varchar(20) NOT NULL,
  `quote_customer` bigint NOT NULL,
  `quote_created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quote_created_by` int NOT NULL,
  `quote_status` int NOT NULL COMMENT '1 - mới tạo, 2 - khách đồng ý, 3 - khách từ chối, 4 - mới điều chỉnh, 5 đã chuyển qua đơn hàng',
  `quote_promotion` int NOT NULL DEFAULT '0',
  `quote_discount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `quote_reviewed_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` bigint NOT NULL,
  `qid` bigint NOT NULL,
  `quote_product_id` bigint NOT NULL,
  `quote_product_qty` int NOT NULL DEFAULT '1',
  `quote_product_price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `quote_product_discount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `quote_product_tax_percent` decimal(20,2) NOT NULL DEFAULT '0.00',
  `quote_product_tax` decimal(20,2) NOT NULL DEFAULT '0.00',
  `quote_product_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `quote_product_note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `request_uid` int NOT NULL,
  `request_new_salary` decimal(20,2) NOT NULL,
  `request_new_commission` decimal(20,2) NOT NULL,
  `request_note` text,
  `request_admin_note` text,
  `request_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_review_time` datetime DEFAULT NULL,
  `request_status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_description` longtext,
  `service_image` varchar(255) DEFAULT NULL,
  `service_category` int NOT NULL,
  `service_created_date` datetime NOT NULL,
  `service_status` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `hicrm_service`
--

INSERT INTO `hicrm_service` (`id`, `service_name`, `service_description`, `service_image`, `service_category`, `service_created_date`, `service_status`) VALUES
(1, 'Gói khám sức khỏe', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', NULL, 7, '2026-01-27 13:33:22', 99),
(2, 'aaaa', 'undefined', 'd25e5a510b4127a0bcb0af41dc049935-Screenshot_10.png', 6, '2026-01-27 20:09:40', 99),
(3, 'CHUYÊN KHOA SIÊU ÂM', 'undefined', '0aba10ab9a03e4df4b9b92369adc5416-tixung.png', 6, '2026-01-27 20:10:45', 99),
(4, 'CHUYÊN KHOA SIÊU ÂM', '<p>aasdasdasd</p>', 'ad4a40eaae9fd0a23b496bfcc9659fc2-PL7.Bocosdngvttyttiuhao.pdf', 6, '2026-01-27 20:18:21', 99),
(5, 'aasdasdasdas', '<p>ấdasdasdasd</p>', '10c35a605065990f54139d0a5c186e39-SDTCR1.png', 7, '2026-01-27 20:20:37', 99),
(6, 'ádasdasd', '<p>âdasdasd</p>', '0b90857b3e07bce8eb2f76531306cf69-SDTCR1.png', 7, '2026-01-27 20:21:49', 99),
(7, 'GÓI KHÁM PHỤ SẢN 1', '<div style=\"color: rgb(0, 0, 0); font-family: Consolas, \" courier=\"\" new\",=\"\" monospace;=\"\" font-size:=\"\" 18px;=\"\" line-height:=\"\" 24px;=\"\" white-space:=\"\" pre;\"=\"\" bis_skin_checked=\"1\"><span style=\"font-family: \" open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" text-align:=\"\" justify;=\"\" white-space:=\"\" normal;\"=\"\">t is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by&nbsp;</span></div>', 'e5881e2f63e45fdb3b73b56eb648daee-Screenshot_5.png', 7, '2026-01-27 20:23:21', 99),
(8, 'aaaa', '<p>sdasdasdasd</p>', '2ae43ec5307c5622dd1ca9e54b95ec68-SDTCR1.png', 6, '2026-01-28 20:03:04', 99),
(9, 'Đối tượng áp dụng', '<p></p><p></p><ul></ul><p></p><li data-start=\"202\" data-end=\"256\"><p data-start=\"204\" data-end=\"256\">Người dân có nhu cầu khám nhanh, chủ động thời gian.</p>\r\n</li><li data-start=\"257\" data-end=\"288\">\r\n<p data-start=\"259\" data-end=\"288\">Người không sử dụng thẻ BHYT.</p>\r\n</li><li data-start=\"289\" data-end=\"341\">\r\n<p data-start=\"291\" data-end=\"341\">Người muốn lựa chọn bác sĩ, gói khám theo yêu cầu.</p>\r\n</li><p>\r\n\r\n\r\n</p><li data-start=\"342\" data-end=\"410\">\r\n<p data-start=\"344\" data-end=\"410\">Khám theo nhu cầu cá nhân, theo dõi bệnh mạn tính.</p></li><ul><li data-start=\"342\" data-end=\"410\"></li></ul>', 'ff5a9104650bfcb672fd00964be5e49c-doi_tuong_tham_gia_BHXH_bat_buoc_3001134430.jpg', 6, '2026-03-03 09:24:43', 1),
(10, 'Thủ tục cần mang theo', '<li data-start=\"440\" data-end=\"474\"><p data-start=\"442\" data-end=\"474\">CCCD/CMND hoặc VNeID mức 2.</p>\r\n</li><li data-start=\"475\" data-end=\"503\">\r\n<p data-start=\"477\" data-end=\"503\">Hồ sơ bệnh án cũ (nếu có).</p>\r\n</li><p>\r\n\r\n</p><li data-start=\"504\" data-end=\"538\">\r\n<p data-start=\"506\" data-end=\"538\">Đơn thuốc đang sử dụng (nếu có).</p></li>', 'cbd490dc6f23bb17489879dae4fb9a53-icon.png', 6, '2026-03-03 15:57:09', 1),
(11, 'Bảng giá dịch vụ', '<iframe src=\"https://phongkhamcdkontum.com.vn/uploads/files/73fefa1a1f095e7bd5916841d496c5ca-Phlc-GiKBCBbanhnhkmtheoQ.pdf\" style=\"width:100%; height:800px;\">\r\n</iframe>', '3ae54baf43c72de44eef705c57fbf367-gi.png', 6, '2026-03-03 15:58:46', 1),
(12, 'Quy trình khám chữa bệnh dịch vụ', '<h2 data-start=\"610\" data-end=\"630\">&nbsp;Quy trình khám</h2>\r\n<ol data-start=\"631\" data-end=\"888\">\r\n<li data-start=\"631\" data-end=\"671\">\r\n<p data-start=\"634\" data-end=\"671\">Đăng ký thông tin tại quầy tiếp nhận.</p>\r\n</li>\r\n<li data-start=\"672\" data-end=\"711\">\r\n<p data-start=\"675\" data-end=\"711\">Nộp phí khám theo bảng giá niêm yết.</p>\r\n</li>\r\n<li data-start=\"712\" data-end=\"740\">\r\n<p data-start=\"715\" data-end=\"740\">Khám lâm sàng với bác sĩ.</p>\r\n</li>\r\n<li data-start=\"741\" data-end=\"815\">\r\n<p data-start=\"744\" data-end=\"815\">Thực hiện cận lâm sàng (xét nghiệm, siêu âm, X-quang…) nếu có chỉ định.</p>\r\n</li>\r\n<li data-start=\"816\" data-end=\"850\">\r\n<p data-start=\"819\" data-end=\"850\">Nhận kết quả – tư vấn điều trị.</p>\r\n</li>\r\n<li data-start=\"851\" data-end=\"888\">\r\n<p data-start=\"854\" data-end=\"888\">Thanh toán.</p>\r\n</li>\r\n</ol>\r\n<p data-start=\"890\" data-end=\"952\">⏱ Thời gian linh hoạt, ưu tiên nhanh chóng, không chờ đợi lâu.</p>', '3bff18b079bc97df2cb3d5327e41e515-tixung.jpg', 6, '2026-03-03 16:10:32', 1),
(13, 'Đối tượng áp dụng', '<li data-start=\"1324\" data-end=\"1364\"><p data-start=\"1326\" data-end=\"1364\">Người có thẻ BHYT còn giá trị sử dụng.</p></li><p>\r\n\r\n</p><li data-start=\"1447\" data-end=\"1497\">\r\n<p data-start=\"1449\" data-end=\"1497\">Trường hợp cấp cứu được tiếp nhận theo quy định.</p></li>', 'ad3f261e2ee1f639c28ed682f254e004-tixung1.jpg', 7, '2026-03-03 16:13:13', 1),
(14, 'Thủ tục cần mang theo', '<li data-start=\"1527\" data-end=\"1550\"><p data-start=\"1529\" data-end=\"1550\">Một trong các loại hồ sơ: Thẻ BHYT còn giá trị; Căn cước công dân; Tài khoản VNeID mức 2.</p></li><p>\r\n\r\n\r\n</p><li data-start=\"1638\" data-end=\"1667\">\r\n<p data-start=\"1640\" data-end=\"1667\">Giấy hẹn tái khám (nếu có).</p></li>', '647ef8753faec8a472d582b041cc016e-icon.png', 7, '2026-03-03 16:15:16', 1),
(15, 'Quy trình khám chữa bệnh BHYT', '<li data-start=\"1695\" data-end=\"1730\"><p data-start=\"1698\" data-end=\"1730\">Lấy số và đăng ký tại quầy BHYT.</p>\r\n</li><li data-start=\"1731\" data-end=\"1775\">\r\n<p data-start=\"1734\" data-end=\"1775\">Xuất trình thẻ BHYT hoặc CCCD hoặc tài khoản VNeID mức 2.</p>\r\n</li><li data-start=\"1776\" data-end=\"1810\">\r\n<p data-start=\"1779\" data-end=\"1810\">Khám lâm sàng theo chuyên khoa.</p>\r\n</li><li data-start=\"1811\" data-end=\"1859\">\r\n<p data-start=\"1814\" data-end=\"1859\">Thực hiện xét nghiệm, siêu âm… theo chỉ định.</p>\r\n</li><li data-start=\"1860\" data-end=\"1896\">\r\n<p data-start=\"1863\" data-end=\"1896\">Nhận thuốc BHYT và hướng dẫn điều trị.</p>\r\n</li><p>\r\n\r\n\r\n\r\n\r\n</p><li data-start=\"1897\" data-end=\"1946\">\r\n<p data-start=\"1900\" data-end=\"1946\">Thanh toán phần chi phí đồng chi trả (nếu có).</p></li>', '9e1dda75dd8151085e1f3af9dc861b9c-tixung.jpg', 7, '2026-03-03 16:21:15', 1),
(16, 'Bảng giá dịch vụ KCB BHYT', '<iframe src=\"https://phongkhamcdkontum.com.vn/uploads/files/gia-bh.pdf\" style=\"width:100%; height:800px;\">\r\n\r\n</iframe>', '6712875a8114665133b09aad7d768415-gi.png', 7, '2026-03-03 16:30:20', 1),
(17, 'Quy định mức hưởng BHYT', '<h1 data-start=\"127\" data-end=\"173\">MỨC HƯỞNG BẢO HIỂM Y TẾ VÀ ĐỐI TƯỢNG ÁP DỤNG</h1><h2 data-start=\"175\" data-end=\"240\">I. Hưởng 100% chi phí khám, chữa bệnh trong phạm vi được hưởng</h2><h3 data-start=\"242\" data-end=\"258\">1. Đối tượng</h3><ul data-start=\"259\" data-end=\"660\">\r\n<li data-start=\"259\" data-end=\"289\">\r\n<p data-start=\"261\" data-end=\"289\">Người có công với cách mạng.</p>\r\n</li>\r\n<li data-start=\"290\" data-end=\"307\">\r\n<p data-start=\"292\" data-end=\"307\">Cựu chiến binh.</p>\r\n</li>\r\n<li data-start=\"308\" data-end=\"329\">\r\n<p data-start=\"310\" data-end=\"329\">Trẻ em dưới 6 tuổi.</p>\r\n</li>\r\n<li data-start=\"330\" data-end=\"353\">\r\n<p data-start=\"332\" data-end=\"353\">Người thuộc hộ nghèo.</p>\r\n</li>\r\n<li data-start=\"354\" data-end=\"455\">\r\n<p data-start=\"356\" data-end=\"455\">Người dân tộc thiểu số sinh sống tại vùng có điều kiện kinh tế – xã hội khó khăn/đặc biệt khó khăn.</p>\r\n</li>\r\n<li data-start=\"456\" data-end=\"496\">\r\n<p data-start=\"458\" data-end=\"496\">Người sinh sống tại xã đảo, huyện đảo.</p>\r\n</li>\r\n<li data-start=\"497\" data-end=\"545\">\r\n<p data-start=\"499\" data-end=\"545\">Người hưởng trợ cấp bảo trợ xã hội hằng tháng.</p>\r\n</li>\r\n<li data-start=\"546\" data-end=\"566\">\r\n<p data-start=\"548\" data-end=\"566\">Thân nhân liệt sĩ.</p>\r\n</li>\r\n<li data-start=\"567\" data-end=\"597\">\r\n<p data-start=\"569\" data-end=\"597\">Khám chữa bệnh tại tuyến xã.</p>\r\n</li>\r\n<li data-start=\"598\" data-end=\"660\">\r\n<p data-start=\"600\" data-end=\"660\">Chi phí cho một lần khám thấp hơn mức do Chính phủ quy định.</p>\r\n</li>\r\n</ul><h3 data-start=\"662\" data-end=\"678\">2. Quyền lợi</h3><ul data-start=\"679\" data-end=\"831\">\r\n<li data-start=\"679\" data-end=\"756\">\r\n<p data-start=\"681\" data-end=\"756\">Được quỹ BHYT thanh toán 100% chi phí trong phạm vi quyền lợi và mức hưởng.</p>\r\n</li>\r\n<li data-start=\"757\" data-end=\"831\">\r\n<p data-start=\"759\" data-end=\"831\">Không phải đồng chi trả (trừ trường hợp sử dụng dịch vụ ngoài danh mục).</p>\r\n</li>\r\n</ul><hr data-start=\"833\" data-end=\"836\"><h2 data-start=\"838\" data-end=\"878\">II. Hưởng 95% chi phí khám, chữa bệnh</h2><h3 data-start=\"880\" data-end=\"896\">1. Đối tượng</h3><ul data-start=\"897\" data-end=\"1051\">\r\n<li data-start=\"897\" data-end=\"958\">\r\n<p data-start=\"899\" data-end=\"958\">Người hưởng lương hưu, trợ cấp mất sức lao động hằng tháng.</p>\r\n</li>\r\n<li data-start=\"959\" data-end=\"1023\">\r\n<p data-start=\"961\" data-end=\"1023\">Thân nhân người có công với cách mạng (trừ thân nhân liệt sĩ).</p>\r\n</li>\r\n<li data-start=\"1024\" data-end=\"1051\">\r\n<p data-start=\"1026\" data-end=\"1051\">Người thuộc hộ cận nghèo.</p>\r\n</li>\r\n</ul><h3 data-start=\"1053\" data-end=\"1069\">2. Quyền lợi</h3><ul data-start=\"1070\" data-end=\"1159\">\r\n<li data-start=\"1070\" data-end=\"1129\">\r\n<p data-start=\"1072\" data-end=\"1129\">Quỹ BHYT thanh toán 95% chi phí trong phạm vi được hưởng.</p>\r\n</li>\r\n<li data-start=\"1130\" data-end=\"1159\">\r\n<p data-start=\"1132\" data-end=\"1159\">Người bệnh đồng chi trả 5%.</p>\r\n</li>\r\n</ul><hr data-start=\"1161\" data-end=\"1164\"><h2 data-start=\"1166\" data-end=\"1207\">III. Hưởng 80% chi phí khám, chữa bệnh</h2><h3 data-start=\"1209\" data-end=\"1225\">1. Đối tượng</h3><ul data-start=\"1226\" data-end=\"1370\">\r\n<li data-start=\"1226\" data-end=\"1266\">\r\n<p data-start=\"1228\" data-end=\"1266\">Người lao động tham gia BHYT bắt buộc.</p>\r\n</li>\r\n<li data-start=\"1267\" data-end=\"1289\">\r\n<p data-start=\"1269\" data-end=\"1289\">Học sinh, sinh viên.</p>\r\n</li>\r\n<li data-start=\"1290\" data-end=\"1329\">\r\n<p data-start=\"1292\" data-end=\"1329\">Người tham gia BHYT theo hộ gia đình.</p>\r\n</li>\r\n<li data-start=\"1330\" data-end=\"1370\">\r\n<p data-start=\"1332\" data-end=\"1370\">Các nhóm đối tượng khác theo quy định.</p>\r\n</li>\r\n</ul><h3 data-start=\"1372\" data-end=\"1388\">2. Quyền lợi</h3><p>\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><ul data-start=\"1389\" data-end=\"1479\">\r\n<li data-start=\"1389\" data-end=\"1448\">\r\n<p data-start=\"1391\" data-end=\"1448\">Quỹ BHYT thanh toán 80% chi phí trong phạm vi được hưởng.</p>\r\n</li>\r\n<li data-start=\"1449\" data-end=\"1479\">\r\n<p data-start=\"1451\" data-end=\"1479\">Người bệnh đồng chi trả 20%.</p></li></ul>', 'f52a12cc15004762ae303d644cc9978b-Mchng.jpg', 7, '2026-03-03 16:33:50', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_spend_collectes`
--

CREATE TABLE `hicrm_spend_collectes` (
  `id` int NOT NULL,
  `spend_collecte_code` int NOT NULL,
  `spend_collecte_name` varchar(255) NOT NULL,
  `spend_collecte_type` int NOT NULL COMMENT '1 - Mục thu. 2- Mục chi',
  `spend_collecte_active` int NOT NULL DEFAULT '0' COMMENT '1-Phát sinh định kỳ. 0-Không phát sinh định kỳ',
  `spend_collecte_parent` int NOT NULL,
  `spend_collecte_description` text,
  `spend_collecte_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `status_label` varchar(255) NOT NULL,
  `status_class` varchar(255) DEFAULT NULL,
  `status_icon` varchar(255) DEFAULT NULL,
  `status_type` int NOT NULL COMMENT '1 Chung, 2 báo giá, 3 đơn hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `supplie_code` varchar(10) NOT NULL,
  `supplie_name` varchar(255) NOT NULL,
  `supplie_status` int NOT NULL,
  `supplie_parent` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `tax_name` varchar(255) NOT NULL,
  `tax_value` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_type` int NOT NULL,
  `template_html` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `template_type_code` varchar(50) NOT NULL,
  `template_type_name` varchar(255) NOT NULL,
  `template_type_description` text,
  `template_type_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `tool_warehouse_id` int NOT NULL,
  `tool_category_id` int NOT NULL,
  `tool_voucher_number` varchar(255) NOT NULL COMMENT 'Số chứng từ',
  `tool_date` date NOT NULL COMMENT 'Ngày ghi tăng',
  `tool_allotment_time` int NOT NULL COMMENT 'Số kỳ phân bổ',
  `tool_allotment_money` decimal(10,0) NOT NULL,
  `tool_code` varchar(100) NOT NULL,
  `tool_description` text NOT NULL,
  `tool_name` varchar(255) NOT NULL,
  `tool_quantity` int NOT NULL,
  `tool_unit` int NOT NULL,
  `tool_price` int NOT NULL,
  `tool_total_money` decimal(10,0) NOT NULL,
  `tool_is_stop` int NOT NULL,
  `tool_reason_stop` text,
  `tool_active` int NOT NULL DEFAULT '1',
  `tool_create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tool_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_transactions`
--

CREATE TABLE `hicrm_transactions` (
  `id` bigint NOT NULL,
  `uid` bigint NOT NULL,
  `trans_code` varchar(20) NOT NULL,
  `trans_type` int NOT NULL,
  `trans_bank` int NOT NULL DEFAULT '1',
  `trans_method` int NOT NULL,
  `trans_amount` decimal(20,2) NOT NULL,
  `trans_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_hash` varchar(64) NOT NULL,
  `trans_status` int NOT NULL,
  `trans_note` text,
  `trans_data` text,
  `trans_approved_by` int DEFAULT NULL,
  `trans_approved_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_type`
--

CREATE TABLE `hicrm_type` (
  `id` int NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_detail` int NOT NULL COMMENT '1. Giới thiệu\r\n2. Dịch vụ\r\n3. Chuyên khoa\r\n4. Nhà thuốc',
  `type_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `unit_code` varchar(30) NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `unit_description` text,
  `unit_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` bigint NOT NULL,
  `user_username` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_fullname` varchar(255) DEFAULT NULL,
  `user_phone` varchar(30) DEFAULT NULL,
  `user_group` int NOT NULL DEFAULT '4',
  `user_dept` int NOT NULL DEFAULT '1',
  `user_address` text,
  `user_avatar` text,
  `user_status` int NOT NULL,
  `user_commission` decimal(20,2) NOT NULL DEFAULT '0.00',
  `user_basic_salary` decimal(20,2) NOT NULL DEFAULT '0.00',
  `user_register_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_class` varchar(255) DEFAULT NULL,
  `group_icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` int NOT NULL,
  `warehouse_uid` int NOT NULL,
  `warehouse_branch_id` int NOT NULL,
  `warehouse_code` varchar(255) NOT NULL,
  `warehouse_quantity` int DEFAULT NULL,
  `warehouse_name` varchar(255) NOT NULL,
  `warehouse_description` text,
  `warehouse_parent` int NOT NULL,
  `warehouse_create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `warehouse_status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `id` bigint NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `otp_uid` bigint NOT NULL,
  `otp_exp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
  `page_type` int NOT NULL,
  `page_content` longtext NOT NULL,
  `page_uid` int NOT NULL,
  `page_status` int NOT NULL,
  `page_created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
-- Chỉ mục cho bảng `hicrm_customer_feedback`
--
ALTER TABLE `hicrm_customer_feedback`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT cho bảng `hicrm_banks`
--
ALTER TABLE `hicrm_banks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT cho bảng `hicrm_bank_accounts`
--
ALTER TABLE `hicrm_bank_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hicrm_bookings`
--
ALTER TABLE `hicrm_bookings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `hicrm_booking_status`
--
ALTER TABLE `hicrm_booking_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_branchs`
--
ALTER TABLE `hicrm_branchs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_calendar_works`
--
ALTER TABLE `hicrm_calendar_works`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_caludar_employees`
--
ALTER TABLE `hicrm_caludar_employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_categories`
--
ALTER TABLE `hicrm_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `hicrm_category_parent`
--
ALTER TABLE `hicrm_category_parent`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_configs`
--
ALTER TABLE `hicrm_configs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `hicrm_currencies`
--
ALTER TABLE `hicrm_currencies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT cho bảng `hicrm_customers`
--
ALTER TABLE `hicrm_customers`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `hicrm_customer_banks`
--
ALTER TABLE `hicrm_customer_banks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_customer_feedback`
--
ALTER TABLE `hicrm_customer_feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_customer_groups`
--
ALTER TABLE `hicrm_customer_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `hicrm_departments`
--
ALTER TABLE `hicrm_departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `hicrm_employees`
--
ALTER TABLE `hicrm_employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `hicrm_employee_banks`
--
ALTER TABLE `hicrm_employee_banks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_events`
--
ALTER TABLE `hicrm_events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `hicrm_expense_items`
--
ALTER TABLE `hicrm_expense_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_images`
--
ALTER TABLE `hicrm_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT cho bảng `hicrm_incomes`
--
ALTER TABLE `hicrm_incomes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `hicrm_income_details`
--
ALTER TABLE `hicrm_income_details`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hicrm_income_types`
--
ALTER TABLE `hicrm_income_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_introduce`
--
ALTER TABLE `hicrm_introduce`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `hicrm_news`
--
ALTER TABLE `hicrm_news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_orders`
--
ALTER TABLE `hicrm_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_order_details`
--
ALTER TABLE `hicrm_order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_payment_policies`
--
ALTER TABLE `hicrm_payment_policies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_permissions`
--
ALTER TABLE `hicrm_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `hicrm_permission_datas`
--
ALTER TABLE `hicrm_permission_datas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT cho bảng `hicrm_positions`
--
ALTER TABLE `hicrm_positions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `hicrm_products`
--
ALTER TABLE `hicrm_products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_products_bk`
--
ALTER TABLE `hicrm_products_bk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_product_categories`
--
ALTER TABLE `hicrm_product_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hicrm_product_warehouses`
--
ALTER TABLE `hicrm_product_warehouses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_promotions`
--
ALTER TABLE `hicrm_promotions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_quotes`
--
ALTER TABLE `hicrm_quotes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_quote_details`
--
ALTER TABLE `hicrm_quote_details`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_request_salary`
--
ALTER TABLE `hicrm_request_salary`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_service`
--
ALTER TABLE `hicrm_service`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `hicrm_spend_collectes`
--
ALTER TABLE `hicrm_spend_collectes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `hicrm_status`
--
ALTER TABLE `hicrm_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `hicrm_supplies`
--
ALTER TABLE `hicrm_supplies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `hicrm_taxs`
--
ALTER TABLE `hicrm_taxs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hicrm_templates`
--
ALTER TABLE `hicrm_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hicrm_template_types`
--
ALTER TABLE `hicrm_template_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `hicrm_transactions`
--
ALTER TABLE `hicrm_transactions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hicrm_type`
--
ALTER TABLE `hicrm_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hicrm_units`
--
ALTER TABLE `hicrm_units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `hicrm_users`
--
ALTER TABLE `hicrm_users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT cho bảng `hicrm_user_groups`
--
ALTER TABLE `hicrm_user_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hicrm_warehouses`
--
ALTER TABLE `hicrm_warehouses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `system_otp`
--
ALTER TABLE `system_otp`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
