<?php

use App\Database\Database;
use App\Repository\UserRepository;
use App\Repository\BookingRepository;
use App\Controller\AuthController;
use App\Controller\BookingController;
use App\Middleware\AuthMiddleware;

session_start();

require dirname(__DIR__) . '/vendor/autoload.php';

try {
    // Kết nối cơ sở dữ liệu
    $pdo = Database::getConnection();

    // Khởi tạo Repository
    $userRepository = new UserRepository($pdo);
    $bookingRepository = new BookingRepository($pdo);

    // Khởi tạo Controller
    $authController = new AuthController($userRepository);
    $bookingController = new BookingController($bookingRepository);

    // Xác định chức năng
    $action = $_GET['action'] ?? 'index';

    switch ($action) {

        // Đăng nhập
        case 'login':
            $authController->login();
            break;

        // Đăng ký
        case 'register':
            $authController->register();
            break;

        // Đăng xuất
        case 'logout':
            AuthMiddleware::requireLogin();
            $authController->logout();
            break;

        // Danh sách đặt phòng
        case 'index':
            AuthMiddleware::requireLogin();
            $bookingController->index();
            break;

        // Thêm đặt phòng
        case 'create':
            AuthMiddleware::requireLogin();
            $bookingController->create();
            break;

        // Sửa đặt phòng
        case 'edit':
            AuthMiddleware::requireLogin();
            $bookingController->edit();
            break;

        // Xóa đặt phòng: chỉ admin
        case 'delete':
            AuthMiddleware::requireAdmin();
            $bookingController->delete();
            break;

        // Chức năng không tồn tại
        default:
            http_response_code(404);
            echo 'Không tìm thấy chức năng yêu cầu.';
            break;
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo 'Đã xảy ra lỗi hệ thống. Vui lòng kiểm tra cấu hình và dữ liệu.';
}