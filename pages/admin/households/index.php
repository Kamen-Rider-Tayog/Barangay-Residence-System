<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$households = getAllHouseholds();

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="dashboard-header">
        <h1>Households Management</h1>
        <a href="create.php" class="btn btn-primary">+ Add Household</a>
    </div>
    
    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr><th>Head of Household</th><th>Email</th><th>Contact</th><th>Phase</th><th>Members</th><th>Actions</th></tr>
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
                            <a href="show.php?id=<?php echo $h['household_id']; ?>"><i class="fas fa-eye"></i></a>
                            <a href="edit.php?id=<?php echo $h['household_id']; ?>"><i class="fas fa-edit"></i></a>
                            <a href="destroy.php?id=<?php echo $h['household_id']; ?>" onclick="return confirm('Delete this household?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>