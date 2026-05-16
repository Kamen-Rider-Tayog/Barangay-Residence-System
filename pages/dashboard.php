<?php
require_once '../includes/core/init.php';
requireAdmin();
$phases = getAllPhases();

$stats = getDashboardStats();
$complaints = getAllComplaints();
$households = getAllHouseholds();

include '../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../includes/layouts/navbar.php'; ?>

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
                <button id="nav-services" class="btn-nav btn-inactive" onclick="location.href='admin/services/index.php'">
                    <i class="fas fa-cogs"></i> Services
                </button>
            </div>
            <a href="/barangay-residence-system/includes/core/logout.php" class="btn btn-outline" style="background: var(--error-red); color: white; border: none;">
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
    
    <!-- Households Section -->
    <div id="section-households">
        <div class="card">
            <div class="card-header flex-between">
                <span class="font-bold">Household Management</span>
                <button onclick="location.href='admin/households/create.php'" class="btn btn-primary">+ Add Household</button>
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
                                <a href="admin/households/show.php?id=<?php echo $h['household_id']; ?>" class="action-icon" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="admin/households/edit.php?id=<?php echo $h['household_id']; ?>" class="action-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="admin/households/destroy.php?id=<?php echo $h['household_id']; ?>" class="action-icon" onclick="return confirm('Delete this household?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Complaints Section with Grid and Filters -->
    <div id="section-complaints" class="hidden-section">
        <div class="card">
            <div class="card-header flex-between">
                <span class="font-bold">Complaint Management</span>
                <div class="complaint-filters">
                    <select id="complaintStatusFilter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="reviewing">Reviewing</option>
                        <option value="resolved">Resolved</option>
                        <option value="dismissed">Dismissed</option>
                    </select>
                    <select id="complaintPriorityFilter" class="filter-select">
                        <option value="all">All Priority</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <div class="search-input" style="width: 200px;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="complaintSearch" placeholder="Search complaints...">
                    </div>
                </div>
            </div>
            <div class="complaints-grid" id="complaintsGrid">
                <?php foreach ($complaints as $c): ?>
                <div class="complaint-card" data-complaint-id="<?php echo $c['complaint_id']; ?>" data-status="<?php echo $c['status']; ?>" data-priority="<?php echo $c['priority']; ?>" data-subject="<?php echo strtolower(htmlspecialchars($c['subject'])); ?>" data-name="<?php echo strtolower($c['first_name'] . ' ' . $c['last_name']); ?>">
                    <div class="complaint-header">
                        <div class="complaint-title">
                            <h3><?php echo htmlspecialchars($c['subject']); ?></h3>
                            <div class="complaint-badges">
                                <span class="badge badge-<?php echo $c['priority'] == 'high' ? 'red' : ($c['priority'] == 'medium' ? 'orange' : 'blue'); ?>">
                                    <?php echo ucfirst($c['priority']); ?>
                                </span>
                                <span class="badge badge-<?php echo $c['status'] == 'resolved' ? 'green' : ($c['status'] == 'pending' ? 'orange' : 'blue'); ?>">
                                    <?php echo ucfirst($c['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="complaint-meta">
                            <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></p>
                            <p><i class="fas fa-hashtag"></i> <?php echo $c['ref_no']; ?></p>
                            <p><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($c['date_submitted'])); ?></p>
                        </div>
                    </div>
                    <div class="complaint-preview">
                        <p><?php echo htmlspecialchars(substr($c['description'], 0, 120)) . (strlen($c['description']) > 120 ? '...' : ''); ?></p>
                    </div>
                    <?php if ($c['admin_response']): ?>
                    <div class="response-preview">
                        <i class="fas fa-reply"></i> <?php echo htmlspecialchars(substr($c['admin_response'], 0, 80)) . (strlen($c['admin_response']) > 80 ? '...' : ''); ?>
                    </div>
                    <?php endif; ?>
                    <div class="complaint-actions">
                        <button onclick="location.href='admin/complaints/show.php?id=<?php echo $c['complaint_id']; ?>'" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                        <button onclick="location.href='admin/complaints/respond.php?id=<?php echo $c['complaint_id']; ?>'" class="btn btn-outline">
                            <i class="fas fa-reply"></i> Respond
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($complaints)): ?>
                <div class="no-data">
                    <i class="fas fa-check-circle"></i>
                    <p>No complaints found.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script src="/barangay-residence-system/assets/js/pages/dashboard.js"></script>