<?php
require_once '../includes/core/init.php';
$campaigns = getActiveCampaigns();

include '../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/campaigns.css">
<?php include '../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="campaigns-header">
        <h2><?php echo __('campaigns-title'); ?></h2>
        <p><?php echo __('campaigns-subtitle'); ?></p>
    </div>

    <div class="campaigns-grid">
        <?php if (empty($campaigns)): ?>
            <div class="no-campaigns">
                <i class="fas fa-calendar-times"></i>
                <p><?php echo __('no-campaigns'); ?></p>
            </div>
        <?php else: ?>
            <?php foreach ($campaigns as $campaign): ?>
            <div class="card campaign-card" data-end-date="<?php echo $campaign['end_date']; ?>">
                <div class="campaign-type <?php echo $campaign['type']; ?>">
                    <?php echo ucfirst($campaign['type']); ?>
                </div>
                <h3><?php echo htmlspecialchars($campaign['title']); ?></h3>
                <p class="campaign-desc"><?php echo htmlspecialchars($campaign['description']); ?></p>
                <div class="campaign-dates">
                    <div class="date-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Starts: <?php echo date('M d, Y', strtotime($campaign['start_date'])); ?></span>
                    </div>
                    <div class="date-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Ends: <?php echo date('M d, Y', strtotime($campaign['end_date'])); ?></span>
                    </div>
                </div>
                <div class="countdown-timer" data-end="<?php echo $campaign['end_date']; ?>">
                    <div class="timer-box">
                        <span class="timer-days">00</span>
                        <span class="timer-label">Days</span>
                    </div>
                    <div class="timer-box">
                        <span class="timer-hours">00</span>
                        <span class="timer-label">Hours</span>
                    </div>
                    <div class="timer-box">
                        <span class="timer-minutes">00</span>
                        <span class="timer-label">Mins</span>
                    </div>
                    <div class="timer-box">
                        <span class="timer-seconds">00</span>
                        <span class="timer-label">Secs</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script>
function updateCountdowns() {
    const timers = document.querySelectorAll('.countdown-timer');
    const now = new Date().getTime();
    
    timers.forEach(timer => {
        const endDate = new Date(timer.getAttribute('data-end')).getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            timer.innerHTML = '<div class="expired-badge">Campaign Ended</div>';
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        timer.querySelector('.timer-days').textContent = days.toString().padStart(2, '0');
        timer.querySelector('.timer-hours').textContent = hours.toString().padStart(2, '0');
        timer.querySelector('.timer-minutes').textContent = minutes.toString().padStart(2, '0');
        timer.querySelector('.timer-seconds').textContent = seconds.toString().padStart(2, '0');
    });
}

setInterval(updateCountdowns, 1000);
updateCountdowns();
</script>

<?php include '../includes/layouts/footer.php'; ?>