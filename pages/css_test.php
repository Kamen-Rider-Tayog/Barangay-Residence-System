<?php
echo "<h2>CSS File Check</h2>";
echo "<hr>";

$css_files = [
    '/assets/css/main.css',
    '/assets/css/components.css',
    '/assets/css/responsive.css',
    '/assets/css/pages/index.css',
    '/assets/css/pages/login.css',
    '/assets/css/pages/services.css',
    '/assets/css/pages/user.css',
    '/assets/css/pages/dashboard.css'
];

$base_path = 'C:/wamp64/www/barangay-residence-system';

foreach ($css_files as $file) {
    $full_path = $base_path . $file;
    if (file_exists($full_path)) {
        echo "✅ " . $file . " - EXISTS<br>";
    } else {
        echo "❌ " . $file . " - MISSING<br>";
    }
}
?>