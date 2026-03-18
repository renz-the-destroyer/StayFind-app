<?php
include 'config.php';
header("Content-Type: application/json");

// 1. Collect Text Data
$id        = $_POST['id'] ?? null;
$title     = $_POST['title'] ?? '';
$category  = $_POST['category'] ?? '';
$price     = $_POST['price'] ?? 0;
$location  = $_POST['location'] ?? '';
$rooms     = $_POST['rooms'] ?? 0;
$size      = $_POST['size'] ?? 0;
$amenities = $_POST['amenities'] ?? '';
$user_id   = $_POST['user_id'] ?? null;

// 2. Handle Image Uploads
$uploaded_paths = [];
$upload_dir = 'uploads/';

// Create folder if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = time() . "_" . $_FILES['images']['name'][$key];
        $target_file = $upload_dir . basename($file_name);
        
        if (move_uploaded_file($tmp_name, $target_file)) {
            $uploaded_paths[] = $target_file;
        }
    }
}

// Convert array of paths to a comma-separated string for the DB
$images_string = implode(',', $uploaded_paths);
$thumbnail = !empty($uploaded_paths) ? $uploaded_paths[0] : 'https://via.placeholder.com/400x200';

try {
    if (!empty($id)) {
        // UPDATE existing
        // If no new images were uploaded, keep the old ones (logic can be expanded here)
        $sql = "UPDATE listings SET title=?, category=?, price=?, location=?, rooms=?, size=?, amenities=? WHERE id=? AND user_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $category, $price, $location, $rooms, $size, $amenities, $id, $user_id]);
        
        // Only update images if new ones were provided
        if (!empty($images_string)) {
            $imgSql = "UPDATE listings SET images=?, thumbnail=? WHERE id=?";
            $pdo->prepare($imgSql)->execute([$images_string, $thumbnail, $id]);
        }
    } else {
        // INSERT new
        $sql = "INSERT INTO listings (title, category, price, location, rooms, size, amenities, user_id, images, thumbnail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $category, $price, $location, $rooms, $size, $amenities, $user_id, $images_string, $thumbnail]);
    }

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
