<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db_connect.php';
require_once '../includes/config.php';

// Handle player deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM players WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_players.php?deleted=1");
    exit;
}

$pageTitle = "Manage Players";
include '../includes/header.php';
?>

<div class="admin-header">
    <div class="container">
        <h2>Manage Players</h2>
        <nav class="admin-nav">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_posts.php">Manage Posts</a></li>
                <li><a href="manage_players.php">Manage Players</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</div>

<section class="dashboard">
    <div class="container">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">
                Player deleted successfully.
            </div>
        <?php endif; ?>
        
        <div class="text-right mb-3">
            <a href="add_player.php" class="btn">Add New Player</a>
        </div>
        
        <div class="admin-form">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Jersey #</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $players = $pdo->query("SELECT * FROM players ORDER BY jersey_number")->fetchAll();
                    
                    foreach ($players as $player) {
                        echo '
                        <tr>
                            <td>' . $player['id'] . '</td>
                            <td>' . htmlspecialchars($player['name']) . '</td>
                            <td>' . htmlspecialchars($player['position']) . '</td>
                            <td>' . $player['jersey_number'] . '</td>
                            <td class="action-links">
                                <a href="edit_player.php?id=' . $player['id'] . '" class="btn btn-secondary">Edit</a>
                                <a href="manage_players.php?delete=' . $player['id'] . '" class="btn" onclick="return confirm(\'Are you sure you want to delete this player?\')">Delete</a>
                            </td>
                        </tr>';
                    }
                    
                    if (count($players) === 0) {
                        echo '<tr><td colspan="5">No players found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>