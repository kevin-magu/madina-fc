<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: blog.php");
    exit;
}

$postId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: blog.php");
    exit;
}

$pageTitle = $post['title'];
include 'includes/header.php';
?>

<section class="main-content">
    <div class="container">
        <article class="single-post">
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="post-meta">
                <span>Posted on <?php echo date('F j, Y', strtotime($post['post_date'])); ?></span>
                <span>By <?php echo htmlspecialchars($post['author'] ?? 'Madina FC'); ?></span>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
        </article>
        
        <div class="text-center mt-4">
            <a href="blog.php" class="btn">Back to Blog</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>