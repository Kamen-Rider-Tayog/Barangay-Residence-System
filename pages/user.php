<?php
$page_title = 'My Profile';
$page_css = 'user.css';
$page_js = 'user.js';
require_once '../includes/init.php';
requireLogin();

if (!isResident()) {
    redirect(SITE_URL . '/pages/login.php');
}

// Get user data from session
$household_id = $_SESSION['household_id'];
$household = getHouseholdById($household_id);
$residents = getResidentsByHouseholdId($household_id);
$requests = getServiceRequestsByHouseholdId($household_id);
$complaints = getComplaintsByHouseholdId($household_id);

include '../includes/header.php';
include '../includes/navbar.php';
?>

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
                        <div class="detail-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
                        <div><p class="detail-label">Email</p><p class="detail-value"><?php echo $household['email']; ?></p></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></div>
                        <div><p class="detail-label">Contact</p><p class="detail-value"><?php echo $residents[0]['contact_no'] ?? 'N/A'; ?></p></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                        <div><p class="detail-label">Address</p><p class="detail-value"><?php echo $household['address']; ?></p></div>
                    </div>
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
                        <div class="item-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
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
            </div>
            
            <div id="complaints" class="tab-content">
                <?php foreach ($complaints as $comp): ?>
                <div class="complaint-item">
                    <div class="item-header">
                        <div class="item-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r="0.5"/></svg></div>
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
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>