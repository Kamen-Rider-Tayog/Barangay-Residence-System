<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$announcement_id = $_GET['id'] ?? 0;
if (!$announcement_id) {
    header('Location: index.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM announcements WHERE announcement_id = ?");
$stmt->bind_param("i", $announcement_id);
$stmt->execute();
$announcement = $stmt->get_result()->fetch_assoc();

if (!$announcement) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $conn->prepare("UPDATE announcements SET title = ?, content = ? WHERE announcement_id = ?");
        $stmt->bind_param("ssi", $title, $content, $announcement_id);
        
        if ($stmt->execute()) {
            $success = 'Announcement updated successfully!';
            header('refresh:2;url=index.php');
        } else {
            $error = 'Failed to update announcement.';
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
            <h2>Edit Announcement</h2>
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
                        <input type="text" name="title" class="form-input" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-input" rows="10" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="index.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>