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
        
        <div class="lang-dropdown">
            <button id="langBtn" class="lang-btn">
                <i class="fa-solid fa-language"></i>
                <span id="currentLangLabel"><?php echo __('language'); ?></span>
                <i id="langArrow" class="fa-solid fa-angle-down"></i>
            </button>
            <div id="langMenu" class="lang-menu">
                <button onclick="changeLanguage('tl')">Tagalog</button>
                <button onclick="changeLanguage('en')">English</button>
            </div>
        </div>
        
        <?php if (isLoggedIn()): ?>
            <a href="/barangay-residence-system/includes/logout.php"><?php echo __('logout'); ?></a>
        <?php else: ?>
            <a href="/barangay-residence-system/pages/login.php" class="btn btn-outline"><?php echo __('login'); ?></a>
        <?php endif; ?>
    </div>
</nav>