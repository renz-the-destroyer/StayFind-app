<?php
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->userId) && !empty($data->role)) {
    try {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->role, $data->userId]);

        echo json_encode(["success" => true]);
    } catch(Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>
