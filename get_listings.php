<?php
include 'config.php';
header("Content-Type: application/json");

try {
    // Join with users table to get the landlord's contact info
    $sql = "SELECT l.*, u.name as landlordName, u.contact 
            FROM listings l 
            JOIN users u ON l.user_id = u.id 
            ORDER BY l.id DESC";
    $stmt = $pdo->query($sql);
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($listings);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>