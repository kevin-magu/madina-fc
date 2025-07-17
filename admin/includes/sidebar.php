 <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../assets/images/madina-logo.png" alt="Madina FC Logo" class="logo">
                <h2>Madina FC</h2>
                <p>Admin Dashboard</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="index.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="upload-news.php">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Upload News</span>
                        </a>
                    </li>
                     <li>
                        <a href="news.php">
                            <i class="fas fa-newspaper"></i>
                            <span>Manage News</span>
                        </a>
                    </li>
                    <li>
                        <a href="fixtures.php">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Fixtures</span>
                        </a>
                    </li>
                    <li>
                        <a href="add-result.php">
                            <i class="fas fa-trophy"></i>
                            <span>Results</span>
                        </a>
                    </li>
                    <li>
                        <a href="training.php">
                            <i class="fas fa-dumbbell"></i>
                            <span>Training</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="players.php">
                            <i class="fas fa-users"></i>
                            <span>Manage Players</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile">
                    <img src="styles/madina-logo.png" alt="Admin Avatar">
                    <div>
                        <h4>Admin User</h4>
                        <p>Super Admin</p>
                    </div>
                </div>
                <a href="#" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
      <script>
// Highlight the current page in the sidebar based on URL
document.addEventListener('DOMContentLoaded', function () {
    const currentPage = window.location.pathname.split('/').pop(); // e.g., upload-news.php
    const links = document.querySelectorAll('.sidebar-nav a');

    links.forEach(link => {
        const linkPage = link.getAttribute('href');
        
        // Compare path to highlight matching link
        if (linkPage === currentPage) {
            link.parentElement.classList.add('active');
        } else {
            link.parentElement.classList.remove('active');
        }

        // Optional: Also handle clicks (for SPA feel or future extensions)
        link.addEventListener('click', () => {
            links.forEach(l => l.parentElement.classList.remove('active'));
            link.parentElement.classList.add('active');
        });
    });
});
</script>
