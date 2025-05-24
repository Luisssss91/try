<?php
/**
 * View all reservations
 * View reservations
 */
$pageTitle = 'View Reservations';
$rootPath = dirname(__DIR__);
include $rootPath . '/includes/header.php';
include $rootPath . '/includes/functions.php';
// Sample reservation data (simplified for demo)
$reservations = array(
    array(
// Get reservations (for demonstration purposes, we'll use sample data)
$reservations = [
    [
        'reservation_id' => 1,
        'guest_id' => 1,
        'first_name' => 'John',
        'last_name' => 'Smith',
        'email' => 'john.smith@example.com',
        'phone' => '123-456-7890',
        'room_id' => 1,
        'room_number' => '101',
        'room_type' => 'Standard',
        'check_in_date' => '2025-05-25',
        'check_out_date' => '2025-05-28',
        'num_guests' => 2,
        'total_price' => 300.00,
        'payment_status' => 'paid'
    ),
    array(
        'payment_status' => 'paid',
        'special_requests' => 'Extra pillows please'
    ],
    [
        'reservation_id' => 2,
        'guest_id' => 2,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane.doe@example.com',
        'phone' => '987-654-3210',
        'room_id' => 3,
        'room_number' => '201',
        'room_type' => 'Deluxe',
        'check_in_date' => '2025-06-01',
        'check_out_date' => '2025-06-05',
        'num_guests' => 2,
        'total_price' => 600.00,
        'payment_status' => 'pending'
    ),
    array(
        'payment_status' => 'pending',
        'special_requests' => 'Late check-in (around 10pm)'
    ],
    [
        'reservation_id' => 3,
        'guest_id' => 3,
        'first_name' => 'Michael',
        'last_name' => 'Johnson',
        'email' => 'michael.johnson@example.com',
        'phone' => '555-123-4567',
        'room_id' => 4,
        'room_number' => '301',
        'room_type' => 'Suite',
        'check_in_date' => '2025-05-20',
        'check_out_date' => '2025-05-22',
        'num_guests' => 3,
        'total_price' => 500.00,
        'payment_status' => 'cancelled'
    )
);
// Handle search/filter (simplified for demo)
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';
if ($search || $status) {
    $filtered = array();
        'payment_status' => 'cancelled',
        'special_requests' => 'No special requests'
    ]
];
// Filter reservations based on search criteria
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';
if (!empty($searchTerm) || !empty($statusFilter)) {
    $filteredReservations = [];
    
    foreach ($reservations as $reservation) {
        $nameMatch = stripos($reservation['first_name'] . ' ' . $reservation['last_name'], $search) !== false;
        $emailMatch = stripos($reservation['email'], $search) !== false;
        $roomMatch = stripos($reservation['room_number'], $search) !== false;
        $statusMatch = !$status || $reservation['payment_status'] === $status;
        $matchesSearch = empty($searchTerm) || 
            stripos($reservation['first_name'], $searchTerm) !== false || 
            stripos($reservation['last_name'], $searchTerm) !== false || 
            stripos($reservation['email'], $searchTerm) !== false || 
            stripos($reservation['phone'], $searchTerm) !== false || 
            stripos($reservation['room_number'], $searchTerm) !== false;
        
        if (($nameMatch || $emailMatch || $roomMatch) && $statusMatch) {
            $filtered[] = $reservation;
        $matchesStatus = empty($statusFilter) || $reservation['payment_status'] === $statusFilter;
        
        if ($matchesSearch && $matchesStatus) {
            $filteredReservations[] = $reservation;
        }
    }
    $reservations = $filtered;
    
    $reservations = $filteredReservations;
}
// Pagination (simplified)
$totalReservations = count($reservations);
$totalPages = ceil($totalReservations / 10);
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
// Pagination (simplified for demo)
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 10;
$totalItems = count($reservations);
$totalPages = ceil($totalItems / $itemsPerPage);
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
}
$startIndex = ($currentPage - 1) * $itemsPerPage;
$displayedReservations = array_slice($reservations, $startIndex, $itemsPerPage);
?>
<h2>View Reservations</h2>
<!-- Search and Filter Form -->
<div class="search-box">
<div class="header-actions">
    <h2>Reservations</h2>
    <a href="create.php" class="btn btn-primary">Create New Reservation</a>
</div>
<div class="filters">
    <form method="get" action="" class="search-form">
        <div class="form-group">
            <label for="search">Search</label>
            <input type="text" id="search" name="search" placeholder="Name, Email, Room #" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="paid" <?php echo (isset($_GET['status']) && $_GET['status'] === 'paid') ? 'selected' : ''; ?>>Paid</option>
                <option value="cancelled" <?php echo (isset($_GET['status']) && $_GET['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="read.php" class="btn">Reset</a>
        <div class="form-row">
            <div class="form-group">
                <input type="text" name="search" placeholder="Search by name, email, phone, room..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>
            
            <div class="form-group">
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="paid" <?php echo $statusFilter === 'paid' ? 'selected' : ''; ?>>Paid</option>
                    <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Filter</button>
                <a href="read.php" class="btn">Reset</a>
            </div>
        </div>
    </form>
</div>
<!-- Reservations Table -->
<div class="table-container">
    <?php if (count($reservations) > 0) : ?>
<?php if (empty($reservations)): ?>
    <div class="alert alert-info">No reservations found.</div>
<?php else: ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
-22
+33
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation) : ?>
                <?php foreach ($displayedReservations as $reservation): ?>
                    <?php $statusInfo = getStatusInfo($reservation['payment_status']); ?>
                    <tr>
                        <td><?php echo $reservation['reservation_id']; ?></td>
                        <td>
                            <?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?><br>
                            <small><?php echo htmlspecialchars($reservation['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($reservation['room_number'] . ' (' . $reservation['room_type'] . ')'); ?></td>
                            <div><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></div>
                            <div class="text-small"><?php echo htmlspecialchars($reservation['email']); ?></div>
                        </td>
                        <td>
                            <div><?php echo htmlspecialchars($reservation['room_number']); ?></div>
                            <div class="text-small"><?php echo htmlspecialchars($reservation['room_type']); ?></div>
                        </td>
                        <td><?php echo formatDate($reservation['check_in_date']); ?></td>
                        <td><?php echo formatDate($reservation['check_out_date']); ?></td>
                        <td><?php echo $reservation['num_guests']; ?></td>
                        <td><?php echo formatPrice($reservation['total_price']); ?></td>
                        <td><span class="badge badge-<?php echo strtolower($reservation['payment_status']); ?>"><?php echo ucfirst($reservation['payment_status']); ?></span></td>
                        <td>
                            <a href="update.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-small">Edit</a>
                            <a href="delete.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?');">Delete</a>
                        <td>
                            <span class="status-indicator <?php echo $statusInfo['class']; ?>">
                                <?php echo $statusInfo['label']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="update.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="delete.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Pagination (simplified) -->
        <?php if ($totalPages > 1) : ?>
            <div class="pagination">
                <?php if ($page > 1) : ?>
                    <a href="?page=<?php echo ($page - 1); ?><?php echo !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo !empty($_GET['status']) ? '&status=' . urlencode($_GET['status']) : ''; ?>" class="btn">&laquo; Previous</a>
    </div>
    
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>&status=<?php echo urlencode($statusFilter); ?>" class="btn btn-sm">&laquo; Previous</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <span class="btn btn-sm btn-primary"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>&status=<?php echo urlencode($statusFilter); ?>" class="btn btn-sm"><?php echo $i; ?></a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++) : ?>
                    <?php if ($i == $page) : ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else : ?>