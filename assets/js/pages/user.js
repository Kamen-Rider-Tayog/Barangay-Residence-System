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
    const transTab = document.getElementById('transactions');
    const compTab = document.getElementById('complaints');
    const tabs = document.querySelectorAll('.tab-btn');
    const actionBtn = document.querySelector('.tab-header .btn-primary');

    if (!transTab || !compTab) return;

    if (tab === 'transactions') {
        transTab.classList.add('active');
        compTab.classList.remove('active');
        if (tabs[0]) tabs[0].classList.add('active');
        if (tabs[1]) tabs[1].classList.remove('active');
        if (actionBtn) {
            actionBtn.innerHTML = '<i class="fas fa-plus"></i> Request Service';
            actionBtn.onclick = () => window.location.href = 'services.php';
        }
    } else {
        compTab.classList.add('active');
        transTab.classList.remove('active');
        if (tabs[1]) tabs[1].classList.add('active');
        if (tabs[0]) tabs[0].classList.remove('active');
        if (actionBtn) {
            actionBtn.innerHTML = '<i class="fas fa-exclamation-circle"></i> File a Complaint';
            actionBtn.onclick = () => toggleComplaintModal(true);
        }
    }
}

// Complaint Modal
function toggleComplaintModal(show) {
    const modal = document.getElementById('complaint-modal');
    if (!modal) return;
    if (show) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Close modal when clicking outside
const modalOverlay = document.getElementById('complaint-modal');
if (modalOverlay) {
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === this) {
            toggleComplaintModal(false);
        }
    });
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
        
        if (!subject || !category || !description) {
            alert('Please fill in all fields.');
            return;
        }
        
        const formData = new FormData();
        formData.append('subject', subject);
        formData.append('category', category);
        formData.append('priority', priority);
        formData.append('description', description);
        
        const submitBtn = complaintForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        fetch('submit_complaint.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Complaint submitted successfully!');
                toggleComplaintModal(false);
                location.reload();
            } else {
                alert(data.message || 'Error submitting complaint. Please try again.');
            }
        })
        .catch(error => {
            alert('Error submitting complaint. Please try again.');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    const activeTab = document.querySelector('.tab-content.active');
    if (!activeTab) {
        const transactions = document.getElementById('transactions');
        if (transactions) transactions.classList.add('active');
    }
});