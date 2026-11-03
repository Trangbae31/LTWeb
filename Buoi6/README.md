# BUỔI 6 – PHP OOP, COMPOSER, MVC VÀ REPOSITORY

## 1. Giới thiệu

Bài tập Buổi 6 thực hiện refactor chương trình quản lý đặt phòng từ Buổi 5 theo mô hình MVC và Repository.

Mục tiêu là tổ chức lại mã nguồn, tách biệt phần xử lý dữ liệu, xử lý nghiệp vụ và giao diện, giúp chương trình dễ quản lý, bảo trì và mở rộng hơn.

## 2. So sánh cấu trúc trước và sau khi refactor

### Trước khi refactor – Buổi 5

Chương trình sử dụng các file PHP riêng biệt để thực hiện từng chức năng:

- `index.php`: hiển thị danh sách đặt phòng.
- `create.php`: thêm đặt phòng.
- `edit.php`: chỉnh sửa đặt phòng.
- `delete.php`: xóa đặt phòng.
- `models/Booking.php`: xử lý các thao tác với dữ liệu đặt phòng.

Cách tổ chức này khiến phần xử lý nghiệp vụ và điều hướng chưa được phân tách rõ ràng.

### Sau khi refactor – Buổi 6

Chương trình được tổ chức theo mô hình MVC kết hợp Repository:

- `src/Database/Database.php`: quản lý kết nối cơ sở dữ liệu bằng PDO.
- `src/Repository/BookingRepository.php`: thực hiện các thao tác truy vấn và thay đổi dữ liệu đặt phòng.
- `src/Controller/BookingController.php`: tiếp nhận yêu cầu, xử lý nghiệp vụ và điều hướng.
- `views/bookings/index.php`: hiển thị danh sách đặt phòng.
- `views/bookings/form.php`: hiển thị biểu mẫu thêm và chỉnh sửa đặt phòng.
- `public/index.php`: điểm truy cập chính, tiếp nhận và điều phối các yêu cầu.

## 3. Cấu trúc thư mục

```text
Buoi6/
│
├── config/
│   └── database.local.php
│
├── public/
│   ├── index.php
│   └── assets/
│       └── css/
│           └── style.css
│
├── src/
│   ├── Controller/
│   │   └── BookingController.php
│   │
│   ├── Database/
│   │   └── Database.php
│   │
│   └── Repository/
│       └── BookingRepository.php
│
├── views/
│   └── bookings/
│       ├── index.php
│       └── form.php
│
├── vendor/
├── composer.json
├── composer.lock
├── .gitignore
└── README.md
```

## 4. Các thành phần chính

### 4.1. Composer và Autoload

Sử dụng Composer để quản lý cấu hình dự án và tự động nạp các lớp PHP theo chuẩn PSR-4.

Namespace `App\\` được ánh xạ đến thư mục `src/`.

### 4.2. Database

Lớp `Database` quản lý kết nối MySQL thông qua PDO. Kết nối được khởi tạo và tái sử dụng khi cần thiết.

Thông tin kết nối được lưu trong file cấu hình cục bộ `config/database.local.php` và không đưa lên GitHub.

### 4.3. Repository

Lớp `BookingRepository` chịu trách nhiệm làm việc trực tiếp với cơ sở dữ liệu, bao gồm:

- Lấy danh sách đặt phòng.
- Tìm đặt phòng theo mã.
- Thêm đặt phòng.
- Cập nhật đặt phòng.
- Xóa đặt phòng.
- Lấy danh sách người dùng và phòng.

Các truy vấn có dữ liệu đầu vào sử dụng Prepared Statements của PDO.

### 4.4. Controller

Lớp `BookingController` tiếp nhận yêu cầu từ người dùng và xử lý các chức năng:

- Hiển thị danh sách đặt phòng.
- Tạo đặt phòng mới.
- Chỉnh sửa thông tin đặt phòng.
- Xóa đặt phòng.
- Kiểm tra và xác thực dữ liệu đầu vào.

Controller gọi Repository để thao tác với dữ liệu và gọi View để hiển thị kết quả.

### 4.5. View

Các file trong thư mục `views/bookings/` đảm nhiệm phần giao diện:

- `index.php`: hiển thị danh sách và chức năng tìm kiếm đặt phòng.
- `form.php`: hiển thị biểu mẫu thêm và chỉnh sửa đặt phòng.

View tập trung vào hiển thị dữ liệu, không trực tiếp thực hiện truy vấn SQL.

## 5. Luồng xử lý của chương trình

1. Người dùng gửi yêu cầu thông qua giao diện.
2. `public/index.php` tiếp nhận yêu cầu và xác định chức năng cần thực hiện.
3. `BookingController` xử lý yêu cầu.
4. Controller gọi `BookingRepository` để truy xuất hoặc cập nhật dữ liệu.
5. Repository sử dụng kết nối PDO từ lớp `Database`.
6. Controller chuyển dữ liệu đến View để hiển thị kết quả cho người dùng.

## 6. Cách cài đặt và chạy chương trình

1. Cài đặt PHP, MySQL và Composer.
2. Tạo cơ sở dữ liệu và sử dụng cấu trúc bảng từ Buổi 5.
3. Tạo file `config/database.local.php` từ cấu hình kết nối cơ sở dữ liệu cá nhân.
4. Mở Terminal tại thư mục `Buoi6` và chạy:

```bash
composer install
```

5. Khởi động Apache và MySQL trong XAMPP.
6. Truy cập chương trình tại:

```text
http://localhost/LTWeb/Buoi6/public/
```

## 7. Kết luận

Sau khi refactor, chương trình quản lý đặt phòng được tổ chức theo mô hình MVC kết hợp Repository. Các thành phần kết nối cơ sở dữ liệu, xử lý nghiệp vụ và giao diện được tách biệt rõ ràng. Việc sử dụng Composer Autoload giúp quản lý và nạp các lớp PHP thuận tiện hơn, đồng thời tạo nền tảng để phát triển các chức năng tiếp theo.