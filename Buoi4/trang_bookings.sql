
USE trang_booking_test;

-- CAU 1: DANH SACH DAT PHONG KEM NGUOI DAT VA TEN PHONG
SELECT
    b.booking_id,
    u.full_name AS nguoi_dat,
    r.room_code AS ma_phong,
    r.room_name AS ten_phong,
    b.title AS ten_yeu_cau,
    b.start_time,
    b.end_time,
    b.status
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN rooms r ON b.room_id = r.room_id;

-- CAU 2: DANH SACH YEU CAU DANG CHO DUYET
SELECT
    b.booking_id,
    u.full_name AS nguoi_dat,
    r.room_name AS ten_phong,
    b.start_time,
    b.end_time,
    b.purpose
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN rooms r ON b.room_id = r.room_id
WHERE b.status = 'pending';

-- CAU 3: LICH DAT PHONG TRONG NGAY 26/10/2026
SELECT
    r.room_code AS ma_phong,
    r.room_name AS ten_phong,
    u.full_name AS nguoi_dat,
    b.start_time,
    b.end_time,
    b.status
FROM bookings b
JOIN rooms r ON b.room_id = r.room_id
JOIN users u ON b.user_id = u.user_id
WHERE b.start_time < '2026-10-27 00:00:00'
  AND b.end_time > '2026-10-26 00:00:00'
  AND b.status IN ('pending', 'approved')
ORDER BY r.room_code, b.start_time;
