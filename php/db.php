<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
header('Content-Type: application/json');

// -------------------- MySQL Connection --------------------
$mysqlHost = "127.0.0.1";
$mysqlDB   = "userconnect";
$mysqlUser = "root";
$mysqlPass = ""; // your MySQL password

try {
    $pdo = new PDO(
        "mysql:host=$mysqlHost;dbname=$mysqlDB;charset=utf8mb4",
        $mysqlUser,
        $mysqlPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'MySQL connection failed: ' . $e->getMessage()]);
    exit;
}

// -------------------- Composer Autoload --------------------
require_once __DIR__ . '/../vendor/autoload.php'; // adjust path if needed

// -------------------- MongoDB Connection --------------------
try {
    $mongoClient = new MongoDB\Client("mongodb://127.0.0.1:27017");
    $mongoDB = $mongoClient->userconnect;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'MongoDB connection failed: ' . $e->getMessage()]);
    exit;
}

// -------------------- Redis Connection --------------------
try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Redis connection failed: ' . $e->getMessage()]);
    exit;
}

// Now $pdo, $mongoDB, and $redis are ready to use
?>
