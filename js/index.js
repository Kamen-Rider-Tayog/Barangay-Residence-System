// Initialize Lucide Icons
lucide.createIcons();

// Dropdown Logic
const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');

if (langBtn && langMenu) {
    langBtn.addEventListener('click', (e) => { 
        e.stopPropagation(); 
        langMenu.classList.toggle('show'); 
    });

    window.addEventListener('click', () => { 
        langMenu.classList.remove('show'); 
    });
}

// Translation Data
const translations = {
    en: {
        "sub-header": "Information & Service Management",
        "nav-home": "Home", "nav-services": "Online Services", "login": "Login",
        "hero-title": "Welcome to Brgy. San Francisco",
        "hero-desc": "Your trusted partner in community service and development in General Trias. Access our online services and stay updated.",
        "btn-request": "Request Services", "btn-learn": "Learn More",
        "stat-residents": "Residents", "stat-households": "Households", "stat-voters": "Voters", "stat-services": "Services",
        "announcement-title": "Latest Announcements",
        "ann-1-title": "Community Clean-Up", "ann-1-desc": "Join us for a clean-up initiative in San Francisco this Saturday at 6:00 AM.",
        "ann-2-title": "Medical Mission", "ann-2-desc": "Free consultation and medicines available at the brgy. hall.",
        "ann-3-title": "Brgy. Assembly", "ann-3-desc": "Everyone is invited to the assembly at 2:00 PM.",
        "about-title": "About Brgy. San Francisco", "about-p1": "Located in General Trias, Cavite, we are dedicated to providing fast and honest public service for all Gentriseños.",
        "about-years": "Years of Service", "about-digital": "Digital Services",
        "map-address": "Brgy. Hall, San Francisco, General Trias, Cavite",
        "f-contact": "Contact Us", "f-links": "Quick Links", "f-follow": "Follow Us",
        "footer-copy": "© 2026 Brgy. San Francisco, General Trias. All Rights Reserved.",
        "label": "Language" 
    },
    tl: {
        "sub-header": "Pamamahala ng Impormasyon at Serbisyo",
        "nav-home": "Home", "nav-services": "Serbisyong Online", "login": "Mag-login",
        "hero-title": "Maligayang Pagdating sa Brgy. San Francisco",
        "hero-desc": "Ang inyong pinagkakatiwalaang katuwang sa serbisyo at pag-unlad ng komunidad sa General Trias. Gamitin ang aming online services at laging maging updated.",
        "btn-request": "Mag-request ng Serbisyo", "btn-learn": "Alamin ang Higit Pa",
        "stat-residents": "Residente", "stat-households": "Sambahayan", "stat-voters": "Botante", "stat-services": "Serbisyo",
        "announcement-title": "Pinakabagong Anunsyo",
        "ann-1-title": "Community Clean-Up", "ann-1-desc": "Samahan kami sa malawakang paglilinis sa San Francisco ngayong Sabado, 6:00 AM.",
        "ann-2-title": "Medical Mission", "ann-2-desc": "Libreng konsultasyon at gamot na makukuha sa ating brgy. hall.",
        "ann-3-title": "Brgy. Assembly", "ann-3-desc": "Inaanyayahan ang lahat na dumalo sa asembleya sa ganap na 2:00 PM.",
        "about-title": "Tungkol sa Brgy. San Francisco", "about-p1": "Matatagpuan sa General Trias, Cavite, kami ay nakatuon sa pagbibigay ng tapat at mabilis na serbisyo para sa bawat Gentriseño.",
        "about-years": "Taon ng Serbisyo", "about-digital": "Digital na Serbisyo",
        "map-address": "Brgy. Hall, San Francisco, General Trias, Cavite",
        "f-contact": "Makipag-ugnayan", "f-links": "Mabilis na Links", "f-follow": "Sumubaybay sa Amin",
        "footer-copy": "© 2026 Brgy. San Francisco, General Trias. Lahat ng Karapatan ay Nakareserba.",
        "label": "Wika"
    }
};

function changeLanguage(lang) {
    document.querySelectorAll('[data-key]').forEach(elem => {
        const key = elem.getAttribute('data-key');
        if (translations[lang][key]) elem.textContent = translations[lang][key];
    });
    const label = document.getElementById('currentLangLabel');
    if (label) label.textContent = translations[lang]['label'];
    
    if (langMenu) langMenu.classList.remove('show');
    document.documentElement.lang = lang;
}

// Login Redirection
const loginBtn = document.querySelector('[data-key="login"]');
if (loginBtn) {
    loginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.location.href = 'login.html'; 
    });
}