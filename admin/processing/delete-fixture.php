<?php
header('Content-Type: application/json');
require_once '../../includes/db_connect.php';

function log_error($msg) {
    $logFile = __DIR__ . '/madina-fc/admin/error.txt';
    $entry = "[" . date('Y-m-d H:i:s') . "] $msg\n";
    error_log($entry, 3, $logFile);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Check if ID is from JSON body
$raw = file_get_contents("php://input");
$input = json_decode($raw, true);

// Get ID either from JSON or URL
$fixtureId = 0;
if (isset($input['id'])) {
    $fixtureId = intval($input['id']);
} elseif (isset($_GET['id'])) {
    $fixtureId = intval($_GET['id']);
}

if ($fixtureId <= 0) {
    log_error("Invalid fixture ID for deletion.");
    echo json_encode(['success' => false, 'message' => 'Invalid fixture ID.']);
    exit;
}

// Proceed with deletion
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

