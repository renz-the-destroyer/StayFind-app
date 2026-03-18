<?php
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password)) {
    try {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 1. I-check kung may nahanap na user
        // 2. I-verify ang password (dapat naka-password_hash sa register.php)
        if($user && password_verify($data->password, $user['password'])) {
            echo json_encode([
                "success" => true,
                "user" => [
                    "id"    => $user['id'], 
                    // SIGURADUHIN: Kung 'full_name' ang nasa DB, gawin itong $user['full_name']
                    "name"  => isset($user['full_name']) ? $user['full_name'] : (isset($user['name']) ? $user['name'] : "User"),
                    "email" => $user['email'],
                    "role"  => $user['role']
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        }
    } catch (PDOException $e) {
        // Mas maganda kung ibabalik ang error message habang nag-te-test tayo
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
}
?>
