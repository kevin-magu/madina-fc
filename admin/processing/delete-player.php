<?php
header("Content-Type: application/json");
require_once '../../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$playerId = $data['id'] ?? null;

if (!$playerId) {
    echo json_encode(['success' => false, 'message' => 'Player ID is required']);
    exit;
}

// Fetch the image path
$stmt = $conn->prepare("SELECT id_photo_path FROM players WHERE id = ?");
$stmt->bind_param("i", $playerId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Player not found']);
    exit;
}

$row = $result->fetch_assoc();
$imagePath = $_SERVER['DOCUMENT_ROOT'] . $row['id_photo_path'];

// Delete from DB
$deleteStmt = $conn->prepare("DELETE FROM players WHERE id = ?");
$deleteStmt->bind_param("i", $playerId);

if ($deleteStmt->execute()) {
    // Remove image file
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    echo json_encode(['success' => true, 'message' => 'Player deleted']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete player']);
}

$stmt->close();
$deleteStmt->close();
$conn->close();
