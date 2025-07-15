<?php
require_once '../includes/db_connect.php';

$currentDate = $_GET['date'] ?? date('Y-m-d');

// Fetch all players
$players = [];
$playerQuery = "SELECT id, name FROM players ORDER BY name ASC";
$playerResult = mysqli_query($conn, $playerQuery);
while ($row = mysqli_fetch_assoc($playerResult)) {
    $players[] = $row;
}

// Get list of present players for selected date
$presentPlayerIds = [];
$checkQuery = "SELECT player_id FROM player_training WHERE training_date = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $presentPlayerIds[] = (int) $row['player_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Training Attendance | Madina FC Admin</title>
    <link rel="stylesheet" href="./styles/training.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php' ?>

    <main class="content">
        <header class="content-header">
            <h1><i class="fas fa-dumbbell"></i> Player Training Attendance</h1>
            <div class="breadcrumb">
                <a href="index.php">Dashboard</a> / <span>Training Attendance</span>
            </div>
        </header>

        <div class="attendance-controls">
            <form method="get" class="date-filter">
                <div class="form-group">
                    <label for="attendanceDate">Date</label>
                    <input type="date" id="attendanceDate" name="date" value="<?= htmlspecialchars($currentDate) ?>" required>
                </div>
                <button type="submit" class="btn small-btn">
                    <i class="fas fa-calendar-alt"></i> Load
                </button>
            </form>

            <div class="bulk-actions">
                <button id="markAllPresent" class="btn success-btn">
                    <i class="fas fa-check-circle"></i> Mark All Present
                </button>
                <button id="markAllAbsent" class="btn danger-btn">
                    <i class="fas fa-times-circle"></i> Mark All Absent
                </button>
            </div>
        </div>

        <div class="search-box">
            <input type="text" id="playerSearch" placeholder="Search players...">
            <i class="fas fa-search"></i>
        </div>

        <div class="attendance-grid" id="attendanceContainer">
            <?php foreach ($players as $player): 
                $playerId = $player['id'];
                $isPresent = in_array($playerId, $presentPlayerIds);
            ?>
            <div class="attendance-card" data-player-id="<?= $playerId ?>">
                <div class="player-info">
                    <span class="player-name"><?= htmlspecialchars($player['name']) ?></span>
                </div>
                <div class="attendance-actions">
                    <button class="attendance-btn present-btn <?= $isPresent ? 'active' : '' ?>" data-status="present">
                        <i class="fas fa-check"></i> Present
                    </button>
                    <button class="attendance-btn absent-btn <?= !$isPresent ? 'active' : '' ?>" data-status="absent">
                        <i class="fas fa-times"></i> Absent
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="form-actions">
            <button id="saveAttendance" class="btn primary-btn">
                <i class="fas fa-save"></i> Save Attendance
            </button>
        </div>
    </main>
</div>

<script>
    const selectedDate = "<?= $currentDate ?>";
</script>
<script src="scripts/training.js"></script>
</body>
</html>
