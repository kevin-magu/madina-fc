<?php
require_once '../includes/db_connect.php';
//require_once 'includes/functions.php';
//require_once 'includes/auth_check.php'; // Ensure admin is logged in

// Get fixtures from database
$fixtures = [];
$query = "SELECT id, match_date, match_time, opponent, location, stadium 
          FROM fixtures 
          ORDER BY match_date ASC, match_time ASC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fixtures[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fixtures | Madina FC Admin</title>
    <link rel="stylesheet" href="styles/fixtures.css">
    <link rel="stylesheet" href="styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="content">
            <header class="content-header">
                <h1><i class="fas fa-calendar-alt"></i> Manage Fixtures</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / <span>Fixtures</span>
                </div>
            </header>

            <div class="admin-actions">
                <a href="add-fixture.php" class="add-new-btn">
                    <i class="fas fa-plus"></i> Add New Fixture
                </a>
            </div>

            <div class="fixtures-container">
                <?php if (empty($fixtures)): ?>
                    <div class="no-fixtures">
                        <i class="far fa-calendar-times"></i>
                        <p>No fixtures scheduled yet.</p>
                        <a href="add-fixture.php" class="btn">Add First Fixture</a>
                    </div>
                <?php else: ?>
                    <div class="fixtures-grid">
                        <?php foreach ($fixtures as $fixture): 
                            $matchDate = new DateTime($fixture['match_date']);
                            $matchTime = new DateTime($fixture['match_time']);
                            $isPastFixture = $matchDate < new DateTime('today');
                        ?>
                        <div class="fixture-card <?php echo $isPastFixture ? 'past' : ''; ?>">
                            <div class="fixture-header">
                                <div class="fixture-date">
                                    <span class="day"><?php echo $matchDate->format('d'); ?></span>
                                    <span class="month"><?php echo $matchDate->format('M'); ?></span>
                                </div>
                                <div class="fixture-details">
                                    <h3><?php echo htmlspecialchars($fixture['opponent']); ?></h3>
                                    <div class="match-meta">
                                        <span class="location <?php echo strtolower($fixture['location']); ?>">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo $fixture['location']; ?>
                                            <?php if (!empty($fixture['stadium'])): ?>
                                                (<?php echo htmlspecialchars($fixture['stadium']); ?>)
                                            <?php endif; ?>
                                        </span>
                                        <span class="time">
                                            <i class="far fa-clock"></i>
                                            <?php echo $matchTime->format('H:i'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="fixture-actions">
                                <a href="edit-fixture.php?id=<?php echo $fixture['id']; ?>" class="action-btn edit-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a data-id="<?php echo $fixture['id']; ?>" class="action-btn delete-btn" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <a class="action-btn update-fixture-btn" href="update-fixture.php?id=<?php echo $fixture['id']; ?>">
                                  <i class="fas fa-check-circle"></i>
                                  <p class='game-done-p'>Mark game as complete</p>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src='scripts/delete-fixture.js'></script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
  // Listen for click events on all update fixture buttons
  document.querySelectorAll('.update-fixture-btn').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault(); // Prevent default navigation

      const confirmDone = confirm("Are you sure the game is complete?");
      if (confirmDone) {
        // Redirect to the href if confirmed
        window.location.href = this.getAttribute('href');
      }
    });
  });
});
</script>

</body>
</html>