<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$phases = getAllPhases();
$households = getAllHouseholds();
?>

<div class="households-section">
    <div class="card">
        <div class="card-header flex-between">
            <span class="font-bold">Household Management</span>
            <div class="search-bar">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="householdSearchInput" class="form-input" placeholder="Search name, email, contact...">
                </div>
                <div class="filter-dropdown">
                    <button id="householdPhaseBtn" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        <span id="householdPhaseLabel">All Phases</span>
                        <i class="fas fa-angle-down"></i>
                    </button>
                    <div id="householdPhaseMenu" class="dropdown-menu">
                        <button class="dropdown-item" data-phase="all">All Phases</button>
                        <?php foreach ($phases as $phase): ?>
                        <button class="dropdown-item" data-phase="<?php echo $phase; ?>"><?php echo htmlspecialchars($phase); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button onclick="location.href='/barangay-residence-system/pages/admin/households/create.php'" class="btn btn-primary">+ Add Household</button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="householdTable">
                <thead>
                    <tr>
                        <th>Head of Household</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Phase</th>
                        <th>Members</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="householdTableBody">
                    <?php foreach ($households as $h): ?>
                    <tr data-phase="<?php echo htmlspecialchars($h['phase_no']); ?>" data-name="<?php echo strtolower(htmlspecialchars($h['head_name'] ?? $h['email'])); ?>">
                        <td class="font-semibold"><?php echo htmlspecialchars($h['head_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($h['email']); ?></td>
                        <td><?php echo htmlspecialchars($h['contact_no'] ?? 'N/A'); ?></td>
                        <td><span class="badge badge-blue"><?php echo htmlspecialchars($h['phase_no']); ?></span></td>
                        <td><?php echo $h['member_count']; ?></td>
                        <td class="action-icons">
                            <a href="/barangay-residence-system/pages/admin/households/show.php?id=<?php echo $h['household_id']; ?>" class="action-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/barangay-residence-system/pages/admin/households/edit.php?id=<?php echo $h['household_id']; ?>" class="action-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-icon delete-item-btn" data-type="household" data-id="<?php echo $h['household_id']; ?>" data-name="<?php echo htmlspecialchars($h['head_name'] ?? $h['email']); ?>" title="Delete">
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