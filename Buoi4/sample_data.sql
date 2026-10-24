
USE trang_booking_test;

-- Them nguoi dung
INSERT INTO users (full_name, email) VALUES
('Nguyen Van An', 'an.test@example.com'),
('Tran Thi Binh', 'binh.test@example.com'),
('Le Minh Quan', 'quan.test@example.com');

-- Them phong thuc hanh
INSERT INTO rooms (room_code, room_name, capacity, status) VALUES
('A101', 'Phong may tinh A101', 40, 'active'),
('A102', 'Phong thuc hanh mang A102', 30, 'active'),
('B201', 'Phong thuc hanh da nang B201', 25, 'active');

-- Them cac yeu cau dat phong
INSERT INTO bookings
(user_id, room_id, title, purpose, start_time, end_time, status, note)
VALUES
(1, 1, 'Thuc hanh PHP', 'Hoc lap trinh PHP',
 '2026-10-26 08:00:00', '2026-10-26 10:00:00',
 'approved', 'Da duoc phe duyet'),

(2, 1, 'Thuc hanh MySQL', 'Thuc hanh truy van SQL',
 '2026-10-26 10:00:00', '2026-10-26 12:00:00',
 'pending', 'Cho giao vien duyet'),

(3, 2, 'Thuc hanh mang', 'Cau hinh mang may tinh',
 '2026-10-26 13:00:00', '2026-10-26 15:00:00',
 'approved', 'Da duoc phe duyet'),

(1, 3, 'Buoi thuc hanh bo sung', 'On tap kien thuc',
 '2026-10-27 08:00:00', '2026-10-27 10:00:00',
 'rejected', 'Phong khong phu hop'),

(2, 2, 'Thuc hanh HTML CSS', 'Thiet ke giao dien web',
 '2026-10-28 13:00:00', '2026-10-28 15:00:00',
 'pending', NULL);

-- Them lich su duyet
INSERT INTO booking_approvals
(booking_id, reviewer_id, action, comment)
VALUES
(1, 3, 'approved', 'Dong y cho dat phong'),
(3, 3, 'approved', 'Da kiem tra lich phong'),
(4, 3, 'rejected', 'Can chon khung gio khac');
