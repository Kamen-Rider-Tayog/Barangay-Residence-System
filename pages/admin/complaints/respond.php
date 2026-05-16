<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$complaint_id = $_GET['id'] ?? 0;
if (!$complaint_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

$sql = "SELECT * FROM complaint WHERE complaint_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$complaint = $stmt->get_result()->fetch_assoc();

if (!$complaint) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? 'pending';
    $response = $_POST['response'] ?? '';
    
    $result = updateComplaintResponse($complaint_id, $status, $response);
    
    if ($result !== false) {
        $success = 'Response submitted successfully!';
        $complaint['status'] = $status;
        $complaint['admin_response'] = $response;
    } else {
        $error = 'Failed to submit response.';
    }
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card">
        <div class="card-header">
            <h2>Respond to Complaint</h2>
            <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">Back to Dashboard</a>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-alert"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="detail-section">
                <h3>Complaint Details</h3>
                <table class="detail-table">
                    <tr><th>Reference No:</th><td><?php echo htmlspecialchars($complaint['ref_no']); ?></td></tr>
                    <tr><th>Subject:</th><td><?php echo htmlspecialchars($complaint['subject']); ?></td></tr>
                    <tr><th>Description:</th><td><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></td></tr>
                    <tr><th>Current Status:</th><td><span class="badge badge-<?php echo $complaint['status'] == 'resolved' ? 'green' : ($complaint['status'] == 'pending' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($complaint['status']); ?></span></td></tr>
                </table>
            </div>
            
            <form method="POST">
                <div class="form-section">
                    <h3>Your Response</h3>
                    <div class="form-group">
                        <label class="form-label">Update Status</label>
                        <select name="status" class="form-input">
                            <option value="pending" <?php echo $complaint['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="reviewing" <?php echo $complaint['status'] == 'reviewing' ? 'selected' : ''; ?>>Reviewing</option>
                            <option value="resolved" <?php echo $complaint['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                            <option value="dismissed" <?php echo $complaint['status'] == 'dismissed' ? 'selected' : ''; ?>>Dismissed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Admin Response</label>
                        <textarea name="response" class="form-input" rows="6" placeholder="Enter your response..."><?php echo htmlspecialchars($complaint['admin_response'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit Response</button>
                    <a href="/barangay-residence-system/pages/dashboard.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>