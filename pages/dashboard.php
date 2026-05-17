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
            <h1>Admin Dashboard</h1>
            <p>Manage households, services, and monitor barangay activities</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div class="nav-buttons">
                <button id="nav-reports" class="btn-nav btn-active" onclick="loadTab('reports')">
                    <i class="fas fa-chart-line"></i> Overview
                </button>
                <button id="nav-households" class="btn-nav btn-inactive" onclick="loadTab('households')">
                    <i class="fas fa-home"></i> Households
                </button>
                <button id="nav-services" class="btn-nav btn-inactive" onclick="loadTab('services')">
                    <i class="fas fa-cogs"></i> Services
                </button>
                <button id="nav-complaints" class="btn-nav btn-inactive" onclick="loadTab('complaints')">
                    <i class="fas fa-comment-dots"></i> Complaints
                </button>
            </div>
            <a href="/barangay-residence-system/includes/core/logout.php" class="btn btn-outline" style="background: var(--error-red); color: var(--white); border: none;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Content Container -->
    <div id="tabContent" class="tab-content-container">
        <div class="loading-spinner">Loading...</div>
    </div>
</main>

<!-- Delete Confirmation Modal (Reusable) -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <div class="modal-header">
            <h3 id="deleteModalTitle">Delete Item</h3>
            <button class="close-modal" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete <strong id="deleteItemName"></strong>?</p>
            <p class="text-muted" id="deleteWarningText" style="font-size: 0.75rem; color: var(--gray-500);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button type="button" class="btn" onclick="closeDeleteModal()">Cancel</button>
            <a href="#" id="deleteConfirmLink" class="btn btn-danger">Delete</a>
        </div>
    </div>
</div>

<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>
