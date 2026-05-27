<?php
require_once '../../../includes/core/init.php';
requireAdmin();

// Get statistics
$stats = getDashboardStats();

// Daily requests for last 7 days
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
$maxDaily = 1;
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dailyLabels[] = date('M d', strtotime($date));
    $dailyCounts[] = $dailyMap[$date] ?? 0;
    if (($dailyMap[$date] ?? 0) > $maxDaily) $maxDaily = $dailyMap[$date] ?? 1;
}

// Weekly trend - last 4 weeks only
$weeklySql = "SELECT 
                YEARWEEK(date_submitted, 1) as week_num,
                DATE(MIN(date_submitted)) as week_start,
                COUNT(*) as count
              FROM service_request 
              WHERE date_submitted >= DATE_SUB(NOW(), INTERVAL 4 WEEK)
              GROUP BY YEARWEEK(date_submitted, 1)
              ORDER BY week_start ASC
              LIMIT 8";
$weeklyResult = $conn->query($weeklySql);
$weeklyLabels = [];
$weeklyCounts = [];
$maxWeekly = 1;
while ($row = $weeklyResult->fetch_assoc()) {
    $weeklyLabels[] = date('M d', strtotime($row['week_start']));
    $weeklyCounts[] = $row['count'];
    if ($row['count'] > $maxWeekly) $maxWeekly = $row['count'];
}

// Peak hours
$hourSql = "SELECT 
              HOUR(date_submitted) as hour,
              COUNT(*) as count
            FROM service_request
            GROUP BY HOUR(date_submitted)
            ORDER BY hour ASC";
$hourResult = $conn->query($hourSql);
$hourMap = [];
while ($row = $hourResult->fetch_assoc()) {
    $hourMap[$row['hour']] = $row['count'];
}
$hourLabels = [];
$hourCounts = [];
$maxHour = 1;
for ($h = 0; $h <= 23; $h++) {
    $hourLabels[] = date('g A', mktime($h, 0, 0));
    $hourCounts[] = $hourMap[$h] ?? 0;
    if (($hourMap[$h] ?? 0) > $maxHour) $maxHour = $hourMap[$h] ?? 1;
}

// Payment methods
$paymentMethodSql = "SELECT 
                       payment_method, 
                       COUNT(*) as count
                     FROM payment
                     WHERE payment_method IS NOT NULL
                     GROUP BY payment_method";
$paymentMethodResult = $conn->query($paymentMethodSql);
$paymentMethods = [];
$paymentMethodCounts = [];
while ($row = $paymentMethodResult->fetch_assoc()) {
    $paymentMethods[] = ucfirst($row['payment_method']);
    $paymentMethodCounts[] = $row['count'];
}
$totalPayments = array_sum($paymentMethodCounts);

// Resident growth (last 6 months)
$growthSql = "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as new_households
              FROM household
              WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
              GROUP BY DATE_FORMAT(created_at, '%Y-%m')
              ORDER BY month ASC";
$growthResult = $conn->query($growthSql);
$growthLabels = [];
$growthCounts = [];
$maxGrowth = 1;
while ($row = $growthResult->fetch_assoc()) {
    $growthLabels[] = date('M Y', strtotime($row['month'] . '-01'));
    $growthCounts[] = $row['new_households'];
    if ($row['new_households'] > $maxGrowth) $maxGrowth = $row['new_households'];
}

// Average resolution time
$resolutionSql = "SELECT 
                    AVG(TIMESTAMPDIFF(DAY, date_submitted, NOW())) as avg_days
                  FROM complaint
                  WHERE status = 'resolved'";
$resolutionResult = $conn->query($resolutionSql);
$avgResolution = round($resolutionResult->fetch_assoc()['avg_days'] ?? 0, 1);

// Complaints by category
$categorySql = "SELECT category, COUNT(*) as count FROM complaint GROUP BY category";
$categoryResult = $conn->query($categorySql);
$categories = [];
$categoryCounts = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = ucfirst($row['category']);
    $categoryCounts[] = $row['count'];
}
$maxCategory = max($categoryCounts) ?: 1;

// Recent requests
$recentRequestsSql = "SELECT sr.*, s.service_name, 
                      (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = sr.household_id AND is_head = 1 LIMIT 1) as resident_name
                      FROM service_request sr
                      JOIN service s ON sr.service_id = s.service_id
                      ORDER BY sr.date_submitted DESC LIMIT 5";
$recentRequests = $conn->query($recentRequestsSql)->fetch_all(MYSQLI_ASSOC);

// Recent complaints
$recentComplaintsSql = "SELECT c.*, 
                        (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = c.household_id AND is_head = 1 LIMIT 1) as resident_name
                        FROM complaint c
                        ORDER BY c.date_submitted DESC LIMIT 5";
$recentComplaints = $conn->query($recentComplaintsSql)->fetch_all(MYSQLI_ASSOC);

// Top services
$topServicesSql = "SELECT s.service_name, COUNT(sr.request_id) as request_count
                   FROM service s
                   LEFT JOIN service_request sr ON s.service_id = sr.service_id
                   GROUP BY s.service_id
                   ORDER BY request_count DESC LIMIT 5";
$topServices = $conn->query($topServicesSql)->fetch_all(MYSQLI_ASSOC);

// Monthly trend (last 12 months)
$monthlySql = "SELECT 
                DATE_FORMAT(date_submitted, '%Y-%m') as month,
                COUNT(*) as count 
               FROM service_request 
               WHERE date_submitted >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
               GROUP BY DATE_FORMAT(date_submitted, '%Y-%m')
               ORDER BY month ASC";
$monthlyResult = $conn->query($monthlySql);

$monthlyData = [];
while ($row = $monthlyResult->fetch_assoc()) {
    $monthlyData[$row['month']] = $row['count'];
}

// Generate last 12 months labels
$monthlyLabels12 = [];
$monthlyCounts12 = [];
$maxMonthly12 = 1;

for ($i = 11; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $monthLabel = date('M Y', strtotime($month . '-01'));
    $count = $monthlyData[$month] ?? 0;
    
    $monthlyLabels12[] = $monthLabel;
    $monthlyCounts12[] = $count;
    if ($count > $maxMonthly12) $maxMonthly12 = $count;
}
if ($maxMonthly12 == 0) $maxMonthly12 = 1;

// Status distribution
$statusSql = "SELECT status, COUNT(*) as count FROM service_request GROUP BY status";
$statusResult = $conn->query($statusSql);
$statusStats = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusStats[$row['status']] = $row['count'];
}
$statuses = ['pending', 'approved', 'processing', 'completed', 'rejected'];
$statusColors = ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'];
$statusLabels = [__('pending'), __('approved'), __('processing'), __('completed'), __('rejected')];
$totalRequests = array_sum($statusStats);
?>

<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/reports.css">

<div class="reports-container">
    <!-- Statistics Cards -->
    <div class="reports-stats-grid">
        <div class="reports-stat-card">
            <div class="reports-stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="reports-stat-info">
                <p><?php echo __('total-residents'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_residents'] ?? 0); ?></p>
            </div>
        </div>
        <div class="reports-stat-card">
            <div class="reports-stat-icon green"><i class="fas fa-home"></i></div>
            <div class="reports-stat-info">
                <p><?php echo __('total-households'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_households'] ?? 0); ?></p>
            </div>
        </div>
        <div class="reports-stat-card">
            <div class="reports-stat-icon purple"><i class="fas fa-check-circle"></i></div>
            <div class="reports-stat-info">
                <p><?php echo __('total-voters'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_voters'] ?? 0); ?></p>
            </div>
        </div>
        <div class="reports-stat-card">
            <div class="reports-stat-icon orange"><i class="fas fa-file-alt"></i></div>
            <div class="reports-stat-info">
                <p><?php echo __('total-service-requests'); ?></p>
                <p class="reports-stat-number"><?php echo number_format($stats['total_requests'] ?? 0); ?></p>
            </div>
        </div>
    </div>

    <!-- Daily Requests Chart -->
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-chart-line"></i> <?php echo __('daily-requests'); ?></h3>
            <span class="badge-blue badge"><?php echo __('total'); ?>: <?php echo array_sum($dailyCounts); ?></span>
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

    <!-- Weekly Trend (Vertical Bar Chart - Last 4 Weeks) -->
    <?php if (!empty($weeklyLabels)): ?>
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-chart-bar"></i> <?php echo __('weekly-trend'); ?></h3>
            <span class="badge-blue badge"><?php echo __('peak'); ?>: <?php echo max($weeklyCounts); ?> <?php echo __('requests'); ?></span>
        </div>
        <div class="report-card-body">
            <div class="vertical-bar-chart">
                <?php foreach ($weeklyLabels as $index => $label): ?>
                <div class="vertical-bar-item">
                    <div class="vertical-bar-container">
                        <div class="vertical-bar" style="height: <?php echo ($weeklyCounts[$index] / $maxWeekly) * 150; ?>px;"></div>
                        <div class="vertical-bar-value"><?php echo $weeklyCounts[$index]; ?></div>
                    </div>
                    <div class="vertical-bar-label"><?php echo $label; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="reports-two-columns">
        <!-- Complaints by Category -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-chart-pie"></i> <?php echo __('complaints-by-category'); ?></h3>
                <span class="badge-orange badge"><?php echo __('total'); ?>: <?php echo array_sum($categoryCounts); ?></span>
            </div>
            <div class="report-card-body">
                <?php if (empty($categories)): ?>
                    <div class="empty-state"><i class="fas fa-inbox"></i><p><?php echo __('no-data'); ?></p></div>
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

        <!-- Payment Methods -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-credit-card"></i> <?php echo __('payment-methods'); ?></h3>
                <span class="badge-green badge"><?php echo __('total'); ?>: <?php echo $totalPayments; ?> <?php echo __('payments'); ?></span>
            </div>
            <div class="report-card-body">
                <?php if (empty($paymentMethods)): ?>
                    <div class="empty-state"><i class="fas fa-inbox"></i><p><?php echo __('no-data'); ?></p></div>
                <?php else: ?>
                    <div class="payment-cards">
                        <?php foreach ($paymentMethods as $index => $method): 
                            $percent = $totalPayments > 0 ? round(($paymentMethodCounts[$index] / $totalPayments) * 100) : 0;
                        ?>
                        <div class="payment-card <?php echo strtolower($method); ?>">
                            <div class="payment-icon">
                                <?php if ($method == 'Cash'): ?>
                                    <i class="fas fa-money-bill-wave"></i>
                                <?php elseif ($method == 'Gcash'): ?>
                                    <i class="fab fa-gcash"></i>
                                <?php else: ?>
                                    <i class="fas fa-university"></i>
                                <?php endif; ?>
                            </div>
                            <div class="payment-percent"><?php echo $percent; ?>%</div>
                            <div class="payment-label"><?php echo $method; ?></div>
                            <div class="payment-count">(<?php echo $paymentMethodCounts[$index]; ?> <?php echo __('payments'); ?>)</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="reports-two-columns">
        <!-- Peak Hours -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-clock"></i> <?php echo __('peak-hours'); ?></h3>
                <span class="badge-blue badge"><?php echo __('most-active'); ?>: <?php 
                    $maxHourIndex = array_search(max($hourCounts), $hourCounts);
                    echo $hourLabels[$maxHourIndex] ?? 'N/A';
                ?></span>
            </div>
            <div class="report-card-body">
                <div class="hours-grid">
                    <?php foreach ($hourLabels as $index => $label):
                        $count = $hourCounts[$index];
                        $percent = $maxHour > 0 ? ($count / $maxHour) * 100 : 0;
                        $intensity = $percent >= 66 ? 'high' : ($percent >= 33 ? 'medium' : 'low');
                    ?>
                    <div class="hour-block <?php echo $intensity; ?>">
                        <div class="hour-time"><?php echo $label; ?></div>
                        <div class="hour-count"><?php echo $count; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Household Growth -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-chart-line"></i> <?php echo __('household-growth'); ?></h3>
                <span class="badge-green badge"><?php echo __('total'); ?>: <?php echo array_sum($growthCounts); ?> <?php echo __('new'); ?></span>
            </div>
            <div class="report-card-body">
                <?php if (empty($growthLabels)): ?>
                    <div class="empty-state"><i class="fas fa-inbox"></i><p><?php echo __('no-data'); ?></p></div>
                <?php else: ?>
                    <div class="growth-chart">
                        <?php foreach ($growthLabels as $index => $label): ?>
                        <div class="growth-bar">
                            <div class="growth-label"><?php echo $label; ?></div>
                            <div class="growth-bar-container">
                                <div class="growth-bar-fill" style="width: <?php echo ($growthCounts[$index] / $maxGrowth) * 100; ?>%;">
                                    <?php echo $growthCounts[$index]; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="reports-two-columns">
        <!-- Resolution Time -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-hourglass-half"></i> <?php echo __('avg-resolution'); ?></h3>
            </div>
            <div class="report-card-body">
                <div class="resolution-card">
                    <div class="resolution-number"><?php echo $avgResolution; ?> <span style="font-size: 1rem;"><?php echo __('days'); ?></span></div>
                    <div class="resolution-label"><?php echo __('avg-resolution-desc'); ?></div>
                </div>
            </div>
        </div>

        <!-- Request Funnel -->
        <div class="report-card">
            <div class="report-card-header">
                <h3><i class="fas fa-filter"></i> <?php echo __('request-funnel'); ?></h3>
            </div>
            <div class="report-card-body">
                <div class="funnel-container">
                    <div class="funnel-step pending"><span>⬇ <?php echo __('pending'); ?>: <?php echo $statusStats['pending'] ?? 0; ?></span></div>
                    <div class="funnel-step approved"><span>⬇ <?php echo __('approved'); ?>: <?php echo $statusStats['approved'] ?? 0; ?></span></div>
                    <div class="funnel-step processing"><span>⬇ <?php echo __('processing'); ?>: <?php echo $statusStats['processing'] ?? 0; ?></span></div>
                    <div class="funnel-step completed"><span>✓ <?php echo __('completed'); ?>: <?php echo $statusStats['completed'] ?? 0; ?></span></div>
                    <div class="funnel-step rejected"><span>✗ <?php echo __('rejected'); ?>: <?php echo $statusStats['rejected'] ?? 0; ?></span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Services -->
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-trophy"></i> <?php echo __('top-requested-services'); ?></h3>
            <span class="badge-green badge"><?php echo __('most-popular'); ?></span>
        </div>
        <div class="report-card-body">
            <?php if (empty($topServices)): ?>
                <div class="empty-state"><i class="fas fa-inbox"></i><p><?php echo __('no-data'); ?></p></div>
            <?php else: ?>
                <?php foreach ($topServices as $service): ?>
                <div class="top-service-item">
                    <span class="top-service-name"><?php echo htmlspecialchars($service['service_name']); ?></span>
                    <span class="top-service-count"><?php echo $service['request_count']; ?> <?php echo __('requests'); ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Monthly Trend (Simple Bar Chart - Last 12 Months) -->
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-chart-bar"></i> <?php echo __('monthly-trend'); ?></h3>
            <span class="badge-green badge"><?php echo __('peak'); ?>: <?php echo $maxMonthly12; ?> <?php echo __('requests'); ?></span>
        </div>
        <div class="report-card-body">
            <div class="monthly-chart">
                <?php foreach ($monthlyLabels12 as $index => $label): ?>
                <div class="monthly-item">
                    <div class="monthly-label"><?php echo $label; ?></div>
                    <div class="monthly-bar-container">
                        <div class="monthly-bar" style="width: <?php echo ($monthlyCounts12[$index] / $maxMonthly12) * 100; ?>%;">
                            <?php if ($monthlyCounts12[$index] > 0): ?>
                            <span><?php echo $monthlyCounts12[$index]; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Request Status Distribution -->
    <div class="report-card">
        <div class="report-card-header">
            <h3><i class="fas fa-chart-simple"></i> <?php echo __('request-status-distribution'); ?></h3>
        </div>
        <div class="report-card-body">
            <div class="progress-ring-container">
                <?php foreach ($statuses as $index => $status): 
                    $count = $statusStats[$status] ?? 0;
                    $percentage = $totalRequests > 0 ? round(($count / $totalRequests) * 100) : 0;
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