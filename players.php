<?php
$pageTitle = "Players";
include 'includes/header.php';
?>

<section class="main-content">
    <div class="container">
        <h2 class="section-title">Our Team</h2>
        
        <div class="players-grid">
            <?php
            // Get all players
            $stmt = $pdo->query("SELECT * FROM players ORDER BY position, jersey_number");
            while ($player = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imagePath = !empty($player['image_path']) ? $player['image_path'] : 'assets/images/player-placeholder.jpg';
                
                echo '
                <div class="player-card">
                    <div class="player-image">
                        <img src="' . $imagePath . '" alt="' . htmlspecialchars($player['name']) . '">
                    </div>
                    <div class="player-info">
                        <div class="player-number">' . $player['jersey_number'] . '</div>
                        <h3 class="player-name">' . htmlspecialchars($player['name']) . '</h3>
                        <div class="player-position">' . htmlspecialchars($player['position']) . '</div>
                    </div>
                </div>';
            }
            
            if ($stmt->rowCount() === 0) {
                echo '<p>No players found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>