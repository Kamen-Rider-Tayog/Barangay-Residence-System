<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$complaints = getAllComplaints();
?>

<div class="complaints-section">
    <div class="card">
        <div class="card-header">
            <span class="font-bold"><?php echo __('complaint-management'); ?></span>
        </div>
        
        <div class="filters-bar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" id="complaintSearchInput" class="form-input" placeholder="<?php echo __('search-complaints'); ?>">
            </div>
            <div class="filter-dropdown">
                <button id="complaintStatusBtn" class="btn-filter">
                    <span id="complaintStatusLabel"><?php echo __('all-status'); ?></span>
                    <i class="fas fa-angle-down"></i>
                </button>
                <div id="complaintStatusMenu" class="dropdown-menu">
                    <button class="dropdown-item" data-status="all"><?php echo __('all-status'); ?></button>
                    <button class="dropdown-item" data-status="pending"><?php echo __('pending'); ?></button>
                    <button class="dropdown-item" data-status="reviewing"><?php echo __('reviewing'); ?></button>
                    <button class="dropdown-item" data-status="resolved"><?php echo __('resolved'); ?></button>
                    <button class="dropdown-item" data-status="dismissed"><?php echo __('dismissed'); ?></button>
                </div>
            </div>
            <div class="filter-dropdown">
                <button id="complaintPriorityBtn" class="btn-filter">
                    <span id="complaintPriorityLabel"><?php echo __('all-priority'); ?></span>
                    <i class="fas fa-angle-down"></i>
                </button>
                <div id="complaintPriorityMenu" class="dropdown-menu">
                    <button class="dropdown-item" data-priority="all"><?php echo __('all-priority'); ?></button>
                    <button class="dropdown-item" data-priority="high"><?php echo __('high'); ?></button>
                    <button class="dropdown-item" data-priority="medium"><?php echo __('medium'); ?></button>
                    <button class="dropdown-item" data-priority="low"><?php echo __('low'); ?></button>
                </div>
            </div>
            <div class="filter-dropdown">
                <button id="complaintSortBtn" class="btn-filter">
                    <span id="complaintSortLabel"><?php echo __('newest-first'); ?></span>
                    <i class="fas fa-angle-down"></i>
                </button>
                <div id="complaintSortMenu" class="dropdown-menu">
                    <button class="dropdown-item" data-sort="newest"><?php echo __('newest-first'); ?></button>
                    <button class="dropdown-item" data-sort="oldest"><?php echo __('oldest-first'); ?></button>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="complaintTable">
                <thead>
                    <tr>
                        <th><?php echo __('reference-no'); ?></th>
                        <th><?php echo __('subject'); ?></th>
                        <th><?php echo __('resident'); ?></th>
                        <th><?php echo __('priority'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th><?php echo __('date'); ?></th>
                        <th><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody id="complaintTableBody">
                    <?php if (!empty($complaints)): ?>
                        <?php foreach ($complaints as $c): ?>
                        <tr class="complaint-row" data-status="<?php echo $c['status']; ?>" data-priority="<?php echo $c['priority']; ?>" data-date="<?php echo $c['date_submitted']; ?>" data-subject="<?php echo strtolower(htmlspecialchars($c['subject'])); ?>" data-name="<?php echo strtolower($c['first_name'] . ' ' . $c['last_name']); ?>">
                            <td><?php echo $c['ref_no']; ?></td>
                            <td class="font-semibold"><?php echo htmlspecialchars($c['subject']); ?></td>
                            <td><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></td>
                            <td><span class="badge badge-<?php echo $c['priority'] == 'high' ? 'red' : ($c['priority'] == 'medium' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($c['priority']); ?></span></td>
                            <td><span class="badge badge-<?php echo $c['status'] == 'resolved' ? 'green' : ($c['status'] == 'pending' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($c['status']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($c['date_submitted'])); ?></td>
                            <td class="action-icons">
                                <a href="/barangay-residence-system/pages/admin/complaints/show.php?id=<?php echo $c['complaint_id']; ?>" class="action-icon" title="<?php echo __('view'); ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/barangay-residence-system/pages/admin/complaints/respond.php?id=<?php echo $c['complaint_id']; ?>" class="action-icon" title="<?php echo __('respond'); ?>">
                                    <i class="fas fa-reply"></i>
                                </a>
                             </a>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>