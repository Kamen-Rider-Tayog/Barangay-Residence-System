<nav class="navbar">
    <a href="/barangay-residence-system/pages/index.php" class="navbar-logo">
        <div class="navbar-logo-img">
            <img src="/barangay-residence-system/assets/images/logo.png" alt="Logo">
        </div>
        <div>
            <h1><?php echo SITE_NAME; ?></h1>
            <p data-key="sub-header">Pamamahala ng Impormasyon at Serbisyo</p>
        </div>
    </a>
    
    <div class="navbar-links">
        <a href="/barangay-residence-system/pages/index.php" data-key="nav-home">Home</a>
        <a href="/barangay-residence-system/pages/services.php" data-key="nav-services">Serbisyong Online</a>
        
        <div class="lang-dropdown">
            <button id="langBtn" class="lang-btn">
                <span>🌐</span>
                <span id="currentLangLabel">Wika</span>
                <span>▼</span>
            </button>
            <div id="langMenu" class="lang-menu">
                <button onclick="changeLanguage('tl')">Tagalog</button>
                <button onclick="changeLanguage('en')">English</button>
            </div>
        </div>
        
        <?php if (isLoggedIn()): ?>
            <a href="/barangay-residence-system/includes/logout.php" data-key="logout">Logout</a>
        <?php else: ?>
            <a href="/barangay-residence-system/pages/login.php" class="btn btn-outline" data-key="login">Mag-login</a>
        <?php endif; ?>
    </div>
</nav>