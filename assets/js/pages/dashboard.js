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

function logoutUser() {
    window.location.href = '../includes/logout.php';
}

// Tab switching
function switchTab(tab) {
    const householdsSection = document.getElementById('section-households');
    const complaintsSection = document.getElementById('section-complaints');
    const householdsBtn = document.getElementById('nav-households');
    const complaintsBtn = document.getElementById('nav-complaints');
    
    if (tab === 'households') {
        if (householdsSection) householdsSection.classList.remove('hidden-section');
        if (complaintsSection) complaintsSection.classList.add('hidden-section');
        if (householdsBtn) {
            householdsBtn.classList.remove('btn-inactive');
            householdsBtn.classList.add('btn-active');
            complaintsBtn.classList.remove('btn-active');
            complaintsBtn.classList.add('btn-inactive');
        }
    } else {
        if (complaintsSection) complaintsSection.classList.remove('hidden-section');
        if (householdsSection) householdsSection.classList.add('hidden-section');
        if (complaintsBtn) {
            complaintsBtn.classList.remove('btn-inactive');
            complaintsBtn.classList.add('btn-active');
            householdsBtn.classList.remove('btn-active');
            householdsBtn.classList.add('btn-inactive');
        }
    }
}

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

// Filter table
function filterTable() {
    const search = document.getElementById("globalSearch");
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
        
        if (!phaseMatch && cells[4]) {
            phaseMatch = cells[4].textContent.trim() === currentPhase;
        }
        
        rows[i].style.display = (textMatch && phaseMatch) ? "" : "none";
    }
}

// Complaint response modal
function openRespondModal(complaintId, subject, description, currentStatus, currentResponse) {
    document.getElementById('modal-complaint-id').value = complaintId;
    document.getElementById('modal-subject').innerText = subject;
    document.getElementById('modal-desc').innerText = description;
    document.getElementById('modal-status').value = currentStatus;
    document.getElementById('modal-response').value = currentResponse || '';
    toggleModal('complaint-modal', true);
}

function toggleModal(id, show) {
    const modal = document.getElementById(id);
    if (modal) {
        if (show) {
            modal.classList.remove('hidden-view');
        } else {
            modal.classList.add('hidden-view');
        }
    }
}

// Submit complaint response
const responseForm = document.getElementById('responseForm');
if (responseForm) {
    responseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const complaintId = document.getElementById('modal-complaint-id').value;
        const status = document.getElementById('modal-status').value;
        const response = document.getElementById('modal-response').value;
        
        const formData = new FormData();
        formData.append('complaint_id', complaintId);
        formData.append('status', status);
        formData.append('response', response);
        
        fetch('update_complaint.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if (result.includes('success')) {
                toggleModal('complaint-modal', false);
                location.reload();
            } else {
                alert('Error updating complaint. Please try again.');
            }
        })
        .catch(error => {
            alert('Error updating complaint. Please try again.');
        });
    });
}