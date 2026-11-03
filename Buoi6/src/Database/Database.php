<?php

namespace App\Database;

use PDO;
use RuntimeException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $configFile = dirname(__DIR__, 2) . '/config/database.local.php';

            if (!file_exists($configFile)) {
                throw new RuntimeException('Không tìm thấy file cấu hình database.');
            }

            require $configFile;

            if (!isset($pdo) || !$pdo instanceof PDO) {
                throw new RuntimeException('Kết nối database không hợp lệ.');
            }

            self::$connection = $pdo;
        }

        return self::$connection;
    }
}
