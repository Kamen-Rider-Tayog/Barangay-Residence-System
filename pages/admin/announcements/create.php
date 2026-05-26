<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $created_by = $_SESSION['admin_id'];
    
    if (empty($title) || empty($content)) {
        $error = 'Title and content are required.';
    } else {
        $sql = "INSERT INTO announcements (title, content, created_by) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $created_by);
        
        if ($stmt->execute()) {
            // Redirect to dashboard with success message
            $_SESSION['flash_message'] = 'Announcement created successfully!';
            $_SESSION['flash_type'] = 'success';
            header('Location: /barangay-residence-system/pages/dashboard.php?tab=announcements');
            exit();
        } else {
            $error = 'Failed to create announcement.';
        }
    }
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card form-container">
        <div class="card-header">
            <h2>Create New Announcement</h2>
            <a href="/barangay-residence-system/pages/dashboard.php?tab=announcements" class="btn btn-outline">Back to Dashboard</a>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="announcement-form">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Content *</label>
                    <textarea name="content" class="form-input" rows="8" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Announcement</button>
                    <a href="/barangay-residence-system/pages/dashboard.php?tab=announcements" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>