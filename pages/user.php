<?php
require_once '../includes/init.php';
requireLogin();

if (!isResident()) {
    header('Location: /barangay-residence-system/pages/login.php');
    exit();
}

$household_id = $_SESSION['household_id'];
$household = getHouseholdById($household_id);
$residents = getResidentsByHouseholdId($household_id);
$requests = getServiceRequestsByHouseholdId($household_id);
$complaints = getComplaintsByHouseholdId($household_id);

include '../includes/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/user.css">
<?php include '../includes/navbar.php'; ?>

<main class="container">
    <h2><?php echo __('profile-title'); ?></h2>
    
    <div class="grid" style="grid-template-columns: 1fr 3fr; gap: 2rem;">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="card profile-card">
                <div class="profile-avatar">
                    <span><?php echo strtoupper(substr($residents[0]['first_name'] ?? 'U', 0, 1)); ?></span>
                </div>
                <h4 class="profile-name"><?php echo $residents[0]['first_name'] . ' ' . $residents[0]['last_name']; ?></h4>
                <p class="profile-email"><?php echo $household['email']; ?></p>
                
                <div class="profile-details">
                    <div class="detail-item">
                        <i class="fas fa-envelope"></i>
                        <div><p class="detail-label">Email</p><p class="detail-value"><?php echo $household['email']; ?></p></div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-phone"></i>
                        <div><p class="detail-label">Contact</p><p class="detail-value"><?php echo $residents[0]['contact_no'] ?? 'N/A'; ?></p></div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div><p class="detail-label">Address</p><p class="detail-value"><?php echo $household['address']; ?></p></div>
                    </div>
                </div>
                
                <div class="logout-sidebar">
                    <a href="/barangay-residence-system/includes/logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="card">
            <div class="tab-header">
                <div class="tabs">
                    <button class="tab-btn active" onclick="switchTab('transactions')">Transactions</button>
                    <button class="tab-btn" onclick="switchTab('complaints')">Complaints</button>
                </div>
                <button class="btn btn-primary" onclick="location.href='services.php'">Request Service</button>
            </div>
            
            <div id="transactions" class="tab-content active">
                <?php foreach ($requests as $req): ?>
                <div class="transaction-item">
                    <div class="item-header">
                        <div class="item-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="item-info">
                            <h5 class="item-title"><?php echo $req['service_name']; ?></h5>
                            <p class="item-ref">Ref: <?php echo $req['ref_no']; ?></p>
                        </div>
                        <span class="badge badge-<?php echo $req['status'] == 'completed' ? 'green' : 'orange'; ?>"><?php echo $req['status']; ?></span>
                    </div>
                    <div class="item-details">
                        <div><p class="detail-header">Date</p><p><?php echo date('M d, Y', strtotime($req['date_submitted'])); ?></p></div>
                        <div><p class="detail-header">Amount</p><p>₱<?php echo $req['total_amount']; ?></p></div>
                        <div><p class="detail-header">Payment</p><p><?php echo $req['is_paid'] ? 'Paid' : 'Unpaid'; ?></p></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($requests)): ?>
                <div class="no-data">No transactions found.</div>
                <?php endif; ?>
            </div>
            
            <div id="complaints" class="tab-content">
                <?php foreach ($complaints as $comp): ?>
                <div class="complaint-item">
                    <div class="item-header">
                        <div class="item-icon"><i class="fas fa-exclamation-circle"></i></div>
                        <div class="item-info">
                            <h5 class="item-title"><?php echo $comp['subject']; ?></h5>
                            <p class="item-ref">Ref: <?php echo $comp['ref_no']; ?></p>
                        </div>
                        <span class="badge badge-<?php echo $comp['status'] == 'resolved' ? 'green' : 'orange'; ?>"><?php echo $comp['status']; ?></span>
                    </div>
                    <div class="item-details">
                        <div><p class="detail-header">Category</p><p><?php echo $comp['category']; ?></p></div>
                        <div><p class="detail-header">Priority</p><p><?php echo $comp['priority']; ?></p></div>
                        <div><p class="detail-header">Date</p><p><?php echo date('M d, Y', strtotime($comp['date_submitted'])); ?></p></div>
                    </div>
                    <?php if ($comp['admin_response']): ?>
                    <div class="complaint-response">
                        <strong>Admin Response:</strong> <?php echo $comp['admin_response']; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php if (empty($complaints)): ?>
                <div class="no-data">No complaints filed.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Complaint Modal -->
<div id="complaint-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3>File a Complaint</h3>
            <button class="close-modal" onclick="toggleComplaintModal(false)">&times;</button>
        </div>
        <form id="complaintForm">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <input type="text" id="complaintSubject" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select id="complaintCategory" class="form-input" required>
                        <option value="">Select Category</option>
                        <option value="infrastructure">Infrastructure</option>
                        <option value="peace_order">Peace & Order</option>
                        <option value="sanitation">Sanitation</option>
                        <option value="noise">Noise Complaint</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select id="complaintPriority" class="form-input" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="complaintDescription" class="form-input" rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="toggleComplaintModal(false)">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Complaint</button>
            </div>
        </form>
    </div>
</div>
<script src="/barangay-residence-system/assets/js/pages/user.js"></script>