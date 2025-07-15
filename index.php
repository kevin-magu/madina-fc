<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madina FC | Pride, Passion, Glory</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
   <?php include 'includes/header.php' ?>

    <!-- Hero Banner (Slideshow) -->
    <section class="hero">
    <div class="hero-slide active">
        <img src="assets/images/madina-team.jpeg" alt="Madina FC Action">
        <!-- Add overlay div -->
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2>Welcome to Madina FC</h2>
            <p>The Home of Champions</p>
            <div class="hero-buttons">
                <a href="#" class="btn">Buy Tickets Now</a>
                <a href="#" class="btn">Shop New Kits</a>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
    <?php
require_once 'includes/db_connect.php';

$latestNews = [];
$query = "SELECT id, title, content, image_path, created_at FROM news_articles ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $latestNews[] = $row;
    }
}
$conn->close();
?>

<!-- Latest News Section -->
<section class="news-section">
    <div class="container">
        <h2 class="section-title">Latest News</h2>
        <div class="news-grid">

            <?php foreach ($latestNews as $news): ?>
                <div class="news-card">
                    <img src="<?php echo !empty($news['image_path']) ? htmlspecialchars($news['image_path']) : 'assets/images/madina-logo.png'; ?>" alt="News Image">
                    <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                    <p><?php echo mb_strimwidth(strip_tags($news['content']), 0, 80, '...'); ?></p>
                    <div class="published-badge">
                        <?php echo date('F j, Y', strtotime($news['created_at'])); ?>
                    </div>
                    <a href="article.php?id=<?php echo $news['id']; ?>" class="read-more">Read More</a>
                </div>
            <?php endforeach; ?>

        </div>

        <a href="news.php" class="view-all">View All News →</a>
    </div>
</section>


    <!-- Fixtures & Results -->
    <section class="fixtures-section">
        <div class="container">
            <h2 class="section-title">Fixtures & Results</h2>
            <div class="fixtures-container">
                <div class="next-match">
                    <h3>Next Match</h3>
                    <div class="match-details">
                        <span class="team">Madina FC</span>
                        <span class="vs">vs</span>
                        <span class="team">Top Opponent</span>
                    </div>
                    <p class="match-info">Saturday, 15 July 2024 | 15:00 | Madina Arena</p>
                    <div class="match-buttons">
                        <a href="#" class="btn">View upcoming matches</a>
                    </div>
                </div>
                <div class="recent-results">
                    <h3>Recent Results</h3>
                    <ul>
                        <li>Madina FC 3-1 City Rivals (W)</li>
                        <li>Madina FC 0-0 Tough Opponent (D)</li>
                        <li>Madina FC 2-3 League Leaders (L)</li>
                    </ul>
                    <a href="#" class="view-all">Full Fixture List →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Squad & Player Spotlight -->
    <section class="squad-section">
        <div class="container">
            <h2 class="section-title">Meet the Squad</h2>
            <div class="player-spotlight">
                <img src="assets/images/best-player.jpeg" alt="Player Image">
                <div class="player-info">
                    <h3>Player of the Month</h3>
                    <p><strong>John Doe</strong> | Striker</p>
                    <p>10 Goals | 5 Assists | 2023/24 Season</p>
                    <a href="#" class="btn">View Full Profile</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tickets & Membership 
    <section class="tickets-section">
        <div class="container">
            <h2 class="section-title">Tickets & Membership</h2>
            <div class="tickets-grid">
                <div class="ticket-card">
                    <h3>Season Tickets</h3>
                    <p>Priority access to all home matches.</p>
                    <a href="#" class="btn">Join Now</a>
                </div>
                <div class="ticket-card">
                    <h3>Matchday Tickets</h3>
                    <p>Secure your seat for the next game.</p>
                    <a href="#" class="btn">Buy Now</a>
                </div>
                <div class="ticket-card">
                    <h3>VIP Hospitality</h3>
                    <p>Luxury experience at Madina Arena.</p>
                    <a href="#" class="btn">Explore</a>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Official Store -->
    <section class="store-section">
        <div class="container">
            <h2 class="section-title">Official Store</h2>
            <div class="store-grid">
                <div class="product-card">
                    <img src="https://via.placeholder.com/250x250" alt="Home Kit">
                    <h3>2024/25 Home Kit</h3>
                    <p>$89.99</p>
                    <a href="#" class="btn">Buy Now</a>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/250x250" alt="Away Kit">
                    <h3>2024/25 Away Kit</h3>
                    <p>$89.99</p>
                    <a href="#" class="btn">Buy Now</a>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/250x250" alt="Training Gear">
                    <h3>Training Jacket</h3>
                    <p>$59.99</p>
                    <a href="#" class="btn">Buy Now</a>
                </div>
            </div>
        </div>
    </section>

   <!-- Madina FC TV Section -->
<section class="video-section">
    <div class="container">
        <h2 class="section-title">Madina FC TV</h2>
        <!-- TikTok Embed -->
        <div class="tiktok-embed">
            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@zack_fitness10/video/7317245233194880262" data-video-id="7317245233194880262">
                <section>
                    <a target="_blank" title="@zack_fitness10" href="https://www.tiktok.com/@zack_fitness10">@zack_fitness10</a>
                    <p>Madina FC training session highlights</p>
                    <a target="_blank" title="♬ original sound - Zack Fitness" href="https://www.tiktok.com/music/original-sound-7317245264170355462">♬ original sound - Zack Fitness</a>
                </section>
            </blockquote>
            <script async src="https://www.tiktok.com/embed.js"></script>
        </div>
        <a href="https://www.tiktok.com/@madinafcofficial" class="btn" target="_blank">Follow Us on TikTok</a>
    </div>
</section>

 <?php include 'includes/footer.php' ?>   
</body>
</html>