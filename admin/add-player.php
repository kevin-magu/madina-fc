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
    <title>Add New Player | Madina FC Admin</title>
    <link rel="stylesheet" href="styles/upload-news.css">
    <link rel="stylesheet" href="styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar (reuse from dashboard) -->
        <?php include 'includes/sidebar.php' ?>
        
        <main class="content">
            <header class="content-header">
                <h1><i class="fas fa-user-plus"></i> Add New Player</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / <span>Add Player</span>
                </div>
            </header>

            <div class="upload-card">
                <form id="playerForm" class="news-form" action="process-add-player.php" method="POST" enctype="multipart/form-data">
                    <!-- Player Basic Info -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="playerName">Full Name</label>
                            <input type="text" id="playerName" name="playerName" placeholder="Enter player's full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="jerseyNumber">Jersey Number</label>
                            <input type="number" id="jerseyNumber" name="jerseyNumber" placeholder="Enter jersey number" min="1" max="99">
                        </div>
                    </div>

                    <!-- Player Photo -->
                    <div class="form-group">
                        <label>Passport Photo/ID</label>
                        <div class="upload-area" id="photoDropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop player photo here or click to browse</p>
                            <input type="file" id="photoInput" name="playerPhoto" accept="image/*" hidden>
                            <button type="button" class="browse-btn" id="photoBrowseBtn">Select Photo</button>
                            <div class="preview-container" id="photoPreviewContainer"></div>
                        </div>
                    </div>

                    <!-- Player Details -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select id="position" name="position" required>
                                <option value="">Select position</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                                <option value="Defender">Defender</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Forward">Forward</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" id="nationality" name="nationality" placeholder="Enter nationality">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob">
                        </div>
                        
                        <div class="form-group">
                            <label for="height">Height (ft)</label>
                            <input type="text" id="height" name="height" placeholder="Enter height in cm" >
                        </div>
                        
                        <div class="form-group">
                            <label for="weight">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" placeholder="Enter weight in kg">
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="admin-players.php" class="cancel-btn">Cancel</a>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save"></i> Add Player
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src='scripts/add-player.js'></script>

</body>
</html>