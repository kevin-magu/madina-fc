<?php
require_once 'includes/db_connect.php';
//require_once 'includes/functions.php';

// Initialize variables
$searchQuery = '';
$newsArticles = [];

// Check for search query
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    $searchParam = "%{$searchQuery}%";
    
    // Prepare search query
    $stmt = $conn->prepare("SELECT id, title, content, image_path, created_at 
                           FROM news_articles 
                           WHERE title LIKE ? OR content LIKE ?
                           ORDER BY created_at DESC");
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Get all news if no search
    $result = $conn->query("SELECT id, title, content, image_path, created_at 
                           FROM news_articles 
                           ORDER BY created_at DESC");
}

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $newsArticles[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madina FC News</title>
    <link rel="stylesheet" href="assets/css/news.css">
     <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="news-main">
        <!-- Search Section -->
        <section class="search-section">
            <div class="container">
                <form method="GET" action="news.php" class="search-form">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="Search news by title or content..." 
                               value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <?php if (!empty($searchQuery)): ?>
                        <div class="search-results-info">
                            <p>Showing results for: <strong>"<?php echo htmlspecialchars($searchQuery); ?>"</strong></p>
                            <a href="news.php" class="clear-search">Clear search</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <!-- News Listing Section -->
        <section class="news-listing">
            <div class="container">
                <h1 class="section-title"><?php echo empty($searchQuery) ? 'All News' : 'Search Results'; ?></h1>
                
                <?php if (empty($newsArticles)): ?>
                    <div class="no-results">
                        <i class="fas fa-newspaper"></i>
                        <p>No news articles found<?php echo !empty($searchQuery) ? ' matching your search' : ''; ?>.</p>
                        <?php if (!empty($searchQuery)): ?>
                            <a href="news.php" class="btn">View All News</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="news-grid">
                        <?php foreach ($newsArticles as $article): ?>
                            <article class="news-card">
                                <div class="news-image">
                                    <img src="<?php echo !empty($article['image_path']) ? htmlspecialchars($article['image_path']) : 'assets/images/madina-logo.png'; ?>" 
                                         alt="<?php echo htmlspecialchars($article['title']); ?>">
                                </div>
                                <div class="news-content">
                                    <div class="news-meta">
                                        <time datetime="<?php echo date('Y-m-d', strtotime($article['created_at'])); ?>">
                                            <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
                                        </time>
                                    </div>
                                    <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                                    <p class="excerpt"><?php echo mb_strimwidth(strip_tags($article['content']), 0, 150, '...'); ?></p>
                                    <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>