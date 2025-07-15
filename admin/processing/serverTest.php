<?php
header("Content-Type: application/json");
//header("Access-Control-Allow-Origin: same-origin");

// === Logger ===
function log_error($message) {
    $logFile = __DIR__ . './error.txt';
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message\n", 3, $logFile);
}


// === Validate Required Fields ===
$required = ['playerName', 'position','jerseyNumber','nationality','dob','height','weight','playerPhoto'];
$missing = [];


foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $missing[] = $field;
    }
}

if (!empty($missing)) {
    log_error("Missing fields: " . implode(', ', $missing));
    echo json_encode(['success' => false, 'status'=>'500', 'message' => 'Missing required fields', 'fields' => $missing]);
    exit;
}

?>