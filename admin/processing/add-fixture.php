<?php
header("Content-Type: application/json");

require_once '../../includes/db_connect.php';
$conn->set_charset("utf8mb4");

// === Logger ===
function log_error($message) {
    $logFile = __DIR__ . '/madina-fc/admin/processing/error.txt';
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[$timestamp] $message\n";
    error_log($entry, 3, $logFile);
}

// === Validate Required Fields ===
$required = ['matchDate', 'matchTime', 'opponent', 'location'];
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
        'message' => 'Missing required fields.',
        'fields' => $missing
    ]);
    exit;
}

// === Sanitize Inputs ===
$matchDate  = trim($_POST['matchDate']);
$matchTime  = trim($_POST['matchTime']);
$opponent   = trim($_POST['opponent']);
$location   = trim($_POST['location']); // 'Home' or 'Away'
$stadium    = trim($_POST['stadium'] ?? '');

// === Insert into DB ===
$stmt = $conn->prepare("INSERT INTO fixtures (match_date, match_time, opponent, location, stadium) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    log_error("DB prepare failed: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Server error (prepare failed).']);
    exit;
}

$stmt->bind_param("sssss", $matchDate, $matchTime, $opponent, $location, $stadium);

if (!$stmt->execute()) {
    log_error("DB execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Could not save fixture.']);
    exit;
}

$stmt->close();
$conn->close();

// ✅ Success
echo json_encode([
    'success' => true,
    'message' => 'Fixture added successfully.'
]);
