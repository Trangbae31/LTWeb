<?php

namespace App\Middleware;

class AuthMiddleware
{
    // Kiểm tra người dùng đã đăng nhập
    public static function requireLogin(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    // Kiểm tra vai trò người dùng
    public static function requireRole(string $role): void
    {
        self::requireLogin();

        if ($_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            exit('Bạn không có quyền truy cập chức năng này.');
        }
    }

    // Kiểm tra người dùng có phải admin hay không
    public static function requireAdmin(): void
    {
        self::requireRole('admin');
    }
}