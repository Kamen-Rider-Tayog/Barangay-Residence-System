<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$stats = getDashboardStats();

// Get monthly request data for chart
$monthlyData = [];
for ($i = 1; $i <= 12; $i++) {
    $monthlyData[$i] = 0;
}
$sql = "SELECT MONTH(date_submitted) as month, COUNT(*) as count FROM service_request WHERE YEAR(date_submitted) = YEAR(NOW()) GROUP BY MONTH(date_submitted)";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $monthlyData[$row['month']] = $row['count'];
}

// Get complaint categories for pie chart
$categories = ['infrastructure' => 0, 'peace_order' => 0, 'sanitation' => 0, 'noise' => 0, 'other' => 0];
$sql = "SELECT category, COUNT(*) as count FROM complaint GROUP BY category";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $categories[$row['category']] = $row['count'];
}

// Get recent service requests
$recentRequests = getRows("SELECT sr.*, s.service_name FROM service_request sr JOIN service s ON sr.service_id = s.service_id ORDER BY sr.date_submitted DESC LIMIT 5");

// Get recent complaints
$recentComplaints = getRows("SELECT * FROM complaint ORDER BY date_submitted DESC LIMIT 5");
?>

<div class="reports-dashboard">
    <div class="stats-grid">
        <div class="card card-hover stat-card">
            <div class="stat-icon bg-blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <p>Total Residents</p>
                <p class="stat-number"><?php echo $stats['total_residents']; ?></p>
            </div>
        </div>
        <div class="card card-hover stat-card">
            <div class="stat-icon bg-green">
                <i class="fas fa-home"></i>
            </div>
            <div class="stat-info">
                <p>Households</p>
                <p class="stat-number"><?php echo $stats['total_households']; ?></p>
            </div>
        </div>
        <div class="card card-hover stat-card">
            <div class="stat-icon bg-purple">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <p>Transactions</p>
                <p class="stat-number"><?php echo $stats['total_requests']; ?></p>
            </div>
        </div>
        <div class="card card-hover stat-card">
            <div class="stat-icon bg-orange">
                <i class="fas fa-comment"></i>
            </div>
            <div class="stat-info">
                <p>Pending Complaints</p>
                <p class="stat-number"><?php echo $stats['pending_complaints']; ?></p>
            </div>
        </div>
    </div>

    <div class="charts-row">
        <div class="card chart-card">
            <h3 class="chart-title">Monthly Service Requests</h3>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>
        <div class="card chart-card">
            <h3 class="chart-title">Complaints by Category</h3>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
    </div>

    <div class="recent-activity">
        <div class="card">
            <div class="card-header">
                <h3>Recent Service Requests</h3>
                <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn btn-sm">View All</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead><tr><th>Service</th><th>Reference</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentRequests as $req): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($req['service_name']); ?></td>
                            <td><?php echo $req['ref_no']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($req['date_submitted'])); ?></td>
                            <td><span class="badge badge-<?php echo $req['status'] == 'completed' ? 'green' : 'orange'; ?>"><?php echo $req['status']; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>Recent Complaints</h3>
                <a href="/barangay-residence-system/pages/dashboard.php?tab=complaints" class="btn btn-sm">View All</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead><tr><th>Subject</th><th>Reference</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentComplaints as $comp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(substr($comp['subject'], 0, 30)); ?></td>
                            <td><?php echo $comp['ref_no']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($comp['date_submitted'])); ?></td>
                            <td><span class="badge badge-<?php echo $comp['status'] == 'resolved' ? 'green' : 'orange'; ?>"><?php echo $comp['status']; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            data: [<?php echo implode(',', $monthlyData); ?>],
            backgroundColor: '#3b82f6',
            borderRadius: 4
        }]
    },
    options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: ['Infrastructure', 'Peace & Order', 'Sanitation', 'Noise', 'Other'],
        datasets: [{
            data: [<?php echo $categories['infrastructure']; ?>, <?php echo $categories['peace_order']; ?>, <?php echo $categories['sanitation']; ?>, <?php echo $categories['noise']; ?>, <?php echo $categories['other']; ?>],
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
        }]
    },
    options: { responsive: true, maintainAspectRatio: true }
});
</script>
