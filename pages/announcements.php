<?php
require_once '../includes/core/init.php';

include '../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/announcements.css">
<?php include '../includes/layouts/navbar.php'; ?>

<main>
    <div class="container">
        <div class="announcements-header">
            <h1>Latest Announcements</h1>
            <p>Stay updated with the latest barangay announcements and news</p>
        </div>

        <div class="announcements-list">
            <?php
            $stmt = $conn->prepare("SELECT * FROM announcements ORDER BY created_at DESC");
            $stmt->execute();
            $announcements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            if (empty($announcements)): ?>
                <div class="no-announcements">
                    <i class="fas fa-bullhorn"></i>
                    <p>No announcements yet. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $a): ?>
                    <div class="announcement-card">
                        <div class="announcement-date">
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo date('F d, Y', strtotime($a['created_at'])); ?></span>
                        </div>
                        <h3><?php echo htmlspecialchars($a['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($a['content'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../includes/layouts/footer.php'; ?>