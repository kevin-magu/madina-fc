<?php
require_once '../includes/db_connect.php';

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
    $resultId = $conn->insert_id; // optional for linking later
} else {
    echo json_encode(["success" => false, "message" => "Failed to save result."]);
    exit;
}

// 2. Update player_stats for scorers
foreach ($scorers as $scorer) {
    $playerId = (int)$scorer['id'];

    // Count how many times this player appears = number of goals
    $goalCount = array_reduce($scorers, function ($count, $item) use ($playerId) {
        return $count + ($item['id'] == $playerId ? 1 : 0);
    }, 0);

    if ($goalCount === 0) continue;

    // Check if record exists
    $check = $conn->prepare("SELECT id, goals FROM player_stats WHERE player_id = ?");
    $check->bind_param("i", $playerId);
    $check->execute();
    $res = $check->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $newGoals = $row['goals'] + $goalCount;

        // Update
        $update = $conn->prepare("UPDATE player_stats SET goals = ? WHERE player_id = ?");
        $update->bind_param("ii", $newGoals, $playerId);
        $update->execute();
    } else {
        // Insert
        $insert = $conn->prepare("INSERT INTO player_stats (player_id, goals) VALUES (?, ?)");
        $insert->bind_param("ii", $playerId, $goalCount);
        $insert->execute();
    }
}

echo json_encode(["success" => true, "message" => "Result and player stats saved successfully."]);
