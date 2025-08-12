<?php
require_once '../includes/db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Get input
$clubId = $data['clubId'] ?? '';
$password = $data['password'] ?? '';

if (!$clubId || !$password) {
    echo json_encode(["success" => false, "message" => "Club ID and password required."]);
    exit;
}

// Step 1: Check if club ID exists in players table
$stmt = $conn->prepare("SELECT id, name FROM players WHERE club_id = ?");
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

// Step 2: Check if club ID exists in player_tokens table
$stmtToken = $conn->prepare("SELECT id, club_id, password, token, expires_at FROM player_tokens WHERE club_id = ?");
$stmtToken->bind_param("s", $clubId);
$stmtToken->execute();
$tokenResult = $stmtToken->get_result();

if ($tokenResult->num_rows === 0) {
    // Player never logged in or set up password
    echo json_encode([
        "success" => false,
        "redirect" => "forgot-password.php",
        "message" => "You need to set up your password before logging in. Click forgot password below."
    ]);
    exit;
}

// Step 3: Validate password from player_tokens table
$playerToken = $tokenResult->fetch_assoc();

if (!password_verify($password, $playerToken['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid password."
    ]);
    exit;
}

// Step 4: Generate new token & expiry
$newToken = bin2hex(random_bytes(32));
$expiresAt = time() + (2 * 24 * 60 * 60); // 2 days
$expiresAtFormatted = date('Y-m-d H:i:s', $expiresAt);

// Update token in DB
$update = $conn->prepare("UPDATE player_tokens SET token = ?, expires_at = ? WHERE id = ?");
$update->bind_param("ssi", $newToken, $expiresAtFormatted, $playerToken['id']);
$update->execute();

// Optionally set secure cookie
setcookie('player_token', $newToken, [
    'expires' => $expiresAt,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Success response
echo json_encode([
    "success" => true,
    "message" => "Login successful.",
    "player" => [
        "club_id" => $playerToken['club_id']
    ],
    "token" => $newToken,
    "expires" => $expiresAtFormatted
]);
