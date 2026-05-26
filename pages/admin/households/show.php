<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$household_id = $_GET['id'] ?? 0;
if (!$household_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

$household = $conn->prepare("SELECT * FROM household WHERE household_id = ?");
$household->bind_param("i", $household_id);
$household->execute();
$householdData = $household->get_result()->fetch_assoc();

$residents = $conn->prepare("SELECT * FROM resident WHERE household_id = ?");
$residents->bind_param("i", $household_id);
$residents->execute();
$residentsData = $residents->get_result()->fetch_all(MYSQLI_ASSOC);

if (!$householdData) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card form-container">
        <div class="card-header">
            <h2><?php echo __('household-details'); ?></h2>
            <div>
                <a href="edit.php?id=<?php echo $household_id; ?>" class="btn btn-primary"><?php echo __('edit'); ?></a>
                <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline"><?php echo __('back'); ?></a>
            </div>
        </div>
        <div class="card-body">
            <div class="detail-section">
                <h3><?php echo __('account-information'); ?></h3>
                <table class="detail-table">
                    <tr><th><?php echo __('email'); ?>:</th><td><?php echo htmlspecialchars($householdData['email']); ?></td></tr>
                    <tr><th><?php echo __('address'); ?>:</th><td><?php echo htmlspecialchars($householdData['address']); ?></td></tr>
                    <tr><th><?php echo __('phase'); ?>:</th><td><?php echo htmlspecialchars($householdData['phase_no']); ?></td></tr>
                    <tr><th><?php echo __('registered'); ?>:</th><td><?php echo date('M d, Y', strtotime($householdData['created_at'])); ?></td></tr>
                </table>
            </div>
            
            <div class="detail-section">
                <h3><?php echo __('residents'); ?></h3>
                <table class="data-table">
                    <thead>
                        <tr><th><?php echo __('name'); ?></th><th><?php echo __('age'); ?></th><th><?php echo __('contact'); ?></th><th><?php echo __('head'); ?></th><th><?php echo __('voter'); ?></th><th><?php echo __('role'); ?></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($residentsData as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></td>
                            <td><?php echo $r['age']; ?></td>
                            <td><?php echo htmlspecialchars($r['contact_no'] ?? __('n-a')); ?></td>
                            <td><?php echo $r['is_head'] ? __('yes') : __('no'); ?></td>
                            <td><?php echo $r['is_voter'] ? __('yes') : __('no'); ?></td>
                            <td><?php echo htmlspecialchars($r['relationship_to_head'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>