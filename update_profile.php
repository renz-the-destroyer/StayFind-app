<?php
// update_profile.php
include 'config.php';
header("Content-Type: application/json");

// Kunin ang data mula sa frontend (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Check kung may email at role na kasama (ito ang pinaka-importante)
if (empty($data['email']) || empty($data['role'])) {
    echo json_encode(["success" => false, "message" => "Required fields missing (email/role)"]);
    exit;
}

$email   = $data['email'];
$name    = $data['name'];
$address = $data['address'];
$contact = $data['contact'];
$role    = $data['role'];

try {
    // Siguraduhin na 'full_name' ang column name gaya ng nasa table structure natin
    $sql = "UPDATE users SET full_name = ?, address = ?, contact = ?, role = ? WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $address, $contact, $role, $email]);

    // Sa UPDATE, kahit 0 ang rowCount (walang binago), basta walang Exception, "Success" pa rin ito.
    echo json_encode([
        "success" => true, 
        "message" => "Profile setup completed successfully!"
    ]);

} catch (PDOException $e) {
    // Ibalik ang actual error para madali tayong makapag-debug kung may typo sa column names
    echo json_encode([
        "success" => false, 
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
