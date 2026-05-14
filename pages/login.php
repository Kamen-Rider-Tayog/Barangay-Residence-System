<?php
require_once '../includes/init.php';
include '../includes/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/login.css">
<div class="login-page">
    <div class="login-container">
        <a href="index.php" class="logo-link">
            <div class="logo-circle">
                <img src="/barangay-residence-system/assets/images/logo.png" alt="Logo" class="logo-img">
            </div>
        </a>
        
        <h1>Barangay San Francisco</h1>
        <p class="subtitle">Login to your account</p>
        
        <div class="card login-card">
            <div id="errorMessage" class="error-alert" style="display: none;">
                Invalid email or password. Please try again.
            </div>

            <form id="loginForm" method="POST" action="login_process.php">
                <div class="form-group">
                    <label for="emailInput">Email or Username</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="text" id="emailInput" name="email" class="form-input" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" id="passwordInput" name="password" class="form-input" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" id="togglePass">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary login-btn">Login</button>
            </form>
        </div>

        <a href="index.php" class="back-home">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-alert" style="display: block;">
            Invalid email or password. Please try again.
        </div>
    <?php endif; ?>
</div>
<script src="/barangay-residence-system/assets/js/pages/login.js"></script>