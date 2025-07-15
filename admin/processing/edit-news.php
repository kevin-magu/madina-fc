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
    $logFile = __DIR__ . '/error.txt';
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
$required = ['newsId', 'newsTitle', 'newsContent'];
$missing = [];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $missing[] = $field;
    }
}

if (!empty($missing)) {
    log_error("Missing fields: " . implode(', ', $missing));
    echo json_encode(['success' => false, 'message' => 'Missing fields.', 'fields' => $missing]);
    exit;
}

$newsId    = intval($_POST['newsId']);
$title     = trim($_POST['newsTitle']);
$content   = trim($_POST['newsContent']);
$oldImage  = $_POST['current_image'] ?? null;
$imagePath = $oldImage;

// === Process New Image Upload If Any ===
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        log_error("Invalid image extension: $ext");
        echo json_encode(['success' => false, 'message' => 'Unsupported image format.']);
        exit;
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/madina-fc/uploads/news/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        log_error("Failed to create directory: $uploadDir");
        echo json_encode(['success' => false, 'message' => 'Upload directory issue.']);
        exit;
    }

    $newName = 'news_' . time() . '_' . uniqid('', true) . '.' . $ext;
    $destination = $uploadDir . $newName;

    if (!compressImageIfLargerThan($tmpName, $destination)) {
        log_error("Failed to compress uploaded image: $originalName");
        echo json_encode(['success' => false, 'message' => 'Image processing failed.']);
        exit;
    }

    $imagePath = '/madina-fc/uploads/news/' . $newName;

    // Delete old image
    if (!empty($oldImage)) {
        $oldImageFullPath = $_SERVER['DOCUMENT_ROOT'] . $oldImage;
        if (file_exists($oldImageFullPath)) {
            unlink($oldImageFullPath);
        }
    }
}

// === Update DB ===
$stmt = $conn->prepare("UPDATE news_articles SET title = ?, content = ?, image_path = ? WHERE id = ?");
if (!$stmt) {
    log_error("Prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database prepare error.']);
    exit;
}

$stmt->bind_param("sssi", $title, $content, $imagePath, $newsId);

if (!$stmt->execute()) {
    log_error("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    $stmt->close();
    $conn->close();
    exit;
}

// ✅ SUCCESS
echo json_encode([
    'success' => true,
    'message' => 'News article updated successfully.',
    'image' => $imagePath
]);

$stmt->close();
$conn->close();
