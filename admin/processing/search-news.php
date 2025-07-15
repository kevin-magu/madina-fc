<?php
require_once '../../includes/db_connect.php';
$conn->set_charset("utf8mb4");

$q = $_GET['q'] ?? '';
$q = '%' . $conn->real_escape_string($q) . '%';

$stmt = $conn->prepare("
    SELECT id, title, content, image_path, created_at 
    FROM news_articles 
    WHERE title LIKE ? OR content LIKE ? 
    ORDER BY created_at DESC
    LIMIT 30
");

$stmt->bind_param("ss", $q, $q);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="no-results">
            <i class="fas fa-newspaper"></i>
            <p>No news articles found.</p>
            <a href="upload-news.php" class="btn">Create New Article</a>
          </div>';
    exit;
}

echo '<div class="admin-news-grid">';
while ($row = $result->fetch_assoc()) {
    $img = $row['image_path'] ?: 'assets/images/madina-logo.png';
    $excerpt = mb_strimwidth(strip_tags($row['content']), 0, 120, '...');
    $date = date('M j, Y', strtotime($row['created_at']));

    echo '
    <div class="admin-news-card">
        <div class="news-card-image">
            <img src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($row['title']) . '">
        </div>
        <div class="news-card-content">
            <h3>' . htmlspecialchars($row['title']) . '</h3>
            <p class="news-excerpt">' . htmlspecialchars($excerpt) . '</p>
            <div class="news-meta">
                <span class="publish-date">
                    <i class="far fa-calendar-alt"></i> ' . $date . '
                </span>
            </div>
        </div>
        <div class="news-card-actions">
            <a href="edit-news.php?id=' . $row['id'] . '" class="action-btn edit-btn" title="Edit">
                <i class="fas fa-edit"></i>
            </a>
            <span 
                class="action-btn delete-btn" 
                title="Delete" 
                data-id="' . $row['id'] . '"
            >
                <i class="fas fa-trash"></i>
            </span>
        </div>
    </div>';
}
echo '</div>';

$stmt->close();
$conn->close();
