// Reusable components
function showFeedbackModal(title, message, type) {
    const existingModal = document.getElementById('feedbackModal');
    if (existingModal) existingModal.remove();
    
    const modalHTML = `
        <div id="feedbackModal" class="modal-overlay hidden">
            <div class="modal-content modal-feedback">
                <div class="feedback-icon ${type}">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                </div>
                <h3>${title}</h3>
                <p>${message}</p>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="closeFeedbackModal()">OK</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = document.getElementById('feedbackModal');
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('active'), 10);
}

function closeFeedbackModal() {
    const modal = document.getElementById('feedbackModal');
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => modal.remove(), 300);
    }
}

function validateForm(fields) {
    for (const field of fields) {
        const element = document.getElementById(field.id);
        if (!element || !element.value.trim()) {
            showFeedbackModal('Error', field.message, 'error');
            return false;
        }
    }
    return true;
}