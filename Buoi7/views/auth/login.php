<?php
$registered = isset($_GET['registered']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý đặt phòng</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="form-container">
        <h1>Đăng nhập</h1>
        <p>Đăng nhập để sử dụng hệ thống quản lý đặt phòng.</p>

        <?php if ($registered): ?>
            <p class="success">
                Đăng ký thành công! Vui lòng đăng nhập.
            </p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login">
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    placeholder="Nhập email của bạn"
                >
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Nhập mật khẩu"
                >
            </div>

            <button type="submit">Đăng nhập</button>
        </form>

        <p class="auth-link">
            Chưa có tài khoản?
            <a href="index.php?action=register">Đăng ký ngay</a>
        </p>
    </div>
</div>

</body>
</html>