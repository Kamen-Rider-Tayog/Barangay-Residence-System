<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$announcement_id = $_GET['id'] ?? 0;
if (!$announcement_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=announcements');
    exit();
}

$sql = "SELECT * FROM announcements WHERE announcement_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $announcement_id);
$stmt->execute();
$announcement = $stmt->get_result()->fetch_assoc();

if (!$announcement) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=announcements');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($title) || empty($content)) {
        $error = __('title-content-required');
    } else {
        $sql = "UPDATE announcements SET title = ?, content = ? WHERE announcement_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $announcement_id);
        
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = __('announcement-updated');
            $_SESSION['flash_type'] = 'success';
            header('Location: /barangay-residence-system/pages/dashboard.php?tab=announcements');
            exit();
        } else {
            $error = __('announcement-update-failed');
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
            <h2><?php echo __('edit-announcement'); ?></h2>
            <a href="/barangay-residence-system/pages/dashboard.php?tab=announcements" class="btn btn-outline"><?php echo __('back-to-dashboard'); ?></a>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="announcement-form">
                <div class="form-group">
                    <label class="form-label"><?php echo __('title'); ?> *</label>
                    <input type="text" name="title" class="form-input" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label"><?php echo __('content'); ?> *</label>
                    <textarea name="content" class="form-input" rows="8" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><?php echo __('save-changes'); ?></button>
                    <a href="/barangay-residence-system/pages/dashboard.php?tab=announcements" class="btn btn-outline"><?php echo __('cancel'); ?></a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>