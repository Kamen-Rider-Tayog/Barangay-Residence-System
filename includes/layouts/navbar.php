<nav class="navbar">
    <a href="/barangay-residence-system/pages/index.php" class="navbar-logo">
        <div class="navbar-logo-img">
            <img src="/barangay-residence-system/assets/images/logo.png" alt="Logo">
        </div>
        <div>
            <h1>Barangay San Francisco</h1>
            <p><?php echo __('sub-header'); ?></p>
        </div>
    </a>
    
    <div class="navbar-links">
        <a href="/barangay-residence-system/pages/index.php"><?php echo __('nav-home'); ?></a>
        <a href="/barangay-residence-system/pages/services.php"><?php echo __('nav-services'); ?></a>
        <a href="/barangay-residence-system/pages/announcements.php">Announcements</a>

        
        <div class="lang-dropdown">
            <button id="langBtn" class="lang-btn">
                <i class="fas fa-language"></i>
                <span id="currentLangLabel"><?php echo __('language'); ?></span>
                <i id="langArrow" class="fas fa-angle-down"></i>
            </button>
            <div id="langMenu" class="lang-menu">
                <button onclick="changeLanguage('tl')">Tagalog</button>
                <button onclick="changeLanguage('en')">English</button>
            </div>
        </div>
        
        <?php if (isLoggedIn()): ?>
            <?php if (isAdmin()): ?>
                <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">
                    <i class="fas fa-chalkboard-user"></i> <?php echo __('admin-dashboard'); ?>
                </a>
            <?php else: ?>
                <a href="/barangay-residence-system/pages/user.php" class="btn btn-outline">
                    <i class="fa-solid fa-user"></i> <?php echo __('my-dashboard'); ?>
                </a>
            <?php endif; ?>
        <?php else: ?>
            <a href="/barangay-residence-system/pages/login.php" class="btn btn-outline">
                <i class="fas fa-sign-in-alt"></i> <?php echo __('login'); ?>
            </a>
        <?php endif; ?>
    </div>
</nav>