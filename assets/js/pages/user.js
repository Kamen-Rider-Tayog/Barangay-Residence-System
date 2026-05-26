// User dashboard
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

function toggleComplaintModal(show) {
    const modal = document.getElementById('complaint-modal');
    if (!modal) return;
    
    if (show) {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.remove('active');
        setTimeout(() => modal.classList.add('hidden'), 300);
        document.body.style.overflow = '';
        const form = document.getElementById('complaintForm');
        if (form) form.reset();
    }
}

// Close modal when clicking outside
const modalOverlay = document.getElementById('complaint-modal');
if (modalOverlay) {
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === this) toggleComplaintModal(false);
    });
}

// Submit complaint
const complaintForm = document.getElementById('complaintForm');
if (complaintForm) {
    complaintForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const subject = document.getElementById('complaintSubject').value;
        const category = document.getElementById('complaintCategory').value;
        const priority = document.getElementById('complaintPriority').value;
        const description = document.getElementById('complaintDescription').value;
        
        if (!subject || !category || !description) {
            showFeedbackModal('Error', 'Please fill in all fields.', 'error');
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
        
        fetch('/barangay-residence-system/pages/api/submit_complaint.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toggleComplaintModal(false);
                    setTimeout(() => showFeedbackModal('Success!', data.message, 'success'), 400);
                    setTimeout(() => location.reload(), 2500);
                } else {
                    showFeedbackModal('Error', data.message, 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(() => {
                showFeedbackModal('Error', 'Something went wrong.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const transactionsSection = document.getElementById('section-transactions');
    if (transactionsSection && transactionsSection.classList.contains('hidden-section')) {
        transactionsSection.classList.remove('hidden-section');
    }
    const transBtn = document.getElementById('nav-transactions');
    if (transBtn) {
        transBtn.classList.add('btn-active');
        transBtn.classList.remove('btn-inactive');
    }
});

function logoutUser() {
    window.location.href = '../includes/logout.php';
}