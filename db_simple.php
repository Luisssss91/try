<?php
/**
 * Simplified Database connection class for SQLite
 */
require_once 'config.php';

class Database {
    private $db;
    
    /**
     * Constructor: Establish database connection
     */
    public function __construct() {
        try {
            // Create db directory if it doesn't exist
            $dbDir = dirname(DB_PATH);
            if (!file_exists($dbDir)) {
                mkdir($dbDir, 0777, true);
            }
            
            $this->db = new SQLite3(DB_PATH);
            $this->db->exec('PRAGMA foreign_keys = ON');
            
            // Initialize database schema if needed
            $this->initDatabase();
            
        } catch (Exception $e) {
            echo "Database error: " . $e->getMessage();
            exit;
        }
    }
    /**
     * Initialize database schema if needed
     */
    private function initDatabase() {
        $tables = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='rooms'");
        if (!$tables || !$tables->fetchArray()) {
            // Database is empty, create schema
            $schemaFile = dirname(__DIR__) . '/sql/schema_sqlite.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $statements = explode(';', $sql);
                
                foreach ($statements as $statement) {
                    if (trim($statement)) {
                        $this->db->exec($statement);
                    }
                }
            }
        }
    }
    

    /**
     * Execute a query and return all results as an array
     */
    public function query($sql) {
        try {
            $result = $this->db->query($sql);
            if (!$result) {
                throw new Exception($this->db->lastErrorMsg());
                }
            
            $rows = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $row;
            }
            
            return $rows;
        } catch (Exception $e) {
            echo "Query error: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Execute a query and return a single row
     */
    public function querySingle($sql) {
        try {
            $result = $this->db->querySingle($sql, true);
            return $result;
        } catch (Exception $e) {
            echo "Query error: " . $e->getMessage();
            return null;
        }
    }
    
    /**
     * Execute an SQL statement (INSERT, UPDATE, DELETE)
     */
    public function exec($sql) {
        try {
            $result = $this->db->exec($sql);
            if ($result === false) {
                throw new Exception($this->db->lastErrorMsg());
            }
            return $result;
        } catch (Exception $e) {
            echo "Exec error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Get the ID of the last inserted row
     */
    public function lastInsertId() {
        return $this->db->lastInsertRowID();
    }
    
    /**
     * Escape a string for use in a query
     */
    public function escape($str) {
        return SQLite3::escapeString($str);
    }
    
    /**
     * Close the database connection
     */
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }