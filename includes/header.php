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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/main.css">
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/components.css">
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/navbar.css">
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/footer.css">
    <link rel="stylesheet" href="/barangay-residence-system/assets/css/responsive.css">
</head>
<body>

<?php
$topAnnouncement = getTopAnnouncement();
$showAnnouncement = !isset($_COOKIE['announcement_closed']) || $_COOKIE['announcement_closed'] != $topAnnouncement['campaign_id'];
if ($topAnnouncement && $showAnnouncement):
?>
<div id="topAnnouncementBar" class="top-announcement-bar">
    <div class="container">
        <div class="top-announcement-content">
            <i class="fas fa-bullhorn"></i>
            <span><strong><?php echo htmlspecialchars($topAnnouncement['title']); ?></strong> - <?php echo htmlspecialchars($topAnnouncement['description']); ?></span>
            <a href="/barangay-residence-system/pages/campaigns.php" class="announcement-link">Learn More <i class="fas fa-arrow-right"></i></a>
            <button class="close-announcement" onclick="closeAnnouncement(<?php echo $topAnnouncement['campaign_id']; ?>)" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
<?php endif; ?>