<?php
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

// Check if required fields are present
if(!empty($data->email) && !empty($data->password) && !empty($data->name)) {
    
    // Hash the password for security
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
    
    // Set default role if not provided
    $role = !empty($data->role) ? $data->role : 'tenant';
    $contact = !empty($data->contact) ? $data->contact : '';

    // Notice we don't insert 'id'—MySQL handles that automatically
    $sql = "INSERT INTO users (name, email, password, role, contact) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$data->name, $data->email, $hashedPassword, $role, $contact]);
        
        // Get the ID of the user we just created
        $newId = $pdo->lastInsertId();

        echo json_encode([
            "success" => true, 
            "user" => [
                "id" => $newId, 
                "name" => $data->name, 
                "email" => $data->email,
                "role" => $role
            ]
        ]);
    } catch(Exception $e) {
        // Most common error here is a duplicate email
        echo json_encode(["success" => false, "message" => "Registration failed. Email might already be in use."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
}
?>