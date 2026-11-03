<?php
declare(strict_types=1);

use App\Database\Database;
use App\Repository\BookingRepository;

header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $payload): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

session_start();

require dirname(__DIR__, 2) . '/vendor/autoload.php';

// Chỉ cho phép phương thức GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    respond(405, [
        'success' => false,
        'message' => 'Phương thức không được hỗ trợ.'
    ]);
}

// Kiểm tra đăng nhập
if (empty($_SESSION['user'])) {
    respond(401, [
        'success' => false,
        'message' => 'Vui lòng đăng nhập để xem dữ liệu.'
    ]);
}

try {
    $pdo = Database::getConnection();
    $repository = new BookingRepository($pdo);

    $user = $_SESSION['user'];

    // Admin xem tất cả, user chỉ xem dữ liệu của mình
    $userId = ($user['role'] ?? '') === 'admin'
        ? null
        : (int) ($user['user_id'] ?? 0);

    $keyword = isset($_GET['keyword']) && is_string($_GET['keyword'])
        ? trim($_GET['keyword'])
        : '';

    $bookings = $repository->getAll($keyword, $userId);

    respond(200, [
        'success' => true,
        'message' => 'Lấy danh sách đặt phòng thành công.',
        'data' => $bookings
    ]);

} catch (Throwable $e) {
    respond(500, [
        'success' => false,
        'message' => 'Không thể tải danh sách đặt phòng.'
    ]);
}