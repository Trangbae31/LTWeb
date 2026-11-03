<?php

namespace App\Controller;

use App\Repository\UserRepository;

class AuthController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Hiển thị trang đăng nhập và xử lý đăng nhập
    public function login(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                $error = 'Vui lòng nhập đầy đủ email và mật khẩu.';
            } else {
                $user = $this->userRepository->findByEmail($email);

                if (
                    $user &&
                    !empty($user['password_hash']) &&
                    password_verify($password, $user['password_hash'])
                ) {
                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'user_id' => $user['user_id'],
                        'full_name' => $user['full_name'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ];

                    header('Location: index.php');
                    exit;
                }

                $error = 'Email hoặc mật khẩu không chính xác.';
            }
        }

        require dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    // Hiển thị trang đăng ký và xử lý đăng ký
    public function register(): void
    {
        $error = '';
        $old = [
            'full_name' => '',
            'email' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $old = [
                'full_name' => $fullName,
                'email' => $email
            ];

            if ($fullName === '' || $email === '' || $password === '') {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email không hợp lệ.';
            } elseif (strlen($password) < 8) {
                $error = 'Mật khẩu phải có ít nhất 8 ký tự.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } elseif ($this->userRepository->findByEmail($email)) {
                $error = 'Email này đã được đăng ký.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                try {
                    $this->userRepository->create(
                        $fullName,
                        $email,
                        $passwordHash
                    );

                    header('Location: index.php?action=login&registered=1');
                    exit;
                } catch (\PDOException $e) {
                    $error = 'Không thể tạo tài khoản. Vui lòng thử lại.';
                }
            }
        }

        require dirname(__DIR__, 2) . '/views/auth/register.php';
    }

    // Xử lý đăng xuất
    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không được phép.');
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?action=login');
        exit;
    }
}
