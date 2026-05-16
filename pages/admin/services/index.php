<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$services = getServices();

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="dashboard-header">
        <h1>Service Management</h1>
        <a href="create.php" class="btn btn-primary">+ Add New Service</a>
    </div>
    
    <div class="card">
        <div class="table-container">
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
                    <tr>
                        <td><?php echo $s['service_id']; ?></td>
                        <td><?php echo htmlspecialchars($s['service_name']); ?></td>
                        <td><?php echo $s['base_price'] == 0 ? 'FREE' : '₱' . number_format($s['base_price'], 2); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $s['is_active'] ? 'green' : 'red'; ?>">
                                <?php echo $s['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td class="action-icons">
                            <a href="show.php?id=<?php echo $s['service_id']; ?>"><i class="fas fa-eye"></i></a>
                            <a href="edit.php?id=<?php echo $s['service_id']; ?>"><i class="fas fa-edit"></i></a>
                            <a href="destroy.php?id=<?php echo $s['service_id']; ?>" onclick="return confirm('Delete this service?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>