<?php
/**
 * Delete a reservation
 */
$rootPath = dirname(__DIR__);
require_once $rootPath . '/includes/config.php';
require_once $rootPath . '/includes/functions.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
        alert('No reservation ID provided');
        window.location.href = 'read.php';
    </script>";
    exit;
}

$reservationId = (int)$_GET['id'];

// In a real application, we would delete from the database
// For this demo, we'll just redirect with a success message
echo "<script>
    if (confirm('Are you sure you want to delete reservation #" . $reservationId . "?')) {
        alert('Reservation deleted successfully!');
    }
    window.location.href = 'read.php';
</script>";
exit;
?>