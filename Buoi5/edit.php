
<?php
require_once __DIR__ . '/models/Booking.php';

$model = new Booking();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php');
    exit;
}

$booking = $model->getById($id);

if (!$booking) {
    http_response_code(404);
    exit('Không tìm thấy yêu cầu đặt phòng.');
}

$users = $model->getUsers();
$rooms = $model->getRooms();
$error = '';

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'user_id' => (int)($_POST['user_id'] ?? 0),
        'room_id' => (int)($_POST['room_id'] ?? 0),
        'title' => trim($_POST['title'] ?? ''),
        'purpose' => trim($_POST['purpose'] ?? ''),
        'start_time' => str_replace('T', ' ', $_POST['start_time'] ?? ''),
        'end_time' => str_replace('T', ' ', $_POST['end_time'] ?? ''),
        'status' => $_POST['status'] ?? '',
        'note' => trim($_POST['note'] ?? '')
    ];

    if (
        $data['user_id'] <= 0 ||
        $data['room_id'] <= 0 ||
        $data['title'] === '' ||
        $data['purpose'] === '' ||
        $data['start_time'] === '' ||
        $data['end_time'] === ''
    ) {
        $error = 'Vui lòng nhập đầy đủ thông tin bắt buộc.';
    } elseif ($data['start_time'] >= $data['end_time']) {
        $error = 'Thời gian kết thúc phải sau thời gian bắt đầu.';
    } elseif (!in_array($data['status'], [
        'pending', 'approved', 'rejected', 'cancelled'
    ], true)) {
        $error = 'Trạng thái không hợp lệ.';
    } else {
        try {
            $model->update($id, $data);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Không thể cập nhật yêu cầu. Hãy kiểm tra dữ liệu.';
        }
    }

    $booking = array_merge($booking, $data);
}

function toDateTimeLocal($value): string
{
    if (!$value) {
        return '';
    }

    return date('Y-m-d\TH:i', strtotime($value));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa yêu cầu đặt phòng</title>
    <style>
        body { font-family: Arial; background: #f5f1e9; color: #3e342b; padding: 30px; }
        .container { max-width: 650px; margin: auto; background: white; padding: 25px; border-radius: 12px; }
        label { display: block; margin-top: 14px; }
        input, select, textarea, button { box-sizing: border-box; width: 100%; padding: 10px; margin-top: 6px; }
        button, .back { background: #6d4c36; color: white; border: 0; padding: 11px; display: inline-block; text-decoration: none; margin-top: 18px; cursor: pointer; }
        .error { color: #a12626; }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Sửa yêu cầu đặt phòng #<?= e($id) ?></h1>

    <?php if ($error !== ''): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Người đặt *</label>
        <select name="user_id" required>
            <?php foreach ($users as $user): ?>
                <option value="<?= e($user['user_id']) ?>"
                    <?= (string)$booking['user_id'] === (string)$user['user_id'] ? 'selected' : '' ?>>
                    <?= e($user['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Phòng *</label>
        <select name="room_id" required>
            <?php foreach ($rooms as $room): ?>
                <option value="<?= e($room['room_id']) ?>"
                    <?= (string)$booking['room_id'] === (string)$room['room_id'] ? 'selected' : '' ?>>
                    <?= e($room['room_code'] . ' - ' . $room['room_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Tiêu đề *</label>
        <input name="title" required value="<?= e($booking['title']) ?>">

        <label>Mục đích *</label>
        <textarea name="purpose" required><?= e($booking['purpose']) ?></textarea>

        <label>Thời gian bắt đầu *</label>
        <input type="datetime-local" name="start_time" required
               value="<?= e(toDateTimeLocal($booking['start_time'])) ?>">

        <label>Thời gian kết thúc *</label>
        <input type="datetime-local" name="end_time" required
               value="<?= e(toDateTimeLocal($booking['end_time'])) ?>">

        <label>Trạng thái</label>
        <select name="status">
            <?php
            $statuses = [
                'pending' => 'Chờ duyệt',
                'approved' => 'Đã duyệt',
                'rejected' => 'Từ chối',
                'cancelled' => 'Đã hủy'
            ];
            foreach ($statuses as $value => $label):
            ?>
                <option value="<?= e($value) ?>"
                    <?= $booking['status'] === $value ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Ghi chú</label>
        <textarea name="note"><?= e($booking['note']) ?></textarea>

        <button type="submit">Lưu thay đổi</button>
    </form>

    <a class="back" href="index.php">Quay lại danh sách</a>
</div>
</body>
</html>

