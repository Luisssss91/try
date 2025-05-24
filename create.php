<?php
/**
 * Create a new reservation
 */
$pageTitle = 'Create Reservation';
$rootPath = dirname(__DIR__);
include $rootPath . '/includes/header.php';
include $rootPath . '/includes/functions.php';

// Initialize variables to store form data
$firstName = $lastName = $email = $phone = $address = '';
$checkInDate = isset($_GET['check_in_date']) ? sanitizeInput($_GET['check_in_date']) : date('Y-m-d');
$checkOutDate = isset($_GET['check_out_date']) ? sanitizeInput($_GET['check_out_date']) : date('Y-m-d', strtotime('+1 day'));
$roomId = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
$numGuests = 1;
$specialRequests = '';

// Sample room data (for demonstration purposes)
$rooms = array(
    array('room_id' => 1, 'room_number' => '101', 'type_id' => 1, 'room_type' => 'Standard', 'capacity' => 2, 'price_per_night' => 100.00),
    array('room_id' => 2, 'room_number' => '102', 'type_id' => 1, 'room_type' => 'Standard', 'capacity' => 2, 'price_per_night' => 100.00),
    array('room_id' => 3, 'room_number' => '201', 'type_id' => 2, 'room_type' => 'Deluxe', 'capacity' => 2, 'price_per_night' => 150.00),
    array('room_id' => 4, 'room_number' => '301', 'type_id' => 3, 'room_type' => 'Suite', 'capacity' => 4, 'price_per_night' => 250.00),
    array('room_id' => 5, 'room_number' => '401', 'type_id' => 4, 'room_type' => 'Family Room', 'capacity' => 5, 'price_per_night' => 200.00)
);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $firstName = sanitizeInput($_POST['first_name']);
    $lastName = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $checkInDate = sanitizeInput($_POST['check_in_date']);
    $checkOutDate = sanitizeInput($_POST['check_out_date']);
    $roomId = (int)$_POST['room_id'];
    $numGuests = (int)$_POST['num_guests'];
    $specialRequests = sanitizeInput($_POST['special_requests']);
    
    // Validate input
    $errors = array();
    
    if (empty($firstName)) {
        $errors[] = "First name is required";
    }
    
    if (empty($lastName)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($checkInDate) || !validateDate($checkInDate)) {
        $errors[] = "Valid check-in date is required";
    }
    
    if (empty($checkOutDate) || !validateDate($checkOutDate)) {
        $errors[] = "Valid check-out date is required";
    }
    
    if ($checkInDate >= $checkOutDate) {
        $errors[] = "Check-out date must be after check-in date";
    }
    
    if ($roomId <= 0) {
        $errors[] = "Room selection is required";
    }
    
    if ($numGuests <= 0) {
        $errors[] = "Number of guests must be greater than zero";
    }
    
    // If no errors, process the reservation (in a real system, this would save to the database)
    if (empty($errors)) {
        // For demonstration, we'll just redirect with a success message
        echo "<script>
            alert('Reservation created successfully!');
            window.location.href = 'read.php';
        </script>";
        exit;
    }
}

// Default dates
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$maxDate = date('Y-m-d', strtotime('+1 year'));
?>

<h2>Create New Reservation</h2>

<?php
// Display errors if any
if (isset($errors) && !empty($errors)) {
    echo '<div class="alert alert-error">';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}
?>

<form id="reservation-form" method="post" action="">
    <div class="form-row">
        <div class="form-col">
            <h3>Guest Information</h3>
            
            <div class="form-group">
                <label for="first-name">First Name *</label>
                <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="last-name">Last Name *</label>
                <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone *</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address"><?php echo htmlspecialchars($address); ?></textarea>
            </div>
        </div>
        
        <div class="form-col">
            <h3>Reservation Details</h3>
            
            <div class="form-group">
                <label for="check-in-date">Check-in Date *</label>
                <input type="date" id="check-in-date" name="check_in_date" value="<?php echo htmlspecialchars($checkInDate ?: $today); ?>" min="<?php echo $today; ?>" max="<?php echo $maxDate; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="check-out-date">Check-out Date *</label>
                <input type="date" id="check-out-date" name="check_out_date" value="<?php echo htmlspecialchars($checkOutDate ?: $tomorrow); ?>" min="<?php echo $tomorrow; ?>" max="<?php echo $maxDate; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="room-id">Room *</label>
                <select id="room-id" name="room_id" required>
                    <option value="">Select a room</option>
                    <?php foreach ($rooms as $room) : ?>
                        <option value="<?php echo $room['room_id']; ?>" <?php echo ($roomId == $room['room_id']) ? 'selected' : ''; ?>>
                            <?php echo $room['room_number']; ?> - <?php echo $room['room_type']; ?> 
                            (Capacity: <?php echo $room['capacity']; ?>, Price: <?php echo formatPrice($room['price_per_night']); ?>/night)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="num-guests">Number of Guests *</label>
                <input type="number" id="num-guests" name="num_guests" value="<?php echo $numGuests; ?>" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="special-requests">Special Requests</label>
                <textarea id="special-requests" name="special_requests"><?php echo htmlspecialchars($specialRequests); ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="read.php" class="btn">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Reservation</button>
    </div>
</form>

<script>
// Additional client-side validation
document.addEventListener('DOMContentLoaded', function() {
    const checkInDate = document.getElementById('check-in-date');
    const checkOutDate = document.getElementById('check-out-date');
    
    checkInDate.addEventListener('change', function() {
        const minCheckOut = new Date(this.value);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        
        const minDateStr = minCheckOut.toISOString().split('T')[0];
        checkOutDate.min = minDateStr;
        
        if (checkOutDate.value && new Date(checkOutDate.value) <= new Date(this.value)) {
            checkOutDate.value = minDateStr;
        }
    });
    
    const roomSelect = document.getElementById('room-id');
    const numGuests = document.getElementById('num-guests');
    
    roomSelect.addEventListener('change', function() {
        if (this.value !== '') {
            const selectedOption = this.options[this.selectedIndex];
            const capacityMatch = selectedOption.textContent.match(/Capacity: (\d+)/);
            
            if (capacityMatch && capacityMatch[1]) {
                const capacity = parseInt(capacityMatch[1]);
                numGuests.max = capacity;
                
                if (parseInt(numGuests.value) > capacity) {
                    numGuests.value = capacity;
                }
            }
        } else {
            numGuests.removeAttribute('max');
        }
    });
    
    // Trigger the change events to set initial constraints
    checkInDate.dispatchEvent(new Event('change'));
    if (roomSelect.value !== '') {
        roomSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<?php include $rootPath . '/includes/footer.php'; ?>