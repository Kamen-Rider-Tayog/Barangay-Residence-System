// Language dropdown
const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');

if (langBtn && langMenu) {
    langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        langMenu.classList.toggle('show');
    });
    document.addEventListener('click', () => {
        if (langMenu) langMenu.classList.remove('show');
    });
}

function changeLanguage(lang) {
    window.location.href = '?lang=' + lang;
}

// Tab switching
function switchTab(tab) {
    const householdsSection = document.getElementById('section-households');
    const complaintsSection = document.getElementById('section-complaints');
    const householdsBtn = document.getElementById('nav-households');
    const complaintsBtn = document.getElementById('nav-complaints');
    
    if (tab === 'households') {
        householdsSection.classList.remove('hidden-section');
        complaintsSection.classList.add('hidden-section');
        householdsBtn.classList.remove('btn-inactive');
        householdsBtn.classList.add('btn-active');
        complaintsBtn.classList.remove('btn-active');
        complaintsBtn.classList.add('btn-inactive');
    } else {
        complaintsSection.classList.remove('hidden-section');
        householdsSection.classList.add('hidden-section');
        complaintsBtn.classList.remove('btn-inactive');
        complaintsBtn.classList.add('btn-active');
        householdsBtn.classList.remove('btn-active');
        householdsBtn.classList.add('btn-inactive');
        
        // Refresh complaint filters when switching to complaints tab
        filterComplaints();
    }
}

// Complaint filtering
function filterComplaints() {
    const status = document.getElementById('complaintStatusFilter')?.value;
    const priority = document.getElementById('complaintPriorityFilter')?.value;
    const search = document.getElementById('complaintSearch')?.value.toLowerCase() || '';
    const cards = document.querySelectorAll('#complaintsGrid .complaint-card');
    
    cards.forEach(card => {
        const cardStatus = card.getAttribute('data-status');
        const cardPriority = card.getAttribute('data-priority');
        const cardSubject = card.getAttribute('data-subject') || '';
        const cardName = card.getAttribute('data-name') || '';
        
        let show = true;
        if (status && status !== 'all' && cardStatus !== status) show = false;
        if (priority && priority !== 'all' && cardPriority !== priority) show = false;
        if (search && !cardSubject.includes(search) && !cardName.includes(search)) show = false;
        
        card.style.display = show ? 'block' : 'none';
    });
}

// Set up filter event listeners
const statusFilter = document.getElementById('complaintStatusFilter');
const priorityFilter = document.getElementById('complaintPriorityFilter');
const complaintSearch = document.getElementById('complaintSearch');
if (statusFilter) statusFilter.addEventListener('change', filterComplaints);
if (priorityFilter) priorityFilter.addEventListener('change', filterComplaints);
if (complaintSearch) complaintSearch.addEventListener('keyup', filterComplaints);

// Phase filter dropdown
const phaseBtn = document.getElementById('phaseBtn');
const phaseMenu = document.getElementById('phaseMenu');

if (phaseBtn && phaseMenu) {
    phaseBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        phaseMenu.classList.toggle('show');
    });
    document.addEventListener('click', () => {
        if (phaseMenu) phaseMenu.classList.remove('show');
    });
}

let currentPhase = 'all';

function setPhaseFilter(phase) {
    currentPhase = phase;
    const phaseLabel = phase === 'all' ? 'All Phases' : phase;
    const currentPhaseLabel = document.getElementById('currentPhaseLabel');
    if (currentPhaseLabel) currentPhaseLabel.textContent = phaseLabel;
    if (phaseMenu) phaseMenu.classList.remove('show');
    filterTable();
}

function filterTable() {
    const search = document.getElementById("searchInput");
    const searchValue = search ? search.value.toUpperCase() : "";
    const table = document.getElementById("householdTable");
    if (!table) return;
    
    const rows = table.getElementsByTagName("tr");
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let textMatch = false;
        let phaseMatch = (currentPhase === 'all');
        
        for (let j = 0; j < cells.length - 1; j++) {
            if (cells[j] && cells[j].textContent.toUpperCase().includes(searchValue)) {
                textMatch = true;
                break;
            }
        }
        
        if (!phaseMatch && cells[3]) {
            phaseMatch = cells[3].textContent.trim() === currentPhase;
        }
        
        rows[i].style.display = (textMatch && phaseMatch) ? "" : "none";
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    // Set default active tab
    const householdsSection = document.getElementById('section-households');
    if (householdsSection && householdsSection.classList.contains('hidden-section')) {
        householdsSection.classList.remove('hidden-section');
    }
});