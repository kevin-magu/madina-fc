<?php
require_once 'includes/db_connect.php';

// Get article ID from the URL
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($articleId < 1) {
    die("Invalid article ID.");
}

// Fetch the article from the database
$stmt = $conn->prepare("SELECT title, content, image_path AS featured_image, created_at AS publish_date FROM news_articles WHERE id = ?");
$stmt->bind_param("i", $articleId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Article not found.");
}

$article = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Optional: Reading time function
function calculateReadingTime($text) {
    $wordCount = str_word_count(strip_tags($text));
    return max(1, ceil($wordCount / 200)); // 200 wpm
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> | Madina FC</title>
    <link rel="stylesheet" href="assets/css/article.css">
     <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <article class="news-article">
        <!-- Article Header -->
        <header class="article-header">
            <div class="article-meta">
                <span class="category">Club News</span>
                <time datetime="<?php echo date('Y-m-d', strtotime($article['publish_date'])); ?>">
                    <?php echo date('F j, Y', strtotime($article['publish_date'])); ?>
                </time>
                <span class="reading-time">
                    <?php echo calculateReadingTime($article['content']); ?> min read
                </span>
            </div>
            <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
            
            <div class="article-author">
                <img src="assets/images/madina-logo.png" alt="Admin" class="author-avatar">
                <div>
                    <span class="author-name">Madina FC Media Team</span>
                    <span class="author-title">Official Club News</span>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if (!empty($article['featured_image'])): ?>
        <figure class="featured-image">
            <img src="<?php echo htmlspecialchars($article['featured_image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
            <figcaption>Featured image for <?php echo htmlspecialchars($article['title']); ?></figcaption>
        </figure>
        <?php endif; ?>

        <!-- Article Content -->
        <div class="article-content">
            <?php echo $article['content']; ?>
        </div>

        <!-- Article Footer -->
        <footer class="article-footer">
            <div class="tags">
                <a href="#" class="tag">Read More</a>
            </div>
            
            <div class="social-share">
                <span>Share:</span>
                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article['title']); ?>&url=<?php echo urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="https://wa.me/?text=<?php echo urlencode($article['title'] . ' - https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="social-icon"><i class="fab fa-whatsapp"></i></a>
            </div>
        </footer>
    </article>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
