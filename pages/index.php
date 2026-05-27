<?php
require_once '../includes/core/init.php';
$stats = getDashboardStats();

// Get latest 3 announcements
$announcementStmt = $conn->prepare("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3");
$announcementStmt->execute();
$latestAnnouncements = $announcementStmt->get_result()->fetch_all(MYSQLI_ASSOC);

include '../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/index.css">
<?php include '../includes/layouts/navbar.php'; ?>

<main>
    <section class="hero">
        <div class="container">
            <h2><?php echo __('hero-title'); ?></h2>
            <p><?php echo __('hero-desc'); ?></p>
            <div class="flex" style="gap: 1rem; justify-content: center;">
                <a href="services.php" class="btn btn-primary-hero"><?php echo __('btn-request'); ?></a>
                <a href="announcements.php" class="btn btn-outline-hero"><?php echo __('btn-learn'); ?></a>
            </div>
        </div>
    </section>

    <div class="container stats-section">
        <div class="grid grid-cols-4">
            <div class="card card-hover stat-card">
                <div class="stat-icon bg-blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <p><?php echo __('stat-residents'); ?></p>
                    <p class="stat-number"><?php echo $stats['total_residents'] ?? '0'; ?></p>
                </div>
            </div>
            <div class="card card-hover stat-card">
                <div class="stat-icon bg-green">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-info">
                    <p><?php echo __('stat-households'); ?></p>
                    <p class="stat-number"><?php echo $stats['total_households'] ?? '0'; ?></p>
                </div>
            </div>
            <div class="card card-hover stat-card">
                <div class="stat-icon bg-purple">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <p><?php echo __('stat-voters'); ?></p>
                    <p class="stat-number"><?php echo $stats['total_voters'] ?? '0'; ?></p>
                </div>
            </div>
            <div class="card card-hover stat-card">
                <div class="stat-icon bg-orange">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <p><?php echo __('stat-services'); ?></p>
                    <p class="stat-number"><?php echo $stats['total_services'] ?? '0'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- DYNAMIC ANNOUNCEMENTS SECTION -->
    <section class="announcement-section">
        <div class="container">
            <div class="section-title">
                <div class="section-title-div">
                <i class="fas fa-bullhorn"></i>
                <h3><?php echo __('announcement-title'); ?></h3>
                </div>
                <a href="announcements.php" class="btn-outline">
                    <?php echo __('view-all'); ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="grid grid-cols-3">
                <?php if (empty($latestAnnouncements)): ?>
                    <div class="card announcement-card" style="grid-column: span 3; text-align: center;">
                        <div class="announcement-date">
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo __('no-announcements'); ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($latestAnnouncements as $announcement): ?>
                    <div class="card card-hover announcement-card">
                        <div class="announcement-date">
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo date('F d, Y', strtotime($announcement['created_at'])); ?></span>
                        </div>
                        <h4><?php echo htmlspecialchars($announcement['title']); ?></h4>
                        <p><?php echo htmlspecialchars(substr($announcement['content'], 0, 120)) . (strlen($announcement['content']) > 120 ? '...' : ''); ?></p>
                        <a href="announcements.php" class="read-more"><?php echo __('read-more'); ?> <i class="fas fa-chevron-right"></i></a>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- BARANGAY OFFICIALS SECTION (USING FONT AWESOME ICONS) -->
    <section class="officials-section">
        <div class="container">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <h3><?php echo __('officials-title'); ?></h3>
            </div>
            <div class="officials-grid">
                <div class="card official-card">
                    <div class="official-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4><?php echo __('official-captain'); ?></h4>
                    <p class="official-name">Jenny Lyn Roquid</p>
                </div>
                <div class="card official-card">
                    <div class="official-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h4><?php echo __('official-chairperson'); ?></h4>
                    <p class="official-name">Chlarenz Togueño</p>
                </div>
                <div class="card official-card">
                    <div class="official-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4><?php echo __('official-secretary'); ?></h4>
                    <p class="official-name">Rhea Mae Gregorio</p>
                </div>
                <div class="card official-card">
                    <div class="official-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h4><?php echo __('official-treasurer'); ?></h4>
                    <p class="official-name">Kate Ashly Baldonado</p>
                </div>
                <div class="card official-card">
                    <div class="official-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4><?php echo __('official-sk'); ?></h4>
                    <p class="official-name">Cristina Mariano</p>
                </div>
                <div class="card official-card">
                    <div class="official-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4><?php echo __('official-tanod'); ?></h4>
                    <p class="official-name">Tayog</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section container">
        <div class="about-content">
            <h3><?php echo __('about-title'); ?></h3>
            <p><?php echo __('about-p1'); ?></p>
            <div class="about-stats">
                <div class="about-stat">
                    <p class="number">30+</p>
                    <p class="label"><?php echo __('about-years'); ?></p>
                </div>
                <div class="about-stat">
                    <p class="number">100%</p>
                    <p class="label"><?php echo __('about-digital'); ?></p>
                </div>
            </div>
        </div>
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3866.52622765275!2d120.923485!3d14.33128!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d446a6f11221%3A0x768c7873273e34b9!2sSan%20Francisco%2C%20General%20Trias%2C%20Cavite!5e0!3m2!1sen!2sph!4v1700000000000" allowfullscreen="" loading="lazy"></iframe>
            <div class="map-address">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo __('map-address'); ?></span>
            </div>
        </div>
    </section>
</main>

<?php include '../includes/layouts/footer.php'; ?>