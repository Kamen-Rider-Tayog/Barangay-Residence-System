lucide.createIcons();

const today = new Date().toISOString().split('T')[0];
document.getElementById('inputDate').setAttribute('min', today);

let currentServiceName = "";
let currentServicePrice = 0;

const langBtn = document.getElementById('langBtn');
const langMenu = document.getElementById('langMenu');
langBtn.addEventListener('click', (e) => { e.stopPropagation(); langMenu.classList.toggle('show'); });
window.addEventListener('click', () => { langMenu.classList.remove('show'); });

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
    
    // Handle display for FREE items in summary
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
    // Only show GCash modal if price > 0
    if (paymentMethod === "GCash" && currentServicePrice > 0) {
        document.getElementById('gcashModal').classList.add('active');
    } else {
        showThankYou();
    }
}

function showThankYou() {
    closeModals();
    document.getElementById('thankYouModal').classList.add('active');
}

function closeModals() {
    document.getElementById('gcashModal').classList.remove('active');
    document.getElementById('thankYouModal').classList.remove('active');
}

const translations = {
    en: {
        "sub-header": "Information & Service Management",
        "nav-home": "Home", "nav-services": "Online Services", "login": "Login",
        "page-title": "Online Services", "page-subtitle": "Request barangay documents and services online",
        "select-service": "Select a Service",
        "srv-clearance-t": "Barangay Clearance", "srv-clearance-d": "Certificate of good moral character",
        "srv-residency-t": "Certificate of Residency", "srv-residency-d": "Proof of residence in the barangay",
        "srv-permit-t": "Business Permit", "srv-permit-d": "Permit for small business operations",
        "srv-indigency-t": "Indigency Certificate", "srv-indigency-d": "Certificate of indigent status",
        "srv-free": "FREE",
        "lbl-email": "Email", "lbl-name": "Full Name", "lbl-address": "Address",
        "lbl-contact": "Contact Number", "lbl-qty": "Quantity", "lbl-purpose": "Purpose",
        "lbl-payment": "Payment Method", "lbl-delivery": "Delivery Method",
        "lbl-date": "Preferred Date", "lbl-time": "Preferred Time",
        "opt-cash": "Cash Payment", "opt-pickup": "Pick-up", "opt-delivery": "Home Delivery",
        "btn-back": "Back", "btn-preview": "Preview Document",
        "f-contact": "Contact Us", "f-links": "Quick Links", "f-follow": "Follow Us",
        "footer-copy": "© 2026 Barangay San Francisco. All Rights Reserved.",
        "label": "Language", "qr-title": "Scan to Pay via GCash", "qr-acc-name": "Account Name",
        "qr-acc-num": "Account Number", "btn-cancel": "Cancel", "ty-title": "Thank You!",
        "ty-msg": "Your request has been sent successfully and is currently being processed.",
        "btn-back-srv": "Back to Services"
    },
    tl: {
        "sub-header": "Pamamahala ng Impormasyon at Serbisyo",
        "nav-home": "Home", "nav-services": "Serbisyong Online", "login": "Mag-login",
        "page-title": "Serbisyong Online", "page-subtitle": "Mag-request ng mga dokumento at serbisyo online",
        "select-service": "Pumili ng Serbisyo",
        "srv-clearance-t": "Barangay Clearance", "srv-clearance-d": "Katibayan ng magandang asal",
        "srv-residency-t": "Certificate of Residency", "srv-residency-d": "Patunay ng pagtira sa barangay",
        "srv-permit-t": "Business Permit", "srv-permit-d": "Permit para sa mga negosyo",
        "srv-indigency-t": "Indigency Certificate", "srv-indigency-d": "Katibayan ng katayuang indigent",
        "srv-free": "LIBRE",
        "lbl-email": "Email", "lbl-name": "Buong Pangalan", "lbl-address": "Tirahan",
        "lbl-contact": "Numero ng Telepono", "lbl-qty": "Dami", "lbl-purpose": "Layunin",
        "lbl-payment": "Paraan ng Pagbabayad", "lbl-delivery": "Paraan ng Pagkuha",
        "lbl-date": "Petsa", "lbl-time": "Oras",
        "opt-cash": "Bayad ng Cash", "opt-pickup": "Kukunin sa Hall", "opt-delivery": "Ipadadala sa Bahay",
        "btn-back": "Bumalik", "btn-preview": "I-preview ang Dokumento",
        "f-contact": "Makipag-ugnayan", "f-links": "Mabilis na Links", "f-follow": "Sundan Kami",
        "footer-copy": "© 2026 Barangay San Francisco. Lahat ng Karapatan ay Nakareserba.",
        "label": "Wika", "qr-title": "I-scan para magbayad gamit ang GCash", "qr-acc-name": "Pangalan ng Account",
        "qr-acc-num": "Numero ng Account", "btn-cancel": "Kanselahin", "ty-title": "Salamat!",
        "ty-msg": "Ang inyong request ay matagumpay na naipadala at kasalukuyang pinoproseso.",
        "btn-back-srv": "Bumalik sa Serbisyo"
    }
};

function changeLanguage(lang) {
    document.querySelectorAll('[data-key]').forEach(elem => {
        const key = elem.getAttribute('data-key');
        if (translations[lang][key]) {
            if (elem.tagName === 'OPTION') {
                elem.text = translations[lang][key];
            } else {
                elem.textContent = translations[lang][key];
            }
        }
    });
    document.getElementById('currentLangLabel').textContent = translations[lang]['label'];
    langMenu.classList.remove('show');
    document.documentElement.lang = lang;
}