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
    <title>System Settings | Madina FC Admin</title>
    <link rel="stylesheet" href="./styles/settings.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar (reuse from dashboard) -->
        <?php include 'includes/sidebar.php' ?>
        
        <main class="content">
            <header class="content-header">
                <h1><i class="fas fa-cog"></i> System Settings</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / <span>Settings</span>
                </div>
            </header>

            <div class="settings-grid">
                <!-- Account Settings Card -->
                <a href="account-settings.php" class="settings-card account-card">
                    <div class="card-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3>Account Settings</h3>
                    <p>Manage your admin profile and password</p>
                </a>

                <!-- Add Admin Card -->
                <a href="register-admin.php" class="settings-card admin-card">
                    <div class="card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3>Add Admin</h3>
                    <p>Create new administrator accounts</p>
                </a>

                <!-- Team Settings Card -->
                <a href="team-settings.php" class="settings-card team-card">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Team Settings</h3>
                    <p>Configure team information and roster</p>
                </a>

                <!-- Notification Settings Card -->
                <a href="notification-settings.php" class="settings-card notification-card">
                    <div class="card-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Notifications</h3>
                    <p>Manage system alerts and emails</p>
                </a>

                <!-- System Preferences Card -->
                <a href="system-preferences.php" class="settings-card system-card">
                    <div class="card-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h3>System Preferences</h3>
                    <p>Configure application settings</p>
                </a>

                <!-- Backup & Restore Card -->
                <a href="backup-restore.php" class="settings-card backup-card">
                    <div class="card-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Backup & Restore</h3>
                    <p>Manage system data backups</p>
                </a>
            </div>
        </main>
    </div>
</body>
</html>