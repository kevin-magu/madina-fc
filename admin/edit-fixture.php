<?php
require_once '../includes/db_connect.php';

// Get fixture ID
$fixtureId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($fixtureId <= 0) {
    die("Invalid fixture ID.");
}

// Fetch fixture details
$stmt = $conn->prepare("SELECT * FROM fixtures WHERE id = ?");
$stmt->bind_param("i", $fixtureId);
$stmt->execute();
$result = $stmt->get_result();
$fixture = $result->fetch_assoc();
if (!$fixture) {
    die("Fixture not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fixture | Madina FC Admin</title>
    <link rel="stylesheet" href="./styles/upload-news.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="content">
            <header class="content-header">
                <h1><i class="fas fa-calendar-alt"></i> Edit Fixture</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / <span>Edit Fixture</span>
                </div>
            </header>

            <div class="upload-card">
                <form id="fixtureForm" class="news-form" action="process-edit-fixture.php" method="POST">
                    <input type="hidden" id="fixtureId" name="fixtureId" value="<?php echo $fixture['id']; ?>">

                    <div class="form-group">
                        <label for="matchDate">Match Date</label>
                        <input type="date" id="matchDate" name="matchDate" value="<?php echo htmlspecialchars($fixture['match_date']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="matchTime">Match Time</label>
                        <input type="time" id="matchTime" name="matchTime" value="<?php echo htmlspecialchars($fixture['match_time']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="opponent">Opponent</label>
                        <input type="text" id="opponent" name="opponent" value="<?php echo htmlspecialchars($fixture['opponent']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Match Location</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="location" value="Home" <?php if ($fixture['location'] === 'Home') echo 'checked'; ?>>
                                <span class="radio-label">Home</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="location" value="Away" <?php if ($fixture['location'] === 'Away') echo 'checked'; ?>>
                                <span class="radio-label">Away</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stadium">Stadium</label>
                        <input type="text" id="stadium" name="stadium" value="<?php echo htmlspecialchars($fixture['stadium']); ?>">
                    </div>

                    <div class="form-actions">
                        <a href="fixtures.php" class="cancel-btn">Cancel</a>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src='scripts/edit-fixture.js'></script>
</body>
</html>
