<?php
require_once '../includes/db_connect.php';
//require_once 'includes/functions.php';
//require_once 'includes/auth_check.php'; // Ensure admin is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Fixture | Madina FC Admin</title>
    <link rel="stylesheet" href="./styles/upload-news.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar (reuse from dashboard) -->
        <?php include 'includes/sidebar.php' ?>
        
        <main class="content">
            <header class="content-header">
                <h1><i class="fas fa-calendar-alt"></i> Add New Fixture</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / <span>Add Fixture</span>
                </div>
            </header>

            <div class="upload-card">
                <form id="fixtureForm" class="news-form" action="process-add-fixture.php" method="POST">
                    <div class="form-group">
                        <label for="matchDate">Match Date</label>
                        <input type="date" id="matchDate" name="matchDate" required>
                    </div>

                    <div class="form-group">
                        <label for="matchTime">Match Time</label>
                        <input type="time" id="matchTime" name="matchTime" required>
                    </div>

                    <div class="form-group">
                        <label for="opponent">Opponent</label>
                        <input type="text" id="opponent" name="opponent" placeholder="Enter opponent team name" required>
                    </div>

                    <div class="form-group">
                        <label>Match Location</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="location" value="Home" checked>
                                <span class="radio-label">Home</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="location" value="Away">
                                <span class="radio-label">Away</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stadium">Stadium</label>
                        <input type="text" id="stadium" name="stadium" placeholder="Enter stadium name">
                    </div>

                    <div class="form-actions">
                        <a href="admin-fixtures.php" class="cancel-btn">Cancel</a>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-plus"></i> Add Fixture
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src='scripts/add-fixture.js'></script>
</body>
</html>