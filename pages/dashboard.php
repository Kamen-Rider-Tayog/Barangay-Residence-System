<?php
require_once '../includes/core/init.php';
requireAdmin();
?>

<?php include '../includes/layouts/header.php'; ?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="dashboard-header">
        <div>
            <h1><?php echo __('admin-dashboard-title'); ?></h1>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div class="nav-buttons">
                <button id="nav-reports" class="btn-nav btn-active" onclick="loadTab('reports')">
                    <i class="fas fa-chart-line"></i> <?php echo __('tab-overview'); ?>
                </button>
                <button id="nav-households" class="btn-nav btn-inactive" onclick="loadTab('households')">
                    <i class="fas fa-home"></i> <?php echo __('tab-households'); ?>
                </button>
                <button id="nav-services" class="btn-nav btn-inactive" onclick="loadTab('services')">
                    <i class="fas fa-cogs"></i> <?php echo __('tab-services'); ?>
                </button>
                <button id="nav-complaints" class="btn-nav btn-inactive" onclick="loadTab('complaints')">
                    <i class="fas fa-comment-dots"></i> <?php echo __('tab-complaints'); ?>
                </button>
                <button id="nav-announcements" class="btn-nav btn-inactive" onclick="loadTab('announcements')">
                    <i class="fas fa-bullhorn"></i> <?php echo __('tab-announcements'); ?>
                </button>
            </div>
            <a href="/barangay-residence-system/includes/core/logout.php" class="btn btn-outline" style="background: var(--error-red); color: var(--white); border: none;">
                <i class="fas fa-sign-out-alt"></i> <?php echo __('logout'); ?>
            </a>
        </div>
    </div>
    
    <!-- Content Container -->
    <div id="tabContent" class="tab-content-container">
        <div class="loading-spinner"><?php echo __('loading'); ?>...</div>
    </div>
</main>

<!-- Delete Confirmation Modal (Reusable) -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <div class="modal-header">
            <h3 id="deleteModalTitle"><?php echo __('delete-item'); ?></h3>
            <button class="close-modal" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p><?php echo __('are-you-sure'); ?> <strong id="deleteItemName"></strong>?</p>
            <p class="text-muted" id="deleteWarningText" style="font-size: 0.75rem; color: var(--gray-500);"><?php echo __('delete-warning-general'); ?></p>
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button type="button" class="btn" onclick="closeDeleteModal()"><?php echo __('cancel'); ?></button>
            <a href="#" id="deleteConfirmLink" class="btn btn-danger"><?php echo __('delete'); ?></a>
        </div>
    </div>
</div>

<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>