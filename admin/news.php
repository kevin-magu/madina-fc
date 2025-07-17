<?php require_once '../includes/db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage News | Madina FC Admin</title>
  <link rel="stylesheet" href="styles/news.css" />
  <link rel="stylesheet" href="styles/commonStyles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body>
  <div class="admin-container">
    <?php include 'includes/sidebar.php'; ?>

    <main class="content">
      <header class="content-header">
        <h1><i class="fas fa-newspaper"></i> Manage News Articles</h1>
        <div class="breadcrumb">
          <a href="index.php">Dashboard</a> / <span>News Management</span>
        </div>
      </header>

      <!-- Search and Add New -->
      <div class="admin-actions">
        <form class="search-form" onsubmit="return false;">
          <div class="search-box">
            <input type="text" id="newsSearchInput" name="search" placeholder="Search news..." />
            <!-- <button type="button" id="newsSearchBtn"><i class="fas fa-search"></i></button> -->
          </div>
        </form>
        <a href="upload-news.php" class="add-new-btn">
          <i class="fas fa-plus"></i> Add New Article
        </a>
      </div>

      <!-- News Listing -->
      <div class="admin-news-listing" id="newsContainer">
        <!-- Results will be injected here -->
      </div>
    </main>
  </div>

  <script src="scripts/delete-news.js"></script>
  <script>
    const searchInput = document.getElementById('newsSearchInput');
    const searchBtn = document.getElementById('newsSearchBtn');
    const newsContainer = document.getElementById('newsContainer');

    async function fetchNews(query = '') {
      try {
        const res = await fetch('processing/search-news.php?q=' + encodeURIComponent(query));
        const html = await res.text();
        newsContainer.innerHTML = html;
      } catch (err) {
        newsContainer.innerHTML = '<p class="error">Failed to load news articles.</p>';
        console.error(err);
      }
    }

    // Load all news on page load
    fetchNews();

    // Real-time search
    searchInput.addEventListener('input', () => {
      fetchNews(searchInput.value);
    });

    // Button-triggered search
    searchBtn.addEventListener('click', () => {
      fetchNews(searchInput.value);
    });
  </script>
</body>
</html>
