<?php
header('Content-Type: application/json');
require_once '../../includes/db_connect.php';

function log_error($msg) {
    $logFile = __DIR__ . '/madina-fc/admin/error.txt';
    $entry = "[" . date('Y-m-d H:i:s') . "] $msg\n";
    error_log($entry, 3, $logFile);
}

// Only allow DELETE method
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Parse URL to get the fixture ID
$fixtureId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($fixtureId <= 0) {
    log_error("Invalid fixture ID for deletion.");
    echo json_encode(['success' => false, 'message' => 'Invalid fixture ID.']);
    exit;
}

// Perform deletion
$stmt = $conn->prepare("DELETE FROM fixtures WHERE id = ?");
if (!$stmt) {
    log_error("Prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
    exit;
}

$stmt->bind_param('i', $fixtureId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Fixture deleted successfully.']);
} else {
    log_error("Execution failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to delete fixture.']);
}

$stmt->close();
$conn->close();
