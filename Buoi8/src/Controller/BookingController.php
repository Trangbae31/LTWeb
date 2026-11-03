<?php

namespace App\Controller;

use App\Repository\BookingRepository;

class BookingController
{
    private BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    // Lấy thông tin tài khoản đang đăng nhập
    private function currentUser(): array
    {
        return $_SESSION['user'] ?? [];
    }

    // Kiểm tra tài khoản có phải admin không
    private function isAdmin(): bool
    {
        return ($this->currentUser()['role'] ?? '') === 'admin';
    }

    // Hiển thị danh sách đặt phòng
    public function index(): void
    {
        $user = $this->currentUser();
        $keyword = trim($_GET['keyword'] ?? '');

        $userId = $this->isAdmin()
            ? null
            : (int) $user['user_id'];

        $bookings = $this->bookingRepository->getAll($keyword, $userId);

        require dirname(__DIR__, 2) . '/views/bookings/index.php';
    }

    // Thêm yêu cầu đặt phòng
    public function create(): void
    {
        $user = $this->currentUser();
        $isAdmin = $this->isAdmin();

        $users = $isAdmin ? $this->bookingRepository->getUsers() : [];
        $rooms = $this->bookingRepository->getRooms();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            // User chỉ được tạo booking cho chính mình
            if (!$isAdmin) {
                $data['user_id'] = (int) $user['user_id'];
                $data['status'] = 'pending';
            }

            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->bookingRepository->create($data);

                header('Location: index.php?message=created');
                exit;
            }
        }

        $booking = null;
        $pageTitle = 'Thêm yêu cầu đặt phòng';

        require dirname(__DIR__, 2) . '/views/bookings/form.php';
    }

    // Sửa yêu cầu đặt phòng
    public function edit(): void
    {
        $user = $this->currentUser();
        $isAdmin = $this->isAdmin();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $userId = $isAdmin ? null : (int) $user['user_id'];

        if (!$id || !$booking = $this->bookingRepository->getById($id, $userId)) {
            http_response_code(404);
            exit('Không tìm thấy yêu cầu đặt phòng hoặc bạn không có quyền truy cập.');
        }

        $users = $isAdmin ? $this->bookingRepository->getUsers() : [];
        $rooms = $this->bookingRepository->getRooms();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            // User không được đổi chủ booking hoặc tự duyệt
            if (!$isAdmin) {
                $data['user_id'] = (int) $user['user_id'];
                $data['status'] = $booking['status'];
            }

            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->bookingRepository->update($id, $data);

                header('Location: index.php?message=updated');
                exit;
            }

            $booking = array_merge($booking, $data);
        }

        $pageTitle = 'Cập nhật yêu cầu đặt phòng';

        require dirname(__DIR__, 2) . '/views/bookings/form.php';
    }

    // Xóa yêu cầu đặt phòng
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không được phép.');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            exit('ID không hợp lệ.');
        }

        $this->bookingRepository->delete($id);

        header('Location: index.php?message=deleted');
        exit;
    }

    // Lấy dữ liệu từ form
    private function getFormData(): array
    {
        return [
            'user_id' => (int) ($_POST['user_id'] ?? 0),
            'room_id' => (int) ($_POST['room_id'] ?? 0),
            'title' => trim($_POST['title'] ?? ''),
            'purpose' => trim($_POST['purpose'] ?? ''),
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
            'status' => $_POST['status'] ?? 'pending',
            'note' => trim($_POST['note'] ?? '')
        ];
    }

    // Kiểm tra dữ liệu đầu vào
    private function validate(array $data): array
    {
        $errors = [];

        if ($data['user_id'] <= 0) {
            $errors[] = 'Vui lòng chọn người đặt phòng.';
        }

        if ($data['room_id'] <= 0) {
            $errors[] = 'Vui lòng chọn phòng.';
        }

        if ($data['title'] === '') {
            $errors[] = 'Vui lòng nhập tiêu đề.';
        }

        if ($data['purpose'] === '') {
            $errors[] = 'Vui lòng nhập mục đích sử dụng.';
        }

        if ($data['start_time'] === '' || $data['end_time'] === '') {
            $errors[] = 'Vui lòng nhập thời gian bắt đầu và kết thúc.';
        } elseif (
            strtotime($data['start_time']) === false ||
            strtotime($data['end_time']) === false ||
            strtotime($data['start_time']) >= strtotime($data['end_time'])
        ) {
            $errors[] = 'Thời gian kết thúc phải sau thời gian bắt đầu.';
        }

        $allowedStatuses = ['pending', 'approved', 'rejected', 'cancelled'];

        if (!in_array($data['status'], $allowedStatuses, true)) {
            $errors[] = 'Trạng thái đặt phòng không hợp lệ.';
        }

        return $errors;
    }
}