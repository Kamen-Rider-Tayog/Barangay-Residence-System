// Campaigns page - countdown timers
document.addEventListener('DOMContentLoaded', function() {
    updateAllCountdowns();
    setInterval(updateAllCountdowns, 1000);
});

function updateAllCountdowns() {
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
        
        if (timer.querySelector('.timer-days')) {
            timer.querySelector('.timer-days').textContent = days.toString().padStart(2, '0');
            timer.querySelector('.timer-hours').textContent = hours.toString().padStart(2, '0');
            timer.querySelector('.timer-minutes').textContent = minutes.toString().padStart(2, '0');
            timer.querySelector('.timer-seconds').textContent = seconds.toString().padStart(2, '0');
        }
    });
}