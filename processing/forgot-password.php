<?php
require_once '../includes/db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Get inputs
$clubId = $data['clubId'] ?? '';
$newPassword = $data['newPassword'] ?? '';
$confirmPassword = $data['confirmPassword'] ?? '';

// Basic validation
if (!$clubId || !$newPassword || !$confirmPassword) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}

// Step 1: Check if club ID exists in players table
$stmt = $conn->prepare("SELECT id FROM players WHERE club_id = ?");
$stmt->bind_param("s", $clubId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Club ID not found. Please check with your coach for correct credentials."
    ]);
    exit;
}

$player = $result->fetch_assoc();
$playerId = $player['id'];

// Step 2: Check if club ID exists in player_tokens table
$stmtToken = $conn->prepare("SELECT id FROM player_tokens WHERE club_id = ?");
$stmtToken->bind_param("s", $clubId);
$stmtToken->execute();
$tokenResult = $stmtToken->get_result();

// Hash the new password
$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

if ($tokenResult->num_rows === 0) {
    // Insert new record with no token
    $insert = $conn->prepare("INSERT INTO player_tokens (club_id, password, token, expires_at, created_at) VALUES (?, ?, NULL, NULL, NOW())");
    $insert->bind_param("is", $clubId, $passwordHash); // <-- playerId is integer
    $insert->execute();
} else {
    // Update existing password
    $update = $conn->prepare("UPDATE player_tokens SET password = ?, token = NULL, expires_at = NULL WHERE club_id = ?");
    $update->bind_param("si", $passwordHash, $playerId);
    $update->execute();
}


echo json_encode([
    "success" => true,
    "message" => "Password set successfully. You can now log in."
]);
