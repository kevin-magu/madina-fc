<?php
$pageTitle = "Blog";
include 'includes/header.php';
?>

<section class="main-content">
    <div class="container">
        <h2 class="section-title">Club News & Updates</h2>
        
        <div class="featured-posts">
            <?php
            // Get all posts
            $stmt = $pdo->query("SELECT * FROM posts ORDER BY post_date DESC");
            while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <div class="post-card">
                    <div class="post-image">
                        <img src="assets/images/post-placeholder.jpg" alt="' . htmlspecialchars($post['title']) . '">
                    </div>
                    <div class="post-content">
                        <div class="post-date">' . date('F j, Y', strtotime($post['post_date'])) . '</div>
                        <h3 class="post-title">' . htmlspecialchars($post['title']) . '</h3>
                        <p class="post-excerpt">' . substr(strip_tags($post['content']), 0, 100) . '...</p>
                        <a href="single-post.php?id=' . $post['id'] . '" class="btn">Read More</a>
                    </div>
                </div>';
            }
            
            if ($stmt->rowCount() === 0) {
                echo '<p>No posts found. Check back later!</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>