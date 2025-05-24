-- Database Schema for Hotel Reservation System

-- Create database
CREATE DATABASE IF NOT EXISTS hotel_reservation_system;
USE hotel_reservation_system;

-- Room Types table
CREATE TABLE IF NOT EXISTS room_types (
    type_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    price_per_night DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    type_id INT NOT NULL,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (type_id) REFERENCES room_types(type_id) ON DELETE RESTRICT
);

-- Guests table
CREATE TABLE IF NOT EXISTS guests (
    guest_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Reservations table
CREATE TABLE IF NOT EXISTS reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    guest_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    num_guests INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    special_requests TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guest_id) REFERENCES guests(guest_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE RESTRICT
);

-- Insert some sample room types
INSERT INTO room_types (name, description, price_per_night, capacity) VALUES
('Standard', 'A comfortable room with basic amenities', 100.00, 2),
('Deluxe', 'A spacious room with premium amenities', 150.00, 2),
('Suite', 'A luxurious suite with separate living area', 250.00, 4),
('Family Room', 'A large room suitable for families', 200.00, 5);

-- Insert some sample rooms
INSERT INTO rooms (room_number, type_id) VALUES
('101', 1), ('102', 1), ('103', 1), ('104', 1), ('105', 1),
('201', 2), ('202', 2), ('203', 2), ('204', 2), ('205', 2),
('301', 3), ('302', 3), ('303', 3),
('401', 4), ('402', 4);
