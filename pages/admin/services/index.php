<?php
require_once '../../../includes/core/init.php';
requireAdmin();

// Get all services (offerings)
$services = getServices();

// Get all service requests with JOIN (read-only)
$requestsSql = "SELECT sr.*, s.service_name, s.base_price, 
        h.email, h.address, h.phase_no,
        p.payment_id, p.is_paid, p.total_amount, p.payment_method, p.paid_at,
        (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = sr.household_id AND is_head = 1 LIMIT 1) as resident_name
        FROM service_request sr
        JOIN service s ON sr.service_id = s.service_id
        JOIN household h ON sr.household_id = h.household_id
        LEFT JOIN payment p ON sr.request_id = p.request_id
        ORDER BY sr.date_submitted DESC";

$requestsResult = $conn->query($requestsSql);
$requests = $requestsResult->fetch_all(MYSQLI_ASSOC);
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
    
    @media (max-width: 768px) {
        .data-table th, .data-table td {
            font-size: 0.7rem;
            padding: 0.5rem;
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

    <!-- SECTION 2: SERVICE REQUESTS (READ-ONLY TABLE) -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <span class="font-bold"><i class="fas fa-clipboard-list"></i> <?php echo __('service-requests'); ?></span>
            <span class="badge badge-blue"><?php echo __('total'); ?>: <?php echo count($requests); ?></span>
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
                    <tr>
                        <td colspan="8" class="no-data text-center">
                            <i class="fas fa-inbox"></i>
                            <p><?php echo __('no-service-requests'); ?></p>
                        </a>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                        <tr>
                            <td class="text-center"><?php echo $req['request_id']; ?></a>
                            <td class="font-semibold"><?php echo htmlspecialchars($req['ref_no']); ?></a>
                            <td><?php echo htmlspecialchars($req['resident_name'] ?? __('n-a')); ?></a>
                            <td><?php echo htmlspecialchars($req['service_name']); ?></a>
                            <td><?php echo date('M d, Y', strtotime($req['date_submitted'])); ?></a>
                            
                            <!-- Status Badge (READ ONLY) -->
                            <td>
                                <span class="status-badge status-<?php echo $req['status']; ?>">
                                    <?php 
                                        $statusKey = $req['status'];
                                        echo __($statusKey);
                                    ?>
                                </span>
                            </a>
                            
                            <!-- Payment Badge (READ ONLY) -->
                            <td>
                                <span class="badge badge-<?php echo $req['is_paid'] ? 'green' : 'red'; ?>">
                                    <?php echo $req['is_paid'] ? __('paid') : __('unpaid'); ?>
                                </span>
                                <?php if ($req['total_amount'] > 0): ?>
                                <small style="font-size: 0.6rem; display: block;">₱<?php echo number_format($req['total_amount'], 2); ?></small>
                                <?php endif; ?>
                            </a>
                            
                            <!-- Actions: View Only -->
                            <td>
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