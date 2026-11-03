<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$currentUser = $_SESSION['user'] ?? [];
$isAdmin = ($currentUser['role'] ?? '') === 'admin';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đặt phòng</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <h1>QUẢN LÝ ĐẶT PHÒNG</h1>

    <div class="user-info">
        <p>
            Xin chào,
            <strong><?= e($currentUser['full_name'] ?? '') ?></strong>
        </p>
        <p>Email: <?= e($currentUser['email'] ?? '') ?></p>
        <p>
            Vai trò:
            <strong><?= $isAdmin ? 'Quản trị viên' : 'Người dùng' ?></strong>
        </p>

        <form method="POST" action="index.php?action=logout">
            <button type="submit">Đăng xuất</button>
        </form>
    </div>

    <p>Danh sách các yêu cầu đặt phòng</p>

    <?php if (isset($_GET['message'])): ?>
        <p class="success">
            <?php
            $messages = [
                'created' => 'Thêm yêu cầu đặt phòng thành công!',
                'updated' => 'Cập nhật yêu cầu đặt phòng thành công!',
                'deleted' => 'Xóa yêu cầu đặt phòng thành công!'
            ];

            echo e($messages[$_GET['message']] ?? '');
            ?>
        </p>
    <?php endif; ?>

    <form method="GET" action="index.php">
        <input type="hidden" name="action" value="index">

        <input
            type="text"
            name="keyword"
            placeholder="Tìm theo tiêu đề, người đặt hoặc phòng..."
            value="<?= e($keyword) ?>"
        >

        <button type="submit">Tìm kiếm</button>
        <a href="index.php">Xóa tìm kiếm</a>
    </form>

    <p>
        <a href="index.php?action=create">+ Thêm yêu cầu đặt phòng</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Mã</th>
                <th>Tiêu đề</th>
                <th>Người đặt</th>
                <th>Phòng</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="8">Chưa có yêu cầu đặt phòng nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= e($booking['booking_id']) ?></td>
                        <td><?= e($booking['title']) ?></td>
                        <td><?= e($booking['full_name']) ?></td>
                        <td>
                            <?= e($booking['room_code']) ?>
                            - <?= e($booking['room_name']) ?>
                        </td>
                        <td><?= e($booking['start_time']) ?></td>
                        <td><?= e($booking['end_time']) ?></td>
                        <td><?= e($booking['status']) ?></td>
                        <td>
                            <?php if ($isAdmin || (int) $booking['user_id'] === (int) ($currentUser['user_id'] ?? 0)): ?>
                                <a href="index.php?action=edit&id=<?= (int) $booking['booking_id'] ?>">
                                    Sửa
                                </a>
                            <?php endif; ?>

                            <?php if ($isAdmin): ?>
                                <form
                                    method="POST"
                                    action="index.php?action=delete"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?');"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $booking['booking_id'] ?>"
                                    >
                                    <button type="submit">Xóa</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Khu vực minh họa Fetch API -->
    <section class="api-panel">
        <h2>DỮ LIỆU ĐẶT PHÒNG QUA FETCH API</h2>

        <p>
            Khu vực này sử dụng JavaScript Fetch để lấy dữ liệu
            từ JSON API mà không cần tải lại trang.
        </p>

        <button type="button" id="loadApiButton">
            Tải dữ liệu từ API
        </button>

        <p id="apiLoading">Đang tải dữ liệu...</p>

        <p id="apiError" style="display: none; color: red;"></p>

        <p id="apiResult"></p>

        <ul id="apiBookingList"></ul>
    </section>
</div>

<script>
const loadApiButton = document.getElementById('loadApiButton');
const apiLoading = document.getElementById('apiLoading');
const apiError = document.getElementById('apiError');
const apiResult = document.getElementById('apiResult');
const apiBookingList = document.getElementById('apiBookingList');

async function loadBookings() {
    apiLoading.style.display = 'block';
    apiLoading.textContent = 'Đang tải dữ liệu...';

    apiError.style.display = 'none';
    apiResult.textContent = '';
    apiBookingList.innerHTML = '';

    loadApiButton.disabled = true;

    try {
        const response = await fetch('api/bookings.php');

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Không thể tải dữ liệu.');
        }

        const bookings = result.data;

        apiLoading.style.display = 'none';

        apiResult.textContent =
            `Tải dữ liệu thành công! Có ${bookings.length} yêu cầu đặt phòng.`;

        if (bookings.length === 0) {
            apiResult.textContent = 'Hiện chưa có yêu cầu đặt phòng nào.';
        }

        bookings.forEach(booking => {
            const item = document.createElement('li');

            item.textContent =
                `${booking.title} | Phòng: ${booking.room_code} | ` +
                `Bắt đầu: ${booking.start_time} | ` +
                `Trạng thái: ${booking.status}`;

            apiBookingList.appendChild(item);
        });

    } catch (error) {
        apiLoading.style.display = 'none';
        apiError.textContent = 'Lỗi: ' + error.message;
        apiError.style.display = 'block';

    } finally {
        loadApiButton.disabled = false;
    }
}

loadApiButton.addEventListener('click', loadBookings);

// Tự động tải dữ liệu khi mở trang
loadBookings();
</script>

</body>
</html>