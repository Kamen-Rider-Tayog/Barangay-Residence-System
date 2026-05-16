<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $phase_no = $_POST['phase_no'] ?? 'Phase 1';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $contact = trim($_POST['contact'] ?? '');
    $is_voter = isset($_POST['is_voter']) ? 1 : 0;
    
    if (empty($email) || empty($password) || empty($address) || empty($first_name) || empty($last_name)) {
        $error = 'Please fill in all required fields.';
    } else {
        $checkEmail = $conn->prepare("SELECT household_id FROM household WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        if ($checkEmail->get_result()->num_rows > 0) {
            $error = 'Email already exists.';
        } else {
            $conn->begin_transaction();
            try {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt1 = $conn->prepare("INSERT INTO household (email, password, address, phase_no) VALUES (?, ?, ?, ?)");
                $stmt1->bind_param("ssss", $email, $hashed_password, $address, $phase_no);
                $stmt1->execute();
                $household_id = $conn->insert_id;
                
                $stmt2 = $conn->prepare("INSERT INTO resident (household_id, first_name, last_name, age, contact_no, is_head, is_voter, relationship_to_head) VALUES (?, ?, ?, ?, ?, 1, ?, 'Self')");
                $stmt2->bind_param("issiis", $household_id, $first_name, $last_name, $age, $contact, $is_voter);
                $stmt2->execute();
                
                $conn->commit();
                $success = 'Household added successfully!';
                header('refresh:2;url=/barangay-residence-system/pages/dashboard.php');
            } catch (Exception $e) {
                $conn->rollback();
                $error = 'Failed to add household. Please try again.';
            }
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
            <h2>Add New Household</h2>
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
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address *</label>
                            <input type="text" name="address" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phase</label>
                            <select name="phase_no" class="form-input">
                                <option value="Phase 1">Phase 1</option>
                                <option value="Phase 2">Phase 2</option>
                                <option value="Phase 3">Phase 3</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Head of Household</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" name="contact" class="form-input" placeholder="09123456789">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_voter" value="1"> Registered Voter
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Household</button>
                    <a href="/barangay-residence-system/pages/dashboard.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>