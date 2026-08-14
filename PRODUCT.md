# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users
* **Khách vãng lai / Người dân địa phương**: Muốn truy cập cổng thông tin để tra cứu sơ đồ chợ, tìm kiếm vị trí các sạp hàng, ngành hàng kinh doanh và tìm thông tin liên hệ của tiểu thương.
* **Tiểu thương / Hộ kinh doanh**: Muốn tìm kiếm thông tin các sạp trống, xem giá thuê công khai và thực hiện đăng ký thuê sạp trực tuyến qua cổng đăng ký.
* **Ban quản lý chợ (BQL) & Sở Công thương**: Sử dụng cổng thông tin công cộng để công bố các thông báo, sơ đồ chợ chính thức, thống kê số liệu hoạt động và giám sát an toàn thực phẩm.

## Product Purpose
* Cổng thông tin điện tử chợ truyền thống tỉnh Quảng Ngãi (quanlycho.vn).
* Số hóa bản đồ sơ đồ sạp, tăng tính minh bạch trong việc cho thuê và hoạt động thương mại tại các chợ truyền thống.
* Cung cấp cổng đăng ký trực tuyến tiện lợi cho tiểu thương mới và tra cứu thông tin nhanh chóng cho người dân.

## Positioning
Cổng thông tin chuyên biệt đầu tiên số hóa toàn bộ sơ đồ sạp hàng, hộ kinh doanh và dịch vụ tiện ích của các chợ truyền thống tại Quảng Ngãi, kết nối dữ liệu trực tiếp với hệ thống quản lý nội bộ đã hoàn thiện của Ban quản lý chợ.

## Operating Context
* Chạy trên mọi trình duyệt web hiện đại (máy tính để bàn cho quản lý, điện thoại di động và máy tính bảng cho khách vãng lai và tiểu thương khi đang ở khu vực chợ).
* Kết nối trực tiếp tới máy chủ dữ liệu MariaDB cục bộ và cơ sở dữ liệu `quanlycho.vn`.

## Capabilities and Constraints
* **Chức năng chính**:
  * Tra cứu bản đồ sơ đồ chợ tương tác (`template/frontend/home/map.php`).
  * Trang giới thiệu hệ thống (`template/frontend/home/about.php`).
  * Biểu mẫu đăng ký thuê sạp trực tuyến (`template/frontend/home/register.php`).
  * Danh mục tiểu thương (`template/frontend/home/traders.php`).
  * Hệ thống admin nội bộ đã hoàn thiện hoàn toàn cho BQL.
* **Ràng buộc**:
  * Sử dụng cơ sở dữ liệu MySQL/MariaDB hiện tại với các bảng như `markets`, `areas`, `stalls`, `contracts`, `bills`.
  * Tích hợp với framework PHP tùy biến của dự án.
  * Phải tương thích hiển thị responsive tốt trên thiết bị di động (vì tiểu thương/khách hàng truy cập chính qua điện thoại).

## Brand Commitments
* **Tên ứng dụng**: Hệ thống phần mềm quản lý chợ tỉnh Quảng Ngãi (`quanlycho.vn`).
* **Giao diện**: Chủ đạo bằng tiếng Việt, thiết kế hiện đại, tin cậy, bố cục rõ ràng để người dân và tiểu thương dễ dàng tra cứu.

## Evidence on Hand
* Bản đồ tương tác và dữ liệu thực tế hơn 1,240 sạp và 986 tiểu thương hoạt động trong cơ sở dữ liệu SQL.
* Bộ mã nguồn template frontend hiện có (`footer.php`, `header.php`, `navbar.php`, `index.php`, `map.php`, `register.php`, `traders.php`, `tree.php`).

## Product Principles
1. **Dữ liệu Thời gian thực (Real-time & Reliable)**: Dữ liệu sơ đồ sạp và trạng thái thuê phải khớp chính xác với hệ thống admin của BQL.
2. **Tiếp cận Đơn giản (Radical Simplicity)**: Giao diện tra cứu sơ đồ và tiểu thương phải cực kỳ trực quan, dễ dùng trên điện thoại cho mọi lứa tuổi (kể cả tiểu thương lớn tuổi).
3. **Minh bạch & Công khai (Transparency)**: Các vị trí sạp trống, giá cả, và thủ tục đăng ký phải được hiển thị rõ ràng, công bằng.
4. **Hiệu năng & Tốc độ tải (Speed & Performance)**: Tối ưu tải sơ đồ sạp đồ họa mượt mà trên môi trường kết nối di động 3G/4G tại chợ.
