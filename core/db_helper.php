<?php
/**
 * Database Helper - Core PHP
 * Provides PDO connection for core voting logic
 * Reads configuration from environment variables
 */

function getDb() {
    static $conn = null;
    if ($conn === null) {
        // Try to get from environment (production)
        $host = getenv('DB_HOST');
        $db = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');
        $port = getenv('DB_PORT');
        
        // Fallback for local development
        if (!$host) {
            $host = '127.0.0.1';
            $db = 'polling_app';
            $user = 'root';
            $pass = '';
            $port = '3306';
        }
        
        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $conn = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
    }
    return $conn;
}

function executeQuery($sql, $params = []) {
    $db = getDb();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
