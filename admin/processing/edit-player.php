<?php
header("Content-Type: application/json");
require_once '../../includes/db_connect.php';
$conn->set_charset("utf8mb4");

$required = ['playerId', 'playerName', 'position', 'jerseyNumber', 'nationality', 'dob', 'height', 'weight'];
$missing = [];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $missing[] = $field;
    }
}

if (!empty($missing)) {
    echo json_encode(['success' => false, 'message' => 'Missing fields', 'fields' => $missing]);
    exit;
}

$playerId     = intval($_POST['playerId']);
$name         = trim($_POST['playerName']);
$position     = trim($_POST['position']);
$jerseyNumber = trim($_POST['jerseyNumber']);
$nationality  = trim($_POST['nationality']);
$dob          = trim($_POST['dob']);
$height       = trim($_POST['height']);
$weight       = trim($_POST['weight']);
$updatedAt    = date('Y-m-d H:i:s');

// Get current image path
$stmt = $conn->prepare("SELECT id_photo_path FROM players WHERE id = ?");
$stmt->bind_param("i", $playerId);
$stmt->execute();
$stmt->bind_result($currentPhoto);
$stmt->fetch();
$stmt->close();

// Handle optional image upload
$idPhotoPath = $currentPhoto;
if (isset($_FILES['playerPhoto']) && $_FILES['playerPhoto']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['playerPhoto']['tmp_name'];
    $originalName = basename($_FILES['playerPhoto']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Unsupported image format']);
        exit;
    }

    // Delete old photo if exists
    if (!empty($currentPhoto) && file_exists($_SERVER['DOCUMENT_ROOT'] . $currentPhoto)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . $currentPhoto);
    }

    // Upload new photo
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/madina-fc/uploads/players/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $newName = 'player_' . time() . '_' . uniqid() . '.' . $ext;
    $destination = $uploadDir . $newName;

    if (!move_uploaded_file($tmpName, $destination)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        exit;
    }

    $idPhotoPath = '/madina-fc/uploads/players/' . $newName;
}

// Update player
$stmt = $conn->prepare("UPDATE players SET name = ?, id_photo_path = ?, position = ?, jersey_number = ?, nationality = ?, date_of_birth = ?, height = ?, weight_kg = ?, updated_at = ? WHERE id = ?");
$stmt->bind_param("sssssssssi", $name, $idPhotoPath, $position, $jerseyNumber, $nationality, $dob, $height, $weight, $updatedAt, $playerId);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to update player']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Player updated']);
$stmt->close();
$conn->close();
