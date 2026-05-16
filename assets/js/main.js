// Global functions and event listeners
document.addEventListener('DOMContentLoaded', function() {
    initLanguageDropdown();
});

function initLanguageDropdown() {
    const langBtn = document.getElementById('langBtn');
    const langMenu = document.getElementById('langMenu');
    const langArrow = document.getElementById('langArrow');
    
    if (langBtn && langMenu) {
        langBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = langMenu.classList.contains('show');
            langMenu.classList.toggle('show');
            
            if (langArrow) {
                langArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
        
        document.addEventListener('click', function(e) {
            if (langMenu.classList.contains('show')) {
                if (!langMenu.contains(e.target) && e.target !== langBtn) {
                    langMenu.classList.remove('show');
                    if (langArrow) langArrow.style.transform = 'rotate(0deg)';
                }
            }
        });
    }
}

function changeLanguage(lang) {
    window.location.href = '?lang=' + lang;
}

function closeAnnouncement(campaignId) {
    const d = new Date();
    d.setTime(d.getTime() + (7 * 24 * 60 * 60 * 1000));
    document.cookie = "announcement_closed=" + campaignId + "; expires=" + d.toUTCString() + "; path=/";
    
    const bar = document.getElementById('topAnnouncementBar');
    if (bar) bar.style.display = 'none';
}

function toggleModal(modalId, show) {
    const modal = document.getElementById(modalId);
    if (modal) {
        if (show) {
            modal.classList.remove('hidden');
            setTimeout(() => modal.classList.add('active'), 10);
        } else {
            modal.classList.remove('active');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }
}