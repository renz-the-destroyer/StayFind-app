<?php
include 'config.php';
header("Content-Type: application/json"); // Always include this for JSON APIs

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password)) {
    try {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password matches
        if($user && password_verify($data->password, $user['password'])) {
            echo json_encode([
                "success" => true,
                "user" => [
                    "id" => $user['id'], 
                    "name" => $user['name'], // Fixed from display_name
                    "email" => $user['email'],
                    "role" => $user['role']   // Added role for frontend logic
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
}
?>