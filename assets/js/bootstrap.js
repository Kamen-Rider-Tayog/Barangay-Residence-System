// Bootstrap JS - Loads all required JavaScript files dynamically
(function() {
    const scripts = [
        '/barangay-residence-system/assets/js/utils/helpers.js',
        '/barangay-residence-system/assets/js/utils/api.js',
        '/barangay-residence-system/assets/js/components.js',
        '/barangay-residence-system/assets/js/main.js',
    ];
    
    // Detect which page we're on to load page-specific JS
    const path = window.location.pathname;
    let pageScript = '';
    
    if (path.includes('index.php') || path === '/barangay-residence-system/pages/') {
        pageScript = '/barangay-residence-system/assets/js/pages/index.js';
    } else if (path.includes('login.php')) {
        pageScript = '/barangay-residence-system/assets/js/pages/login.js';
    } else if (path.includes('services.php')) {
        pageScript = '/barangay-residence-system/assets/js/pages/services.js';
    } else if (path.includes('user.php')) {
        pageScript = '/barangay-residence-system/assets/js/pages/user.js';
    } else if (path.includes('dashboard.php')) {
        pageScript = '/barangay-residence-system/assets/js/pages/dashboard.js';
    } else if (path.includes('campaigns.php')) {
        pageScript = '/barangay-residence-system/assets/js/pages/campaigns.js';
    }
    
    if (pageScript) {
        scripts.push(pageScript);
    }
    
    // Load scripts sequentially
    function loadScript(index) {
        if (index >= scripts.length) return;
        
        const script = document.createElement('script');
        script.src = scripts[index];
        script.onload = function() {
            loadScript(index + 1);
        };
        script.onerror = function() {
            console.error('Failed to load script:', scripts[index]);
            loadScript(index + 1);
        };
        document.head.appendChild(script);
    }
    
    loadScript(0);
})();

// Language dropdown toggle
document.addEventListener('DOMContentLoaded', function() {
    const langBtn = document.getElementById('langBtn');
    const langMenu = document.getElementById('langMenu');
    const langArrow = document.getElementById('langArrow');
    
    if (langBtn && langMenu) {
        langBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = langMenu.classList.contains('show');
            langMenu.classList.toggle('show');
            if (langArrow) {
                langArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
        
        document.addEventListener('click', function() {
            if (langMenu.classList.contains('show')) {
                langMenu.classList.remove('show');
                if (langArrow) {
                    langArrow.style.transform = 'rotate(0deg)';
                }
            }
        });
    }
});

function changeLanguage(lang) {
    window.location.href = '?lang=' + lang;
}

// Hash navigation for dashboard tabs
function setupHashNavigation() {
    if (typeof loadTab !== 'function') {
        setTimeout(setupHashNavigation, 100);
        return;
    }
    
    var originalLoadTab = loadTab;
    
    window.loadTab = function(tab) {
        window.location.hash = tab;
        originalLoadTab(tab);
    };
    
    var savedHash = window.location.hash.substring(1);
    var validTabs = ['reports', 'households', 'services', 'complaints', 'announcements'];
    
    if (savedHash && validTabs.includes(savedHash)) {
        setTimeout(function() {
            loadTab(savedHash);
        }, 50);
    }
}

setupHashNavigation();