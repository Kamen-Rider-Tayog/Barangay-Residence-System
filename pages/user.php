<?php
require_once '../includes/core/init.php';
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

include '../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/user.css">
<?php include '../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="dashboard-header">
        <div>
            <h2><?php echo __('profile-title'); ?></h2>
            <p>View your transactions and complaints</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div class="nav-buttons">
                <button id="nav-transactions" class="btn-nav btn-active" onclick="switchTab('transactions')">
                    <i class="fas fa-file-alt"></i> Transactions
                </button>
                <button id="nav-complaints" class="btn-nav btn-inactive" onclick="switchTab('complaints')">
                    <i class="fas fa-comment-dots"></i> Complaints
                    <?php if (count($complaints) > 0): ?>
                    <span class="badge-red"><?php echo count($complaints); ?></span>
                    <?php endif; ?>
                </button>
            </div>
            <a href="/barangay-residence-system/includes/core/logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="grid" style="grid-template-columns: 1fr 3fr; gap: 2rem;">
        <!-- Sidebar Profile -->
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
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="card">
            <div id="section-transactions">
                <div class="table-header">
                    <h3><i class="fas fa-file-alt"></i> My Transactions</h3>
                    <button class="btn btn-primary" onclick="location.href='services.php'">
                        <i class="fas fa-plus"></i> Request Service
                    </button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Reference No.</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $req): ?>
                            <tr>
                                <td><?php echo $req['service_name']; ?></td>
                                <td><?php echo $req['ref_no']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($req['date_submitted'])); ?></td>
                                <td>₱<?php echo $req['total_amount']; ?></td>
                                <td><span class="badge badge-<?php echo $req['status'] == 'completed' ? 'green' : 'orange'; ?>"><?php echo $req['status']; ?></span></td>
                                <td><span class="badge badge-<?php echo $req['is_paid'] ? 'green' : 'red'; ?>"><?php echo $req['is_paid'] ? 'Paid' : 'Unpaid'; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($requests)): ?>
                            <tr><td colspan="6" class="no-data">No transactions found. <a href="services.php">Request a service</a></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="section-complaints" class="hidden-section">
                <div class="table-header">
                    <h3><i class="fas fa-comment-dots"></i> My Complaints</h3>
                    <button class="btn btn-primary" onclick="toggleComplaintModal(true)">
                        <i class="fas fa-plus"></i> File Complaint
                    </button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Reference No.</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($complaints as $comp): ?>
                            <tr>
                                <td><?php echo $comp['subject']; ?></td>
                                <td><?php echo $comp['ref_no']; ?></td>
                                <td><?php echo ucfirst($comp['category']); ?></td>
                                <td><span class="badge badge-<?php echo $comp['priority'] == 'high' ? 'red' : ($comp['priority'] == 'medium' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($comp['priority']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($comp['date_submitted'])); ?></td>
                                <td><span class="badge badge-<?php echo $comp['status'] == 'resolved' ? 'green' : 'orange'; ?>"><?php echo ucfirst($comp['status']); ?></span></td>
                            </tr>
                            <?php if ($comp['admin_response']): ?>
                            <tr class="response-row">
                                <td colspan="6">
                                    <div class="complaint-response">
                                        <strong>Admin Response:</strong> <?php echo $comp['admin_response']; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (empty($complaints)): ?>
                            <tr><td colspan="6" class="no-data">No complaints filed. <a href="#" onclick="toggleComplaintModal(true)">File a complaint</a></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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