<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$services = getServices();
?>

<div class="services-section">
    <div class="card">
        <div class="card-header flex-between">
            <span class="font-bold">Service Management</span>
            <button onclick="location.href='/barangay-residence-system/pages/admin/services/create.php'" class="btn btn-primary">+ Add Service</button>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $s): ?>
                    <tr id="service-row-<?php echo $s['service_id']; ?>">
                        <td class="text-center"><?php echo $s['service_id']; ?></td>
                        <td class="font-semibold"><?php echo htmlspecialchars($s['service_name']); ?></td>
                        <td><?php echo $s['base_price'] == 0 ? 'FREE' : '₱' . number_format($s['base_price'], 2); ?></td>
                        <td><span class="badge badge-<?php echo $s['is_active'] ? 'green' : 'red'; ?>"><?php echo $s['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
                        <td class="action-icons">
                            <a href="/barangay-residence-system/pages/admin/services/show.php?id=<?php echo $s['service_id']; ?>" class="action-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/barangay-residence-system/pages/admin/services/edit.php?id=<?php echo $s['service_id']; ?>" class="action-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-icon delete-item-btn" data-type="service" data-id="<?php echo $s['service_id']; ?>" data-name="<?php echo htmlspecialchars($s['service_name']); ?>" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>