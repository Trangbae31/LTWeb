<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Quản lý đặt phòng</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="form-container">
        <h1>Đăng ký tài khoản</h1>
        <p>Tạo tài khoản để sử dụng hệ thống quản lý đặt phòng.</p>

        <?php if (!empty($error)): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register">
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                    maxlength="100"
                    required
                    placeholder="Nhập họ và tên"
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    maxlength="150"
                    required
                    placeholder="Nhập email"
                >
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required
                    placeholder="Tối thiểu 8 ký tự"
                >
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="8"
                    required
                    placeholder="Nhập lại mật khẩu"
                >
            </div>

            <button type="submit">Đăng ký</button>
        </form>

        <p class="auth-link">
            Đã có tài khoản?
            <a href="index.php?action=login">Đăng nhập</a>
        </p>
    </div>
</div>

</body>
</html>