
<?php
/**
 * List all rooms and their details
 * List all rooms
 */
$pageTitle = 'Room List';
$rootPath = dirname(__DIR__);
include $rootPath . '/includes/header.php';
include $rootPath . '/includes/functions.php';
// Get all room types (simplified for now)
$roomTypes = array(
    array('type_id' => 1, 'name' => 'Standard', 'description' => 'A comfortable room with basic amenities', 'price_per_night' => 100.00, 'capacity' => 2),
    array('type_id' => 2, 'name' => 'Deluxe', 'description' => 'A spacious room with premium amenities', 'price_per_night' => 150.00, 'capacity' => 2),
    array('type_id' => 3, 'name' => 'Suite', 'description' => 'A luxurious suite with separate living area', 'price_per_night' => 250.00, 'capacity' => 4),
    array('type_id' => 4, 'name' => 'Family Room', 'description' => 'A large room suitable for families', 'price_per_night' => 200.00, 'capacity' => 5)
);
// Get rooms (for demonstration purposes, we'll use sample data)
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
// Sample room data (simplified for now)
$rooms = array(
    array('room_id' => 1, 'room_number' => '101', 'type_id' => 1, 'status' => 'available', 'room_type' => 'Standard', 'description' => 'A comfortable room with basic amenities', 'price_per_night' => 100.00, 'capacity' => 2),
    array('room_id' => 2, 'room_number' => '102', 'type_id' => 1, 'status' => 'available', 'room_type' => 'Standard', 'description' => 'A comfortable room with basic amenities', 'price_per_night' => 100.00, 'capacity' => 2),
    array('room_id' => 3, 'room_number' => '201', 'type_id' => 2, 'status' => 'available', 'room_type' => 'Deluxe', 'description' => 'A spacious room with premium amenities', 'price_per_night' => 150.00, 'capacity' => 2),
    array('room_id' => 4, 'room_number' => '301', 'type_id' => 3, 'status' => 'available', 'room_type' => 'Suite', 'description' => 'A luxurious suite with separate living area', 'price_per_night' => 250.00, 'capacity' => 4),
    array('room_id' => 5, 'room_number' => '401', 'type_id' => 4, 'status' => 'available', 'room_type' => 'Family Room', 'description' => 'A large room suitable for families', 'price_per_night' => 200.00, 'capacity' => 5)
);
// Filter rooms based on type
$typeFilter = isset($_GET['type']) ? (int)$_GET['type'] : 0;
// Handle room type filter (simplified)
$typeFilter = isset($_GET['type']) ? (int)$_GET['type'] : 0;
if ($typeFilter > 0) {
    $filteredRooms = array();
    $filteredRooms = [];
    
    foreach ($rooms as $room) {
        if ($room['type_id'] == $typeFilter) {
            $filteredRooms[] = $room;
        }
    }
    
    $rooms = $filteredRooms;
}
// Room types for filter
$roomTypes = [
    1 => 'Standard',
    2 => 'Deluxe',
    3 => 'Suite',
    4 => 'Family Room',
    5 => 'Presidential Suite'
];
?>
<h2>Room List</h2>
<div class="header-actions">
    <h2>Our Rooms</h2>
    <a href="availability.php" class="btn btn-primary">Check Availability</a>
</div>
<!-- Filter Form -->
<div class="search-box">
<div class="filters">
    <form method="get" action="" class="search-form">
        <div class="form-group">
            <label for="type">Filter by Room Type</label>
            <select id="type" name="type" onchange="this.form.submit()">
                <option value="0">All Room Types</option>
                <?php foreach ($roomTypes as $type) : ?>
                    <option value="<?php echo $type['type_id']; ?>" <?php echo $typeFilter == $type['type_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($type['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <div class="form-row">
            <div class="form-group">
                <label for="type-filter">Filter by Room Type:</label>
                <select id="type-filter" name="type" onchange="this.form.submit()">
                    <option value="0">All Room Types</option>
                    <?php foreach ($roomTypes as $typeId => $typeName): ?>
                        <option value="<?php echo $typeId; ?>" <?php echo $typeFilter == $typeId ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($typeName); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
</div>
<!-- Rooms Grid -->
<div class="room-grid">
    <?php if (count($rooms) > 0) : ?>
        <?php foreach ($rooms as $room) : ?>
            <div class="room-card">
                <h3><?php echo htmlspecialchars($room['room_number']); ?></h3>
                <div class="room-info">
                    <p class="room-type"><?php echo htmlspecialchars($room['room_type']); ?></p>
                    <p class="price"><?php echo formatPrice($room['price_per_night']); ?> / night</p>
    <?php foreach ($rooms as $room): ?>
        <div class="room-card">
            <div class="room-card-image">
                Room Image Placeholder
            </div>
            <div class="room-card-content">
                <h3 class="room-card-title">Room <?php echo htmlspecialchars($room['room_number']); ?></h3>
                <div class="room-card-type"><?php echo htmlspecialchars($room['room_type']); ?></div>
                <div class="room-card-info">
                    <p><?php echo htmlspecialchars($room['description']); ?></p>
                    <p>Capacity: <?php echo $room['capacity']; ?> guests</p>
                </div>
                <div class="details">
                    <p><?php echo htmlspecialchars($room['description']); ?></p>
                    <p><strong>Capacity:</strong> <?php echo $room['capacity']; ?> guests</p>
                    <p><strong>Status:</strong> <span class="status-<?php echo $room['status']; ?>"><?php echo ucfirst($room['status']); ?></span></p>
                </div>
                
                <div class="availability">
                    <span class="availability-status available">
                        Available Today
                    </span>
                </div>
                
                <div class="actions">
                    <a href="../reservations/create.php?room_id=<?php echo $room['room_id']; ?>" class="btn btn-small">Book Now</a>
                    <a href="availability.php?room_id=<?php echo $room['room_id']; ?>" class="btn btn-small">Check Availability</a>
                <div class="room-card-price"><?php echo formatPrice($room['price_per_night']); ?> per night</div>
                <div class="room-card-actions">
                    <a href="../reservations/create.php?room_id=<?php echo $room['room_id']; ?>" class="btn btn-primary">Book Now</a>
                    <a href="availability.php?room_id=<?php echo $room['room_id']; ?>" class="btn">Check Availability</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="empty-state">
            <p>No rooms found.</p>
        </div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
<style>
/* Additional styles for room cards */
.room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}
.room-card {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
.room-card h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #2c3e50;
    font-size: 20px;
}
.room-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}
.room-type {
    font-weight: bold;
    color: #7f8c8d;
}
.price {
    color: #e74c3c;
    font-weight: bold;
}
.details {
    margin-bottom: 15px;
    font-size: 14px;
}
.details p {
    margin-bottom: 5px;
}
.status-available {
    color: #2ecc71;
}
.status-occupied {
    color: #e74c3c;
}
.status-maintenance {
    color: #f39c12;
}
.availability {
    margin-bottom: 15px;
}
.availability-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 14px;
    font-weight: bold;
}
.available {
    background-color: #2ecc71;
    color: white;
}
.not-available {
    background-color: #e74c3c;
    color: white;
}
.actions {
    display: flex;
    gap: 10px;
}
.actions .btn {
    flex: 1;
    text-align: center;
}
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 0;
    color: #7f8c8d;
}
</style>
<?php if (empty($rooms)): ?>
    <div class="alert alert-info">No rooms found matching your criteria.</div>
<?php endif; ?>
<?php include $rootPath . '/includes/footer.php'; ?>