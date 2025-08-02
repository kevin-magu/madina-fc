<?php
require_once '../includes/db_connect.php';
$fixtureId = $_GET['id'] ?? null;
if (!$fixtureId) {
    die("Fixture ID is required.");
}

// Fetch fixture details
$stmt = $conn->prepare("SELECT * FROM fixtures WHERE id = ?");
$stmt->bind_param("i", $fixtureId);
$stmt->execute();
$result = $stmt->get_result();
$fixture = $result->fetch_assoc();
$stmt->close();

if (!$fixture) {
    die("Fixture not found.");
}

// Fetch players for scorer selection
$playerResult = $conn->query("SELECT id, name FROM players ORDER BY name ASC");
$players = [];
while ($row = $playerResult->fetch_assoc()) {
    $players[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Fixture | Madina FC Admin</title>
    <link rel="stylesheet" href="./styles/add-result.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <?php include 'includes/sidebar.php' ?>
    <main class="content">
        <header class="content-header">
            <h1><i class="fas fa-clipboard-check"></i> Update Past Match</h1>
            <div class="breadcrumb">
                <a href="index.php">Dashboard</a> / <span>Update Fixture</span>
            </div>
        </header>

        <div class="upload-card">
            <form action="processing/process-update-fixture.php" method="POST" id="resultForm">
                <input type="hidden" name="fixtureId" value="<?= $fixtureId ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="matchDate">Match Date</label>
                        <input type="date" id="matchDate" name="matchDate" value="<?= $fixture['match_date'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="matchTime">Match Time</label>
                        <input type="time" id="matchTime" name="matchTime" value="<?= $fixture['match_time'] ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="opponent">Opponent Team</label>
                        <input type="text" id="opponent" name="opponent" value="<?= htmlspecialchars($fixture['opponent']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Match Location</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="location" value="Home" <?= $fixture['location'] == 'Home' ? 'checked' : '' ?>>
                                <span class="radio-label">Home</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="location" value="Away" <?= $fixture['location'] == 'Away' ? 'checked' : '' ?>>
                                <span class="radio-label">Away</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="stadium">Stadium</label>
                        <input type="text" id="stadium" name="stadium" value="<?= htmlspecialchars($fixture['stadium']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="referee">Referee</label>
                        <input type="text" id="referee" name="referee" value="<?= htmlspecialchars($fixture['referee'] ?? '') ?>">
                    </div>
                </div>

                <div class="score-section">
                    <h3>Match Scores</h3>
                    <div class="form-row">
                        <div class="team-score">
                            <h4>Madina FC</h4>
                            <div class="form-group">
                                <label for="homeScore">Goals</label>
                                <input type="number" id="homeScore" name="homeScore"  min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="homeYellowCards">Yellow Cards</label>
                                <input type="number" id="homeYellowCards" name="homeYellowCards" min="0">
                            </div>
                            <div class="form-group">
                                <label for="homeRedCards">Red Cards</label>
                                <input type="number" id="homeRedCards" name="homeRedCards" min="0">
                            </div>
                        </div>

                        <div class="vs-separator">vs</div>

                        <div class="team-score">
                            <h4><?= htmlspecialchars($fixture['opponent']) ?></h4>
                            <div class="form-group">
                                <label for="awayScore">Goals</label>
                                <input type="number" id="awayScore" name="awayScore"  required>
                            </div>
                            <div class="form-group">
                                <label for="awayYellowCards">Yellow Cards</label>
                                <input type="number" id="awayYellowCards" name="awayYellowCards" min="0">
                            </div>
                            <div class="form-group">
                                <label for="awayRedCards">Red Cards</label>
                                <input type="number" id="awayRedCards" name="awayRedCards" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="scorerSelect">Select Scorers (click each time a player scores)</label>
                    <select id="scorerSelect" class="scorer-select">
                        <option value="">-- Select Player --</option>
                        <?php foreach ($players as $player): ?>
                            <option value="<?= $player['id'] ?>" data-name="<?= htmlspecialchars($player['name']) ?>">
                                <?= htmlspecialchars($player['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="scorerList" class="selected-players"></div>
                </div>
                <input type="hidden" name="scorersJson" id="scorersJson">

                <div class="form-group full-width">
                    <label for="matchReport">Match Report</label>
                    <textarea id="matchReport" name="matchReport" placeholder="Enter match summary"><?= htmlspecialchars($fixture['report'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="fixtures.php" class="cancel-btn">Cancel</a>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Update Fixture
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src='scripts/add-result.js'></script>
</body>
</html>
