<?php
// config.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Isulat natin mismo ang credentials para iwas-error sa Render environment
$host = "baqwemdwrhxssdp5yekr-mysql.services.clever-cloud.com";
$db_name = "baqwemdwrhxssdp5yekr";
$username = "uifpvjnaigyucgcl";
$password = "iEUJZ5p2MtUODjBjrnJ8"; 
$port = "3306";

try {
    // Kinonekta natin gamit ang eksaktong details
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // Set error mode para makita natin kung may table issues
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // Ito ang lalabas kapag mali pa rin ang credentials
    echo json_encode([
        "success" => false, 
        "message" => "Database Connection Failed: " . $e->getMessage()
    ]);
    exit;
}
?>
