// Services page
let currentServiceName = "";
let currentServicePrice = 0;

function openServiceModal(serviceName, price) {
    currentServiceName = serviceName;
    currentServicePrice = price;
    
    document.getElementById('modalServiceName').textContent = 'Request ' + serviceName;
    document.getElementById('modalServiceNameHidden').value = serviceName;
    document.getElementById('modalServicePrice').value = price;
    
    updatePriceSummary();
    
    const qtyInput = document.getElementById('reqQty');
    const deliverySelect = document.getElementById('reqDelivery');
    
    if (qtyInput) {
        qtyInput.removeEventListener('input', updatePriceSummary);
        qtyInput.addEventListener('input', updatePriceSummary);
    }
    if (deliverySelect) {
        deliverySelect.removeEventListener('change', updatePriceSummary);
        deliverySelect.addEventListener('change', updatePriceSummary);
    }
    
    const modal = document.getElementById('serviceModal');
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('active'), 10);
}

function updatePriceSummary() {
    const qty = parseInt(document.getElementById('reqQty')?.value || 1);
    const deliveryMethod = document.getElementById('reqDelivery')?.value || 'pickup';
    
    const serviceFee = currentServicePrice * qty;
    const deliveryFee = deliveryMethod === 'delivery' ? 50 : 0;
    const total = serviceFee + deliveryFee;
    
    document.getElementById('summaryServiceFee').textContent = '₱' + serviceFee.toFixed(2);
    document.getElementById('summaryQtyDisplay').textContent = qty;
    document.getElementById('summaryDeliveryFee').textContent = deliveryFee === 0 ? '₱0.00' : '₱50.00';
    document.getElementById('summaryTotal').textContent = '₱' + total.toFixed(2);
    updateGcashAmount();
}

function updateGcashAmount() {
    const totalElement = document.getElementById('summaryTotal');
    const gcashAmountElement = document.getElementById('gcashAmount');
    if (totalElement && gcashAmountElement) {
        gcashAmountElement.textContent = totalElement.textContent;
    }
}

function closeServiceModal() {
    const modal = document.getElementById('serviceModal');
    modal.classList.remove('active');
    setTimeout(() => {
        modal.classList.add('hidden');
        const form = document.getElementById('serviceRequestForm');
        if (form) form.reset();
        document.getElementById('summaryQtyDisplay').textContent = '1';
        document.getElementById('summaryServiceFee').textContent = '₱0.00';
        document.getElementById('summaryDeliveryFee').textContent = '₱0.00';
        document.getElementById('summaryTotal').textContent = '₱0.00';
    }, 300);
}

function closeGcashModal() {
    const modal = document.getElementById('gcashModal');
    modal.classList.remove('active');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('active');
    setTimeout(() => {
        modal.classList.add('hidden');
        window.location.reload();
    }, 300);
}

function openGcashApp() {
    window.location.href = 'gcash://';
    setTimeout(() => {
        if (document.hasFocus()) {
            showFeedbackModal('GCash App', 'Do you want to download GCash app?', 'info');
            setTimeout(() => {
                window.open('https://play.google.com/store/apps/details?id=com.globe.gcash.android', '_blank');
            }, 1000);
        }
    }, 2000);
}

function confirmGcashPayment() {
    closeGcashModal();
    setTimeout(() => {
        const successModal = document.getElementById('successModal');
        successModal.classList.remove('hidden');
        setTimeout(() => successModal.classList.add('active'), 50);
    }, 300);
}

// Submit service request
const serviceForm = document.getElementById('serviceRequestForm');
if (serviceForm) {
    serviceForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('reqEmail').value;
        const name = document.getElementById('reqName').value;
        const address = document.getElementById('reqAddress').value;
        const paymentMethod = document.getElementById('reqPayment').value;
        
        if (!email || !name || !address) {
            showFeedbackModal('Error', 'Please fill in all required fields.', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('email', email);
        formData.append('name', name);
        formData.append('address', address);
        formData.append('contact', document.getElementById('reqContact').value);
        formData.append('service_name', currentServiceName);
        formData.append('qty', document.getElementById('reqQty').value);
        formData.append('purpose', document.getElementById('reqPurpose').value);
        formData.append('payment_method', paymentMethod);
        formData.append('delivery_method', document.getElementById('reqDelivery').value);
        
        const submitBtn = serviceForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        fetch('/barangay-residence-system/pages/api/submit_request.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const serviceModal = document.getElementById('serviceModal');
                    serviceModal.classList.remove('active');
                    
                    setTimeout(() => {
                        serviceModal.classList.add('hidden');
                        
                        if (paymentMethod === 'gcash' && currentServicePrice > 0) {
                            updateGcashAmount();
                            const gcashModal = document.getElementById('gcashModal');
                            gcashModal.classList.remove('hidden');
                            setTimeout(() => gcashModal.classList.add('active'), 50);
                        } else {
                            const successModal = document.getElementById('successModal');
                            successModal.classList.remove('hidden');
                            setTimeout(() => successModal.classList.add('active'), 50);
                        }
                    }, 300);
                } else {
                    showFeedbackModal('Error', data.message || 'Error submitting request.', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(() => {
                showFeedbackModal('Error', 'Network error. Please try again.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    });
}

// Click outside to close
const modalOverlay = document.getElementById('serviceModal');
if (modalOverlay) {
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === this) closeServiceModal();
    });
}

const gcashOverlay = document.getElementById('gcashModal');
if (gcashOverlay) {
    gcashOverlay.addEventListener('click', function(e) {
        if (e.target === this) closeGcashModal();
    });
}

const successOverlay = document.getElementById('successModal');
if (successOverlay) {
    successOverlay.addEventListener('click', function(e) {
        if (e.target === this) closeSuccessModal();
    });
}