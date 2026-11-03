# BUỔI 8 - XÂY DỰNG JSON API VÀ FETCH API

## 1. Giới thiệu

Buổi 8 xây dựng chức năng cung cấp dữ liệu đặt phòng thông qua JSON API và sử dụng JavaScript Fetch API để lấy, xử lý và hiển thị dữ liệu mà không cần tải lại toàn bộ trang.

Dự án được phát triển dựa trên cấu trúc MVC và Repository từ Buổi 6, đồng thời kế thừa chức năng đăng nhập và phân quyền từ Buổi 7.

## 2. Công nghệ sử dụng

- PHP 8.2
- MySQL 8.0
- PDO
- Composer và Autoload
- JavaScript Fetch API
- HTML và CSS
- Mô hình MVC
- Repository Pattern

## 3. Chức năng đã thực hiện

### 3.1. Xây dựng JSON API

File: `public/api/bookings.php`

API cung cấp danh sách yêu cầu đặt phòng dưới định dạng JSON.

Các chức năng:
- Sử dụng phương thức GET để lấy dữ liệu.
- Kiểm tra trạng thái đăng nhập trước khi truy cập.
- Admin có thể xem toàn bộ danh sách đặt phòng.
- User chỉ có thể xem các yêu cầu đặt phòng của mình.
- Hỗ trợ tìm kiếm thông qua tham số keyword.
- Trả về mã trạng thái HTTP phù hợp.

Định dạng dữ liệu trả về:

{
    "success": true,
    "message": "Lấy danh sách đặt phòng thành công.",
    "data": []
}

Các mã trạng thái được sử dụng:
- 200: Lấy dữ liệu thành công.
- 401: Người dùng chưa đăng nhập.
- 405: Phương thức HTTP không được hỗ trợ.
- 500: Lỗi xử lý phía máy chủ.

### 3.2. Sử dụng Fetch API

File: `views/bookings/index.php`

JavaScript Fetch API được sử dụng để gửi yêu cầu đến JSON API và nhận dữ liệu trả về.

Các bước xử lý:
1. Gửi yêu cầu GET đến `api/bookings.php`.
2. Nhận phản hồi từ máy chủ.
3. Chuyển dữ liệu JSON thành đối tượng JavaScript bằng `response.json()`.
4. Kiểm tra kết quả trả về.
5. Hiển thị danh sách đặt phòng lên giao diện.

### 3.3. Xử lý trạng thái giao diện

Giao diện hỗ trợ:
- Loading: Hiển thị thông báo khi đang tải dữ liệu.
- Success: Hiển thị số lượng và danh sách đặt phòng nhận được.
- Error: Hiển thị thông báo khi xảy ra lỗi.
- Finally: Kết thúc quá trình tải và bật lại nút thao tác.

### 3.4. Bảo mật API

- Kiểm tra phiên đăng nhập trước khi trả về dữ liệu.
- Phân quyền theo vai trò admin và user.
- User không được truy cập dữ liệu đặt phòng của người khác.
- Không trả về thông tin lỗi kỹ thuật nhạy cảm cho người dùng.

## 4. Cấu trúc thư mục

```text
Buoi8/
├── config/
├── public/
│   ├── api/
│   │   └── bookings.php
│   ├── assets/
│   │   └── css/
│   ├── index.php
│   └── ...
├── src/
│   ├── Controller/
│   ├── Database/
│   ├── Middleware/
│   └── Repository/
├── views/
│   ├── auth/
│   └── bookings/
├── vendor/
├── composer.json
├── composer.lock
└── README.md