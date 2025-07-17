<?php
require_once '../includes/db_connect.php';
// require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data here
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | Madina FC</title>
    <link rel="stylesheet" href="./styles/register-admin.css">
    <link rel="stylesheet" href="./styles/commonStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <main class="content">
            <div class="registration-card">
                <div class="registration-header">
                    <h1><i class="fas fa-user-shield"></i> Admin Registration</h1>
                    <p>Create a new administrator account</p>
                </div>

                <form id="registrationForm" class="registration-form" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="fullName" name="fullName" placeholder="Enter full name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" placeholder="Enter email address" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nationalId">National ID</label>
                            <div class="input-wrapper">
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="number" id="nationalId" name="nationalId" placeholder="Enter national ID" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="location">Location</label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <input type="text" id="location" name="location" placeholder="Enter your location" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" placeholder="Create password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Repeat password" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-user-plus"></i> Register Admin
                        </button>
                        <div class="login-link">
                            Already have an account? <a href="login.php">Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src="scripts/register-admin.js"></script>
</body>
</html>