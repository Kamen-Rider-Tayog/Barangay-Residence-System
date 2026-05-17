<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$complaints = getAllComplaints();
?>

<div class="complaints-section">
    <div class="card">
        <div class="card-header flex-between">
            <span class="font-bold">Complaint Management</span>
            <div class="search-bar">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="complaintSearchInput" class="form-input" placeholder="Search complaints...">
                </div>
                <div class="filter-dropdown">
                    <button id="complaintStatusBtn" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        <span id="complaintStatusLabel">All Status</span>
                        <i class="fas fa-angle-down"></i>
                    </button>
                    <div id="complaintStatusMenu" class="dropdown-menu">
                        <button class="dropdown-item" data-status="all">All Status</button>
                        <button class="dropdown-item" data-status="pending">Pending</button>
                        <button class="dropdown-item" data-status="reviewing">Reviewing</button>
                        <button class="dropdown-item" data-status="resolved">Resolved</button>
                        <button class="dropdown-item" data-status="dismissed">Dismissed</button>
                    </div>
                </div>
                <div class="filter-dropdown">
                    <button id="complaintPriorityBtn" class="btn-filter">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="complaintPriorityLabel">All Priority</span>
                        <i class="fas fa-angle-down"></i>
                    </button>
                    <div id="complaintPriorityMenu" class="dropdown-menu">
                        <button class="dropdown-item" data-priority="all">All Priority</button>
                        <button class="dropdown-item" data-priority="high">High</button>
                        <button class="dropdown-item" data-priority="medium">Medium</button>
                        <button class="dropdown-item" data-priority="low">Low</button>
                    </div>
                </div>
                <div class="filter-dropdown">
                    <button id="complaintSortBtn" class="btn-filter">
                        <i class="fas fa-sort-amount-down"></i>
                        <span id="complaintSortLabel">Newest First</span>
                        <i class="fas fa-angle-down"></i>
                    </button>
                    <div id="complaintSortMenu" class="dropdown-menu">
                        <button class="dropdown-item" data-sort="newest">Newest First</button>
                        <button class="dropdown-item" data-sort="oldest">Oldest First</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="complaintTable">
                <thead>
                    <tr>
                        <th>Reference No.</th>
                        <th>Subject</th>
                        <th>Resident</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="complaintTableBody">
                    <?php foreach ($complaints as $c): ?>
                    <tr data-status="<?php echo $c['status']; ?>" data-priority="<?php echo $c['priority']; ?>" data-date="<?php echo $c['date_submitted']; ?>" data-subject="<?php echo strtolower(htmlspecialchars($c['subject'])); ?>" data-name="<?php echo strtolower($c['first_name'] . ' ' . $c['last_name']); ?>">
                        <td><?php echo $c['ref_no']; ?></td>
                        <td class="font-semibold"><?php echo htmlspecialchars($c['subject']); ?></td>
                        <td><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></td>
                        <td><span class="badge badge-<?php echo $c['priority'] == 'high' ? 'red' : ($c['priority'] == 'medium' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($c['priority']); ?></span></td>
                        <td><span class="badge badge-<?php echo $c['status'] == 'resolved' ? 'green' : ($c['status'] == 'pending' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($c['status']); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($c['date_submitted'])); ?></td>
                        <td class="action-icons">
                            <a href="/barangay-residence-system/pages/admin/complaints/show.php?id=<?php echo $c['complaint_id']; ?>" class="action-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/barangay-residence-system/pages/admin/complaints/respond.php?id=<?php echo $c['complaint_id']; ?>" class="action-icon" title="Respond">
                                <i class="fas fa-reply"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($complaints)): ?>
                    <tr>
                        <td colspan="7" class="no-data">
                            <i class="fas fa-check-circle"></i>
                            <p>No complaints found.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>