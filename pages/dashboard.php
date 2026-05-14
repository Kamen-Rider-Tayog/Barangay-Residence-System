<?php
require_once '../includes/init.php';
requireAdmin();
$phases = getAllPhases();

$stats = getDashboardStats();
$complaints = getAllComplaints();
$households = getAllHouseholds();

include '../includes/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../includes/navbar.php'; ?>

<main class="container">
    <div class="dashboard-header">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Manage households and monitor barangay activities</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div class="nav-buttons">
                <button id="nav-households" class="btn-nav btn-active" onclick="switchTab('households')">
                    <i class="fas fa-home"></i> Households
                </button>
                <button id="nav-complaints" class="btn-nav btn-inactive" onclick="switchTab('complaints')">
                    <i class="fas fa-comment-dots"></i> Complaints
                    <?php if ($stats['pending_complaints'] > 0): ?>
                    <span class="badge-red"><?php echo $stats['pending_complaints']; ?></span>
                    <?php endif; ?>
                </button>
            </div>
            <a href="/barangay-residence-system/includes/logout.php" class="btn btn-outline" style="background: var(--error-red); color: white; border: none;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon bg-blue"><i class="fas fa-users"></i></div>
            <div><p class="stat-label">Total Residents</p><p class="stat-number"><?php echo $stats['total_residents']; ?></p></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon bg-green"><i class="fas fa-home"></i></div>
            <div><p class="stat-label">Households</p><p class="stat-number"><?php echo $stats['total_households']; ?></p></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon bg-purple"><i class="fas fa-file-alt"></i></div>
            <div><p class="stat-label">Transactions</p><p class="stat-number"><?php echo $stats['total_requests']; ?></p></div>
        </div>
        <div class="stat-item">
            <div class="stat-icon bg-orange"><i class="fas fa-comment"></i></div>
            <div><p class="stat-label">Complaints</p><p class="stat-number"><?php echo $stats['pending_complaints']; ?></p></div>
        </div>
    </div>
    
    <div id="section-households">
        <div class="card">
            <div class="card-header flex-between">
                <span class="font-bold">Household Management</span>
                <button onclick="showAddForm()" class="btn btn-primary">+ Add Household</button>
            </div>
            
            <div class="search-bar">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search name, email, contact..." onkeyup="filterTable()">
                </div>
                <div class="filter-dropdown">
                    <button id="phaseBtn" class="filter-btn">
                        <i class="fas fa-filter"></i>
                        <span id="currentPhaseLabel">All Phases</span>
                        <i class="fas fa-angle-down"></i>
                    </button>
                    <div id="phaseMenu" class="filter-menu">
                        <button onclick="setPhaseFilter('all')">All Phases</button>
                        <?php foreach ($phases as $phase): ?>
                        <button onclick="setPhaseFilter('<?php echo $phase; ?>')"><?php echo htmlspecialchars($phase); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table" id="householdTable">
                    <thead>
                        <tr>
                            <th>Head of Household</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Phase</th>
                            <th>Members</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($households as $h): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($h['head_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($h['email']); ?></td>
                            <td><?php echo htmlspecialchars($h['contact_no'] ?? 'N/A'); ?></td>
                            <td><span class="badge badge-blue"><?php echo htmlspecialchars($h['phase_no']); ?></span></td>
                            <td><?php echo $h['member_count']; ?></td>
                            <td class="action-icons">
                                <i class="fas fa-eye" onclick="viewHousehold(<?php echo $h['household_id']; ?>)"></i>
                                <i class="fas fa-edit" onclick="editHousehold(<?php echo $h['household_id']; ?>)"></i>
                                <i class="fas fa-trash" onclick="deleteHousehold(<?php echo $h['household_id']; ?>)"></i>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div id="section-complaints" class="hidden-section">
        <div class="card">
            <div class="card-header">
                <span class="font-bold">Complaint Management</span>
            </div>
            <div class="complaints-list">
                <?php if (empty($complaints)): ?>
                    <div class="no-data">
                        <i class="fas fa-check-circle"></i>
                        <p>No complaints found.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($complaints as $c): ?>
                    <div class="complaint-card">
                        <div class="complaint-header">
                            <div>
                                <h3><?php echo htmlspecialchars($c['subject']); ?></h3>
                                <p class="complaint-meta">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?>
                                    | <i class="fas fa-hashtag"></i> <?php echo $c['ref_no']; ?>
                                    | <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($c['date_submitted'])); ?>
                                </p>
                            </div>
                            <div class="complaint-meta-badges">
                                <span class="badge badge-<?php echo $c['priority'] == 'high' ? 'red' : ($c['priority'] == 'medium' ? 'orange' : 'blue'); ?>">
                                    <?php echo ucfirst($c['priority']); ?>
                                </span>
                                <span class="badge badge-<?php echo $c['status'] == 'resolved' ? 'green' : 'orange'; ?>">
                                    <?php echo ucfirst($c['status']); ?>
                                </span>
                            </div>
                        </div>
                        <p class="complaint-desc"><?php echo htmlspecialchars($c['description']); ?></p>
                        <?php if ($c['admin_response']): ?>
                        <div class="response-box">
                            <strong><i class="fas fa-reply"></i> Admin Response:</strong>
                            <p><?php echo htmlspecialchars($c['admin_response']); ?></p>
                        </div>
                        <?php endif; ?>
                        <div class="complaint-actions">
                            <button onclick="openRespondModal(<?php echo $c['complaint_id']; ?>)" class="btn btn-primary">
                                <i class="fas fa-reply"></i> Respond
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script src="/barangay-residence-system/assets/js/pages/dashboard.js"></script>