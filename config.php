<?php
// config.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Clever Cloud uses different names for their database info
$host = getenv('MYSQL_ADDON_HOST') ?: "localhost"; 
$port = getenv('MYSQL_ADDON_PORT') ?: "3306";
$db_name = getenv('MYSQL_ADDON_DB') ?: "";
$username = getenv('MYSQL_ADDON_USER') ?: "";
$password = getenv('MYSQL_ADDON_PASSWORD') ?: ""; 

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $e->getMessage()]);
    exit;
}
?>
