<?php
/**
 * Main entry point for the Hotel Reservation System
 * Hotel Reservation System - Homepage
 */
$pageTitle = 'Home';
include 'includes/header.php';
include 'includes/functions.php';
// Sample statistics (for demonstration purposes)
$stats = [
    'total_rooms' => 8,
    'available_rooms' => 8,
    'total_reservations' => 3,
    'upcoming_checkins' => 2
];
// Sample recent reservations
$recentReservations = [
    [
        'reservation_id' => 1,
        'guest_name' => 'John Smith',
        'room_number' => '101',
        'check_in_date' => '2025-05-25',
        'check_out_date' => '2025-05-28',
        'status' => 'paid'
    ],
    [
        'reservation_id' => 2,
        'guest_name' => 'Jane Doe',
        'room_number' => '201',
        'check_in_date' => '2025-06-01',
        'check_out_date' => '2025-06-05',
        'status' => 'pending'
    ],
    [
        'reservation_id' => 3,
        'guest_name' => 'Michael Johnson',
        'room_number' => '301',
        'check_in_date' => '2025-05-20',
        'check_out_date' => '2025-05-22',
        'status' => 'cancelled'
    ]
];
?>
<section class="welcome">
    <h2>Welcome to Hotel Reservation System</h2>
    <p>Manage hotel reservations, check room availability, and more.</p>
</section>
<div class="hero">
    <h1>Welcome to Hotel Reservation System</h1>
    <p>Manage your hotel bookings with ease</p>
</div>
<section class="dashboard">
    <div class="row">
        <div class="col">
            <div class="card">
                <h3>Quick Actions</h3>
                <ul class="action-links">
                    <li><a href="reservations/create.php" class="btn btn-primary">Create New Reservation</a></li>
                    <li><a href="reservations/read.php" class="btn">View All Reservations</a></li>
                    <li><a href="rooms/availability.php" class="btn">Check Room Availability</a></li>
                </ul>
            </div>
        </div>
        
        <div class="col">
            <div class="card">
                <h3>Recent Reservations</h3>
                <p>View and manage your recent hotel reservations.</p>
                <p class="view-all"><a href="reservations/read.php">View all reservations</a></p>
            </div>
        </div>
<div class="quick-actions">
    <a href="reservations/create.php" class="action-card">
        <div class="action-icon">➕</div>
        <h3>Create Reservation</h3>
        <p>Add a new booking to the system</p>
    </a>
    
    <a href="reservations/read.php" class="action-card">
        <div class="action-icon">📋</div>
        <h3>View Reservations</h3>
        <p>See all current and upcoming bookings</p>
    </a>
    
    <a href="rooms/list.php" class="action-card">
        <div class="action-icon">🏠</div>
        <h3>Room List</h3>
        <p>Browse available rooms and details</p>
    </a>
    
    <a href="rooms/availability.php" class="action-card">
        <div class="action-icon">🔍</div>
        <h3>Check Availability</h3>
        <p>Find available rooms for specific dates</p>
    </a>
</div>
<div class="dashboard">
    <div class="stats-card">
        <div class="stats-card-title">Total Rooms</div>
        <div class="stats-card-value"><?php echo $stats['total_rooms']; ?></div>
    </div>
    
    <div class="row">
        <div class="col">
            <div class="card">
                <h3>Room Availability Overview</h3>
                <p>Check room availability for your desired dates.</p>
                <p class="view-all"><a href="rooms/availability.php">Check detailed availability</a></p>
            </div>
        </div>
    <div class="stats-card">
        <div class="stats-card-title">Available Rooms</div>
        <div class="stats-card-value"><?php echo $stats['available_rooms']; ?></div>
    </div>
</section>
    
    <div class="stats-card">
        <div class="stats-card-title">Total Reservations</div>
        <div class="stats-card-value"><?php echo $stats['total_reservations']; ?></div>
    </div>
    
    <div class="stats-card">
        <div class="stats-card-title">Upcoming Check-ins</div>
        <div class="stats-card-value"><?php echo $stats['upcoming_checkins']; ?></div>
    </div>
</div>
<div class="recent-activity">
    <h2>Recent Reservations</h2>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentReservations as $reservation): ?>
                    <?php $statusInfo = getStatusInfo($reservation['status']); ?>
                    <tr>
                        <td><?php echo $reservation['reservation_id']; ?></td>
                        <td><?php echo htmlspecialchars($reservation['guest_name']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['room_number']); ?></td>
                        <td><?php echo formatDate($reservation['check_in_date']); ?></td>
                        <td><?php echo formatDate($reservation['check_out_date']); ?></td>
                        <td>
                            <span class="status-indicator <?php echo $statusInfo['class']; ?>">
                                <?php echo $statusInfo['label']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="reservations/update.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="reservations/delete.php?id=<?php echo $reservation['reservation_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="view-all">
        <a href="reservations/read.php" class="btn">View All Reservations</a>
    </div>
</div>
<style>
/* Additional styling for the dashboard */
.dashboard {
    margin-top: 30px;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px 30px;
}
.col {
    flex: 1;
    min-width: 300px;
    padding: 0 15px;
    margin-bottom: 20px;
}
.card {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    height: 100%;
}
.card h3 {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ecf0f1;
    color: #2c3e50;
}
.action-links {
    list-style: none;
}
.action-links li {
    margin-bottom: 10px;
}
.action-links .btn {
    display: block;
    text-align: center;
}
.view-all {
    margin-top: 15px;
    text-align: right;
}
.view-all a {
    color: #3498db;
    text-decoration: none;
}
.availability-summary {
    margin-bottom: 15px;
}
.welcome {
    text-align: center;
    margin-bottom: 30px;
}
.welcome h2 {
    font-size: 28px;
    margin-bottom: 10px;
    border-bottom: none;
}
.btn-small {
    padding: 5px 10px;
    font-size: 14px;
}
    .hero {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .hero h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    .hero p {
        font-size: 1.2rem;
        color: #666;
    }
    
    .action-card {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        color: var(--dark-color);
        text-decoration: none;
    }
    
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
    
    .action-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .action-card h3 {
        margin-bottom: 0.5rem;
    }
    
    .action-card p {
        color: #666;
        font-size: 0.9rem;
    }
    
    .view-all {
        text-align: center;
        margin-top: 1.5rem;
    }
</style>
<?php include 'includes/footer.php'; ?>