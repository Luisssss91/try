
<?php 
// Use the proper absolute path for includes
$rootPath = realpath($_SERVER['DOCUMENT_ROOT']);
require_once $rootPath . '/includes/config.php';
<?php
/**
 * Header file for the Hotel Reservation System
 */
// Include config file if not already included
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <script src="<?php echo BASE_URL; ?>js/validation.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <h1><a href="<?php echo BASE_URL; ?>"><?php echo APP_NAME; ?></a></h1>
            </div>
    <header>
        <div class="container">
            <h1><a href="<?php echo BASE_URL; ?>"><?php echo APP_NAME; ?></a></h1>
            <nav>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>rooms/list.php">Rooms</a></li>
                    <li><a href="<?php echo BASE_URL; ?>reservations/read.php">Reservations</a></li>
                    <li><a href="<?php echo BASE_URL; ?>reservations/create.php">New Reservation</a></li>
                    <li><a href="<?php echo BASE_URL; ?>rooms/list.php">Rooms</a></li>
                    <li><a href="<?php echo BASE_URL; ?>rooms/availability.php">Check Availability</a></li>
                </ul>
            </nav>
        </header>
        
        <?php
        // Display flash messages if any
        $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
        if ($flash) {
            unset($_SESSION['flash']);
            echo '<div class="alert alert-' . $flash['type'] . '">' . $flash['message'] . '</div>';
        }
        ?>
        
        <main>
        </div>
    </header>
    
    <main class="container">