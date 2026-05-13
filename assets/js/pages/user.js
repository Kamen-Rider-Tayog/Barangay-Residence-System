// Language dropdown
const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');

if (langBtn && langMenu) {
    langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        langMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', () => {
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
    const transTab = document.getElementById('transactions');
    const compTab = document.getElementById('complaints');
    const transBtn = document.getElementById('tab-trans-btn');
    const compBtn = document.getElementById('tab-comp-btn');
    const actionBtn = document.getElementById('action-btn');

    if (!transTab || !compTab || !actionBtn) return;

    if (tab === 'transactions') {
        transTab.classList.add('active');
        compTab.classList.remove('active');
        if (transBtn) transBtn.className = "tab-btn active";
        if (compBtn) compBtn.className = "tab-btn";
        actionBtn.innerText = "Request Service";
        actionBtn.onclick = () => window.location.href = 'services.php';
    } else {
        compTab.classList.add('active');
        transTab.classList.remove('active');
        if (compBtn) compBtn.className = "tab-btn active";
        if (transBtn) transBtn.className = "tab-btn";
        actionBtn.innerText = "File a Complaint";
        actionBtn.onclick = () => toggleComplaintModal(true);
    }
}

// Complaint Modal
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

// Submit complaint via AJAX
const complaintForm = document.getElementById('complaintForm');
if (complaintForm) {
    complaintForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const subject = document.getElementById('complaintSubject').value;
        const category = document.getElementById('complaintCategory').value;
        const priority = document.getElementById('complaintPriority').value;
        const description = document.getElementById('complaintDescription').value;
        
        const formData = new FormData();
        formData.append('subject', subject);
        formData.append('category', category);
        formData.append('priority', priority);
        formData.append('description', description);
        
        fetch('submit_complaint.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if (result.includes('success')) {
                alert('Complaint submitted successfully!');
                toggleComplaintModal(false);
                location.reload();
            } else {
                alert('Error submitting complaint. Please try again.');
            }
        })
        .catch(error => {
            alert('Error submitting complaint. Please try again.');
        });
    });
}

// Initialize icons on load
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});