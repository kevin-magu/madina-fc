<?php
require_once '../../includes/db_connect.php';

header("Content-Type: application/json");

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Sanitize and extract
$fullName       = trim($data['fullName'] ?? '');
$email          = trim($data['email'] ?? '');
$phone          = trim($data['phone'] ?? '');
$nationalId     = trim($data['nationalId'] ?? '');
$location       = trim($data['location'] ?? '');
$password       = $data['password'] ?? '';
$confirmPassword= $data['confirmPassword'] ?? '';

// Validate
if (!$fullName || !$email || !$phone || !$nationalId || !$location || !$password || !$confirmPassword) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email address."]);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}

// Check if email or national ID already exists
$checkStmt = $conn->prepare("SELECT id FROM admins WHERE email = ? OR national_id = ?");
$checkStmt->bind_param("ss", $email, $nationalId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email or National ID already registered."]);
    exit;
}

// Hash password
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Insert admin
$insertStmt = $conn->prepare("
    INSERT INTO admins (full_name, email, phone, national_id, location, password_hash) 
    VALUES (?, ?, ?, ?, ?, ?)
");
$insertStmt->bind_param("ssssss", $fullName, $email, $phone, $nationalId, $location, $passwordHash);

if ($insertStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Admin registered successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed. Please try again."]);
}
