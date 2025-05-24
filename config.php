<?php
/**
 * Configuration file for the Hotel Reservation System
 */

// Error reporting for development (should be turned off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
define('DB_TYPE', 'sqlite');
define('DB_PATH', __DIR__ . '/../db/hotel_reservation_system.db');

// Application URL and paths
define('BASE_URL', '/');
define('APP_NAME', 'Hotel Reservation System');

// Session start
if (!session_id()) {
    session_start();
}

// Set timezone
date_default_timezone_set('UTC');