<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Database\Database;
use App\Repository\BookingRepository;
use App\Controller\BookingController;

try {
    // Khởi tạo kết nối database
    $pdo = Database::getConnection();

    // Khởi tạo Repository
    $bookingRepository = new BookingRepository($pdo);

    // Khởi tạo Controller
    $bookingController = new BookingController($bookingRepository);

    // Xác định chức năng cần thực hiện
    $action = $_GET['action'] ?? 'index';

    switch ($action) {
        case 'index':
            $bookingController->index();
            break;

        case 'create':
            $bookingController->create();
            break;

        case 'edit':
            $bookingController->edit();
            break;

        case 'delete':
            $bookingController->delete();
            break;

        default:
            http_response_code(404);
            echo 'Không tìm thấy chức năng yêu cầu.';
            break;
    }

} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());

    echo 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.';
}
