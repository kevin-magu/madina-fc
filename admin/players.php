<?php
require_once '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Players | Madina FC Admin</title>
    <link rel="stylesheet" href="styles/players.css">
    <link rel="stylesheet" href="styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="content">
            <header class="content-header">
                <h1><i class="fas fa-users"></i> Manage Players</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / <span>Players</span>
                </div>
            </header>

            <div class="admin-actions">
                <form class="search-form" onsubmit="return false;">
                    <div class="search-box">
                        <input type="text" id="searchInput" name="search" placeholder="Search player...">
                      <!--  <button type="button" id="searchBtn"><i class="fas fa-search"></i></button> -->
                    </div>
                </form>
                <a href="add-player.php" class="add-new-btn">
                    <i class="fas fa-plus"></i> Add New Player
                </a>
            </div>

            <div class="players-container" id="playersContainer">
                <!-- Player cards will be loaded here dynamically -->
            </div>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const playersContainer = document.getElementById('playersContainer');

        // Fetch players from backend
        async function fetchPlayers(query = '') {
            try {
                const response = await fetch('processing/search-player.php?q=' + encodeURIComponent(query));
                const html = await response.text();
                playersContainer.innerHTML = html;
            } catch (error) {
                playersContainer.innerHTML = '<p class="error">Failed to load players.</p>';
                console.error(error);
            }
        }

        // Load all players on page load
        fetchPlayers();

        // Search on input (real-time)
        searchInput.addEventListener('input', () => {
            fetchPlayers(searchInput.value);
        });

        // Search on button click
        searchBtn.addEventListener('click', () => {
            fetchPlayers(searchInput.value);
        });
    </script>
    <script src='scripts/delete-player.js'></script>
</body>
</html>
