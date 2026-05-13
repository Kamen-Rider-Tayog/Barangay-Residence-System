<?php
$page_title = 'Admin Dashboard';
$page_css = 'dashboard.css';
$page_js = 'dashboard.js';
require_once '../includes/init.php';
requireAdmin();

$stats = getDashboardStats();
$complaints = getRows("SELECT c.*, r.first_name, r.last_name FROM complaint c JOIN resident r ON c.resident_id = r.resident_id ORDER BY c.date_submitted DESC");
$households = getRows("SELECT h.*, COUNT(r.resident_id) as member_count FROM household h LEFT JOIN resident r ON h.household_id = r.household_id GROUP BY h.household_id");

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container">
    <div class="dashboard-header">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Manage households and monitor barangay activities</p>
        </div>
        <div class="nav-buttons">
            <button id="nav-households" class="btn-nav btn-active" onclick="switchTab('households')">Households</button>
            <button id="nav-complaints" class="btn-nav btn-inactive" onclick="switchTab('complaints')">Complaints</button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon bg-blue">👥</div>
            <div><p class="stat-label">Total Residents</p><p class="stat-number"><?php echo $stats['total_residents']; ?></p></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon bg-green">🏠</div>
            <div><p class="stat-label">Households</p><p class="stat-number"><?php echo $stats['total_households']; ?></p></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon bg-purple">📄</div>
            <div><p class="stat-label">Transactions</p><p class="stat-number"><?php echo $stats['total_requests']; ?></p></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon bg-orange">💬</div>
            <div><p class="stat-label">Complaints</p><p class="stat-number"><?php echo $stats['pending_complaints']; ?></p></div>
        </div>
    </div>
    
    <!-- Households Section -->
    <div id="section-households">
        <div class="card">
            <div class="flex-between" style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">
                <span class="font-bold">Household Management</span>
                <button onclick="showAddForm()" class="btn btn-primary">+ Add Household</button>
            </div>
            
            <div class="search-bar">
                <div class="search-input">
                    <i>🔍</i>
                    <input type="text" id="searchInput" placeholder="Search name, email, contact..." onkeyup="filterTable()">
                </div>
                <select id="phaseFilter" onchange="filterTable()">
                    <option value="all">All Phases</option>
                    <option value="Phase 1">Phase 1</option>
                    <option value="Phase 2">Phase 2</option>
                </select>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr><th>Head of Household</th><th>Email</th><th>Contact</th><th>Phase</th><th>Members</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($households as $h): ?>
                        <tr>
                            <td><?php echo $h['first_name'] . ' ' . $h['last_name']; ?></td>
                            <td><?php echo $h['email']; ?></td>
                            <td><?php echo $h['contact_no']; ?></td>
                            <td><span class="badge badge-blue"><?php echo $h['phase_no']; ?></span></td>
                            <td><?php echo $h['member_count']; ?></td>
                            <td class="action-icons">
                                <i class="fa fa-eye" onclick="viewHousehold(<?php echo $h['household_id']; ?>)"></i>
                                <i class="fa fa-edit" onclick="editHousehold(<?php echo $h['household_id']; ?>)"></i>
                                <i class="fa fa-trash" onclick="deleteHousehold(<?php echo $h['household_id']; ?>)"></i>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Complaints Section (hidden by default) -->
    <div id="section-complaints" class="hidden-section">
        <div class="card">
            <div class="flex-between" style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">
                <span class="font-bold">Complaint Management</span>
            </div>
            <div style="padding: 1rem;">
                <?php foreach ($complaints as $c): ?>
                <div class="complaint-card">
                    <div class="complaint-header">
                        <div><h3><?php echo $c['subject']; ?></h3><p>By: <?php echo $c['first_name'] . ' ' . $c['last_name']; ?> | Ref: <?php echo $c['ref_no']; ?></p></div>
                        <div class="complaint-meta">
                            <span class="badge badge-<?php echo $c['priority'] == 'high' ? 'red' : ($c['priority'] == 'medium' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($c['priority']); ?></span>
                            <span class="badge badge-<?php echo $c['status'] == 'resolved' ? 'green' : 'orange'; ?>"><?php echo ucfirst($c['status']); ?></span>
                        </div>
                    </div>
                    <p><?php echo $c['description']; ?></p>
                    <?php if ($c['admin_response']): ?>
                    <div class="response-box"><strong>Response:</strong> <?php echo $c['admin_response']; ?></div>
                    <?php endif; ?>
                    <div class="flex" style="justify-content: flex-end; gap: 0.5rem;">
                        <button onclick="openRespondModal(<?php echo $c['complaint_id']; ?>)" class="btn">Respond</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>