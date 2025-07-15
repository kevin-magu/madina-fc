<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Adjust based on your security preferences

require_once '../../includes/db_connect.php';
$conn->set_charset("utf8mb4");

// === Centralized Logger ===
function log_error($message) {
    $logFile = __DIR__ . '/madina-fc/admin/error.txt';
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[$timestamp] $message\n";
    error_log($entry, 3, $logFile);
}

// === Validate Request Method ===
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// === Parse URL or JSON input for ID ===
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid article ID.']);
    exit;
}

// === Get the image path before deletion ===
$stmt = $conn->prepare("SELECT image_path FROM news_articles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($imagePath);
$stmt->fetch();
$stmt->close();

// === Delete article from DB ===
$deleteStmt = $conn->prepare("DELETE FROM news_articles WHERE id = ?");
$deleteStmt->bind_param("i", $id);

if (!$deleteStmt->execute()) {
    log_error("Failed to delete article ID $id: " . $deleteStmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to delete article.']);
    exit;
}
$deleteStmt->close();

// === Delete image file if it exists ===
if (!empty($imagePath)) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
    if (file_exists($fullPath)) {
        if (!unlink($fullPath)) {
            log_error("Failed to delete image file: $fullPath");
        }
    }
}

// ✅ Success
echo json_encode(['success' => true, 'message' => 'Article deleted successfully.']);
$conn->close();
