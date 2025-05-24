<?php
/**
 * Check room availability for a date range
 * Check room availability
 */
$pageTitle = 'Check Room Availability';
$rootPath = dirname(__DIR__);
include $rootPath . '/includes/header.php';
include $rootPath . '/includes/functions.php';
// Default date range (today and tomorrow)
$checkInDate = isset($_GET['check_in_date']) ? sanitizeInput($_GET['check_in_date']) : date('Y-m-d');
$checkOutDate = isset($_GET['check_out_date']) ? sanitizeInput($_GET['check_out_date']) : date('Y-m-d', strtotime('+1 day'));
$roomTypeId = isset($_GET['room_type_id']) ? (int)$_GET['room_type_id'] : 0;
$specificRoomId = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
// Sample room types (simplified for demo)
$roomTypes = array(
    array('type_id' => 1, 'name' => 'Standard', 'description' => 'A comfortable room with basic amenities', 'price_per_night' => 100.00, 'capacity' => 2),
    array('type_id' => 2, 'name' => 'Deluxe', 'description' => 'A spacious room with premium amenities', 'price_per_night' => 150.00, 'capacity' => 2),
    array('type_id' => 3, 'name' => 'Suite', 'description' => 'A luxurious suite with separate living area', 'price_per_night' => 250.00, 'capacity' => 4),
    array('type_id' => 4, 'name' => 'Family Room', 'description' => 'A large room suitable for families', 'price_per_night' => 200.00, 'capacity' => 5)
);
// Sample available rooms (simplified for demo)
$availableRooms = array(
    array('room_id' => 1, 'room_number' => '101', 'room_type' => 'Standard', 'description' => 'A comfortable room with basic amenities', 'price_per_night' => 100.00, 'capacity' => 2),
    array('room_id' => 2, 'room_number' => '102', 'room_type' => 'Standard', 'description' => 'A comfortable room with basic amenities', 'price_per_night' => 100.00, 'capacity' => 2),
    array('room_id' => 3, 'room_number' => '201', 'room_type' => 'Deluxe', 'description' => 'A spacious room with premium amenities', 'price_per_night' => 150.00, 'capacity' => 2),
    array('room_id' => 4, 'room_number' => '301', 'room_type' => 'Suite', 'description' => 'A luxurious suite with separate living area', 'price_per_night' => 250.00, 'capacity' => 4),
    array('room_id' => 5, 'room_number' => '401', 'room_type' => 'Family Room', 'description' => 'A large room suitable for families', 'price_per_night' => 200.00, 'capacity' => 5)
);
// Filter by room type if specified
if ($roomTypeId > 0) {
    $filteredRooms = array();
    foreach ($availableRooms as $room) {
        $roomTypeName = '';
        foreach ($roomTypes as $type) {
            if ($type['type_id'] == $roomTypeId) {
                $roomTypeName = $type['name'];
                break;
            }
        }
        if ($room['room_type'] == $roomTypeName) {
            $filteredRooms[] = $room;
        }
    }
    $availableRooms = $filteredRooms;
// Default dates
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$nextWeek = date('Y-m-d', strtotime('+7 days'));
// Get parameters
$checkInDate = isset($_GET['check_in_date']) ? sanitizeInput($_GET['check_in_date']) : $today;
$checkOutDate = isset($_GET['check_out_date']) ? sanitizeInput($_GET['check_out_date']) : $tomorrow;
$roomTypeFilter = isset($_GET['room_type']) ? (int)$_GET['room_type'] : 0;
$guestsFilter = isset($_GET['guests']) ? (int)$_GET['guests'] : 1;
$roomIdFilter = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
// Validate dates
if (!validateDate($checkInDate)) {
    $checkInDate = $today;
}
// Filter by specific room if specified
if ($specificRoomId > 0) {
    $filteredRooms = array();
    foreach ($availableRooms as $room) {
        if ($room['room_id'] == $specificRoomId) {
            $filteredRooms[] = $room;
            break;
        }
    }
    $availableRooms = $filteredRooms;
if (!validateDate($checkOutDate) || $checkOutDate <= $checkInDate) {
    $checkOutDate = date('Y-m-d', strtotime($checkInDate . ' +1 day'));
}
// Calculate number of nights
$checkIn = new DateTime($checkInDate);
$checkOut = new DateTime($checkOutDate);
$interval = $checkIn->diff($checkOut);
$numNights = $interval->days;
// Sample room data
$rooms = [
    [
        'room_id' => 1,
        'room_number' => '101',
        'type_id' => 1,
        'room_type' => 'Standard',
        'capacity' => 2,
        'price_per_night' => 100.00,
        'description' => 'Standard room with city view',
        'status' => 'available'
    ],
    [
        'room_id' => 2,
        'room_number' => '102',
        'type_id' => 1,
        'room_type' => 'Standard',
        'capacity' => 2,
        'price_per_night' => 100.00,
        'description' => 'Standard room with garden view',
        'status' => 'available'
    ],
    [
        'room_id' => 3,
        'room_number' => '201',
        'type_id' => 2,
        'room_type' => 'Deluxe',
        'capacity' => 2,
        'price_per_night' => 150.00,
        'description' => 'Deluxe room with city view',
        'status' => 'available'
    ],
    [
        'room_id' => 4,
        'room_number' => '202',
        'type_id' => 2,
        'room_type' => 'Deluxe',
        'capacity' => 3,
        'price_per_night' => 150.00,
        'description' => 'Deluxe room with garden view',
        'status' => 'available'
    ],
    [
        'room_id' => 5,
        'room_number' => '301',
        'type_id' => 3,
        'room_type' => 'Suite',
        'capacity' => 4,
        'price_per_night' => 250.00,
        'description' => 'Suite with city view',
        'status' => 'available'
    ],
    [
        'room_id' => 6,
        'room_number' => '302',
        'type_id' => 3,
        'room_type' => 'Suite',
        'capacity' => 4,
        'price_per_night' => 250.00,
        'description' => 'Suite with garden view',
        'status' => 'available'
    ],
    [
        'room_id' => 7,
        'room_number' => '401',
        'type_id' => 4,
        'room_type' => 'Family Room',
        'capacity' => 5,
        'price_per_night' => 200.00,
        'description' => 'Family room with two queen beds',
        'status' => 'available'
    ],
    [
        'room_id' => 8,
        'room_number' => '501',
        'type_id' => 5,
        'room_type' => 'Presidential Suite',
        'capacity' => 4,
        'price_per_night' => 500.00,
        'description' => 'Presidential suite with panoramic view',
        'status' => 'available'
    ]
];
// Room types for filter
$roomTypes = [
    1 => 'Standard',
    2 => 'Deluxe',
    3 => 'Suite',
    4 => 'Family Room',
    5 => 'Presidential Suite'
];
// Filter rooms based on criteria
$availableRooms = [];
foreach ($rooms as $room) {
    // Check type filter
    if ($roomTypeFilter > 0 && $room['type_id'] != $roomTypeFilter) {
        continue;
    }
    
    // Check capacity
    if ($guestsFilter > $room['capacity']) {
        continue;
    }
    
    // Check specific room
    if ($roomIdFilter > 0 && $room['room_id'] != $roomIdFilter) {
        continue;
    }
    
    // Check availability (in a real system, this would query the database)
    if (isRoomAvailable($room['room_id'], $checkInDate, $checkOutDate)) {
        $availableRooms[] = $room;
    }
}
// Calculate stay details
$nights = calculateNights($checkInDate, $checkOutDate);
?>
<h2>Check Room Availability</h2>
<!-- Search Form -->
<div class="search-box">
    <form method="get" action="" class="search-form">
        <?php if ($specificRoomId > 0) : ?>
            <input type="hidden" name="room_id" value="<?php echo $specificRoomId; ?>">
        <?php else : ?>
            <div class="form-group">
                <label for="room-type-id">Room Type</label>
                <select id="room-type-id" name="room_type_id">
                    <option value="0">All Room Types</option>
                    <?php foreach ($roomTypes as $type) : ?>
                        <option value="<?php echo $type['type_id']; ?>" <?php echo $roomTypeId == $type['type_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type['name']); ?> - <?php echo formatPrice($type['price_per_night']); ?>/night
<div class="availability-form">
    <form method="get" action="">
        <div class="availability-form-row">
            <div class="availability-form-group">
                <label for="check-in-date">Check-in Date</label>
                <input type="date" id="check-in-date" name="check_in_date" value="<?php echo $checkInDate; ?>" min="<?php echo $today; ?>" required>
            </div>
            
            <div class="availability-form-group">
                <label for="check-out-date">Check-out Date</label>
                <input type="date" id="check-out-date" name="check_out_date" value="<?php echo $checkOutDate; ?>" min="<?php echo $tomorrow; ?>" required>
            </div>
            
            <div class="availability-form-group">
                <label for="room-type">Room Type</label>
                <select id="room-type" name="room_type">
                    <option value="0">Any Type</option>
                    <?php foreach ($roomTypes as $typeId => $typeName): ?>
                        <option value="<?php echo $typeId; ?>" <?php echo $roomTypeFilter == $typeId ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($typeName); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="check-in-date">Check-in Date</label>
            <input type="date" id="check-in-date" name="check_in_date" value="<?php echo $checkInDate; ?>" min="<?php echo date('Y-m-d'); ?>" required>
            
            <div class="availability-form-group">
                <label for="guests">Number of Guests</label>
                <input type="number" id="guests" name="guests" value="<?php echo $guestsFilter; ?>" min="1" max="10">
            </div>
            
            <div class="availability-form-group">
                <label for="check-availability">&nbsp;</label>
                <button type="submit" id="check-availability" class="btn btn-primary">Check Availability</button>
            </div>
        </div>
        
        <div class="form-group">
            <label for="check-out-date">Check-out Date</label>
            <input type="date" id="check-out-date" name="check_out_date" value="<?php echo $checkOutDate; ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Check Availability</button>
            <?php if ($specificRoomId > 0) : ?>
                <a href="availability.php" class="btn">Clear Filter</a>
            <?php endif; ?>
        </div>
        <?php if ($roomIdFilter > 0): ?>
            <input type="hidden" name="room_id" value="<?php echo $roomIdFilter; ?>">
        <?php endif; ?>
    </form>
</div>
<!-- Availability Results -->
<div class="search-results">
    <h3>Available Rooms for <?php echo formatDate($checkInDate); ?> to <?php echo formatDate($checkOutDate); ?> (<?php echo $numNights; ?> night<?php echo $numNights > 1 ? 's' : ''; ?>)</h3>
    
    <?php if (!empty($availableRooms)) : ?>
<div class="availability-results">
    <h3>Available Rooms for <?php echo formatDate($checkInDate); ?> to <?php echo formatDate($checkOutDate); ?> (<?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?>)</h3>
    
    <?php if (empty($availableRooms)): ?>
        <div class="alert alert-info">
            Sorry, no available rooms match your criteria for the selected dates.
            <br>
            Please try different dates or criteria.
        </div>
    <?php else: ?>
        <div class="room-grid">
            <?php foreach ($availableRooms as $room) : ?>
            <?php foreach ($availableRooms as $room): ?>
                <?php $totalPrice = calculateTotalPrice($room['price_per_night'], $nights); ?>
