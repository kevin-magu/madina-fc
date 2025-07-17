<?php
require_once '../../includes/db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

$playerId = (int)($data['playerId'] ?? 0);
$status   = $data['status'] ?? '';
$date     = $data['date'] ?? date('Y-m-d');
$notes    = $data['notes'] ?? null;

if (!$playerId || !in_array($status, ['present', 'absent']) || !$date) {
    echo json_encode(["success" => false, "message" => "Invalid data provided."]);
    exit;
}

$check = $conn->prepare("SELECT id FROM player_training WHERE player_id = ? AND date = ?");
$check->bind_param("is", $playerId, $date);
$check->execute();
$res = $check->get_result();

if ($status === 'present') {
    if ($res && $res->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO player_training (player_id, date, status, notes, created_at) VALUES (?, ?, 'present', ?, NOW())");
        $insert->bind_param("iss", $playerId, $date, $notes);
        $insert->execute();
    } elseif ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $update = $conn->prepare("UPDATE player_training SET status = 'present', notes = ?, created_at = NOW() WHERE id = ?");
        $update->bind_param("si", $notes, $row['id']);
        $update->execute();
    }
} else {
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $delete = $conn->prepare("DELETE FROM player_training WHERE id = ?");
        $delete->bind_param("i", $row['id']);
        $delete->execute();
    }
}

echo json_encode(["success" => true, "message" => "Attendance updated"]);
