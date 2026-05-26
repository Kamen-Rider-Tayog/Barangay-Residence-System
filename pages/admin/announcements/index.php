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
            <span class="font-bold"><?php echo __('announcements-management'); ?></span>
            <a href="/barangay-residence-system/pages/admin/announcements/create.php" class="btn btn-primary"><?php echo __('add-announcement'); ?></a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?php echo __('title'); ?></th>
                        <th><?php echo __('content'); ?></th>
                        <th><?php echo __('date'); ?></th>
                        <th><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($announcements)): ?>
                    <tr>
                        <td colspan="4" class="text-center"><?php echo __('no-announcements-found'); ?></a>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($announcements as $a): ?>
                        <tr>
                            <td class="font-semibold"><?php echo htmlspecialchars($a['title']); ?></a>
                            <td><?php echo htmlspecialchars(substr($a['content'], 0, 100)) . (strlen($a['content']) > 100 ? '...' : ''); ?></a>
                            <td><?php echo date('M d, Y', strtotime($a['created_at'])); ?></a>
                            <td class="action-icons">
                                <a href="/barangay-residence-system/pages/admin/announcements/edit.php?id=<?php echo $a['announcement_id']; ?>" class="action-icon" title="<?php echo __('edit'); ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/barangay-residence-system/pages/admin/announcements/destroy.php?id=<?php echo $a['announcement_id']; ?>" class="action-icon" title="<?php echo __('delete'); ?>" onclick="return confirm('<?php echo __('delete-announcement-confirm'); ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                             </a>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>