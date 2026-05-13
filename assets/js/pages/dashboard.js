// Initialize icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Language dropdown
const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');

if (langBtn && langMenu) {
    langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        langMenu.classList.toggle('hidden');
    });
    window.addEventListener('click', () => {
        if (langMenu) langMenu.classList.add('hidden');
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
            householdsBtn.className = "btn-nav btn-active";
            complaintsBtn.className = "btn-nav btn-inactive";
        }
    } else {
        if (complaintsSection) complaintsSection.classList.remove('hidden-section');
        if (householdsSection) householdsSection.classList.add('hidden-section');
        if (complaintsBtn) {
            complaintsBtn.className = "btn-nav btn-active";
            householdsBtn.className = "btn-nav btn-inactive";
        }
    }
}

// Filter table
function filterTable() {
    const search = document.getElementById("globalSearch");
    const phase = document.getElementById("phaseFilter");
    if (!search || !phase) return;
    
    const searchValue = search.value.toUpperCase();
    const phaseValue = phase.value;
    const table = document.getElementById("householdTable");
    if (!table) return;
    
    const tr = table.getElementsByTagName("tr");
    for (let i = 1; i < tr.length; i++) {
        let match = false;
        const cells = tr[i].getElementsByTagName("td");
        for (let j = 0; j < cells.length - 1; j++) {
            if (cells[j] && cells[j].textContent.toUpperCase().includes(searchValue)) {
                match = true;
                break;
            }
        }
        const phaseMatch = (phaseValue === "All" || (cells[4] && cells[4].textContent.trim() === phaseValue));
        tr[i].style.display = (match && phaseMatch) ? "" : "none";
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

// Charts (keep as is - they work)
if (document.getElementById('barChart')) {
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{ data: [45, 52, 48, 61, 55], backgroundColor: '#3b82f6', borderRadius: 4 }]
        },
        options: { plugins: { legend: { display: false } } }
    });
}

if (document.getElementById('pieChart')) {
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
            plugins: { 
                legend: { 
                    position: 'right',
                    align: 'center',
                    labels: { boxWidth: 15, padding: 15, font: { size: 12, weight: 'bold' } } 
                } 
            }
        }
    });
}