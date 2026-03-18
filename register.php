<?php
// register.php
include 'config.php';
header("Content-Type: application/json");

// Kunin ang JSON data mula sa request
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password) && !empty($data->name)) {
    
    // 1. I-hash ang password para sa security
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
    
    // 2. Default Role: 'pending' (para sa dashboard selection)
    // Kung walang data->role na pinasa, automatic itong magiging 'pending'
    $role = !empty($data->role) ? $data->role : 'pending';
    
    // Default Contact: Empty string muna, ia-update sa dashboard
    $contact = !empty($data->contact) ? $data->contact : '';

    // 3. Ihanda ang SQL query (Siguraduhin na 'full_name' ang column name sa DB)
    $sql = "INSERT INTO users (full_name, email, password, role, contact) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        // 4. I-execute ang pag-save sa database
        $stmt->execute([$data->name, $data->email, $hashedPassword, $role, $contact]);
        
        $newId = $pdo->lastInsertId();

        // 5. Ibalik ang success response kasama ang user data
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
        // Ibalik ang error message kung halimbawa duplicate ang email
        echo json_encode([
            "success" => false, 
            "message" => "Registration failed: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
}
?>
