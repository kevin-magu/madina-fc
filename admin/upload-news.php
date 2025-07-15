<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madina FC - Upload News</title>
    <link rel="stylesheet" href="./styles/upload-news.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar (reuse from dashboard) -->
       <?php include 'includes/sidebar.php' ?>
        <main class="content">
            <header class="content-header">
                <h1><i class="fas fa-newspaper"></i> Upload News Article</h1>
                <div class="breadcrumb">
                    <a href="index.php">Dashboard</a> / <span>Upload News</span>
                </div>
            </header>

            <div class="upload-card">
                <form id="newsForm" class="news-form">
                    <div class="form-group">
                        <label for="newsTitle">News Title</label>
                        <input type="text" id="newsTitle" name="newsTitle" placeholder="Enter news headline" required>
                    </div>

                    <div class="form-group">
                        <label>Featured Image</label>
                        <div class="upload-area" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop your image here or click to browse</p>
                            <input type="file" id="fileInput" accept="image/*" hidden>
                            <button type="button" class="browse-btn" id="browseBtn">Select Image</button>
                         <div class="preview-container" id="previewContainer"></div> 
                        </div>
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
                        <div id="newsContent" class="content-editor" contenteditable="true" placeholder="Write your news content here..."></div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Publish News
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src='scripts/upload-news.js'></script>
</body>
</html>