<?php
/**
 * Database connection class
 */
require_once 'config.php';
class Database {
    private $conn;
    private $lastId;
 * Database connection for the Hotel Reservation System
 */
// Include config file if not already included
if (!defined('DB_PATH')) {
    require_once __DIR__ . '/config.php';
}
/**
 * Get database connection
 * 
 * @return SQLite3 The database connection
 */
function getDbConnection() {
    static $db = null;
    
    /**
     * Constructor: Establish database connection
     */
    public function __construct() {
    if ($db === null) {
        try {
            // Create db directory if it doesn't exist
            // Create database directory if it doesn't exist
            $dbDir = dirname(DB_PATH);
            if (!file_exists($dbDir)) {
                mkdir($dbDir, 0777, true);
            if (!is_dir($dbDir)) {
                mkdir($dbDir, 0755, true);
            }
            
            $this->conn = new SQLite3(DB_PATH);
            $this->conn->enableExceptions(true);
            // Create new SQLite database connection
            $db = new SQLite3(DB_PATH);
            $db->enableExceptions(true);
            
            // Set pragmas for better performance
            $this->conn->exec('PRAGMA foreign_keys = ON');
            $this->conn->exec('PRAGMA journal_mode = WAL');
            $db->exec('PRAGMA foreign_keys = ON');
            $db->exec('PRAGMA journal_mode = WAL');
            
            // Initialize the database if it's new
            $this->initializeDatabase();
            // Create tables if they don't exist
            createTables($db);
            
        } catch (Exception $e) {
            echo "Database connection error: " . $e->getMessage();
            exit;
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Initialize the database schema and sample data
     */
    private function initializeDatabase() {
        try {
            // Check if the rooms table exists
            $result = $this->conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='rooms'");
            if (!$result || !$result->fetchArray()) {
                // Load and execute the schema SQL
                $schemaFile = dirname(__DIR__) . '/sql/schema_sqlite.sql';
                if (file_exists($schemaFile)) {
                    $sql = file_get_contents($schemaFile);
                    // Split the SQL file by semicolons
                    $statements = array_filter(array_map('trim', explode(';', $sql)));
                    
                    foreach ($statements as $statement) {
                        if (!empty($statement)) {
                            $this->conn->exec($statement);
                        }
                    }
    return $db;
}
/**
 * Create tables if they don't exist
 * 
 * @param SQLite3 $db The database connection
 */
function createTables($db) {
    try {
        // Create room_types table
        $db->exec('
            CREATE TABLE IF NOT EXISTS room_types (
                type_id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ');
        
        // Create rooms table
        $db->exec('
            CREATE TABLE IF NOT EXISTS rooms (
                room_id INTEGER PRIMARY KEY AUTOINCREMENT,
                room_number TEXT NOT NULL UNIQUE,
                type_id INTEGER NOT NULL,
                capacity INTEGER NOT NULL DEFAULT 1,
                price_per_night REAL NOT NULL,
                description TEXT,
                status TEXT DEFAULT "available",
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (type_id) REFERENCES room_types(type_id)
            )
        ');
        
        // Create guests table
        $db->exec('
            CREATE TABLE IF NOT EXISTS guests (
                guest_id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                email TEXT,
                phone TEXT,
                address TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ');
        
        // Create reservations table
        $db->exec('
            CREATE TABLE IF NOT EXISTS reservations (
                reservation_id INTEGER PRIMARY KEY AUTOINCREMENT,
                guest_id INTEGER NOT NULL,
                room_id INTEGER NOT NULL,
                check_in_date TEXT NOT NULL,
                check_out_date TEXT NOT NULL,
                num_guests INTEGER NOT NULL DEFAULT 1,
                total_price REAL NOT NULL,
                payment_status TEXT DEFAULT "pending",
                special_requests TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (guest_id) REFERENCES guests(guest_id),
                FOREIGN KEY (room_id) REFERENCES rooms(room_id)
            )
        ');
        
        // Insert sample data if tables are empty
        insertSampleData($db);
        
    } catch (Exception $e) {
        die('Error creating tables: ' . $e->getMessage());
    }
}
/**
 * Insert sample data if tables are empty
 * 
 * @param SQLite3 $db The database connection
 */
function insertSampleData($db) {
    try {
        // Check if room_types table is empty
        $result = $db->query('SELECT COUNT(*) as count FROM room_types');
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($row['count'] == 0) {
            // Insert sample room types
            $db->exec('
                INSERT INTO room_types (name, description) VALUES
                ("Standard", "A comfortable room with essential amenities"),
                ("Deluxe", "A spacious room with premium furnishings"),
                ("Suite", "A luxurious suite with separate living area"),
                ("Family Room", "A large room suitable for families"),
                ("Presidential Suite", "Our most luxurious accommodation")
            ');
        }
        
        // Check if rooms table is empty
        $result = $db->query('SELECT COUNT(*) as count FROM rooms');
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($row['count'] == 0) {
            // Insert sample rooms
            $db->exec('
                INSERT INTO rooms (room_number, type_id, capacity, price_per_night, description, status) VALUES
                ("101", 1, 2, 100.00, "Standard room with city view", "available"),
                ("102", 1, 2, 100.00, "Standard room with garden view", "available"),
                ("201", 2, 2, 150.00, "Deluxe room with city view", "available"),
                ("202", 2, 3, 150.00, "Deluxe room with garden view", "available"),
                ("301", 3, 4, 250.00, "Suite with city view", "available"),
                ("302", 3, 4, 250.00, "Suite with garden view", "available"),
                ("401", 4, 5, 200.00, "Family room with two queen beds", "available"),
                ("501", 5, 4, 500.00, "Presidential suite with panoramic view", "available")
            ');
        }
        
        // Check if guests table is empty
        $result = $db->query('SELECT COUNT(*) as count FROM guests');
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($row['count'] == 0) {
            // Insert sample guests
            $db->exec('
                INSERT INTO guests (first_name, last_name, email, phone, address) VALUES
                ("John", "Smith", "john.smith@example.com", "123-456-7890", "123 Main St, Anytown, USA"),
                ("Jane", "Doe", "jane.doe@example.com", "987-654-3210", "456 Oak St, Somewhere, USA"),
                ("Michael", "Johnson", "michael.johnson@example.com", "555-123-4567", "789 Pine St, Elsewhere, USA")
            ');
        }
        
        // Check if reservations table is empty
        $result = $db->query('SELECT COUNT(*) as count FROM reservations');
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($row['count'] == 0) {
            // Insert sample reservations
            $db->exec('
                INSERT INTO reservations (guest_id, room_id, check_in_date, check_out_date, num_guests, total_price, payment_status, special_requests) VALUES
                (1, 1, "2025-05-25", "2025-05-28", 2, 300.00, "paid", "Extra pillows please"),
                (2, 3, "2025-06-01", "2025-06-05", 2, 600.00, "pending", "Late check-in (around 10pm)"),
                (3, 4, "2025-05-20", "2025-05-22", 3, 500.00, "cancelled", "No special requests")
            ');
        }
        
    } catch (Exception $e) {
        die('Error inserting sample data: ' . $e->getMessage());
    }
}
/**
 * Execute query and return all results as an array
 * 
 * @param string $sql The SQL query to execute
 * @param array $params Optional parameters for prepared statement
 * @return array|false Array of results or false on failure
 */
function executeQuery($sql, $params = []) {
    try {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        
        if ($stmt === false) {
            throw new Exception('Failed to prepare statement: ' . $db->lastErrorMsg());
        }
        
        // Bind parameters if any
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $paramName = is_int($key) ? $key + 1 : ':' . $key;
                
                if (is_int($value)) {
                    $stmt->bindValue($paramName, $value, SQLITE3_INTEGER);
                } elseif (is_float($value)) {
                    $stmt->bindValue($paramName, $value, SQLITE3_FLOAT);
                } elseif (is_null($value)) {
                    $stmt->bindValue($paramName, null, SQLITE3_NULL);
                } else {
                    throw new Exception("Schema file not found: $schemaFile");
                    $stmt->bindValue($paramName, $value, SQLITE3_TEXT);
                }
            }
        } catch (Exception $e) {
            echo "Database initialization error: " . $e->getMessage();
            exit;
        }
    }
    
    /**
     * Execute a query
     * 
     * @param string $sql The SQL query
     * @return SQLite3Result|bool Result set or boolean
     */
    public function query($sql) {
        try {
            $result = $this->conn->query($sql);
            return $result;
        } catch (Exception $e) {
            echo "Database query error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Prepare a statement
     * 
     * @param string $sql The SQL statement
     * @return SQLiteStatement|bool Prepared statement or boolean
     */
    public function prepare($sql) {
        try {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->conn->lastErrorMsg());
            }
            return new SQLiteStatement($stmt, $this->conn);
        } catch (Exception $e) {
            echo "Database prepare error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Get the last inserted ID
     * 
     * @return int Last inserted ID
     */
    public function getLastId() {
        return $this->conn->lastInsertRowID();
    }
    
    /**
     * Get the number of affected rows (NOTE: SQLite doesn't support this directly)
     * 
     * @return int Number of affected rows
     */
    public function getAffectedRows() {
        return $this->conn->changes();
    }