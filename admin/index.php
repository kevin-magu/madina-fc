<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madina FC - Admin Dashboard</title>
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
       <?php include 'includes/sidebar.php' ?>
        <!-- Main Content Area -->
        <main class="main-content">
            <header class="main-header">
                <h1>Dashboard Overview</h1>
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-icon" style="background-color: #e94560;">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="card-info">
                        <h3>News Articles</h3>
                        <p>12 Published</p>
                        <a href="#upload-news" class="card-link">Upload News</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon" style="background-color: #1a1a2e;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-info">
                        <h3>Fixtures</h3>
                        <p>5 Upcoming</p>
                        <a href="add-fixture.php" class="card-link">Manage Fixtures</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon" style="background-color: #4CAF50;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="card-info">
                        <h3>Player of Month</h3>
                        <p>Current: John Doe</p>
                        <a href="#player-month" class="card-link">Update Player</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon" style="background-color: #FF9800;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-info">
                        <h3>Players</h3>
                        <p>25 Registered</p>
                        <a href="#manage-players" class="card-link">Manage Players</a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <section class="recent-activity">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="activity-details">
                            <p>You published a new article: "Madina FC Signs Star Striker"</p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="activity-details">
                            <p>Added new fixture: Madina FC vs City Rivals</p>
                            <span class="activity-time">1 day ago</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="activity-details">
                            <p>Updated player profile: John Doe</p>
                            <span class="activity-time">2 days ago</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

</body>
</html>