# BUỔI 7 – AUTHENTICATION, AUTHORIZATION VÀ SECURITY

## 1. Giới thiệu

Xây dựng chức năng xác thực và phân quyền người dùng cho hệ thống quản lý đặt phòng bằng PHP, MySQL và PDO.

Các chức năng chính:
- Đăng ký tài khoản.
- Đăng nhập và đăng xuất.
- Mã hóa mật khẩu.
- Phân quyền người dùng và quản trị viên.
- Kiểm soát quyền truy cập các chức năng quản lý đặt phòng.

## 2. Công nghệ sử dụng

- PHP 8.2
- MySQL 8
- PDO
- Composer
- HTML, CSS
- PHP Session

## 3. Cấu trúc chức năng

### Xác thực tài khoản
- Đăng ký tài khoản với họ tên, email và mật khẩu.
- Kiểm tra email đã tồn tại.
- Kiểm tra độ dài mật khẩu và xác nhận mật khẩu.
- Sử dụng `password_hash()` để mã hóa mật khẩu.
- Sử dụng `password_verify()` để xác minh khi đăng nhập.
- Sử dụng Session để lưu thông tin người dùng sau khi đăng nhập.
- Sử dụng `session_regenerate_id(true)` khi đăng nhập thành công.

### Phân quyền người dùng

Hệ thống có hai vai trò:

**User:**
- Đăng nhập và xem danh sách đặt phòng của mình.
- Tạo yêu cầu đặt phòng.
- Chỉnh sửa yêu cầu đặt phòng của mình.
- Không được tự thay đổi trạng thái đặt phòng.
- Không được xóa đặt phòng.

**Admin:**
- Xem tất cả yêu cầu đặt phòng.
- Tạo và chỉnh sửa đặt phòng.
- Thay đổi trạng thái đặt phòng.
- Xóa đặt phòng.

### Bảo mật
- Kiểm tra đăng nhập bằng `AuthMiddleware`.
- Kiểm tra vai trò ở phía máy chủ.
- Sử dụng PDO Prepared Statements khi truy vấn dữ liệu.
- Sử dụng `htmlspecialchars()` khi hiển thị dữ liệu.
- Chỉ cho phép xóa dữ liệu bằng phương thức POST.

## 4. Cấu trúc thư mục

- `config/`: Cấu hình kết nối cơ sở dữ liệu.
- `public/`: Điểm truy cập chính của ứng dụng và tài nguyên giao diện.
- `src/Controller/`: Xử lý đăng nhập, đăng ký và đặt phòng.
- `src/Database/`: Kết nối cơ sở dữ liệu.
- `src/Middleware/`: Kiểm tra đăng nhập và phân quyền.
- `src/Repository/`: Truy vấn và thao tác dữ liệu.
- `views/`: Giao diện đăng nhập, đăng ký và quản lý đặt phòng.
- `vendor/`: Thư viện được Composer quản lý.

## 5. Cài đặt và sử dụng

1. Cài đặt PHP, MySQL và Composer.
2. Cấu hình thông tin kết nối trong `config/database.local.php`.
3. Cài đặt thư viện:
   `composer install`
4. Cập nhật bảng `users` để bổ sung các cột `password_hash` và `role` theo câu lệnh SQL trong quá trình triển khai.
5. Truy cập trang đăng ký để tạo tài khoản.
6. Đăng nhập để sử dụng chức năng quản lý đặt phòng.

Để cấp quyền quản trị viên cho một tài khoản đã đăng ký, thực hiện câu lệnh SQL:

```sql
UPDATE users
SET role = 'admin'
WHERE email = 'email-tai-khoan';
```

Thay `email-tai-khoan` bằng email tài khoản cần cấp quyền.

## 6. Các thành phần chính

- `AuthController.php`: Xử lý đăng ký, đăng nhập và đăng xuất.
- `AuthMiddleware.php`: Kiểm tra trạng thái đăng nhập và quyền truy cập.
- `UserRepository.php`: Truy vấn và tạo tài khoản người dùng.
- `BookingController.php`: Xử lý nghiệp vụ đặt phòng theo quyền người dùng.
- `BookingRepository.php`: Truy vấn dữ liệu đặt phòng.
- `public/index.php`: Điều hướng các chức năng của hệ thống.

## 7. Kết quả

Hoàn thành chức năng đăng ký, đăng nhập, đăng xuất và phân quyền người dùng. Các chức năng quản lý đặt phòng được kiểm soát theo vai trò, bảo đảm người dùng thông thường chỉ thao tác trên dữ liệu của mình và quản trị viên có quyền quản lý toàn bộ dữ liệu.