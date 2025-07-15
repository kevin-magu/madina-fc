<?php
require_once '../../includes/db_connect.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Sanitize input
$matchDate         = $data['matchDate'] ?? '';
$matchTime         = $data['matchTime'] ?? '';
$opponent          = $data['opponent'] ?? '';
$location          = $data['location'] ?? '';
$stadium           = $data['stadium'] ?? '';
$referee           = $data['referee'] ?? '';
$homeScore         = (int)($data['homeScore'] ?? 0);
$homeYellowCards   = (int)($data['homeYellowCards'] ?? 0);
$homeRedCards      = (int)($data['homeRedCards'] ?? 0);
$awayScore         = (int)($data['awayScore'] ?? 0);
$awayYellowCards   = (int)($data['awayYellowCards'] ?? 0);
$awayRedCards      = (int)($data['awayRedCards'] ?? 0);
$matchReport       = $data['matchReport'] ?? '';
$scorers           = $data['scorers'] ?? [];

// 1. Insert into results table
$stmt = $conn->prepare("INSERT INTO results (match_date, match_time, opponent, location, stadium, referee, home_score, home_yellow_cards, home_red_cards, away_score, away_yellow_cards, away_red_cards, match_report) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "ssssssiiiiiis",
    $matchDate,
    $matchTime,
    $opponent,
    $location,
    $stadium,
    $referee,
    $homeScore,
    $homeYellowCards,
    $homeRedCards,
    $awayScore,
    $awayYellowCards,
    $awayRedCards,
    $matchReport
);

if ($stmt->execute()) {
    $resultId = $conn->insert_id;
} else {
    echo json_encode(["success" => false, "message" => "Failed to save result."]);
    exit;
}

// 2. Update player_stats for scorers (linked by result_id)
$scorerGoals = [];
foreach ($scorers as $scorer) {
    $id = (int)$scorer['id'];
    if (!isset($scorerGoals[$id])) $scorerGoals[$id] = 0;
    $scorerGoals[$id]++;
}

foreach ($scorerGoals as $playerId => $goalCount) {
    if ($goalCount === 0) continue;

    // Check if player has a stat for this result already
    $check = $conn->prepare("SELECT id, goals FROM player_stats WHERE player_id = ? AND result_id = ?");
    $check->bind_param("ii", $playerId, $resultId);
    $check->execute();
    $res = $check->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $newGoals = $row['goals'] + $goalCount;

        // Update
        $update = $conn->prepare("UPDATE player_stats SET goals = ? WHERE id = ?");
        $update->bind_param("ii", $newGoals, $row['id']);
        $update->execute();
    } else {
        // Insert new stat linked to this result
        $insert = $conn->prepare("INSERT INTO player_stats (player_id, goals, result_id) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $playerId, $goalCount, $resultId);
        $insert->execute();
    }
}

echo json_encode(["success" => true, "message" => "Result and player stats saved successfully."]);
