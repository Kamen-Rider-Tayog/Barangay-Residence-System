// Initialize Icons
lucide.createIcons();

// Translation Data
const translations = {
    en: {
        "sub-header": "Information & Service Management",
        "f-contact": "Contact Us",
        "f-links": "Quick Links",
        "f-follow": "Follow Us",
        "footer-copy": "© 2026 Brgy. San Francisco, General Trias. All Rights Reserved.",
        "nav-home": "Home",
        "nav-services": "Online Services",
        "login": "Login",
        "logout": "Logout"
    },
    tl: {
        "sub-header": "Pamamahala ng Impormasyon at Serbisyo",
        "f-contact": "Makipag-ugnayan",
        "f-links": "Mabilis na Links",
        "f-follow": "Sundan Kami",
        "footer-copy": "© 2026 Barangay San Francisco, General Trias. Lahat ng Karapatan ay Nakareserba.",
        "nav-home": "Home",
        "nav-services": "Serbisyong Online",
        "login": "Mag-login",
        "logout": "Mag-logout"
    }
};

function changeLanguage(lang) {
    document.querySelectorAll('[data-key]').forEach(elem => {
        const key = elem.getAttribute('data-key');
        if (translations[lang][key]) elem.textContent = translations[lang][key];
    });
    document.getElementById('currentLangLabel').textContent = lang === 'en' ? 'Language' : 'Wika';
    document.getElementById('langMenu').classList.add('hidden');
}
// --- Language Dropdown ---
const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');

if (langBtn) {
    langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        langMenu.classList.toggle('hidden');
    });
}

window.addEventListener('click', () => {
    if (langMenu) langMenu.classList.add('hidden');
});


        
function toggleModal(id, show) { const modal = document.getElementById(id); if(show) modal.classList.remove('hidden-view'); else modal.classList.add('hidden-view'); }
        
function openRespondModal(subject, desc, status, response) {
    document.getElementById('modal-subject').innerText = subject;
    document.getElementById('modal-desc').innerText = desc;
    document.getElementById('modal-status').value = status;
    document.getElementById('modal-response').value = response;
    toggleModal('complaint-modal', true);
}

function switchMainTab(tab) {
    const sections = ['section-households', 'section-complaints', 'form-view'];
    const header = document.getElementById('static-dashboard-top');
    sections.forEach(s => document.getElementById(s).classList.add('hidden-view'));
    if (tab === 'households' || tab === 'complaints') {
        header.classList.remove('hidden-view');
        document.getElementById('section-' + tab).classList.remove('hidden-view');
        document.getElementById('nav-households').className = tab === 'households' ? "btn-nav btn-active" : "btn-nav btn-inactive";
        document.getElementById('nav-complaints').className = tab === 'complaints' ? "btn-nav btn-active" : "btn-nav btn-inactive";
    } else { header.classList.add('hidden-view'); document.getElementById('form-view').classList.remove('hidden-view'); }
}

function filterTable() {
    const search = document.getElementById("globalSearch").value.toUpperCase();
    const phase = document.getElementById("phaseFilter").value;
    const tr = document.getElementById("householdTable").getElementsByTagName("tr");
    for (let i = 1; i < tr.length; i++) {
        let match = false;
        const cells = tr[i].getElementsByTagName("td");
        for (let j = 0; j < cells.length - 1; j++) {
            if (cells[j] && cells[j].textContent.toUpperCase().includes(search)) { match = true; break; }
        }
        const phaseMatch = (phase === "All" || cells[4].textContent.trim() === phase);
        tr[i].style.display = (match && phaseMatch) ? "" : "none";
    }
}

function triggerFileUpload() { if (document.getElementById('pic-box').classList.contains('pic-changeable')) document.getElementById('image-input').click(); }
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('pic-preview'), placeholder = document.getElementById('pic-placeholder');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); placeholder.classList.add('hidden'); };
        reader.readAsDataURL(file);
    }
}

function handleFormPage(mode, f='', m='', l='', s='', age='', addr='', contact='', phase='', total=1, email='', pass='') {
    switchMainTab('form');
    document.getElementById('f-name').value = f; document.getElementById('m-name').value = m; document.getElementById('l-name').value = l;
    document.getElementById('s-name').value = s; document.getElementById('h-age').value = age; document.getElementById('h-addr').value = addr;
    document.getElementById('h-contact').value = contact; document.getElementById('h-phase').value = phase; document.getElementById('res-count').value = total;
    document.getElementById('h-email').value = email; document.getElementById('reg-password').value = pass;
    updateMemberFields(total, mode === 'view');
    const picBox = document.getElementById('pic-box'), allInputs = document.querySelectorAll('#main-form input, #main-form select');
    if (mode === 'view') { document.getElementById('form-title').innerText = "Resident Profile"; document.getElementById('form-actions').classList.add('hidden'); picBox.classList.remove('pic-changeable'); allInputs.forEach(el => el.disabled = true); }
    else { 
        document.getElementById('form-title').innerText = mode === 'edit' ? "Edit Records" : "New Registration"; 
        document.getElementById('form-actions').classList.remove('hidden'); picBox.classList.add('pic-changeable'); 
        allInputs.forEach(el => { el.disabled = false; if(mode === 'edit' && (el.id === 'h-addr' || el.id === 'h-phase')) el.disabled = true; }); 
    }
}
function showAddPage() { handleFormPage('add'); }

function updateMemberFields(count, isReadOnly = false) {
    const container = document.getElementById('member-container'); container.innerHTML = ''; if (count <= 1) return;
    const title = document.createElement('div'); title.className = 'text-slate-800 font-bold border-b mt-6 pb-2 uppercase text-[10px]'; title.innerText = `Additional Household Members (${count - 1})`; container.appendChild(title);
    for (let i = 1; i < count; i++) {
        const div = document.createElement('div'); div.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-slate-50 border rounded-xl mb-4 mt-4';
        div.innerHTML = `<div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Member #${i+1} Name</label><input type="text" ${isReadOnly ? 'disabled' : ''} class="w-full border rounded px-3 py-1.5 text-sm outline-none focus:border-slate-400"></div>
            <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Age</label><input type="number" min="0" ${isReadOnly ? 'disabled' : ''} class="w-full border rounded px-3 py-1.5 text-sm outline-none focus:border-slate-400"></div>
            <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Role</label><select ${isReadOnly ? 'disabled' : ''} class="w-full border rounded px-3 py-1.5 text-sm outline-none">
                <option>Spouse</option><option>Daughter</option><option>Son</option><option>Parent</option><option>Grandparent</option><option>Relative</option><option>Other</option>
            </select></div>`;
        container.appendChild(div);
    }
}


new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{ data: [45, 52, 48, 61, 55], backgroundColor: '#3b82f6', borderRadius: 4 }]
    },
    options: { plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Residency', 'Clearance', 'Indigency', 'Business'],
        datasets: [{ 
            data: [29, 43, 13, 16], 
            backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#f59e0b'],
            hoverOffset: 15
        }]
    },
    options: { 
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            animateRotate: true, 
            duration: 2000,           
            easing: 'easeInOutQuart'
        },
        plugins: { 
            legend: { 
                position: 'right', // Moves the color titles to the right side
                align: 'center',
                labels: { 
                    boxWidth: 15, 
                    padding: 15, 
                    font: { size: 12, weight: 'bold' } 
                } 
            } 
        },
        layout: {
            padding: {
                left: 10,
                right: 20
            }
        }
    }
});

const loginBtn = document.querySelector('[data-key="login"]');
if (loginBtn) {
    loginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.location.href = 'login.html'; 
    });
}