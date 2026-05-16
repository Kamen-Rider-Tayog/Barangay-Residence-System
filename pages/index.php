<?php
require_once '../includes/core/init.php';
$stats = getDashboardStats();

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
                <a href="services.php" class="btn btn-primary"><?php echo __('btn-request'); ?></a>
                <a href="campaigns.php" class="btn btn-outline"><?php echo __('btn-learn'); ?></a>
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

    <section class="announcement-section">
        <div class="container">
            <div class="section-title">
                <i class="fas fa-bullhorn"></i>
                <h3><?php echo __('announcement-title'); ?></h3>
            </div>
            <div class="grid grid-cols-3">
                <div class="card card-hover announcement-card">
                    <div class="announcement-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>Mayo 15, 2026</span>
                    </div>
                    <h4><?php echo __('ann-1-title'); ?></h4>
                    <p><?php echo __('ann-1-desc'); ?></p>
                </div>
                <div class="card card-hover announcement-card">
                    <div class="announcement-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>Mayo 20, 2026</span>
                    </div>
                    <h4><?php echo __('ann-2-title'); ?></h4>
                    <p><?php echo __('ann-2-desc'); ?></p>
                </div>
                <div class="card card-hover announcement-card">
                    <div class="announcement-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>Mayo 25, 2026</span>
                    </div>
                    <h4><?php echo __('ann-3-title'); ?></h4>
                    <p><?php echo __('ann-3-desc'); ?></p>
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