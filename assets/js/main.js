// Vanilla JS - No external libraries
document.addEventListener('DOMContentLoaded', function() {
    // Language dropdown
    const langBtn = document.getElementById('langBtn');
    const langMenu = document.getElementById('langMenu');
    
    if (langBtn && langMenu) {
        langBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            langMenu.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function() {
            langMenu.classList.add('hidden');
        });
    }
});

function changeLanguage(lang) {
    window.location.href = '?lang=' + lang;
}