
CREATE DATABASE IF NOT EXISTS trang_booking_test
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE trang_booking_test;

-- 1. USERS: NGUOI DUNG
CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 2. ROOMS: PHONG THUC HANH
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_code VARCHAR(20) NOT NULL UNIQUE,
    room_name VARCHAR(100) NOT NULL,
    capacity INT UNSIGNED DEFAULT 30,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

-- 3. BOOKINGS: YEU CAU DAT PHONG
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    room_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    purpose TEXT,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM(
        'pending',
        'approved',
        'rejected',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_bookings_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT fk_bookings_room
        FOREIGN KEY (room_id) REFERENCES rooms(room_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT chk_bookings_time
        CHECK (start_time < end_time),

    INDEX idx_bookings_user (user_id),
    INDEX idx_bookings_room_time (room_id, start_time, end_time),
    INDEX idx_bookings_status (status)
) ENGINE=InnoDB;

-- 4. BOOKING_APPROVALS: LICH SU DUYET DAT PHONG
CREATE TABLE IF NOT EXISTS booking_approvals (
    approval_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    reviewer_id INT UNSIGNED NOT NULL,
    action ENUM('approved', 'rejected') NOT NULL,
    comment TEXT,
    reviewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_booking_approvals_booking
        FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT fk_booking_approvals_reviewer
        FOREIGN KEY (reviewer_id) REFERENCES users(user_id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    INDEX idx_approval_booking (booking_id),
    INDEX idx_approval_reviewer (reviewer_id)
) ENGINE=InnoDB;
