<?php
// update_profile.php
include 'config.php';
header("Content-Type: application/json");

// Kunin ang data mula sa frontend (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Check kung may email na kasama
if (!isset($data['email']) || empty($data['email'])) {
    echo json_encode(["success" => false, "message" => "User email missing"]);
    exit;
}

$email = $data['email'];
$name = $data['name'];
$address = $data['address'];
$contact = $data['contact'];
$role = $data['role'];

try {
    // Siguraduhin na ang column name sa SQL ay tugma sa database mo
    // Kung 'full_name' ang nilagay mo sa SQL command kanina, gamitin ang 'full_name' dito.
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, address = ?, contact = ?, role = ? WHERE email = ?");
    $stmt->execute([$name, $address, $contact, $role, $email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Profile updated"]);
    } else {
        // Kung walang nabago (halimbawa: same data or email not found)
        echo json_encode(["success" => true, "message" => "No changes made or user not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
