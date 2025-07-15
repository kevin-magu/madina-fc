<?php
header('Content-Type: application/json');
require_once '../../includes/db_connect.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

function log_error($message) {
    $logFile = __DIR__ . '/error.txt';
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message\n", 3, $logFile);
}

// Validate and sanitize inputs
$required = ['fixtureId', 'matchDate', 'matchTime', 'opponent', 'location', 'stadium'];
$missing = [];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $missing[] = $field;
    }
}

if (!empty($missing)) {
    log_error("Missing fields: " . implode(', ', $missing));
    echo json_encode([
        'success' => false,
        'message' => 'Missing fields.',
        'missing' => $missing
    ]);
    exit;
}

$fixtureId = (int)$_POST['fixtureId'];
$matchDate = trim($_POST['matchDate']);
$matchTime = trim($_POST['matchTime']);
$opponent  = trim($_POST['opponent']);
$location  = trim($_POST['location']);
$stadium   = trim($_POST['stadium']);

// Update the fixture
$stmt = $conn->prepare("UPDATE fixtures SET match_date = ?, match_time = ?, opponent = ?, location = ?, stadium = ? WHERE id = ?");
if (!$stmt) {
    log_error("Prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
    exit;
}

$stmt->bind_param('sssssi', $matchDate, $matchTime, $opponent, $location, $stadium, $fixtureId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Fixture updated successfully.']);
} else {
    log_error("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to update fixture.']);
}

$stmt->close();
$conn->close();
