document.addEventListener('DOMContentLoaded', function() {
    const langBtn = document.getElementById('langBtn');
    const langMenu = document.getElementById('langMenu');
    const langArrow = document.getElementById('langArrow');
    
    if (langBtn && langMenu) {
        langBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = langMenu.classList.contains('show');
            langMenu.classList.toggle('show');
            
            if (langArrow) {
                if (!isOpen) {
                    langArrow.style.transform = 'rotate(180deg)';
                } else {
                    langArrow.style.transform = 'rotate(0deg)';
                }
            }
        });
        
        document.addEventListener('click', function(e) {
            if (langMenu.classList.contains('show')) {
                if (!langMenu.contains(e.target) && e.target !== langBtn) {
                    langMenu.classList.remove('show');
                    if (langArrow) {
                        langArrow.style.transform = 'rotate(0deg)';
                    }
                }
            }
        });
    }
});

function changeLanguage(lang) {
    window.location.href = '?lang=' + lang;
}

function closeAnnouncement(campaignId) {
    // Set cookie that expires in 7 days
    const d = new Date();
    d.setTime(d.getTime() + (7 * 24 * 60 * 60 * 1000));
    document.cookie = "announcement_closed=" + campaignId + "; expires=" + d.toUTCString() + "; path=/";
    
    // Hide the announcement bar
    const bar = document.getElementById('topAnnouncementBar');
    if (bar) {
        bar.style.display = 'none';
    }
}