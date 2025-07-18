<?php
require_once '../includes/db_connect.php';
// require_once 'includes/functions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Madina FC</title>
    <link rel="stylesheet" href="./styles/login.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <main class="content">
            <div class="login-card">
            <div class="login-header">
                    <h1><i class="fas fa-sign-in-alt"></i> Admin Login</h1>
                    <p>Access your administrator dashboard</p>
                </div>

                <form id="loginForm" class="login-form" method="POST">
                    <div class="form-group">
                        <label for="email">Admin Username</label>
                        <div class="input-wrapper">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="number" id="username" name="username" placeholder="Enter your username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>

                    <div class="register-link">
                        Don't have an account? <a href="register-admin.php">Register</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
<script src="scripts/login.js"></script>

</body>
</html>