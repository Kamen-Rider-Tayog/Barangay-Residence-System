// --- 1. Header & Language Logic ---
const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');

// Toggle para sa Language Dropdown Menu
if (langBtn && langMenu) {
    langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        langMenu.classList.toggle('hidden');
    });
    // Isasara ang menu pag nag-click sa labas
    document.addEventListener('click', () => {
        if (langMenu) langMenu.classList.add('hidden');
    });
}

function changeLanguage(lang) {
    const translations = {
        'en': {
            "sub-header": "Information & Service Management",
            "nav-home": "Home", "nav-services": "Online Services", "login": "Login",
            'nav-home': 'Home',
            'profile-title': 'My Profile',
            'personal-info': 'Personal Information',
            'logout': 'Logout',
            'tab-trans': 'Transaction History',
            'tab-comp': 'My Complaints',
            'btn-request': 'Request Service',
            "f-contact": "Contact Us", "f-links": "Quick Links", "f-follow": "Follow Us",
            "footer-copy": "© 2026 Brgy. San Francisco, General Trias. All Rights Reserved.",
            "label": "Language"
        },
        'tl': {
            "sub-header": "Pamamahala ng Impormasyon at Serbisyo",
            "nav-home": "Home", "nav-services": "Serbisyong Online", "login": "Mag-login",
            'nav-home': 'Home',
            'profile-title': 'Aking Profile',
            'personal-info': 'Personal na Impormasyon',
            'logout': 'Mag-logout',
            'tab-trans': 'Kasaysayan ng Transaksyon',
            'tab-comp': 'Aking mga Reklamo',
            'btn-request': 'Humiling ng Serbisyo',
            "f-contact": "Makipag-ugnayan", "f-links": "Mabilis na Links", "f-follow": "Sumubaybay sa Amin",
            "footer-copy": "© 2026 Brgy. San Francisco, General Trias. Lahat ng Karapatan ay Nakareserba.",
            "label": "Wika"
        }
    };

    const elements = document.querySelectorAll('[data-key]');
    elements.forEach(el => {
        const key = el.getAttribute('data-key');
        if (translations[lang] && translations[lang][key]) {
            el.innerText = translations[lang][key];
        }
    });

    // I-update ang label sa main button
    const label = document.getElementById('currentLangLabel');
    if (label) {
        label.innerText = lang === 'en' ? 'English' : 'Wika';
    }
    
    if (langMenu) langMenu.classList.add('hidden');
}

function logoutUser() {
    localStorage.removeItem("isLoggedIn");
    window.location.href = "login.html";
}

// --- 2. UI Navigation ---
function showSection(type) {
    const comp = document.getElementById('section-complaints');
    const house = document.getElementById('section-households');
    const btnC = document.getElementById('btn-complaints');
    const btnH = document.getElementById('btn-households');

    if (type === 'complaints') {
        comp.classList.remove('hidden'); house.classList.add('hidden');
        btnC.classList.add('active-tab'); btnH.classList.remove('active-tab');
    } else {
        comp.classList.add('hidden'); house.classList.remove('hidden');
        btnH.classList.add('active-tab'); btnC.classList.remove('active-tab');
    }
}

function toggleModal(id, show) {
    const modal = document.getElementById(id);
    if (modal) {
        show ? modal.classList.remove('hidden') : modal.classList.add('hidden');
    }
}

// --- 3. Automatic Email Generation ---
const fnameInput = document.getElementById('head-fname');
const lnameInput = document.getElementById('head-lname');
const emailOutput = document.getElementById('gen-email');

function generateEmail() {
    if (!fnameInput || !lnameInput || !emailOutput) return;
    const fn = fnameInput.value.toLowerCase().replace(/\s/g, '');
    const ln = lnameInput.value.toLowerCase().replace(/\s/g, '');
    emailOutput.value = (fn && ln) ? `${fn}.${ln}@gmail.com` : "";
}

if (fnameInput && lnameInput) {
    fnameInput.addEventListener('input', generateEmail);
    lnameInput.addEventListener('input', generateEmail);
}

// --- 4. Dynamic Family Members Logic ---
const residentCount = document.getElementById('resident-count');
const familySection = document.getElementById('family-members-section');
const familyContainer = document.getElementById('family-container');

if (residentCount) {
    residentCount.addEventListener('input', function() {
        const val = parseInt(this.value);
        const otherMembersCount = val - 1;

        if (otherMembersCount > 0) {
            familySection.classList.remove('hidden');
            familyContainer.innerHTML = ""; 
            
            for (let i = 1; i <= otherMembersCount; i++) {
                const row = document.createElement('div');
                row.className = "grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-100";
                row.innerHTML = `
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Full Name (Member ${i})</label>
                        <input type="text" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Position (e.g. Wife, Son)</label>
                        <input type="text" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Age</label>
                        <input type="number" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                `;
                familyContainer.appendChild(row);
            }
        } else {
            familySection.classList.add('hidden');
            familyContainer.innerHTML = "";
        }
    });
}

// --- 5. Complaint Form & Tab Logic ---
function toggleComplaintModal(show) {
    const modal = document.getElementById('complaint-modal');
    if (!modal) return;
    if (show) {
        modal.classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    } else {
        modal.classList.add('hidden');
    }
}

function switchTab(tab) {
    const transTab = document.getElementById('transactions');
    const compTab = document.getElementById('complaints');
    const transBtn = document.getElementById('tab-trans-btn');
    const compBtn = document.getElementById('tab-comp-btn');
    const actionBtn = document.getElementById('action-btn');

    if (!transTab || !compTab || !actionBtn) return;

    if (tab === 'transactions') {
        transTab.classList.add('active');
        compTab.classList.remove('active');
        transBtn.className = "py-5 font-bold text-blue-600 border-b-2 border-blue-600 transition";
        compBtn.className = "py-5 font-bold text-gray-400 border-b-2 border-transparent hover:text-gray-600 transition";
        actionBtn.innerText = "Request Service";
        actionBtn.onclick = () => window.location.href = 'services.html';
    } else {
        compTab.classList.add('active');
        transTab.classList.remove('active');
        compBtn.className = "py-5 font-bold text-blue-600 border-b-2 border-blue-600 transition";
        transBtn.className = "py-5 font-bold text-gray-400 border-b-2 border-transparent hover:text-gray-600 transition";
        actionBtn.innerText = "File a Complaint";
        actionBtn.onclick = () => toggleComplaintModal(true);
    }
}

// --- 6. Form Submissions ---
document.getElementById('householdForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert("New household added!");
    toggleModal('add-household-modal', false);
    this.reset();
    if (familyContainer) familyContainer.innerHTML = "";
    if (familySection) familySection.classList.add('hidden');
});

document.getElementById('complaintForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert("Salamat! Ang iyong reklamo ay naisumite na.");
    toggleComplaintModal(false);
    this.reset();
});

// Initialize Icons on Load
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});