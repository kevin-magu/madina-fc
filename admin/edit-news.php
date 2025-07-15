<?php
require_once '../includes/db_connect.php';
//require_once 'includes/functions.php';
//require_once 'includes/auth_check.php'; // Ensure admin is logged in

// Check if article ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin-news.php');
    exit();
}

$articleId = (int)$_GET['id'];
$article = [];

// Fetch article data from database
$stmt = $conn->prepare("SELECT id, title, content, image_path FROM news_articles WHERE id = ?");
$stmt->bind_param("i", $articleId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $article = $result->fetch_assoc();
} else {
    header('Location: admin-news.php');
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News Article | Madina FC Admin</title>
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
                <h1><i class="fas fa-edit"></i> Edit News Article</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / 
                    <a href="news.php">News Management</a> / 
                    <span>Edit Article</span>
                </div>
            </header>

            <div class="upload-card">
                <form id="newsForm" data-article-id="<?php echo $article['id']; ?>" class="news-form">
                    <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                    
                    <div class="form-group">
                        <label for="newsTitle">News Title</label>
                        <input type="text" id="newsTitle" name="newsTitle" 
                               placeholder="Enter news headline" 
                               value="<?php echo htmlspecialchars($article['title']); ?>" required>
                    </div>
                    <div class="form-group">
    <label>Featured Image</label>
    <div class="upload-area" id="dropZone">
        <?php if (!empty($article['image_path'])): ?>
            <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="Current Image" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; margin-bottom: 10px;">
        <?php else: ?>
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag & drop your image here or click to browse</p>
        <?php endif; ?>

        <!-- File input and browse button always present -->
        <input type="file" id="fileInput" name="featuredImage" accept="image/*" hidden>
        <button type="button" class="browse-btn" id="browseBtn">Select Image</button>
        <div class="preview-container" id="previewContainer"></div>
    </div>

    <!-- Hidden input to keep track of existing image -->
    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($article['image_path'] ?? ''); ?>">
</div>


                    <div class="form-group">
                        <label for="newsContent">News Content</label>
                        <div class="editor-toolbar">
                            <button type="button" data-command="bold"><i class="fas fa-bold"></i></button>
                            <button type="button" data-command="italic"><i class="fas fa-italic"></i></button>
                            <button type="button" data-command="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
                            <button type="button" data-command="insertOrderedList"><i class="fas fa-list-ol"></i></button>
                            <button type="button" data-command="createLink"><i class="fas fa-link"></i></button>
                        </div>
                        <div id="newsContent" class="content-editor" contenteditable="true" placeholder="Write your news content here...">
                            <?php echo $article['content']; ?>
                        </div>
                        <textarea name="newsContent" id="hiddenNewsContent" style="display:none;"></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="news.php" class="cancel-btn">Cancel</a>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save"></i> Update Article
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src='scripts/edit-news.js'></script>
</body>
</html>