<?php
require_once '../../../includes/core/init.php';
requireAdmin();

// Get statistics
$stats = getDashboardStats();

// Get daily requests for last 7 days (for bar visualization)
$dailySql = "SELECT DATE(date_submitted) as date, COUNT(*) as count 
             FROM service_request 
             WHERE date_submitted >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(date_submitted)
             ORDER BY date ASC";
$dailyResult = $conn->query($dailySql);
$dailyMap = [];
while ($row = $dailyResult->fetch_assoc()) {
    $dailyMap[$row['date']] = $row['count'];
}

$dailyLabels = [];
$dailyCounts = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dailyLabels[] = date('M d', strtotime($date));
    $dailyCounts[] = $dailyMap[$date] ?? 0;
}
$maxDaily = max($dailyCounts) ?: 1;

// Get complaints by category
$categorySql = "SELECT category, COUNT(*) as count FROM complaint GROUP BY category";
$categoryResult = $conn->query($categorySql);
$categories = [];
$categoryCounts = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = ucfirst($row['category']);
    $categoryCounts[] = $row['count'];
}
$maxCategory = max($categoryCounts) ?: 1;

// Get recent service requests (last 5)
$recentRequestsSql = "SELECT sr.*, s.service_name, 
                      (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = sr.household_id AND is_head = 1 LIMIT 1) as resident_name
                      FROM service_request sr
                      JOIN service s ON sr.service_id = s.service_id
                      ORDER BY sr.date_submitted DESC LIMIT 5";
$recentRequests = $conn->query($recentRequestsSql)->fetch_all(MYSQLI_ASSOC);

// Get recent complaints (last 5)
$recentComplaintsSql = "SELECT c.*, 
                        (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = c.household_id AND is_head = 1 LIMIT 1) as resident_name
                        FROM complaint c
                        ORDER BY c.date_submitted DESC LIMIT 5";
$recentComplaints = $conn->query($recentComplaintsSql)->fetch_all(MYSQLI_ASSOC);

// Get top requested services
$topServicesSql = "SELECT s.service_name, COUNT(sr.request_id) as request_count
                   FROM service s
                   LEFT JOIN service_request sr ON s.service_id = sr.service_id
                   GROUP BY s.service_id
                   ORDER BY request_count DESC LIMIT 5";
$topServices = $conn->query($topServicesSql)->fetch_all(MYSQLI_ASSOC);
$maxTopService = $topServices ? max(array_column($topServices, 'request_count')) : 1;

// Get monthly trend (last 6 months)
$monthlySql = "SELECT DATE_FORMAT(date_submitted, '%Y-%m') as month, COUNT(*) as count 
               FROM service_request 
               WHERE date_submitted >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
               GROUP BY DATE_FORMAT(date_submitted, '%Y-%m')
               ORDER BY month ASC";
$monthlyResult = $conn->query($monthlySql);
$monthlyLabels = [];
$monthlyCounts = [];
while ($row = $monthlyResult->fetch_assoc()) {
    $monthlyLabels[] = date('M Y', strtotime($row['month'] . '-01'));
    $monthlyCounts[] = $row['count'];
}
$maxMonthly = max($monthlyCounts) ?: 1;
?>

<style>
/* Reports Page Styles */
.reports-container {
    padding: 0.5rem;
}

/* Stats Grid */
.reports-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.reports-stat-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
}

.reports-stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: var(--white);
}

.reports-stat-icon.blue { background: var(--primary-blue); }
.reports-stat-icon.green { background: var(--success-green); }
.reports-stat-icon.purple { background: #8b5cf6; }
.reports-stat-icon.orange { background: var(--warning-orange); }

.reports-stat-info p:first-child {
    font-size: 0.7rem;
    color: var(--gray-500);
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.reports-stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
}

/* Report Cards */
.report-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.report-card-header {
    padding: 1rem 1.25rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.report-card-header h3 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.report-card-header h3 i {
    color: var(--primary-blue);
}

.report-card-body {
    padding: 1.25rem;
}

/* Two Column Layout */
.reports-two-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Bar Chart (CSS-based) */
.bar-chart {
    margin-top: 0.5rem;
}

.bar-item {
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.bar-label {
    width: 60px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-600);
}

.bar-bar-container {
    flex: 1;
    height: 28px;
    background: var(--gray-100);
    border-radius: 0.375rem;
    overflow: hidden;
}

.bar-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-blue), var(--primary-blue-light));
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 0.5rem;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    transition: width 0.3s ease;
}

.bar-value {
    min-width: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-700);
    text-align: right;
}

/* Horizontal Bar for Categories */
.category-bar {
    margin-bottom: 1rem;
}

.category-name {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 0.25rem;
    display: flex;
    justify-content: space-between;
}

.category-bar-container {
    height: 24px;
    background: var(--gray-100);
    border-radius: 0.375rem;
    overflow: hidden;
}

.category-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 0.5rem;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Progress Ring (CSS circle) */
.progress-ring-container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.progress-ring-item {
    text-align: center;
}

.progress-ring {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
}

.progress-ring-label {
    font-size: 0.7rem;
    color: var(--gray-500);
}

/* Data Table */
.reports-table {
    width: 100%;
    border-collapse: collapse;
}

.reports-table th {
    text-align: left;
    padding: 0.75rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--gray-500);
    border-bottom: 1px solid var(--gray-200);
}

.reports-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.8rem;
    border-bottom: 1px solid var(--gray-100);
}

.reports-table tr:hover td {
    background: var(--gray-50);
}

.status-badge-sm {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 2rem;
    font-size: 0.65rem;
    font-weight: 600;
}

.status-pending { background: #fef3c7; color: #d97706; }
.status-approved { background: #dbeafe; color: #2563eb; }
.status-processing { background: #e9d5ff; color: #7e22ce; }
.status-completed { background: #d1fae5; color: #059669; }
.status-rejected { background: #fee2e2; color: #dc2626; }
.status-resolved { background: #d1fae5; color: #059669; }

/* Top Services List */
.top-service-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.top-service-name {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--gray-700);
}

.top-service-count {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--primary-blue);
    background: var(--primary-blue-50);
    padding: 0.2rem 0.6rem;
    border-radius: 2rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--gray-400);
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

/* Responsive */
@media (max-width: 1024px) {
    .reports-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .reports-two-columns {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .reports-stats-grid {
        grid-template-columns: 1fr;
    }
    .bar-item {
        flex-wrap: wrap;
    }
    .bar-label {
        width: 100%;
    }
    .bar-bar-container {
        width: 100%;
    }
}
</style>

<div class="reports-container">
    <!-- Statistics Cards -->
    <div class="reports-stats-grid">
        <div class="reports-stat-card">
            <div class="reports-stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="reports-stat-info">
                <p><?php echo __('total-residents'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_residents'] ?? 0); ?></p>
            </div>
        </div>
        <div class="reports-stat-card">
            <div class="reports-stat-icon green">
                <i class="fas fa-home"></i>
            </div>
            <div class="reports-stat-info">
                <p><?php echo __('total-households'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_households'] ?? 0); ?></p>
            </div>
        </div>
        <div class="reports-stat-card">
            <div class="reports-stat-icon purple">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="reports-stat-info">
                <p><?php echo __('total-voters'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_voters'] ?? 0); ?></p>
            </div>
        </div>
        <div class="reports-stat-card">
            <div class="reports-stat-icon orange">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="reports-stat-info">
                <p><?php echo __('total-service-requests'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_requests'] ?? 0); ?></p>
            </div>
        </div>
    </div>

    <!-- Daily Requests Chart (Last 7 Days) -->
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-chart-line"></i> <?php echo __('daily-requests'); ?></h3>
            <span class="badge badge-blue"><?php echo __('total'); ?>: <?php echo array_sum($dailyCounts); ?></span>
        </div>
        <div class="report-card-body">
            <div class="bar-chart">
                <?php foreach ($dailyLabels as $index => $label): ?>
                <div class="bar-item">
                    <div class="bar-label"><?php echo $label; ?></div>
                    <div class="bar-bar-container">
                        <div class="bar-bar" style="width: <?php echo ($dailyCounts[$index] / $maxDaily) * 100; ?>%;">
                            <?php if ($dailyCounts[$index] > 0): ?>
                            <span><?php echo $dailyCounts[$index]; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="reports-two-columns">
        <!-- Complaints by Category -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-chart-pie"></i> <?php echo __('complaints-by-category'); ?></h3>
                <span class="badge badge-orange"><?php echo __('total'); ?>: <?php echo array_sum($categoryCounts); ?></span>
            </div>
            <div class="report-card-body">
                <?php if (empty($categories)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p><?php echo __('no-data'); ?></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($categories as $index => $category): ?>
                    <div class="category-bar">
                        <div class="category-name">
                            <span><?php echo ucfirst($category); ?></span>
                            <span><?php echo $categoryCounts[$index]; ?></span>
                        </div>
                        <div class="category-bar-container">
                            <div class="category-bar-fill" style="width: <?php echo ($categoryCounts[$index] / $maxCategory) * 100; ?>%;">
                                <?php if ($categoryCounts[$index] > 0 && ($categoryCounts[$index] / $maxCategory) > 0.15): ?>
                                <span><?php echo $categoryCounts[$index]; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top Requested Services -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-trophy"></i> <?php echo __('top-requested-services'); ?></h3>
                <span class="badge badge-green"><?php echo __('most-popular'); ?></span>
            </div>
            <div class="report-card-body">
                <?php if (empty($topServices)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p><?php echo __('no-data'); ?></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($topServices as $service): ?>
                    <div class="top-service-item">
                        <span class="top-service-name"><?php echo htmlspecialchars($service['service_name']); ?></span>
                        <span class="top-service-count"><?php echo $service['request_count']; ?> requests</span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    <?php if (!empty($monthlyLabels)): ?>
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-chart-bar"></i> <?php echo __('monthly-trend'); ?></h3>
            <span class="badge badge-blue"><?php echo __('total'); ?>: <?php echo array_sum($monthlyCounts); ?></span>
        </div>
        <div class="report-card-body">
            <div class="bar-chart">
                <?php foreach ($monthlyLabels as $index => $label): ?>
                <div class="bar-item">
                    <div class="bar-label"><?php echo $label; ?></div>
                    <div class="bar-bar-container">
                        <div class="bar-bar" style="width: <?php echo ($monthlyCounts[$index] / $maxMonthly) * 100; ?>%; background: linear-gradient(90deg, #10b981, #34d399);">
                            <?php if ($monthlyCounts[$index] > 0): ?>
                            <span><?php echo $monthlyCounts[$index]; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Request Status Distribution -->
    <?php
    $statusSql = "SELECT status, COUNT(*) as count FROM service_request GROUP BY status";
    $statusResult = $conn->query($statusSql);
    $statusStats = [];
    while ($row = $statusResult->fetch_assoc()) {
        $statusStats[$row['status']] = $row['count'];
    }
    $statuses = ['pending', 'approved', 'processing', 'completed', 'rejected'];
    $statusColors = ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'];
    $statusLabels = [__('pending'), __('approved'), __('processing'), __('completed'), __('rejected')];
    ?>
    
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-chart-simple"></i> <?php echo __('request-status-distribution'); ?></h3>
        </div>
        <div class="report-card-body">
            <div class="progress-ring-container">
                <?php foreach ($statuses as $index => $status): 
                    $count = $statusStats[$status] ?? 0;
                    $total = array_sum($statusStats);
                    $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                ?>
                <div class="progress-ring-item">
                    <div class="progress-ring" style="background: conic-gradient(<?php echo $statusColors[$index]; ?> 0deg <?php echo $percentage * 3.6; ?>deg, var(--gray-100) <?php echo $percentage * 3.6; ?>deg 360deg);">
                        <span><?php echo $percentage; ?>%</span>
                    </div>
                    <div class="progress-ring-label"><?php echo $statusLabels[$index]; ?><br>(<?php echo $count; ?>)</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>