<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'] ?? 0;
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content, created_by) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Announcement added successfully!';
            header('refresh:2;url=index.php');
        } else {
            $error = 'Failed to add announcement.';
        }
    }
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<style>
.form-section {
    margin-bottom: 2rem;
}
</style>
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card form-container">
        <div class="card-header">
            <h2>Add New Announcement</h2>
            <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">Back to Announcements</a>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-alert"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="announcement-form">
                <div class="form-section">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-input" rows="10" required></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Announcement</button>
                    <a href="index.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>