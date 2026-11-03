<?php

namespace App\Repository;

use PDO;

class BookingRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lấy danh sách đặt phòng theo tài khoản và tìm kiếm
    public function getAll(string $keyword = '', ?int $userId = null): array
    {
        $sql = "SELECT b.*, u.full_name, r.room_code, r.room_name
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN rooms r ON b.room_id = r.room_id
                WHERE 1 = 1";

        $params = [];

        if ($userId !== null) {
            $sql .= " AND b.user_id = :user_id";
            $params['user_id'] = $userId;
        }

        if ($keyword !== '') {
            $sql .= " AND (
                        b.title LIKE :keyword_title
                        OR u.full_name LIKE :keyword_user
                        OR r.room_name LIKE :keyword_room
                      )";

            $search = '%' . $keyword . '%';

            $params['keyword_title'] = $search;
            $params['keyword_user'] = $search;
            $params['keyword_room'] = $search;
        }

        $sql .= " ORDER BY b.booking_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy một yêu cầu đặt phòng theo ID và tài khoản
    public function getById(int $id, ?int $userId = null): array|false
    {
        $sql = "SELECT * FROM bookings WHERE booking_id = :id";

        $params = ['id' => $id];

        if ($userId !== null) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm yêu cầu đặt phòng
    public function create(array $data): bool
    {
        $sql = "INSERT INTO bookings
                (user_id, room_id, title, purpose,
                 start_time, end_time, status, note)
                VALUES
                (:user_id, :room_id, :title, :purpose,
                 :start_time, :end_time, :status, :note)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $data['user_id'],
            'room_id' => $data['room_id'],
            'title' => $data['title'],
            'purpose' => $data['purpose'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => $data['status'],
            'note' => $data['note']
        ]);
    }

    // Cập nhật yêu cầu đặt phòng
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE bookings SET
                    user_id = :user_id,
                    room_id = :room_id,
                    title = :title,
                    purpose = :purpose,
                    start_time = :start_time,
                    end_time = :end_time,
                    status = :status,
                    note = :note
                WHERE booking_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'user_id' => $data['user_id'],
            'room_id' => $data['room_id'],
            'title' => $data['title'],
            'purpose' => $data['purpose'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => $data['status'],
            'note' => $data['note'],
            'id' => $id
        ]);
    }

    // Xóa yêu cầu đặt phòng
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM bookings WHERE booking_id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }

    // Lấy danh sách người dùng
    public function getUsers(): array
    {
        $stmt = $this->pdo->query(
            "SELECT user_id, full_name FROM users ORDER BY user_id"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách phòng
    public function getRooms(): array
    {
        $stmt = $this->pdo->query(
            "SELECT room_id, room_code, room_name
             FROM rooms ORDER BY room_id"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}