<?php
require_once '../includes/core/init.php';
include '../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/login.css">
<div class="login-page">
    <div class="login-container">
        
        <h1><?php echo __('login-title'); ?></h1>
        <p class="subtitle"><?php echo __('login-subtitle'); ?></p>
        
        <div class="card login-card">
            <div id="errorMessage" class="error-alert" style="display: none;">
                <?php echo __('login-error'); ?>
            </div>

            <form id="loginForm" method="POST" action="login_process.php">
                <div class="form-group">
                    <label for="emailInput"><?php echo __('login-email'); ?></label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="text" id="emailInput" name="email" class="form-input" placeholder="<?php echo __('login-email-placeholder'); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="passwordInput"><?php echo __('login-password'); ?></label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" id="passwordInput" name="password" class="form-input" placeholder="<?php echo __('login-password-placeholder'); ?>" required>
                        <button type="button" class="toggle-password" id="togglePass">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary login-btn"><?php echo __('login-btn'); ?></button>
            </form>
        </div>

        <a href="index.php" class="back-home">
            <i class="fas fa-arrow-left"></i>
            <?php echo __('back-home'); ?>
        </a>
    </div>
</div>

<?php if (isset($_GET['error'])): ?>
<script>
    document.getElementById('errorMessage').style.display = 'block';
</script>
<?php endif; ?>

<script src="/barangay-residence-system/assets/js/pages/login.js"></script>