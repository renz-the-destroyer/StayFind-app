<?php
// register.php
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password) && !empty($data->name)) {
    
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
    
    $role = !empty($data->role) ? $data->role : 'tenant';
    $contact = !empty($data->contact) ? $data->contact : '';

    // Siniguro nating match ito sa columns ng Clever Cloud database mo
    $sql = "INSERT INTO users (name, email, password, role, contact) VALUES (?, ?, ?, ?, ?)";
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
        // Mas malinaw na error message para alam natin kung bakit nag-fail
        echo json_encode([
            "success" => false, 
            "message" => "Registration failed: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
}
?>
