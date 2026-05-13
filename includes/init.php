<?php
// ============================================
// INIT.PHP - Bootstrap file
// Follows finals guidelines:
// - Secure database connection via config.php
// - Prepared statements ready
// - Session management
// - Error handling ready
// ============================================

// Load configuration and functions
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// ============================================
// ERROR REPORTING (Development only)
// ============================================
// Turn off in production, but enabled for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================
// SESSION CONFIGURATION
// ============================================
// Session already started in config.php
// Set session timeout (30 minutes)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['login_time'] = time();

// ============================================
// LANGUAGE SETTINGS
// ============================================
// Default language: Tagalog
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'tl';
}

// Change language from URL parameter
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    // Validate language to prevent injection
    if ($lang === 'en' || $lang === 'tl') {
        $_SESSION['lang'] = $lang;
        
        // If user is logged in, update preference in database
        if (isset($_SESSION['household_id'])) {
            $updateLang = $conn->prepare("UPDATE household SET preferred_language = ? WHERE household_id = ?");
            $updateLang->bind_param("si", $lang, $_SESSION['household_id']);
            $updateLang->execute();
        }
    }
}

// ============================================
// CSRF PROTECTION (for forms)
// ============================================
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================
// HELPER FUNCTIONS (Security)
// ============================================

/**
 * Redirect to URL with error/success message
 */
function redirectWithMessage($url, $message, $type = 'error') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: " . $url);
    exit();
}

/**
 * Display flash message
 */
function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'error';
        $class = ($type === 'success') ? 'alert-success' : 'alert-error';
        echo '<div class="' . $class . '">' . htmlspecialchars($_SESSION['flash_message']) . '</div>';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}

// ============================================
// DATABASE CONNECTION VERIFICATION
// ============================================
// Verify connection is still alive
if (!$conn->ping()) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
}
?>