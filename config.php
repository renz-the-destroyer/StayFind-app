<?php
// config.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Use getenv() so Railway can fill these in automatically
$host = getenv('MYSQLHOST') ?: "stay-find67-stay-find.a.aivencloud.com"; 
$port = getenv('MYSQLPORT') ?: "26647";
$db_name = getenv('MYSQLDATABASE') ?: "defaultdb";
$username = getenv('MYSQLUSER') ?: "avnadmin";
$password = getenv('MYSQLPASSWORD') ?: "AVNS_aFLR1W04kE47kO7vGNn"; 

try {
    // Connection string
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $e->getMessage()]);
    exit;
}
?>