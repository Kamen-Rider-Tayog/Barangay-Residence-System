<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $missing = [];
    if (empty(trim($_POST['email'] ?? ''))) $missing[] = 'Email';
    if (empty($_POST['password'] ?? '')) $missing[] = 'Password';
    if (empty(trim($_POST['address'] ?? ''))) $missing[] = 'Address';
    if (empty(trim($_POST['first_name'] ?? ''))) $missing[] = 'First Name';
    if (empty(trim($_POST['last_name'] ?? ''))) $missing[] = 'Last Name';
    
    if (!empty($missing)) {
        $error = 'Missing required fields: ' . implode(', ', $missing);
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $address = trim($_POST['address']);
        $phase_no = $_POST['phase_no'] ?? 'Phase 1';
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $suffix = trim($_POST['suffix'] ?? '');
        $age = (int)($_POST['age'] ?? 0);
        $contact = trim($_POST['contact'] ?? '');
        $is_voter = isset($_POST['is_voter']) ? 1 : 0;
        
        $members = json_decode($_POST['members'] ?? '[]', true);
        
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
                
                $stmt2 = $conn->prepare("INSERT INTO resident (household_id, first_name, last_name, suffix, age, contact_no, is_head, is_voter, relationship_to_head) VALUES (?, ?, ?, ?, ?, ?, 1, ?, 'Head')");
                $stmt2->bind_param("issssis", $household_id, $first_name, $last_name, $suffix, $age, $contact, $is_voter);
                $stmt2->execute();
                
                if (!empty($members)) {
                    $stmt3 = $conn->prepare("INSERT INTO resident (household_id, first_name, last_name, suffix, age, contact_no, is_head, is_voter, relationship_to_head) VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?)");
                    foreach ($members as $member) {
                        $m_first = $member['first_name'] ?? '';
                        $m_last = $member['last_name'] ?? '';
                        $m_suffix = $member['suffix'] ?? '';
                        $m_age = (int)($member['age'] ?? 0);
                        $m_contact = $member['contact'] ?? '';
                        $m_voter = isset($member['is_voter']) ? 1 : 0;
                        $m_relationship = $member['relationship'] ?? 'Other';
                        
                        if (!empty($m_first) && !empty($m_last)) {
                            $stmt3->bind_param("issssiss", $household_id, $m_first, $m_last, $m_suffix, $m_age, $m_contact, $m_voter, $m_relationship);
                            $stmt3->execute();
                        }
                    }
                }
                
                $conn->commit();
                $success = 'Household added successfully!';
                echo '<script>setTimeout(function() { window.location.href = "/barangay-residence-system/pages/dashboard.php"; }, 2000);</script>';
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
<style>
.member-form-container {
    margin-top: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}
.members-list-table {
    margin-top: 1rem;
}
.member-form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}
.text-center {
    text-align: center;
}
.household-members-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.household-members-header h3 {
    margin: 0;
}
</style>
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card form-container">
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
            
            <form method="POST" id="householdForm" class="household-form">
                <input type="hidden" name="members" id="membersData" value="[]">
                
                <div class="form-section">
                    <h3>Account Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-input">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address *</label>
                            <input type="text" name="address" class="form-input">
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
                            <input type="text" name="first_name" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Suffix</label>
                            <input type="text" name="suffix" class="form-input" placeholder="Jr., Sr., III">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-input">
                        </div>
                    </div>
                    <div class="form-row">
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
                
                <div class="form-section">
                    <div class="household-members-header">
                        <h3>Household Members</h3>
                        <button type="button" id="showMemberFormBtn" class="btn btn-outline">+ Add Member</button>
                    </div>
                    
                    <div id="memberFormContainer" style="display: none;" class="member-form-container">
                        <div class="member-form-row">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" id="memberFirstName" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" id="memberLastName" class="form-input">
                            </div>
                        </div>
                        <div class="member-form-row">
                            <div class="form-group">
                                <label>Suffix</label>
                                <input type="text" id="memberSuffix" class="form-input" placeholder="Jr., Sr., III">
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" id="memberAge" class="form-input">
                            </div>
                        </div>
                        <div class="member-form-row">
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="tel" id="memberContact" class="form-input" placeholder="09123456789">
                            </div>
                            <div class="form-group">
                                <label>Relationship</label>
                                <select id="memberRelationship" class="form-input">
                                    <option value="Spouse">Spouse</option>
                                    <option value="Child">Child</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Sibling">Sibling</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="member-form-row">
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="memberIsVoter"> Registered Voter
                                </label>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" id="saveMemberBtn" class="btn btn-primary">Add to List</button>
                            <button type="button" id="cancelMemberBtn" class="btn btn-outline">Cancel</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive members-list-table">
                        <table class="data-table" id="membersTable">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Suffix</th>
                                    <th>Age</th>
                                    <th>Contact</th>
                                    <th>Voter</th>
                                    <th>Relationship</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="membersTableBody">
                                <tr id="noMembersRow">
                                    <td colspan="8" class="text-center">No household members added yet. Use "Add Member" to add.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Household</button>
                    <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/barangay-residence-system/assets/js/pages/households.js"></script>
<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>   qw