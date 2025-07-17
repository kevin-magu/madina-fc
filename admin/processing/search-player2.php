<?php
require_once '../../includes/db_connect.php';

header('Content-Type: text/html; charset=utf-8');

// Get and sanitize inputs
$search = trim($_POST['query'] ?? '');
$date = $_POST['date'] ?? date('Y-m-d');

// Validate date format
if (!DateTime::createFromFormat('Y-m-d', $date)) {
    $date = date('Y-m-d');
}

// Fetch matching players
$players = [];
$like = '%' . $conn->real_escape_string($search) . '%';

$playerQuery = "SELECT id, name FROM players WHERE name LIKE ? ORDER BY name ASC";
$stmt = $conn->prepare($playerQuery);
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

// Fetch attendance for the date
$attendance = [];
$attQuery = "SELECT player_id FROM player_training WHERE date = ?";
$attStmt = $conn->prepare($attQuery);
$attStmt->bind_param("s", $date);
$attStmt->execute();
$attResult = $attStmt->get_result();

while ($row = $attResult->fetch_assoc()) {
    $attendance[] = (int)$row['player_id'];
}

// Build HTML output
foreach ($players as $player) {
    $playerId = (int)$player['id'];
    $isPresent = in_array($playerId, $attendance);
    
    echo '<div class="attendance-card" data-player-id="' . $playerId . '">
        <div class="player-info">
            <span class="player-name">' . htmlspecialchars($player['name']) . '</span>
        </div>
        <div class="attendance-actions">
            <button class="attendance-btn present-btn ' . ($isPresent ? 'active' : '') . '" data-status="present">
                <i class="fas fa-check"></i> Present
            </button>
            <button class="attendance-btn absent-btn ' . (!$isPresent ? 'active' : '') . '" data-status="absent">
                <i class="fas fa-times"></i> Absent
            </button>
        </div>
    </div>';
}

// Close connections
$stmt->close();
$attStmt->close();
$conn->close();
?>