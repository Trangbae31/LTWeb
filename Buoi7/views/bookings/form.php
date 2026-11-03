
<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$booking = $booking ?? [];
$currentUser = $_SESSION['user'] ?? [];
$isAdmin = ($currentUser['role'] ?? '') === 'admin';

$oldStartTime = !empty($booking['start_time'])
    ? date('Y-m-d\TH:i', strtotime($booking['start_time']))
    : '';

$oldEndTime = !empty($booking['end_time'])
    ? date('Y-m-d\TH:i', strtotime($booking['end_time']))
    : '';

$oldStatus = $booking['status'] ?? 'pending';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <h1><?= e($pageTitle) ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label for="title">Tiêu đề đặt phòng</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?= e($booking['title'] ?? '') ?>"
                required
            >
        </div>

        <?php if ($isAdmin): ?>
            <div class="form-group">
                <label for="user_id">Người đặt phòng</label>
                <select id="user_id" name="user_id" required>
                    <option value="">-- Chọn người đặt --</option>

                    <?php foreach ($users as $user): ?>
                        <option
                            value="<?= (int) $user['user_id'] ?>"
                            <?= (int) ($booking['user_id'] ?? 0) === (int) $user['user_id'] ? 'selected' : '' ?>
                        >
                            <?= e($user['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="room_id">Phòng</label>
            <select id="room_id" name="room_id" required>
                <option value="">-- Chọn phòng --</option>

                <?php foreach ($rooms as $room): ?>
                    <option
                        value="<?= (int) $room['room_id'] ?>"
                        <?= (int) ($booking['room_id'] ?? 0) === (int) $room['room_id'] ? 'selected' : '' ?>
                    >
                        <?= e($room['room_code']) ?> -
                        <?= e($room['room_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="purpose">Mục đích sử dụng</label>
            <textarea id="purpose" name="purpose" required><?= e($booking['purpose'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="start_time">Thời gian bắt đầu</label>
            <input
                type="datetime-local"
                id="start_time"
                name="start_time"
                value="<?= e($oldStartTime) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="end_time">Thời gian kết thúc</label>
            <input
                type="datetime-local"
                id="end_time"
                name="end_time"
                value="<?= e($oldEndTime) ?>"
                required
            >
        </div>

        <?php if ($isAdmin): ?>
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="status">
                    <option value="pending" <?= $oldStatus === 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="approved" <?= $oldStatus === 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                    <option value="rejected" <?= $oldStatus === 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                    <option value="cancelled" <?= $oldStatus === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="note">Ghi chú</label>
            <textarea id="note" name="note"><?= e($booking['note'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Lưu thông tin</button>
            <a href="index.php">Quay lại</a>
        </div>

    </form>

</div>

</body>
</html>
