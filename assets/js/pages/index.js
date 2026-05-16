// Homepage countdown timer
document.addEventListener('DOMContentLoaded', function() {
    updateHomeCountdown();
    setInterval(updateHomeCountdown, 1000);
});

function updateHomeCountdown() {
    const countdownEl = document.getElementById('homeCountdown');
    if (!countdownEl) return;
    
    const endDate = countdownEl.getAttribute('data-end');
    if (!endDate) return;
    
    const now = new Date().getTime();
    const end = new Date(endDate).getTime();
    const distance = end - now;
    
    if (distance < 0) {
        countdownEl.innerHTML = 'Campaign Ended';
        return;
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    countdownEl.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
}