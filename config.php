<?php
// config.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Gamit ang iyong bagong reset password mula sa Clever Cloud
$host = "baqwemdwrhxssdp5yekr-mysql.services.clever-cloud.com";
$db_name = "baqwemdwrhxssdp5yekr";
$username = "uifpvjnaigyucgcl";
$password = "XQq3egbgQ0fSkTKtxD7a"; // <--- Updated na ito
$port = "3306";

try {
    // Koneksyon gamit ang bagong credentials
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // Set error mode para sa debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // Error message kung may problema pa rin sa login
    echo json_encode([
        "success" => false, 
        "message" => "Database Connection Failed: " . $e->getMessage()
    ]);
    exit;
}
?>
