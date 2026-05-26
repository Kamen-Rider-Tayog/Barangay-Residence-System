<?php
if (isset($_GET['reset_announcement'])) {
    setcookie('announcement_closed', '', time() - 3600, '/');
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? 'tl'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay San Francisco</title>
    
    <link rel="shortcut icon" href="/barangay-residence-system/assets/images/logo.ico" type="image/x-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.css">
    
    <!-- Single CSS file that imports everything -->
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/main.css">
</head>
<body>