<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/translations.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['login_time'] = time();

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'tl';
}

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if ($lang === 'en' || $lang === 'tl') {
        $_SESSION['lang'] = $lang;
        if (isset($_SESSION['household_id'])) {
            $updateLang = $conn->prepare("UPDATE household SET preferred_language = ? WHERE household_id = ?");
            $updateLang->bind_param("si", $lang, $_SESSION['household_id']);
            $updateLang->execute();
        }
    }
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirectWithMessage($url, $message, $type = 'error') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: " . $url);
    exit();
}

function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'error';
        $class = ($type === 'success') ? 'alert-success' : 'alert-error';
        echo '<div class="' . $class . '">' . htmlspecialchars($_SESSION['flash_message']) . '</div>';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}

if (!$conn->ping()) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
}
?>