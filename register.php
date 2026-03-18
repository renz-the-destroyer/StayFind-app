<?php
// register.php
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password) && !empty($data->name)) {
    
    // Hashing the password (SECURITY: Correct!)
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
    
    $role = !empty($data->role) ? $data->role : 'tenant';
    $contact = !empty($data->contact) ? $data->contact : '';

    // INAYOS: Ginamit ang 'full_name' para match sa database mo kanina
    $sql = "INSERT INTO users (full_name, email, password, role, contact) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$data->name, $data->email, $hashedPassword, $role, $contact]);
        
        $newId = $pdo->lastInsertId();

        echo json_encode([
            "success" => true, 
            "message" => "Registration successful!",
            "user" => [
                "id" => $newId, 
                "name" => $data->name, 
                "email" => $data->email,
                "role" => $role
            ]
        ]);
    } catch(Exception $e) {
        echo json_encode([
            "success" => false, 
            "message" => "Registration failed: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
}
?>
