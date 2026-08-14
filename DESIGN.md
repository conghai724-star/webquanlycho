---
name: quanlycho.vn - Frontend
description: Cổng thông tin điện tử chợ truyền thống tỉnh Quảng Ngãi
colors:
  primary: "#FF9800"
  primary-deep: "#F57C00"
  blue-900: "#0D3C7A"
  blue-700: "#1565C0"
  blue-600: "#1976D2"
  blue-50: "#EAF2FC"
  neutral-text: "#1C2733"
  neutral-muted: "#5B6B7A"
  border: "#DCE3EA"
  bg-light: "#F4F7FA"
  white: "#FFFFFF"
typography:
  display:
    fontFamily: "Be Vietnam Pro, sans-serif"
    fontSize: "clamp(24px, 3vw, 34px)"
    fontWeight: 800
    lineHeight: 1.25
  body:
    fontFamily: "Be Vietnam Pro, sans-serif"
    fontSize: "15px"
    fontWeight: 400
    lineHeight: 1.5
  code:
    fontFamily: "Roboto Mono, monospace"
    fontSize: "13px"
    fontWeight: 500
rounded:
  sm: "8px"
  md: "14px"
  lg: "22px"
spacing:
  sm: "10px"
  md: "16px"
  lg: "24px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.white}"
    rounded: "999px"
    padding: "14px 26px"
  button-primary-hover:
    backgroundColor: "{colors.primary-deep}"
  button-secondary:
    backgroundColor: "rgba(255, 255, 255, 0.14)"
    textColor: "{colors.white}"
    rounded: "999px"
    padding: "14px 26px"
  button-outline:
    backgroundColor: "{colors.white}"
    textColor: "{colors.blue-700}"
    rounded: "999px"
    padding: "14px 26px"
---

# Design System: quanlycho.vn (Frontend)

## Overview

**Creative North Star: "Cổng Chợ Hiện Đại - Sạch Sẽ & Minh Bạch"**

Hệ thống thiết kế hướng tới việc xây dựng một cổng thông tin điện tử hiện đại, tin cậy, và minh bạch dành riêng cho các chợ truyền thống tại tỉnh Quảng Ngãi. Giao diện tập trung vào việc hiển thị sơ đồ sạp trực quan, tra cứu tiểu thương dễ dàng và đơn giản hóa thủ tục đăng ký thuê sạp cho người dân.

**Key Characteristics:**
* **Ngăn nắp & Rõ ràng**: Cấu trúc dữ liệu và các bảng biểu hiển thị mạch lạc, giúp người dùng phổ thông dễ dàng tìm thấy thông tin.
* **Mềm mại & Thân thiện**: Sử dụng các góc bo lớn (`--radius-md`, `--radius-lg`) và các nút dạng tròn (`pill`) tạo cảm giác dễ tiếp cận, không cứng nhắc hành chính.
* **Nổi & Tactile**: Chiều sâu giao diện được phân tách rõ ràng bằng các lớp bóng đổ nhẹ nhàng (`--shadow-sm`, `--shadow-md`) để làm nổi bật các khu vực thông tin quan trọng.

## Colors

Bảng màu kết hợp giữa tông màu xanh biển đậm của các dịch vụ hành chính công ích và màu cam ấm áp đại diện cho sự phồn thịnh, giao thương nhộn nhịp của chợ truyền thống.

### Primary
* **Orange Accent** (`#FF9800`): Dùng cho các phần tử hành động chính (primary CTA), tiêu điểm cần chú ý hoặc trang trí nhỏ nổi bật.
* **Orange Hover** (`#F57C00`): Trạng thái hover cho các nút hành động chính.

### Secondary
* **Deep Brand Blue** (`#0D3C7A`): Sử dụng cho các tiêu đề chính, các phần hero banner tối và logo thương hiệu.
* **Active Brand Blue** (`#1565C0`): Dùng cho liên kết, nút bấm phụ nổi bật hoặc trạng thái active của menu.
* **Light Tint Blue** (`#EAF2FC`): Nền nhạt cho các khu vực highlight hoặc hover liên kết.

### Neutral
* **Charcoal Text** (`#1C2733`): Màu chữ nội dung chính, độ tương phản cao, dễ đọc.
* **Slate Muted** (`#5B6B7A`): Chữ phụ, mô tả nhỏ hoặc icon.
* **Border Gray** (`#DCE3EA`): Màu viền ngăn cách nhẹ giữa các phần tử.
* **Light bg** (`#F4F7FA`): Màu nền phụ cho các section để tách biệt với nền trắng chính.

## Typography

**Display Font:** Be Vietnam Pro (sans-serif)
**Body Font:** Be Vietnam Pro (sans-serif)
**Label/Mono Font:** Roboto Mono (monospace)

Sự kết hợp đồng nhất font chữ Be Vietnam Pro giúp tối ưu hóa khả năng hiển thị tiếng Việt có dấu cực kỳ chuẩn xác và hiện đại trên cả máy tính lẫn thiết bị di động. Roboto Mono được dùng riêng cho các mã sạp hàng hoặc số liệu thống kê để hiển thị rõ ràng nhất.

### Hierarchy
* **Display** (800, `clamp(24px, 3vw, 34px)`, 1.25): Sử dụng cho tiêu đề chính ở Hero và các tiêu đề mục lớn.
* **Headline** (700, 20px, 1.3): Tiêu đề các thẻ (card headings) hoặc tiêu đề nhóm.
* **Title** (600, 16.5px, 1.4): Tiêu đề mục nhỏ hoặc nút nhấn lớn.
* **Body** (400, 15px, 1.5): Nội dung văn bản thường (giới hạn tối đa 65-75 ký tự trên một dòng để dễ đọc).
* **Label** (Roboto Mono, 500, 13px, bình thường): Mã sạp, mã hóa đơn, thông tin số liệu.

## Layout

Hệ thống lưới linh hoạt đảm bảo hiển thị tốt trên cả màn hình lớn của Ban quản lý và điện thoại di động của tiểu thương tại chợ.

* **Container**: Độ rộng cố định ở mức 90% (`width: 90%`, `max-width: 90%`) căn giữa.
* **Header**: Cố định phía trên (`fixed-top`, chiều cao `76px`) sử dụng làm mờ nền (`backdrop-filter: blur(10px)`) và viền dưới siêu nhẹ.
* **Rhythm**: Khoảng cách lề (padding/margin) tuân theo thang chuẩn `10px` / `16px` / `24px` để tạo nhịp điệu thị giác nhất quán.

## Elevation & Depth

Surfaces được định nghĩa chiều sâu rõ ràng dựa trên các thẻ nổi (floating cards).

### Shadow Vocabulary
* **Shadow Small** (`0 2px 8px rgba(13, 60, 122, 0.08)`): Dùng cho các thẻ nhỏ, các phần tử hover nhẹ hoặc header cố định khi scroll.
* **Shadow Medium** (`0 8px 24px rgba(13, 60, 122, 0.12)`): Dùng cho các block thẻ thông tin chính trên trang chủ, bản đồ số.
* **Shadow Large** (`0 16px 40px rgba(13, 60, 122, 0.16)`): Dùng cho modal, popup hoặc biểu mẫu đăng ký nổi bật.

## Shapes

Ngôn ngữ hình khối mềm mại là đặc trưng của hệ thống thiết kế này để giảm bớt tính khô khan của phần mềm quản lý hành chính.

* **Radius Small** (8px): Bo góc nhẹ cho các phần tử nhỏ như menu item dropdown, các ô chọn trong form.
* **Radius Medium** (14px): Bo góc cho các card sản phẩm, kiot, sạp hàng thông thường.
* **Radius Large** (22px): Bo góc cho các section lớn, modal popup hoặc hình ảnh đại diện lớn.
* **Radius Pill** (999px): Áp dụng cho toàn bộ các nút bấm (`.btn`) và nút đăng nhập.

## Components

### Buttons
* **Shape**: Dạng bo tròn hoàn toàn (`999px` / pill-shape).
* **Primary**: Nền cam (`--orange`), chữ trắng. Có bóng đổ cam khi ở trạng thái tĩnh.
* **Hover State**: Đổi nền sang cam đậm (`--orange-600`), nâng bóng đổ và di chuyển trượt lên nhẹ (`transform: translateY(-2px)`).
* **Secondary**: Nền trong suốt màu trắng nhẹ (`rgba(255, 255, 255, 0.14)`) kết hợp viền mờ, dùng trên nền tối của hero banner.

### Cards / Containers
* **Corner Style**: `14px` (`--radius-md`).
* **Background**: Màu trắng tinh khiết, viền mỏng `1px solid var(--gray-300)` kết hợp với bóng đổ `shadow-sm` hoặc `shadow-md` để phân biệt với nền trang.

### Inputs / Fields
* **Style**: Viền `1.5px` màu xám nhạt (`--gray-300`), bo góc `8px`.
* **Focus**: Viền đổi sang màu xanh thương hiệu (`--blue-700`) hoặc cam (`--orange`) kết hợp hiệu ứng viền sáng nhẹ.

## Do's and Don't

### Do:
* **Do** Luôn kế thừa và sử dụng các biến CSS custom properties định nghĩa trong `:root` để nhất quán màu sắc và bo góc.
* **Do** Giữ thiết kế có tỷ lệ tương phản cao đối với chữ viết để đảm bảo khả năng tiếp cận (Accessibility) tốt cho người lớn tuổi.
* **Do** Áp dụng hiệu ứng hover trượt lên nhẹ cho các nút để tạo cảm giác phản hồi nhanh nhạy.

### Don't:
* **Don't** Không sử dụng các thiết kế bo góc sắc nhọn (nhỏ hơn 8px) cho các khối nội dung lớn.
* **Don't** Không sử dụng màu đen thuần (`#000`), thay vào đó sử dụng màu xám đậm của chữ (`--gray-900` / `#1C2733`).
* **Don't** Không đặt chữ xám nhạt trên các nền màu sáng để tránh lỗi độ tương phản thấp.
