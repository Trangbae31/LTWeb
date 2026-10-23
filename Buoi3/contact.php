<?php
// ============================================
// 1. KHỞI TẠO DỮ LIỆU VÀ DANH SÁCH LỖI
// ============================================

$data = [
    'name'    => '',
    'email'   => '',
    'subject' => '',
    'content' => ''
];

$errors = [];
$success = false;


// ============================================
// 2. HÀM MÃ HÓA DỮ LIỆU, PHÒNG CHỐNG XSS
// ============================================

function e($value)
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


// ============================================
// 3. TIẾP NHẬN VÀ KIỂM TRA FORM
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lấy dữ liệu và loại bỏ khoảng trắng thừa ở hai đầu
    foreach ($data as $field => $value) {
        $data[$field] = trim($_POST[$field] ?? '');
    }

    // Kiểm tra họ tên
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    } elseif (mb_strlen($data['name'], 'UTF-8') < 2) {
        $errors['name'] = 'Họ tên phải có ít nhất 2 ký tự.';
    } elseif (mb_strlen($data['name'], 'UTF-8') > 100) {
        $errors['name'] = 'Họ tên không được vượt quá 100 ký tự.';
    }

    // Kiểm tra email
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không đúng định dạng.';
    } elseif (strlen($data['email']) > 254) {
        $errors['email'] = 'Email quá dài.';
    }

    // Kiểm tra chủ đề
    if ($data['subject'] === '') {
        $errors['subject'] = 'Vui lòng nhập chủ đề.';
    } elseif (mb_strlen($data['subject'], 'UTF-8') > 150) {
        $errors['subject'] = 'Chủ đề không được vượt quá 150 ký tự.';
    }

    // Kiểm tra nội dung
    if ($data['content'] === '') {
        $errors['content'] = 'Vui lòng nhập nội dung liên hệ.';
    } elseif (mb_strlen($data['content'], 'UTF-8') < 10) {
        $errors['content'] = 'Nội dung phải có ít nhất 10 ký tự.';
    } elseif (mb_strlen($data['content'], 'UTF-8') > 2000) {
        $errors['content'] = 'Nội dung không được vượt quá 2000 ký tự.';
    }

    // Nếu không có lỗi, thông báo gửi thành công
    if (empty($errors)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form liên hệ an toàn</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ========================================
         4. KHUNG NỘI DUNG CHÍNH
    ========================================= -->

    <div class="container">

        <h1>FORM LIÊN HỆ</h1>

        <p class="description">
            Vui lòng điền đầy đủ thông tin để gửi liên hệ.
        </p>

        <!-- THÔNG BÁO THÀNH CÔNG -->
        <?php if ($success): ?>

            <div class="success">
                Gửi thông tin thành công!
                Dữ liệu đã được kiểm tra hợp lệ.
            </div>

        <?php endif; ?>

        <!-- ====================================
             5. FORM NHẬP THÔNG TIN
        ===================================== -->

        <form method="POST" action="">

            <!-- HỌ TÊN -->
            <div class="form-group">
                <label for="name">Họ và tên</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="100"
                    value="<?= e($data['name']) ?>"
                    required
                >

                <?php if (isset($errors['name'])): ?>
                    <p class="error">
                        <?= e($errors['name']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="254"
                    value="<?= e($data['email']) ?>"
                    required
                >

                <?php if (isset($errors['email'])): ?>
                    <p class="error">
                        <?= e($errors['email']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- CHỦ ĐỀ -->
            <div class="form-group">
                <label for="subject">Chủ đề</label>

                <input
                    type="text"
                    id="subject"
                    name="subject"
                    maxlength="150"
                    value="<?= e($data['subject']) ?>"
                    required
                >

                <?php if (isset($errors['subject'])): ?>
                    <p class="error">
                        <?= e($errors['subject']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- NỘI DUNG -->
            <div class="form-group">
                <label for="content">Nội dung liên hệ</label>

                <textarea
                    id="content"
                    name="content"
                    rows="5"
                    maxlength="2000"
                    required
                ><?= e($data['content']) ?></textarea>

                <?php if (isset($errors['content'])): ?>
                    <p class="error">
                        <?= e($errors['content']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- NÚT GỬI -->
            <button type="submit">Gửi liên hệ</button>

        </form>

        <!-- ====================================
             6. CHÂN TRANG
        ===================================== -->

        <footer class="footer">
            <p>Họ và tên: <strong>Nguyễn Thu Trang</strong></p>
            <p>Mã sinh viên: <strong>224001756</strong></p>
        </footer>

    </div>

</body>
</html>
