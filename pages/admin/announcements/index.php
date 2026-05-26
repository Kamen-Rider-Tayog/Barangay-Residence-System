<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$stmt = $conn->prepare("SELECT * FROM announcements ORDER BY created_at DESC");
$stmt->execute();
$announcements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="announcements-section">
    <div class="card">
        <div class="card-header">
            <span class="font-bold">Announcements Management</span>
            <a href="/barangay-residence-system/pages/admin/announcements/create.php" class="btn btn-primary">+ Add Announcement</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($announcements)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No announcements found.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($announcements as $a): ?>
                        <tr>
                            <td class="font-semibold"><?php echo htmlspecialchars($a['title']); ?></td>
                            <td><?php echo htmlspecialchars(substr($a['content'], 0, 100)) . (strlen($a['content']) > 100 ? '...' : ''); ?></td>
                            <td><?php echo date('M d, Y', strtotime($a['created_at'])); ?></td>
                            <td class="action-icons">
                                <a href="/barangay-residence-system/pages/admin/announcements/edit.php?id=<?php echo $a['announcement_id']; ?>" class="action-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/barangay-residence-system/pages/admin/announcements/destroy.php?id=<?php echo $a['announcement_id']; ?>" class="action-icon" title="Delete" onclick="return confirm('Delete this announcement?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>