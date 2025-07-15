<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: same-origin");

require_once '../../includes/db_connect.php';
$conn->set_charset("utf8mb4");

ini_set('display_errors', 0);
ini_set('log_errors', 0);
error_reporting(E_ALL);

// === Centralized Logger ===
function log_error($message) {
    $logFile = __DIR__ . './error.txt';
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[$timestamp] $message\n";
    error_log($entry, 3, $logFile);
}

// === Compress Image ===
function compressImageIfLargerThan($tmpPath, $destinationPath, $maxSizeKB = 800) {
    $info = getimagesize($tmpPath);
    $mime = $info['mime'];
    $fileSizeKB = filesize($tmpPath) / 1024;

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($tmpPath); break;
        case 'image/png':
            $image = imagecreatefrompng($tmpPath); break;
        case 'image/webp':
            $image = imagecreatefromwebp($tmpPath); break;
        default:
            log_error("Unsupported image type: $mime");
            return false;
    }

    $quality = ($fileSizeKB > $maxSizeKB) ? 75 : 90;

    $success = false;
    if ($mime === 'image/jpeg') {
        $success = imagejpeg($image, $destinationPath, $quality);
    } elseif ($mime === 'image/png') {
        $compression = ($fileSizeKB > $maxSizeKB) ? 6 : 3;
        $success = imagepng($image, $destinationPath, $compression);
    } elseif ($mime === 'image/webp') {
        $success = imagewebp($image, $destinationPath, $quality);
    }

    imagedestroy($image);
    return $success;
}

// === Validate Required Fields ===
$missing = [];

if (!isset($_POST['newsTitle']) || trim($_POST['newsTitle']) === '') $missing[] = 'newsTitle';
if (!isset($_POST['newsContent']) || trim($_POST['newsContent']) === '') $missing[] = 'newsContent';

if (!empty($missing)) {
    log_error("Missing fields: " . implode(', ', $missing));
    echo json_encode(['success' => false, 'message' => 'Missing fields.', 'fields' => $missing]);
    exit;
}

// === Sanitize Inputs ===
$title = trim($_POST['newsTitle']);
$content = trim($_POST['newsContent']);
$imagePath = null;

// === Handle Image Upload ===
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        log_error("Invalid extension: $ext");
        echo json_encode(['success' => false, 'message' => 'Unsupported image format.']);
        exit;
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/madina-fc/uploads/news/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        log_error("Failed to create dir: $uploadDir");
        echo json_encode(['success' => false, 'message' => 'Could not create upload directory.']);
        exit;
    }

    if (!is_writable($uploadDir)) {
        log_error("Dir not writable: $uploadDir");
        echo json_encode(['success' => false, 'message' => 'Upload directory is not writable.']);
        exit;
    }

    $newName = 'news_' . time() . '_' . uniqid('', true) . '.' . $ext;
    $destination = $uploadDir . $newName;

    if (!compressImageIfLargerThan($tmpName, $destination)) {
        log_error("Image compression failed: $originalName");
        echo json_encode(['success' => false, 'message' => 'Failed to process uploaded image.']);
        exit;
    }

    $imagePath = '/madina-fc/uploads/news/' . $newName;
}

// === Insert to DB ===
$stmt = $conn->prepare("INSERT INTO news_articles (title, content, image_path) VALUES (?, ?, ?)");
if (!$stmt) {
    log_error("DB prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error.', 'error' => 'prepare failed']);
    exit;
}

$stmt->bind_param("sss", $title, $content, $imagePath);
if (!$stmt->execute()) {
    log_error("DB execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to save article.']);
    exit;
}

// ✅ SUCCESS
echo json_encode([
    'success' => true,
    'message' => 'News uploaded successfully.',
    'image' => $imagePath
]);

$stmt->close();
$conn->close();
