<?php
$page_title = 'Home';
$page_css = 'index.css';
$page_js = 'index.js';
require_once '../includes/init.php';
$stats = getDashboardStats();
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main>
    <section class="hero">
        <div class="container">
            <h2><?php echo __('hero-title'); ?></h2>
            <p><?php echo __('hero-desc'); ?></p>
            <div class="flex" style="gap: 1rem; justify-content: center;">
                <a href="services.php" class="btn btn-primary"><?php echo __('btn-request'); ?></a>
                <a href="#" class="btn btn-outline"><?php echo __('btn-learn'); ?></a>
            </div>
        </div>
    </section>

    <div class="container" style="margin-top: -3rem;">
        <div class="grid grid-cols-4">
            <div class="card card-hover" style="padding: 1.5rem; text-align: center;">
                <h3><?php echo $stats['total_residents'] ?? '0'; ?></h3>
                <p><?php echo __('stat-residents'); ?></p>
            </div>
            <div class="card card-hover" style="padding: 1.5rem; text-align: center;">
                <h3><?php echo $stats['total_households'] ?? '0'; ?></h3>
                <p><?php echo __('stat-households'); ?></p>
            </div>
            <div class="card card-hover" style="padding: 1.5rem; text-align: center;">
                <h3><?php echo $stats['total_voters'] ?? '0'; ?></h3>
                <p><?php echo __('stat-voters'); ?></p>
            </div>
            <div class="card card-hover" style="padding: 1.5rem; text-align: center;">
                <h3><?php echo $stats['total_services'] ?? '0'; ?></h3>
                <p><?php echo __('stat-services'); ?></p>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>