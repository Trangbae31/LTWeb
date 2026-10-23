<?php
// ==========================================
// 1. KHAI BÁO CÁC HÀM XỬ LÝ
// ==========================================

// Kiểm tra điểm có hợp lệ hay không
function isValidScore($score)
{
    return $score !== ''
        && is_numeric($score)
        && $score >= 0
        && $score <= 10;
}

// Tính điểm tổng kết học phần
function finalScore($attendance, $midterm, $final)
{
    return $attendance * 0.1
        + $midterm * 0.3
        + $final * 0.6;
}

// ==========================================
// 2. KHỞI TẠO DỮ LIỆU
// ==========================================

$name = '';
$attendance = '';
$midterm = '';
$final = '';

$errors = [];
$result = null;

// ==========================================
// 3. XỬ LÝ DỮ LIỆU FORM
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $attendance = trim($_POST['attendance'] ?? '');
    $midterm = trim($_POST['midterm'] ?? '');
    $final = trim($_POST['final'] ?? '');

    // Kiểm tra họ và tên
    if ($name === '') {
        $errors['name'] = 'Vui lòng nhập họ và tên.';
    }

    // Kiểm tra điểm chuyên cần
    if (!isValidScore($attendance)) {
        $errors['attendance'] = 'Điểm chuyên cần phải từ 0 đến 10.';
    }

    // Kiểm tra điểm giữa kỳ
    if (!isValidScore($midterm)) {
        $errors['midterm'] = 'Điểm giữa kỳ phải từ 0 đến 10.';
    }

    // Kiểm tra điểm cuối kỳ
    if (!isValidScore($final)) {
        $errors['final'] = 'Điểm cuối kỳ phải từ 0 đến 10.';
    }

    // Chỉ tính điểm khi không có lỗi
    if (empty($errors)) {
        $total = finalScore(
            (float) $attendance,
            (float) $midterm,
            (float) $final
        );

        // Xếp loại học phần
        if ($total >= 8.5) {
            $classification = 'Giỏi';
        } elseif ($total >= 7) {
            $classification = 'Khá';
        } elseif ($total >= 5) {
            $classification = 'Trung bình';
        } else {
            $classification = 'Chưa đạt';
        }

        $result = [
            'total' => $total,
            'classification' => $classification
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Máy tính điểm học phần</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>MÁY TÍNH ĐIỂM HỌC PHẦN</h1>

        <p class="description">
            Nhập thông tin và điểm các thành phần để tính điểm tổng kết.
        </p>

        <form method="POST" action="">
            <!-- Họ và tên -->
            <div class="form-group">
                <label for="name">Họ và tên</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nhập họ và tên"
                >

                <?php if (isset($errors['name'])): ?>
                    <p class="error">
                        <?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Điểm chuyên cần -->
            <div class="form-group">
                <label for="attendance">Điểm chuyên cần (10%)</label>

                <input
                    type="number"
                    id="attendance"
                    name="attendance"
                    min="0"
                    max="10"
                    step="any"
                    value="<?= htmlspecialchars($attendance, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nhập điểm từ 0 đến 10"
                >

                <?php if (isset($errors['attendance'])): ?>
                    <p class="error">
                        <?= htmlspecialchars($errors['attendance'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Điểm giữa kỳ -->
            <div class="form-group">
                <label for="midterm">Điểm giữa kỳ (30%)</label>

                <input
                    type="number"
                    id="midterm"
                    name="midterm"
                    min="0"
                    max="10"
                    step="any"
                    value="<?= htmlspecialchars($midterm, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nhập điểm từ 0 đến 10"
                >

                <?php if (isset($errors['midterm'])): ?>
                    <p class="error">
                        <?= htmlspecialchars($errors['midterm'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Điểm cuối kỳ -->
            <div class="form-group">
                <label for="final">Điểm cuối kỳ (60%)</label>

                <input
                    type="number"
                    id="final"
                    name="final"
                    min="0"
                    max="10"
                    step="any"
                    value="<?= htmlspecialchars($final, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nhập điểm từ 0 đến 10"
                >

                <?php if (isset($errors['final'])): ?>
                    <p class="error">
                        <?= htmlspecialchars($errors['final'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>
            </div>

            <button type="submit">Tính điểm học phần</button>
        </form>

        <!-- Kết quả tính điểm -->
        <?php if ($result !== null): ?>
            <div class="result">
                <h2>Kết quả học tập</h2>

                <p>
                    Họ và tên:
                    <strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong>
                </p>

                <p>
                    Điểm tổng kết:
                    <strong><?= number_format($result['total'], 2) ?></strong>
                </p>

                <p>
                    Xếp loại:
                    <strong><?= htmlspecialchars($result['classification'], ENT_QUOTES, 'UTF-8') ?></strong>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- CHÂN TRANG -->
    <footer class="footer">
        <p>Họ và tên: <strong>Nguyễn Thu Trang</strong></p>
        <p>Mã sinh viên: <strong>224001756</strong></p>
    </footer>
</body>
</html>