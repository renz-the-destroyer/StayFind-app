<?php
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data['email']) {
    echo json_encode(["success" => false, "message" => "User email missing"]);
    exit;
}

$email = $data['email'];
$name = $data['name'];
$address = $data['address'];
$contact = $data['contact'];
$role = $data['role'];

try {
    // Update the existing user record in MySQL
    $stmt = $pdo->prepare("UPDATE users SET name = ?, address = ?, contact = ?, role = ? WHERE email = ?");
    $stmt->execute([$name, $address, $contact, $role, $email]);

    echo json_encode(["success" => true, "message" => "Profile updated"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>