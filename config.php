<?php
// config.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Clever Cloud fills these automatically once you link the MySQL Add-on
$host = getenv('MYSQL_ADDON_HOST');
$port = getenv('MYSQL_ADDON_PORT') ?: "3306"; // Default MySQL port
$db_name = getenv('MYSQL_ADDON_DB');
$username = getenv('MYSQL_ADDON_USER');
$password = getenv('MYSQL_ADDON_PASSWORD');

try {
    // We added "charset=utf8mb4" to support emojis and special characters
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // If it fails, we send a clear JSON error back to your website
    echo json_encode(["success" => false, "message" => "Database Connection Error"]);
    exit;
}
?>
