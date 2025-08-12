<?php
session_start();
require_once 'includes/db_connect.php';

// Check if user session exists
if (!isset($_SESSION['player_id']) || !isset($_SESSION['session_token'])) {
    header('Location: login.php');
    exit();
}

$playerId = $_SESSION['player_id'];
$sessionToken = $_SESSION['session_token'];

// Verify session from DB
$stmt = $conn->prepare("SELECT * FROM player_tokens WHERE id = ? AND token = ?");
$stmt->bind_param("is", $playerId, $sessionToken);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();

if (!$player) {
    // Session invalid in DB → force logout
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Fetch player stats
$statsStmt = $conn->prepare("SELECT 
    SUM(goals) as total_goals,
    SUM(assists) as total_assists,
    SUM(yellow_cards) as total_yellows,
    SUM(red_cards) as total_reds,
    SUM(minutes_played) as total_minutes
    FROM player_stats WHERE player_id = ?");
$statsStmt->bind_param("i", $playerId);
$statsStmt->execute();
$stats = $statsStmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($player['name']) ?> | Player Profile</title>
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php' ?>
    <div class="profile-container">
        <!-- Player Banner -->
        <div class="player-banner">
            <div class="profile-picture">
                <img src="<?= !empty($player['id_photo_path']) ?  htmlspecialchars($player['id_photo_path']) : 'assets/images/default-player.jpg' ?>" alt="<?= htmlspecialchars($player['name']) ?>">
                <div class="goals-badge"><?= $player['jersey_number'] ?? 0 ?></div>
            </div>
            <h1><?= htmlspecialchars($player['name']) ?></h1>
            <p class="position"><?= htmlspecialchars($player['position']) ?></p>
        </div>

        <!-- Stats Overview -->
        <div class="stats-overview">
            <div class="stat-card">
                <i class="fas fa-futbol"></i>
                <h3>Goals</h3>
                <p><?= $stats['total_goals'] ?? 0 ?></p>
            </div>
        </div>

        <div class='logout-button'>
            <form method="post" action="logout.php">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

    <?php include 'includes/header.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/profile.js"></script>
</body>
</html>
