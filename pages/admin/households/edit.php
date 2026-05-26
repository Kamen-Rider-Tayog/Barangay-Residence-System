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

$membersStmt = $conn->prepare("SELECT * FROM resident WHERE household_id = ? AND is_head = 0");
$membersStmt->bind_param("i", $household_id);
$membersStmt->execute();
$existingMembers = $membersStmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (!$householdData) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $phase_no = $_POST['phase_no'] ?? 'Phase 1';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $contact = trim($_POST['contact'] ?? '');
    $is_voter = isset($_POST['is_voter']) ? 1 : 0;
    
    $members = json_decode($_POST['members'] ?? '[]', true);
    $deletedMembers = json_decode($_POST['deleted_members'] ?? '[]', true);
    
    if (empty($address) || empty($first_name) || empty($last_name)) {
        $error = 'Please fill in all required fields.';
    } else {
        $conn->begin_transaction();
        try {
            $stmt1 = $conn->prepare("UPDATE household SET address = ?, phase_no = ? WHERE household_id = ?");
            $stmt1->bind_param("ssi", $address, $phase_no, $household_id);
            $stmt1->execute();
            
            $stmt2 = $conn->prepare("UPDATE resident SET first_name = ?, last_name = ?, suffix = ?, age = ?, contact_no = ?, is_voter = ? WHERE household_id = ? AND is_head = 1");
            $stmt2->bind_param("ssssisi", $first_name, $last_name, $suffix, $age, $contact, $is_voter, $household_id);
            $stmt2->execute();
            
            if (!empty($deletedMembers)) {
                $deleteStmt = $conn->prepare("DELETE FROM resident WHERE resident_id = ? AND household_id = ? AND is_head = 0");
                foreach ($deletedMembers as $delId) {
                    $deleteStmt->bind_param("ii", $delId, $household_id);
                    $deleteStmt->execute();
                }
            }
            
            $insertStmt = $conn->prepare("INSERT INTO resident (household_id, first_name, last_name, suffix, age, contact_no, is_head, is_voter, relationship_to_head) VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?)");
            $updateStmt = $conn->prepare("UPDATE resident SET first_name = ?, last_name = ?, suffix = ?, age = ?, contact_no = ?, is_voter = ?, relationship_to_head = ? WHERE resident_id = ? AND household_id = ? AND is_head = 0");
            
            foreach ($members as $member) {
                $m_first = $member['first_name'] ?? '';
                $m_last = $member['last_name'] ?? '';
                $m_suffix = $member['suffix'] ?? '';
                $m_age = (int)($member['age'] ?? 0);
                $m_contact = $member['contact'] ?? '';
                $m_voter = isset($member['is_voter']) ? 1 : 0;
                $m_relationship = $member['relationship'] ?? 'Other';
                $m_id = $member['id'] ?? 0;
                
                if (empty($m_first) || empty($m_last)) continue;
                
                if ($m_id > 0) {
                    $updateStmt->bind_param("sssssissi", $m_first, $m_last, $m_suffix, $m_age, $m_contact, $m_voter, $m_relationship, $m_id, $household_id);
                    $updateStmt->execute();
                } else {
                    $insertStmt->bind_param("issssiss", $household_id, $m_first, $m_last, $m_suffix, $m_age, $m_contact, $m_voter, $m_relationship);
                    $insertStmt->execute();
                }
            }
            
            $conn->commit();
            $success = 'Household updated successfully!';
            echo '<script>setTimeout(function() { window.location.href = "/barangay-residence-system/pages/dashboard.php"; }, 2000);</script>';
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Failed to update household.';
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
            
            <form method="POST" id="householdForm" class="household-form">
                <input type="hidden" name="members" id="membersData" value="[]">
                <input type="hidden" name="deleted_members" id="deletedMembersData" value="[]">
                
                <div class="form-section">
                    <h3>Account Information</h3>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" value="<?php echo htmlspecialchars($householdData['email']); ?>" disabled>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Address *</label>
                            <input type="text" name="address" class="form-input" value="<?php echo htmlspecialchars($householdData['address']); ?>">
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
                            <input type="text" name="first_name" class="form-input" value="<?php echo htmlspecialchars($residentData['first_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input" value="<?php echo htmlspecialchars($residentData['last_name'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Suffix</label>
                            <input type="text" name="suffix" class="form-input" value="<?php echo htmlspecialchars($residentData['suffix'] ?? ''); ?>" placeholder="Jr., Sr., III">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-input" value="<?php echo $residentData['age'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" name="contact" class="form-input" value="<?php echo htmlspecialchars($residentData['contact_no'] ?? ''); ?>" placeholder="09123456789">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_voter" value="1" <?php echo ($residentData['is_voter'] ?? 0) ? 'checked' : ''; ?>> Registered Voter
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
                                <?php if (empty($existingMembers)): ?>
                                <tr id="noMembersRow">
                                    <td colspan="8" class="text-center">No household members added yet. Use "Add Member" to add.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($existingMembers as $member): ?>
                                    <tr data-member-id="<?php echo $member['resident_id']; ?>">
                                        <td><?php echo htmlspecialchars($member['first_name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['suffix'] ?? ''); ?></td>
                                        <td><?php echo $member['age']; ?></td>
                                        <td><?php echo htmlspecialchars($member['contact_no'] ?? ''); ?></td>
                                        <td><?php echo $member['is_voter'] ? 'Yes' : 'No'; ?></td>
                                        <td><?php echo htmlspecialchars($member['relationship_to_head'] ?? 'Other'); ?></td>
                                        <td>
                                            <button type="button" class="action-icon edit-existing" data-id="<?php echo $member['resident_id']; ?>" data-first="<?php echo htmlspecialchars($member['first_name']); ?>" data-last="<?php echo htmlspecialchars($member['last_name']); ?>" data-suffix="<?php echo htmlspecialchars($member['suffix'] ?? ''); ?>" data-age="<?php echo $member['age']; ?>" data-contact="<?php echo htmlspecialchars($member['contact_no'] ?? ''); ?>" data-voter="<?php echo $member['is_voter']; ?>" data-relationship="<?php echo htmlspecialchars($member['relationship_to_head'] ?? 'Other'); ?>"><i class="fas fa-edit"></i></button>
                                            <a href="destroy.php?type=member&id=<?php echo $member['resident_id']; ?>&household_id=<?php echo $household_id; ?>" class="action-icon" onclick="return confirm('Delete this member?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                         
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
var existingMembersData = <?php echo json_encode($existingMembers); ?>;
var householdId = <?php echo $household_id; ?>;
</script>
<script src="/barangay-residence-system/assets/js/pages/households.js"></script>
<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>