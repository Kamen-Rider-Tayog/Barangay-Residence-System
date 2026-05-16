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
    const transactionsSection = document.getElementById('section-transactions');
    const complaintsSection = document.getElementById('section-complaints');
    const transBtn = document.getElementById('nav-transactions');
    const compBtn = document.getElementById('nav-complaints');
    
    if (tab === 'transactions') {
        transactionsSection.classList.remove('hidden-section');
        complaintsSection.classList.add('hidden-section');
        transBtn.classList.remove('btn-inactive');
        transBtn.classList.add('btn-active');
        compBtn.classList.remove('btn-active');
        compBtn.classList.add('btn-inactive');
    } else {
        complaintsSection.classList.remove('hidden-section');
        transactionsSection.classList.add('hidden-section');
        compBtn.classList.remove('btn-inactive');
        compBtn.classList.add('btn-active');
        transBtn.classList.remove('btn-active');
        transBtn.classList.add('btn-inactive');
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
    // Set default active tab
    const transactionsSection = document.getElementById('section-transactions');
    const transBtn = document.getElementById('nav-transactions');
    
    if (transactionsSection && !transactionsSection.classList.contains('hidden-section')) {
        // Already active, do nothing
    } else if (transactionsSection) {
        transactionsSection.classList.remove('hidden-section');
        if (transBtn) {
            transBtn.classList.add('btn-active');
            transBtn.classList.remove('btn-inactive');
        }
    }
});