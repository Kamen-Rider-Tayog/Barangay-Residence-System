<?php
$page_title = 'Login';
$page_css = 'login.css';
$page_js = 'login.js';
require_once '../includes/init.php';
include '../includes/header.php';
?>

<div class="login-page">
    <div class="login-container">
        <a href="index.php" class="logo-link">
            <div class="logo-circle">
                <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Logo" class="logo-img">
            </div>
        </a>
        
        <h1><?php echo SITE_NAME; ?></h1>
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
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <input type="text" id="emailInput" name="email" class="form-input" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <input type="password" id="passwordInput" name="password" class="form-input" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" id="togglePass">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary login-btn">Login</button>
            </form>
        </div>

        <a href="index.php" class="back-home">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Back to Home
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>