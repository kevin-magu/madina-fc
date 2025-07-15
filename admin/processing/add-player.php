<?php
header("Content-Type: application/json");
require_once '../../includes/db_connect.php';
$conn->set_charset("utf8mb4");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Fixed logger path
function log_error($message) {
    $logFile = __DIR__ . '/error.txt';
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message\n", 3, $logFile);
}

/**
 * Compresses an image if it's larger than specified size
 * @param string $tmpPath Temporary file path
 * @param string $destinationPath Destination path
 * @param int $maxSizeKB Maximum size in KB before compression
 * @return bool True on success, false on failure
 */
function compressImageIfLargerThan($tmpPath, $destinationPath, $maxSizeKB = 800) {
    // Check if GD extension is installed
    if (!extension_loaded('gd')) {
        log_error("GD extension not loaded");
        return false;
    }

    $info = getimagesize($tmpPath);
    if ($info === false) {
        log_error("Invalid image file");
        return false;
    }

    $mime = $info['mime'];
    $fileSizeKB = filesize($tmpPath) / 1024;

    // Create image resource based on mime type
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($tmpPath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($tmpPath);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($tmpPath);
            break;
        default:
            log_error("Unsupported image type: $mime");
            return false;
    }

    if ($image === false) {
        log_error("Failed to create image resource");
        return false;
    }

    // Set quality based on file size
    $quality = ($fileSizeKB > $maxSizeKB) ? 75 : 90;
    $success = false;

    // Save image with appropriate compression
    switch ($mime) {
        case 'image/jpeg':
            $success = imagejpeg($image, $destinationPath, $quality);
            break;
        case 'image/png':
            $quality = 9 - (int)($quality / 10); // Convert to PNG compression level (0-9)
            $success = imagepng($image, $destinationPath, $quality);
            break;
        case 'image/webp':
            $success = imagewebp($image, $destinationPath, $quality);
            break;
    }

    imagedestroy($image);
    return $success;
}

// === Get Input Data ===
$data = $_POST;
$files = $_FILES;

// If POST is empty, try JSON
if (empty($data)) {
    $jsonInput = file_get_contents("php://input");
    if (!empty($jsonInput)) {
        $data = json_decode($jsonInput, true);
        // For JSON requests, files won't be available
        $files = [];
    }
}

// Debug what we received
file_put_contents('debug_data.log', print_r($data, true));
file_put_contents('debug_files.log', print_r($files, true));

// === Validate Required Fields ===
$required = ['playerName', 'position', 'jerseyNumber', 'nationality', 'dob', 'height', 'weight'];
$missing = [];

foreach ($required as $field) {
    if (empty($data[$field])) {
        $missing[] = $field;
    }
}

// Handle file field
if (empty($files['playerPhoto']) || $files['playerPhoto']['error'] !== UPLOAD_ERR_OK) {
    $missing[] = 'playerPhoto';
}

if (!empty($missing)) {
    log_error("Missing fields: " . implode(', ', $missing));
    echo json_encode([
        'success' => false,
        'status' => '400',
        'message' => 'Missing required fields',
        'fields' => $missing,
        'received_data' => $data
    ]);
    exit;
}

// === Sanitize Inputs ===
$name = trim($data['playerName']);
$position = trim($data['position']);
$jerseyNumber = trim($data['jerseyNumber'] ?? '');
$nationality = trim($data['nationality'] ?? '');
$dob = trim($data['dob'] ?? null);
$height = trim($data['height'] ?? null);
$weight = trim($data['weight'] ?? null);
$joinedAt = date('Y-m-d');

// === Handle Image Upload ===
$idPhotoPath = null;
if (!empty($files['playerPhoto']) && $files['playerPhoto']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $files['playerPhoto']['tmp_name'];
    $originalName = basename($files['playerPhoto']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        log_error("Invalid image extension: $ext");
        echo json_encode(['success' => false, 'message' => 'Unsupported image format']);
        exit;
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/madina-fc/uploads/players/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            log_error("Failed to create upload directory");
            echo json_encode(['success' => false, 'message' => 'Upload directory error']);
            exit;
        }
    }

    $newName = 'player_' . time() . '_' . uniqid() . '.' . $ext;
    $destination = $uploadDir . $newName;

    if (!compressImageIfLargerThan($tmpName, $destination)) {
        log_error("Image compression failed: $originalName");
        echo json_encode(['success' => false, 'message' => 'Image processing failed']);
        exit;
    }

    $idPhotoPath = '/madina-fc/uploads/players/' . $newName;
}

// === Insert Player ===
$stmt = $conn->prepare("INSERT INTO players (name, id_photo_path, position, jersey_number, nationality, date_of_birth, height, weight_kg, joined_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    log_error("DB prepare error: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'DB error']);
    exit;
}

// Bind parameters
$heightFloat = !empty($height) ? (float)$height : null;
$weightFloat = !empty($weight) ? (float)$weight : null;

$stmt->bind_param(
    "ssssssdds",
    $name,
    $idPhotoPath,
    $position,
    $jerseyNumber,
    $nationality,
    $dob,
    $heightFloat,
    $weightFloat,
    $joinedAt
);

if (!$stmt->execute()) {
    log_error("DB execution error: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to save player']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Player added successfully',
    'player_id' => $conn->insert_id
]);

$stmt->close();
$conn->close();
?>