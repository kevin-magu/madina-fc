<?php
require_once '../../includes/db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get raw JSON input
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!isset($data['player_id'], $data['status'], $data['date'])) {
    echo json_encode(["success" => false, "message" => "Missing required data."]);
    exit;
}

$playerId = (int) $data['player_id'];
$status = $data['status']; // 'present' or 'absent'
$trainingDate = $data['date'];

// Sanity check
if (!in_array($status, ['present', 'absent'])) {
    echo json_encode(["success" => false, "message" => "Invalid status provided."]);
    exit;
}

// If status is 'present', insert if not exists
if ($status === 'present') {
    $checkStmt = $conn->prepare("SELECT id FROM player_training WHERE player_id = ? AND training_date = ?");
    $checkStmt->bind_param("is", $playerId, $trainingDate);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        $insertStmt = $conn->prepare("INSERT INTO player_training (player_id, training_date, training_count, created_at) VALUES (?, ?, 1, NOW())");
        $insertStmt->bind_param("is", $playerId, $trainingDate);
        if ($insertStmt->execute()) {
            echo json_encode(["success" => true, "message" => "Player marked as present."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to mark present."]);
        }
    } else {
        echo json_encode(["success" => true, "message" => "Player already marked as present."]);
    }
} else {
    // status is 'absent' → delete if exists
    $deleteStmt = $conn->prepare("DELETE FROM player_training WHERE player_id = ? AND training_date = ?");
    $deleteStmt->bind_param("is", $playerId, $trainingDate);
    if ($deleteStmt->execute()) {
        echo json_encode(["success" => true, "message" => "Player marked as absent (removed)."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to mark absent."]);
    }
}
?>
