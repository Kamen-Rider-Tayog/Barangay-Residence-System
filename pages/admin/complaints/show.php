<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$complaint_id = $_GET['id'] ?? 0;
if (!$complaint_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

$sql = "SELECT c.*, h.email, r.first_name, r.last_name 
        FROM complaint c
        JOIN household h ON c.household_id = h.household_id
        LEFT JOIN resident r ON h.household_id = r.household_id AND r.is_head = 1
        WHERE c.complaint_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$complaint = $stmt->get_result()->fetch_assoc();

if (!$complaint) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card">
        <div class="card-header">
            <h2>Complaint Details</h2>
            <div>
                <button onclick="openRespondModal(<?php echo $complaint['complaint_id']; ?>)" class="btn btn-primary">Respond</button>
                <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">Back to Dashboard</a>
            </div>
        </div>
        <div class="card-body">
            <div class="detail-section">
                <h3>Complaint Information</h3>
                <table class="detail-table">
                    <tr><th>Reference No:</th><td><?php echo htmlspecialchars($complaint['ref_no']); ?></td></tr>
                    <tr><th>Subject:</th><td><?php echo htmlspecialchars($complaint['subject']); ?></td></tr>
                    <tr><th>Description:</th><td><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></td></tr>
                    <tr><th>Category:</th><td><?php echo ucfirst($complaint['category']); ?></td></tr>
                    <tr><th>Priority:</th><td><span class="badge badge-<?php echo $complaint['priority'] == 'high' ? 'red' : ($complaint['priority'] == 'medium' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($complaint['priority']); ?></span></td></tr>
                    <tr><th>Status:</th><td><span class="badge badge-<?php echo $complaint['status'] == 'resolved' ? 'green' : ($complaint['status'] == 'pending' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($complaint['status']); ?></span></td></tr>
                    <tr><th>Date Submitted:</th><td><?php echo date('F d, Y h:i A', strtotime($complaint['date_submitted'])); ?></td></tr>
                </table>
            </div>
            
            <div class="detail-section">
                <h3>Resident Information</h3>
                <table class="detail-table">
                    <tr><th>Name:</th><td><?php echo htmlspecialchars($complaint['first_name'] . ' ' . $complaint['last_name']); ?></td></tr>
                    <tr><th>Email:</th><td><?php echo htmlspecialchars($complaint['email']); ?></td></tr>
                </table>
            </div>
            
            <?php if ($complaint['admin_response']): ?>
            <div class="detail-section">
                <h3>Admin Response</h3>
                <div class="response-box">
                    <p><?php echo nl2br(htmlspecialchars($complaint['admin_response'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Respond Modal -->
<div id="respondModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Respond to Complaint</h3>
            <button class="close-modal" onclick="closeRespondModal()">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="respond-complaint-id">
            <div class="form-group">
                <label class="form-label">Update Status</label>
                <select id="respond-status" class="form-input">
                    <option value="pending">Pending</option>
                    <option value="reviewing">Reviewing</option>
                    <option value="resolved">Resolved</option>
                    <option value="dismissed">Dismissed</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Admin Response</label>
                <textarea id="respond-response" class="form-input" rows="5" placeholder="Enter your response..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="closeRespondModal()">Cancel</button>
            <button type="button" class="btn btn-primary" id="submitRespondBtn">Submit Response</button>
        </div>
    </div>
</div>

<script>
function openRespondModal(complaintId) {
    fetch('/barangay-residence-system/pages/api/get_complaint.php?id=' + complaintId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('respond-complaint-id').value = complaintId;
            document.getElementById('respond-status').value = data.status;
            document.getElementById('respond-response').value = data.admin_response || '';
            document.getElementById('respondModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('respondModal').classList.add('active'), 10);
        });
}

function closeRespondModal() {
    const modal = document.getElementById('respondModal');
    modal.classList.remove('active');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

document.getElementById('submitRespondBtn').addEventListener('click', function() {
    const complaintId = document.getElementById('respond-complaint-id').value;
    const status = document.getElementById('respond-status').value;
    const response = document.getElementById('respond-response').value;
    
    const formData = new FormData();
    formData.append('complaint_id', complaintId);
    formData.append('status', status);
    formData.append('response', response);
    
    fetch('/barangay-residence-system/pages/api/update_complaint.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeRespondModal();
            location.reload();
        } else {
            alert('Error updating complaint');
        }
    });
});
</script>

<?php include '../../../includes/layouts/footer.php'; ?>