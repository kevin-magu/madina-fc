<?php
require_once '../../includes/db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Get input
$nationalId = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$nationalId || !$password) {
    echo json_encode(["success" => false, "message" => "Username and password required."]);
    exit;
}

// Fetch admin by national_id
$stmt = $conn->prepare("SELECT id, full_name, password_hash FROM admins WHERE national_id = ?");
$stmt->bind_param("s", $nationalId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid username or password."]);
    exit;
}

$admin = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $admin['password_hash'])) {
    echo json_encode(["success" => false, "message" => "Invalid username or password."]);
    exit;
}

// Generate token
$token = bin2hex(random_bytes(32));
$expiresAt = time() + (2 * 24 * 60 * 60); // 2 days in seconds
$expiresAtFormatted = date('Y-m-d H:i:s', $expiresAt);

// Save token into admin_tokens table
$insert = $conn->prepare("INSERT INTO admin_tokens (admin_id, token, expires_at, created_at) VALUES (?, ?, ?, NOW())");
$insert->bind_param("iss", $admin['id'], $token, $expiresAtFormatted);
$insert->execute();

// Set secure HTTP-only cookie
setcookie('admin_token', $token, [
    'expires' => $expiresAt,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Return success response
echo json_encode([
    "success" => true,
    "message" => "Login successful.",
    "admin" => [
        "id" => $admin['id'],
        "name" => $admin['full_name']
    ],
    "token" => $token, // Still returning for clients that need it
    "expires" => $expiresAtFormatted
]);