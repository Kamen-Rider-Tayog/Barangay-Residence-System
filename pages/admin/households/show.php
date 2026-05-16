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
    <div class="card">
        <div class="card-header">
            <h2>Household Details</h2>
            <div>
                <a href="edit.php?id=<?php echo $household_id; ?>" class="btn btn-primary">Edit</a>
                <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="detail-section">
                <h3>Account Information</h3>
                <table class="detail-table">
                    <tr><th>Email:</th><td><?php echo htmlspecialchars($householdData['email']); ?></td></tr>
                    <tr><th>Address:</th><td><?php echo htmlspecialchars($householdData['address']); ?></td></tr>
                    <tr><th>Phase:</th><td><?php echo htmlspecialchars($householdData['phase_no']); ?></td></tr>
                    <tr><th>Registered:</th><td><?php echo date('M d, Y', strtotime($householdData['created_at'])); ?></td></tr>
                </table>
            </div>
            
            <div class="detail-section">
                <h3>Residents</h3>
                <table class="data-table">
                    <thead>
                        <tr><th>Name</th><th>Age</th><th>Contact</th><th>Head?</th><th>Voter?</th><th>Role</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($residentsData as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></td>
                            <td><?php echo $r['age']; ?></td>
                            <td><?php echo htmlspecialchars($r['contact_no'] ?? 'N/A'); ?></td>
                            <td><?php echo $r['is_head'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $r['is_voter'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo htmlspecialchars($r['relationship_to_head'] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>