<?php
/**
 * Common functions for the Hotel Reservation System
 */
require_once 'db_simple.php';

/**
 * Sanitize user input
 * 
 * @param string $data The input to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Validate a date format
 * 
 * @param string $date The date to validate
 * @param string $format The expected format
 * @return bool True if valid, false otherwise
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Check if a given room is available for the specified dates
 * 
 * @param int $roomId The room ID
 * @param string $checkIn The check-in date
 * @param string $checkOut The check-out date
 * @param int $excludeReservationId Optional reservation ID to exclude from check
 * @return bool True if available, false otherwise
 */
function isRoomAvailable($roomId, $checkIn, $checkOut, $excludeReservationId = null) {
    $db = new Database();
    
    $sql = "SELECT COUNT(*) as count FROM reservations 
            WHERE room_id = ? 
            AND payment_status != 'cancelled'
            AND ((check_in_date <= ? AND check_out_date > ?) 
                OR (check_in_date < ? AND check_out_date >= ?) 
                OR (check_in_date >= ? AND check_out_date <= ?))";
    
    // Exclude current reservation if updating
    if ($excludeReservationId) {
        $sql .= " AND reservation_id != ?";
    }
    
    $stmt = $db->prepare($sql);
    
    if ($excludeReservationId) {
        $stmt->bind_param("isssssi", $roomId, $checkOut, $checkIn, $checkOut, $checkIn, $checkIn, $checkOut, $excludeReservationId);
    } else {
        $stmt->bind_param("isssss", $roomId, $checkOut, $checkIn, $checkOut, $checkIn, $checkIn, $checkOut);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return ($row['count'] == 0);
}

/**
 * Calculate the total price for a reservation
 * 
 * @param int $roomTypeId The room type ID
 * @param string $checkIn The check-in date
 * @param string $checkOut The check-out date
 * @return float The total price
 */
function calculateTotalPrice($roomTypeId, $checkIn, $checkOut) {
    $db = new Database();
    
    // Get the price per night for the room type
    $stmt = $db->prepare("SELECT price_per_night FROM room_types WHERE type_id = ?");
    $stmt->bind_param("i", $roomTypeId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        return 0;
    }
    
    $row = $result->fetch_assoc();
    $pricePerNight = $row['price_per_night'];
    
    // Calculate the number of nights
    $checkInDate = new DateTime($checkIn);
    $checkOutDate = new DateTime($checkOut);
    $nights = $checkOutDate->diff($checkInDate)->days;
    
    // Calculate the total price
    return $pricePerNight * $nights;
}

/**
 * Get available rooms for the specified dates
 * 
 * @param string $checkIn The check-in date
 * @param string $checkOut The check-out date
 * @param int $roomTypeId Optional room type ID to filter by
 * @return array Array of available rooms
 */
function getAvailableRooms($checkIn, $checkOut, $roomTypeId = null) {
    $db = new Database();
    
    // Escape input values for SQL injection prevention
    $checkInEsc = $db->escapeString($checkIn);
    $checkOutEsc = $db->escapeString($checkOut);
    
    $sql = "SELECT r.room_id, r.room_number, rt.name AS room_type, rt.price_per_night, rt.capacity 
            FROM rooms r 
            JOIN room_types rt ON r.type_id = rt.type_id 
            WHERE r.status = 'available'";
    
    if ($roomTypeId) {
        $sql .= " AND rt.type_id = " . (int)$roomTypeId;
    }
    
    $sql .= " AND r.room_id NOT IN (
                SELECT room_id FROM reservations 
                WHERE payment_status != 'cancelled'
                AND ((check_in_date <= '$checkOutEsc' AND check_out_date > '$checkInEsc') 
                    OR (check_in_date < '$checkOutEsc' AND check_out_date >= '$checkInEsc') 
                    OR (check_in_date >= '$checkInEsc' AND check_out_date <= '$checkOutEsc'))
            )";
    
    $result = $db->query($sql);
    
    $availableRooms = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $availableRooms[] = $row;
        }
    }
    
    return $availableRooms;
}

/**
 * Get all room types
 * 
 * @return array Array of room types
 */
function getRoomTypes() {
    $db = new Database();
    
    $sql = "SELECT * FROM room_types ORDER BY price_per_night";
    $result = $db->query($sql);
    
    $roomTypes = [];
    while ($row = $result->fetch_assoc()) {
        $roomTypes[] = $row;
    }
    
    return $roomTypes;
}

/**
 * Format a date for display
 * 
 * @param string $date The date to format
 * @return string Formatted date
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Format a price for display
 * 
 * @param float $price The price to format
 * @return string Formatted price
 */
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

/**
 * Redirect to a URL
 * 
 * @param string $url The URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Set a flash message
 * 
 * @param string $type The message type (success, error, warning, info)
 * @param string $message The message content
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * 
 * @return array|null Flash message or null if none
 */
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
