<?php
require_once '../includes/db_connect.php';

$playerId = $_GET['id'] ?? null;
if (!$playerId) {
    header("Location: admin-players.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM players WHERE id = ?");
$stmt->bind_param("i", $playerId);
$stmt->execute();
$result = $stmt->get_result();
$player = $result->fetch_assoc();
$stmt->close();

if (!$player) {
    echo "<p>Player not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Player | Madina FC Admin</title>
  <link rel="stylesheet" href="styles/upload-news.css" />
  <link rel="stylesheet" href="styles/commonStyles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
  <div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content">
      <header class="content-header">
        <h1><i class="fas fa-user-edit"></i> Edit Player</h1>
        <div class="breadcrumb">
          <a href="index.php">Dashboard</a> / <span>Edit Player</span>
        </div>
      </header>

      <div class="upload-card">
        <form id="playerForm" class="news-form" action="process-edit-player.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="playerId" value="<?= $player['id'] ?>">

          <div class="form-row">
            <div class="form-group">
              <label for="playerName">Full Name</label>
              <input type="text" id="playerName" name="playerName" required value="<?= htmlspecialchars($player['name']) ?>">
            </div>
            <div class="form-group">
              <label for="jerseyNumber">Jersey Number</label>
              <input type="number" id="jerseyNumber" name="jerseyNumber" min="1" max="99" value="<?= htmlspecialchars($player['jersey_number']) ?>">
            </div>
          </div>

          <div class="form-group">
            <label>Current Photo</label><br>
            <?php if (!empty($player['id_photo_path'])): ?>
              <img src="<?= htmlspecialchars($player['id_photo_path']) ?>" alt="Player Photo" width="100" style="margin-bottom:10px;">
            <?php else: ?>
              <p>No photo uploaded.</p>
            <?php endif; ?>
            <div class="upload-area" id="photoDropZone">
              <i class="fas fa-cloud-upload-alt"></i>
              <p>Drag & drop player photo here or click to browse</p>
              <input type="file" id="photoInput" name="playerPhoto" accept="image/*" hidden>
              <button type="button" class="browse-btn" id="photoBrowseBtn">Select Photo</button>
              <div class="preview-container" id="photoPreviewContainer"></div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="position">Position</label>
              <select id="position" name="position" required>
                <option value="">Select position</option>
                <?php
                $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'];
                foreach ($positions as $pos) {
                    $selected = $player['position'] === $pos ? 'selected' : '';
                    echo "<option value=\"$pos\" $selected>$pos</option>";
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label for="nationality">Nationality</label>
              <input type="text" id="nationality" name="nationality" value="<?= htmlspecialchars($player['nationality']) ?>">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="dob">Date of Birth</label>
              <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($player['date_of_birth']) ?>">
            </div>

            <div class="form-group">
              <label for="height">Height (cm)</label>
              <input type="text" id="height" name="height" value="<?= htmlspecialchars($player['height']) ?>">
            </div>

            <div class="form-group">
              <label for="weight">Weight (kg)</label>
              <input type="number" id="weight" name="weight" value="<?= htmlspecialchars($player['weight_kg']) ?>">
            </div>
          </div>

          <div class="form-actions">
            <a href="admin-players.php" class="cancel-btn">Cancel</a>
            <button type="submit" class="submit-btn">
              <i class="fas fa-save"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>

  <script src="scripts/edit-player.js"></script>
</body>
</html>
