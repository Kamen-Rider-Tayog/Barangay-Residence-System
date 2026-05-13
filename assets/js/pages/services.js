lucide.createIcons();

const today = new Date().toISOString().split('T')[0];
const dateInput = document.getElementById('inputDate');
if (dateInput) dateInput.setAttribute('min', today);

let currentServiceName = "";
let currentServicePrice = 0;

// Language dropdown
const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');
if (langBtn && langMenu) {
    langBtn.addEventListener('click', (e) => { 
        e.stopPropagation(); 
        langMenu.classList.toggle('show'); 
    });
    window.addEventListener('click', () => { 
        langMenu.classList.remove('show'); 
    });
}

function changeLanguage(lang) {
    window.location.href = '?lang=' + lang;
}

function showForm(serviceName, price) {
    currentServiceName = serviceName;
    currentServicePrice = price;
    document.getElementById('serviceSelection').classList.add('hidden-section');
    document.getElementById('serviceForm').classList.remove('hidden-section');
    document.getElementById('formTitle').textContent = "Request " + serviceName;
    window.scrollTo(0, 0);
}

function hideForm() {
    document.getElementById('serviceForm').classList.add('hidden-section');
    document.getElementById('serviceSelection').classList.remove('hidden-section');
    window.scrollTo(0, 0);
}

function handlePreview(e) {
    e.preventDefault();
    const name = document.getElementById('inputName').value;
    const address = document.getElementById('inputAddress').value;
    const purpose = document.getElementById('inputPurpose').value;
    const qty = document.getElementById('inputQty').value;
    
    document.getElementById('prevName').textContent = name;
    document.getElementById('prevAddr').textContent = address;
    document.getElementById('prevPurpose').textContent = purpose;
    document.getElementById('previewDocHeader').textContent = currentServiceName.toUpperCase();
    document.getElementById('summarySrv').textContent = currentServiceName;
    document.getElementById('summaryQty').textContent = qty;
    
    const feeDisplay = currentServicePrice === 0 ? "FREE" : "₱" + currentServicePrice;
    const totalDisplay = currentServicePrice === 0 ? "FREE" : "₱" + (currentServicePrice * qty);
    
    document.getElementById('summaryFee').textContent = feeDisplay;
    document.getElementById('summaryTotal').textContent = totalDisplay;
    document.getElementById('summaryMethod').textContent = document.getElementById('inputDelivery').value;
    document.getElementById('summaryDate').textContent = document.getElementById('inputDate').value;
    document.getElementById('summaryTime').textContent = document.getElementById('inputTime').value;
    document.getElementById('summaryPay').textContent = document.getElementById('inputPayment').value;
    
    document.getElementById('serviceForm').classList.add('hidden-section');
    document.getElementById('previewSection').classList.remove('hidden-section');
    window.scrollTo(0, 0);
}

function backToEdit() {
    document.getElementById('previewSection').classList.add('hidden-section');
    document.getElementById('serviceForm').classList.remove('hidden-section');
    window.scrollTo(0, 0);
}

function handleConfirm() {
    const paymentMethod = document.getElementById('inputPayment').value;
    
    const formData = new FormData();
    formData.append('email', document.getElementById('inputEmail').value);
    formData.append('name', document.getElementById('inputName').value);
    formData.append('address', document.getElementById('inputAddress').value);
    formData.append('contact', document.getElementById('inputContact').value);
    formData.append('service_name', currentServiceName);
    formData.append('qty', document.getElementById('inputQty').value);
    formData.append('purpose', document.getElementById('inputPurpose').value);
    formData.append('payment_method', paymentMethod);
    formData.append('delivery_method', document.getElementById('inputDelivery').value);
    
    fetch('submit_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        if (result.includes('success')) {
            if (paymentMethod === "GCash" && currentServicePrice > 0) {
                document.getElementById('gcashModal').classList.add('active');
            } else {
                showThankYou();
            }
        } else {
            alert('Error submitting request. Please try again.');
        }
    })
    .catch(error => {
        alert('Error submitting request. Please try again.');
    });
}

function showThankYou() {
    closeModals();
    document.getElementById('thankYouModal').classList.add('active');
}

function closeModals() {
    const gcashModal = document.getElementById('gcashModal');
    const thankYouModal = document.getElementById('thankYouModal');
    if (gcashModal) gcashModal.classList.remove('active');
    if (thankYouModal) thankYouModal.classList.remove('active');
}