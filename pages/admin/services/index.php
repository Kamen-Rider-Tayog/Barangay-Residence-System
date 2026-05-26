<?php
require_once '../../../includes/core/init.php';
requireAdmin();

// Get all services (offerings)
$services = getServices();

// Get filter parameters
$status_filter = $_GET['status'] ?? 'all';
$payment_filter = $_GET['payment'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query for service requests with filters
$requestsSql = "SELECT sr.*, s.service_name, s.base_price, 
        h.email, h.address, h.phase_no,
        p.payment_id, p.is_paid, p.total_amount, p.payment_method, p.paid_at,
        (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = sr.household_id AND is_head = 1 LIMIT 1) as resident_name
        FROM service_request sr
        JOIN service s ON sr.service_id = s.service_id
        JOIN household h ON sr.household_id = h.household_id
        LEFT JOIN payment p ON sr.request_id = p.request_id
        WHERE 1=1";

if ($status_filter !== 'all') {
    $requestsSql .= " AND sr.status = '" . $conn->real_escape_string($status_filter) . "'";
}

if ($payment_filter !== 'all') {
    $is_paid = ($payment_filter === 'paid') ? 1 : 0;
    $requestsSql .= " AND p.is_paid = " . $is_paid;
}

if (!empty($search)) {
    $requestsSql .= " AND (sr.ref_no LIKE '%" . $conn->real_escape_string($search) . "%' 
                       OR (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = sr.household_id AND is_head = 1 LIMIT 1) LIKE '%" . $conn->real_escape_string($search) . "%'
                       OR s.service_name LIKE '%" . $conn->real_escape_string($search) . "%')";
}

$requestsSql .= " ORDER BY sr.date_submitted DESC";

$requestsResult = $conn->query($requestsSql);
$requests = $requestsResult->fetch_all(MYSQLI_ASSOC);

// Get counts for filter badges
$countsSql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
    SUM(CASE WHEN p.is_paid = 1 THEN 1 ELSE 0 END) as paid,
    SUM(CASE WHEN p.is_paid = 0 OR p.is_paid IS NULL THEN 1 ELSE 0 END) as unpaid
FROM service_request sr
LEFT JOIN payment p ON sr.request_id = p.request_id";
$countsResult = $conn->query($countsSql);
$counts = $countsResult->fetch_assoc();
?>

<style>
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-approved { background: #dbeafe; color: #2563eb; }
    .status-processing { background: #e9d5ff; color: #7e22ce; }
    .status-completed { background: #d1fae5; color: #059669; }
    .status-rejected { background: #fee2e2; color: #dc2626; }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .btn-view {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        background: var(--primary-blue);
        color: white;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .btn-view:hover {
        background: var(--primary-blue-dark);
    }
    
    /* Filter Bar Styles */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        padding: 1rem;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
        justify-content: center;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .filter-group label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--gray-500);
        text-transform: uppercase;
    }
    .filter-select {
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        border: 1px solid var(--gray-300);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background: white;
        cursor: pointer;
    }
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-blue);
    }
    .search-box input {
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--gray-300);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        width: 250px;
    }
    .search-box input:focus {
        outline: none;
        border-color: var(--primary-blue);
    }
    .apply-btn {
        padding: 0.5rem 1.25rem;
        background: var(--primary-blue);
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
    }
    .apply-btn:hover {
        background: var(--primary-blue-dark);
    }
    .reset-btn {
        padding: 0.5rem 1rem;
        background: var(--gray-200);
        color: var(--gray-700);
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.875rem;
    }
    .reset-btn:hover {
        background: var(--gray-300);
    }
    
    @media (max-width: 768px) {
        .data-table th, .data-table td {
            font-size: 0.7rem;
            padding: 0.5rem;
        }
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group {
            width: 100%;
        }
        .filter-select, .search-box input, .apply-btn, .reset-btn {
            width: 100%;
        }
    }
</style>

<div class="services-management">
    <!-- SECTION 1: SERVICE OFFERINGS (available services) -->
    <div class="card">
        <div class="card-header flex-between">
            <span class="font-bold"><i class="fas fa-cogs"></i> <?php echo __('service-offerings'); ?></span>
            <button onclick="location.href='/barangay-residence-system/pages/admin/services/create.php'" class="btn btn-primary"><?php echo __('add-service'); ?></button>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?php echo __('service-id'); ?></th>
                        <th><?php echo __('service-name'); ?></th>
                        <th><?php echo __('price'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $s): ?>
                    <tr id="service-row-<?php echo $s['service_id']; ?>">
                        <td class="text-center"><?php echo $s['service_id']; ?></td>
                        <td class="font-semibold"><?php echo htmlspecialchars($s['service_name']); ?></td>
                        <td><?php echo $s['base_price'] == 0 ? __('free') : '₱' . number_format($s['base_price'], 2); ?></td>
                        <td><span class="badge badge-<?php echo $s['is_active'] ? 'green' : 'red'; ?>"><?php echo $s['is_active'] ? __('active') : __('inactive'); ?></span></td>
                        <td class="action-icons">
                            <a href="/barangay-residence-system/pages/admin/services/show.php?id=<?php echo $s['service_id']; ?>" class="action-icon" title="<?php echo __('view'); ?>">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/barangay-residence-system/pages/admin/services/edit.php?id=<?php echo $s['service_id']; ?>" class="action-icon" title="<?php echo __('edit'); ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/barangay-residence-system/pages/admin/services/destroy.php?id=<?php echo $s['service_id']; ?>" class="action-icon" title="<?php echo __('delete'); ?>" onclick="return confirm('<?php echo __('delete-service-confirm'); ?>')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </a>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 2: SERVICE REQUESTS (WITH DROPDOWN FILTERS) -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <span class="font-bold"><i class="fas fa-clipboard-list"></i> <?php echo __('service-requests'); ?></span>
            <span class="badge badge-blue"><?php echo __('total'); ?>: <?php echo $counts['total']; ?></span>
        </div>
        
        <div class="filter-bar">
            <div class="filter-group">
                <label><?php echo __('status'); ?></label>
                <select id="requestStatusFilter" class="filter-select">
                    <option value="all"><?php echo __('all-status'); ?></option>
                    <option value="pending"><?php echo __('pending'); ?></option>
                    <option value="approved"><?php echo __('approved'); ?></option>
                    <option value="processing"><?php echo __('processing'); ?></option>
                    <option value="completed"><?php echo __('completed'); ?></option>
                    <option value="rejected"><?php echo __('rejected'); ?></option>
                </select>
            </div>
            
            <div class="filter-group">
                <label><?php echo __('payment'); ?></label>
                <select id="requestPaymentFilter" class="filter-select">
                    <option value="all"><?php echo __('all-payments'); ?></option>
                    <option value="paid"><?php echo __('paid'); ?></option>
                    <option value="unpaid"><?php echo __('unpaid'); ?></option>
                </select>
            </div>
            
            <div class="search-box">
                <label>&nbsp;</label>
                <input type="text" id="requestSearchInput" placeholder="<?php echo __('search-requests-placeholder'); ?>">
            </div>
            
            <div class="filter-group">
                <label>&nbsp;</label>
                <button id="applyRequestFilters" class="apply-btn"><i class="fas fa-search"></i> <?php echo __('apply-filters'); ?></button>
            </div>
            
            <div class="filter-group">
                <label>&nbsp;</label>
                <button id="clearRequestFilters" class="reset-btn"><i class="fas fa-times"></i> <?php echo __('clear-filters'); ?></button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?php echo __('request-id'); ?></th>
                        <th><?php echo __('ref-no'); ?></th>
                        <th><?php echo __('resident'); ?></th>
                        <th><?php echo __('service'); ?></th>
                        <th><?php echo __('date'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th><?php echo __('payment'); ?></th>
                        <th><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                    <tr class="text-center">
                        <td colspan="8" class="no-data text-center">
                            <i class="fas fa-inbox"></i>
                            <p>No service requests found.</p>
                        </a>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                        <tr>
                            <td class="text-center"><?php echo $req['request_id']; ?></td>
                            <td class="font-semibold"><?php echo htmlspecialchars($req['ref_no']); ?></td>
                            <td><?php echo htmlspecialchars($req['resident_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($req['service_name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($req['date_submitted'])); ?></td>
                            
                            <!-- Status Badge -->
                            <td>
                                <span class="status-badge status-<?php echo $req['status']; ?>">
                                    <?php echo ucfirst($req['status']); ?>
                                </span>
                            </a>
                            
                            <!-- Payment Badge -->
                            <td>
                                <span class="badge badge-<?php echo $req['is_paid'] ? 'green' : 'red'; ?>">
                                    <?php echo $req['is_paid'] ? __('paid') : __('unpaid'); ?>
                                </span>
                                <?php if ($req['total_amount'] > 0): ?>
                                <small style="font-size: 0.6rem; display: block;">₱<?php echo number_format($req['total_amount'], 2); ?></small>
                                <?php endif; ?>
                            </a>
                            
                            <!-- Actions -->
                            <td class="action-buttons">
                                <a href="/barangay-residence-system/pages/admin/services/request_show.php?id=<?php echo $req['request_id']; ?>" class="btn-view">
                                    <i class="fas fa-eye"></i> <?php echo __('view-edit'); ?>
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