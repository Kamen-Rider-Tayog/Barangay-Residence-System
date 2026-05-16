<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$household_id = $_GET['id'] ?? 0;
if (!$household_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

$error = '';
$success = '';

$household = $conn->prepare("SELECT * FROM household WHERE household_id = ?");
$household->bind_param("i", $household_id);
$household->execute();
$householdData = $household->get_result()->fetch_assoc();

$resident = $conn->prepare("SELECT * FROM resident WHERE household_id = ? AND is_head = 1");
$resident->bind_param("i", $household_id);
$resident->execute();
$residentData = $resident->get_result()->fetch_assoc();

if (!$householdData) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $phase_no = $_POST['phase_no'] ?? 'Phase 1';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $contact = trim($_POST['contact'] ?? '');
    $is_voter = isset($_POST['is_voter']) ? 1 : 0;
    
    if (empty($address) || empty($first_name) || empty($last_name)) {
        $error = 'Please fill in all required fields.';
    } else {
        $conn->begin_transaction();
        try {
            $stmt1 = $conn->prepare("UPDATE household SET address = ?, phase_no = ? WHERE household_id = ?");
            $stmt1->bind_param("ssi", $address, $phase_no, $household_id);
            $stmt1->execute();
            
            $stmt2 = $conn->prepare("UPDATE resident SET first_name = ?, last_name = ?, age = ?, contact_no = ?, is_voter = ? WHERE household_id = ? AND is_head = 1");
            $stmt2->bind_param("ssiisi", $first_name, $last_name, $age, $contact, $is_voter, $household_id);
            $stmt2->execute();
            
            $conn->commit();
            $success = 'Household updated successfully!';
            header('refresh:2;url=/barangay-residence-system/pages/dashboard.php');
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Failed to update household.';
        }
    }
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card">
        <div class="card-header">
            <h2>Edit Household</h2>
            <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">Back to Dashboard</a>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-alert"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="household-form">
                <div class="form-section">
                    <h3>Account Information</h3>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" value="<?php echo htmlspecialchars($householdData['email']); ?>" disabled>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address *</label>
                            <input type="text" name="address" class="form-input" value="<?php echo htmlspecialchars($householdData['address']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phase</label>
                            <select name="phase_no" class="form-input">
                                <option value="Phase 1" <?php echo $householdData['phase_no'] == 'Phase 1' ? 'selected' : ''; ?>>Phase 1</option>
                                <option value="Phase 2" <?php echo $householdData['phase_no'] == 'Phase 2' ? 'selected' : ''; ?>>Phase 2</option>
                                <option value="Phase 3" <?php echo $householdData['phase_no'] == 'Phase 3' ? 'selected' : ''; ?>>Phase 3</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Head of Household</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-input" value="<?php echo htmlspecialchars($residentData['first_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input" value="<?php echo htmlspecialchars($residentData['last_name'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-input" value="<?php echo $residentData['age'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" name="contact" class="form-input" value="<?php echo htmlspecialchars($residentData['contact_no'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_voter" value="1" <?php echo ($residentData['is_voter'] ?? 0) ? 'checked' : ''; ?>> Registered Voter
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/barangay-residence-system/pages/dashboard.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>