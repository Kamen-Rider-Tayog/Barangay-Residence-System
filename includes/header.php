<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? 'tl'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> | <?php echo $page_title ?? 'Management System'; ?></title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS - Absolute Paths -->
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/main.css">
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/components.css">
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/responsive.css">
    
    <!-- Page Specific CSS -->
    <?php if (isset($page_css)): ?>
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/<?php echo $page_css; ?>">
    <?php endif; ?>
</head>
<body>