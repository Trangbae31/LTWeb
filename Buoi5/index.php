
<?php

require_once __DIR__ . '/models/Booking.php';

$bookingModel = new Booking();
$keyword = trim($_GET['keyword'] ?? '');
$bookings = $bookingModel->getAll($keyword);

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đặt phòng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f1e9;
            color: #3e342b;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
        h1 { color: #6d4c36; }
        form { margin-bottom: 20px; }
        input, button, a.button {
            padding: 10px 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button, a.button {
            background: #6d4c36;
            color: white;
            cursor: pointer;
            text-decoration: none;
            border: none;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5dfd5;
            text-align: left;
        }
        th { background: #ede7db; }
        .empty { padding: 20px; text-align: center; }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Quản lý yêu cầu đặt phòng</h1>

    <form method="GET">
        <input
            type="text"
            name="keyword"
            placeholder="Tìm theo tiêu đề, người đặt hoặc phòng..."
            value="<?= e($keyword) ?>"
        >
        <button type="submit">Tìm kiếm</button>
        <a class="button" href="index.php">Làm mới</a>
    </form>

    <a class="button" href="create.php">+ Thêm yêu cầu đặt phòng</a>

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
        <?php if (count($bookings) > 0): ?>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= e($booking['booking_id']) ?></td>
                    <td><?= e($booking['title']) ?></td>
                    <td><?= e($booking['full_name']) ?></td>
                    <td><?= e($booking['room_code']) ?> - <?= e($booking['room_name']) ?></td>
                    <td><?= e($booking['start_time']) ?></td>
                    <td><?= e($booking['end_time']) ?></td>            
                    <td><?= e($booking['status']) ?></td>
                    <td>
                        <a class="button"
                        href="edit.php?id=<?= (int)$booking['booking_id'] ?>">Sửa</a>

                        <form method="POST" action="delete.php"
                            onsubmit="return confirm('M có chắc muốn xóa yêu cầu này không?');"
                            style="display:inline">
                            <input type="hidden" name="id"
                                value="<?= (int)$booking['booking_id'] ?>">
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="empty">Không tìm thấy yêu cầu đặt phòng.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

