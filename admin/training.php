<?php
require_once '../includes/db_connect.php';

// Get the date from GET or use today's date
$currentDate = $_GET['date'] ?? date('Y-m-d');

// Fetch all players (initial load)
$players = [];
$stmt = $conn->prepare("SELECT id, name FROM players ORDER BY name ASC");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

// Get attendance for the selected date
$presentPlayerIds = [];
$attStmt = $conn->prepare("SELECT player_id FROM player_training WHERE date = ?");
$attStmt->bind_param("s", $currentDate);
$attStmt->execute();
$attResult = $attStmt->get_result();

while ($row = $attResult->fetch_assoc()) {
    $presentPlayerIds[] = (int)$row['player_id'];
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
            <div class="date-filter">
                <div class="form-group">
                    <label for="attendanceDate">Date</label>
                    <input type="date" id="attendanceDate" name="date" value="<?= htmlspecialchars($currentDate) ?>">
                </div>
                <button type="button" class="btn small-btn" id="loadDate">
                    <i class="fas fa-calendar-alt"></i> Load Attendance
                </button>
            </div>

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
                $playerId = (int)$player['id'];
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
    const currentDate = "<?= $currentDate ?>";
</script>
<script src="scripts/training.js"></script>
</body>
</html>