<?php
require_once '../../includes/db_connect.php';
$conn->set_charset("utf8mb4");

$q = $_GET['q'] ?? '';
$q = '%' . $conn->real_escape_string($q) . '%';

$sql = "
    SELECT id, name, id_photo_path, position, jersey_number, nationality, date_of_birth, height, club_id,weight_kg
    FROM players
    WHERE name LIKE ? OR position LIKE ? OR nationality LIKE ?
    ORDER BY name ASC
    LIMIT 30
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $q, $q, $q);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<p>No players found.</p>';
    exit;
}

while ($row = $result->fetch_assoc()) {
    $photoPath = $row['id_photo_path'] ?: 'assets/images/default.jpg';
    $age = $row['date_of_birth'] ? date_diff(date_create($row['date_of_birth']), date_create('today'))->y : 'N/A';
    $clubId = $row['club_id'];

    echo '<div class="player-card">
        <div class="player-photo">
            <img src="' . htmlspecialchars($photoPath) . '" alt="Player Photo">
            <span class="jersey-number">' . htmlspecialchars($row['jersey_number']) . '</span>
        </div>
        <div class="player-info">
            <h3 class="player-name">' . htmlspecialchars($row['name']) . '</h3>
            <div class="player-meta">
                <span class="position"><i class="fas fa-running"></i> ' . htmlspecialchars($row['position']) . '</span>
                <span class="nationality"><i class="fas fa-flag"></i> ' . htmlspecialchars($row['nationality']) . '</span>
            </div>
            <div class="player-stats">
                <span><i class="fas fa-ruler-vertical"></i> ' . htmlspecialchars($row['height']) . 'cm</span>
                <span><i class="fas fa-weight"></i> ' . htmlspecialchars($row['weight_kg']) . 'kg</span>
                <span><i class="fas fa-birthday-cake"></i> ' . $age . '</span>
                <span><i class="fas fa-id-badge"></i> ' . $clubId . '</span>
            </div>
        </div>
        <div class="player-actions">
            <a href="edit-player.php?id=' . $row['id'] . '" class="action-btn edit-btn" title="Edit">
                <i class="fas fa-edit"></i>
            </a>
        <span class="action-btn delete-btn" title="Delete" data-id="' . $row['id'] . '">
            <i class="fas fa-trash-alt"></i>
        </span>


        </div>
    </div>';
}
$stmt->close();
$conn->close();
