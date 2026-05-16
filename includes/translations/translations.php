<?php
function __($key) {
    $lang = $_SESSION['lang'] ?? 'tl';
    
    $translations = [
        'en' => [
            // Navigation
            'nav-home' => 'Home',
            'nav-services' => 'Online Services',
            'nav-programs' => 'Programs',
            'login' => 'Login',
            'logout' => 'Logout',
            'sub-header' => 'Information & Service Management',
            'language' => 'Language',
            'admin-dashboard' => 'Admin Panel',
            'my-dashboard' => 'My Dashboard',
            
            // Hero
            'hero-title' => 'Welcome to Barangay San Francisco',
            'hero-desc' => 'Your trusted partner in community service.',
            'btn-request' => 'Request Service',
            'btn-learn' => 'Learn More',
            
            // Stats
            'stat-residents' => 'Residents',
            'stat-households' => 'Households',
            'stat-voters' => 'Voters',
            'stat-services' => 'Services',
            
            // Announcements
            'announcement-title' => 'Latest Announcements',
            'ann-1-title' => 'Community Clean-Up',
            'ann-1-desc' => 'Join us for a clean-up initiative in San Francisco this Saturday at 6:00 AM.',
            'ann-2-title' => 'Medical Mission',
            'ann-2-desc' => 'Free consultation and medicines available at the brgy. hall.',
            'ann-3-title' => 'Barangay Assembly',
            'ann-3-desc' => 'Everyone is invited to the assembly at 2:00 PM.',
            
            // About
            'about-title' => 'About Barangay San Francisco',
            'about-p1' => 'Located in General Trias, Cavite, we are dedicated to providing fast and honest public service for all Gentriseños.',
            'about-years' => 'Years of Service',
            'about-digital' => 'Digital Services',
            'map-address' => 'Barangay Hall, San Francisco, General Trias, Cavite',
            
            // Footer
            'f-contact' => 'Contact Us',
            'f-links' => 'Quick Links',
            'f-follow' => 'Follow Us',
            'footer-copy' => '© 2026 Barangay San Francisco. All Rights Reserved.',
            
            // User Profile
            'profile-title' => 'My Profile',
            'personal-info' => 'Personal Information',
            'lbl-email' => 'Email',
            'lbl-phone' => 'Phone Number',
            'lbl-address' => 'Address',
            'lbl-household' => 'Household Members',
            'lbl-registered' => 'Registration Date',
            'service-stats' => 'Service Statistics',
            'stat-spent' => 'Total Spent',
            'tab-trans' => 'Transaction History',
            'tab-comp' => 'My Complaints',
            
            // Services Page
            'page-title' => 'Online Services',
            'page-subtitle' => 'Request barangay documents online',
            'select-service' => 'Select a Service',
            'lbl-name' => 'Full Name',
            'lbl-qty' => 'Quantity',
            'lbl-purpose' => 'Purpose',
            'lbl-payment' => 'Payment Method',
            'lbl-delivery' => 'Delivery Method',
            'lbl-date' => 'Preferred Date',
            'lbl-time' => 'Preferred Time',
            'opt-cash' => 'Cash Payment',
            'opt-pickup' => 'Pick-up',
            'opt-delivery' => 'Home Delivery',
            'btn-back' => 'Back',
            'btn-preview' => 'Preview Document',
            
            // Service Cards
            'srv-clearance-t' => 'Barangay Clearance',
            'srv-clearance-d' => 'Certificate of good moral character',
            'srv-residency-t' => 'Certificate of Residency',
            'srv-residency-d' => 'Proof of residence in the barangay',
            'srv-permit-t' => 'Business Permit',
            'srv-permit-d' => 'Permit for small business operations',
            'srv-indigency-t' => 'Indigency Certificate',
            'srv-indigency-d' => 'Certificate of indigent status',
            'srv-free' => 'FREE',
            
            // Modals
            'qr-title' => 'Scan to Pay via GCash',
            'qr-acc-name' => 'Account Name',
            'qr-acc-num' => 'Account Number',
            'btn-cancel' => 'Cancel',
            'ty-title' => 'Thank You!',
            'ty-msg' => 'Your request has been sent successfully and is currently being processed.',
            'btn-back-srv' => 'Back to Services',
            
            // Campaigns
            'campaigns-title' => 'Active Programs',
            'campaigns-subtitle' => 'Stay updated with our ongoing barangay programs',
            'no-campaigns' => 'No active programs at the moment.',
        ],
        'tl' => [
            // Navigation
            'nav-home' => 'Home',
            'nav-services' => 'Serbisyong Online',
            'nav-programs' => 'Programa',
            'login' => 'Mag-login',
            'logout' => 'Mag-logout',
            'sub-header' => 'Pamamahala ng Impormasyon at Serbisyo',
            'language' => 'Wika',
            'admin-dashboard' => 'Admin Panel',
            'my-dashboard' => 'Aking Dashboard',
            
            // Hero
            'hero-title' => 'Maligayang Pagdating sa Barangay San Francisco',
            'hero-desc' => 'Ang inyong katuwang sa serbisyo ng komunidad.',
            'btn-request' => 'Mag-request',
            'btn-learn' => 'Alamin',
            
            // Stats
            'stat-residents' => 'Residente',
            'stat-households' => 'Sambahayan',
            'stat-voters' => 'Botante',
            'stat-services' => 'Serbisyo',
            
            // Announcements
            'announcement-title' => 'Pinakabagong Anunsyo',
            'ann-1-title' => 'Community Clean-Up',
            'ann-1-desc' => 'Samahan kami sa malawakang paglilinis sa San Francisco ngayong Sabado, 6:00 AM.',
            'ann-2-title' => 'Medical Mission',
            'ann-2-desc' => 'Libreng konsultasyon at gamot na makukuha sa ating brgy. hall.',
            'ann-3-title' => 'Barangay Assembly',
            'ann-3-desc' => 'Inaanyayahan ang lahat na dumalo sa asembleya sa ganap na 2:00 PM.',
            
            // About
            'about-title' => 'Tungkol sa Barangay San Francisco',
            'about-p1' => 'Matatagpuan sa General Trias, Cavite, kami ay nakatuon sa pagbibigay ng tapat at mabilis na serbisyo para sa bawat Gentriseño.',
            'about-years' => 'Taon ng Serbisyo',
            'about-digital' => 'Digital na Serbisyo',
            'map-address' => 'Brgy. Hall, San Francisco, General Trias, Cavite',
            
            // Footer
            'f-contact' => 'Makipag-ugnayan',
            'f-links' => 'Mabilis na Links',
            'f-follow' => 'Sundan Kami',
            'footer-copy' => '© 2026 Barangay San Francisco. Lahat ng Karapatan ay Nakareserba.',
            
            // User Profile
            'profile-title' => 'Aking Profile',
            'personal-info' => 'Personal na Impormasyon',
            'lbl-email' => 'Email',
            'lbl-phone' => 'Numero ng Telepono',
            'lbl-address' => 'Tirahan',
            'lbl-household' => 'Miyembro ng Sambahayan',
            'lbl-registered' => 'Petsa ng Pagrehistro',
            'service-stats' => 'Statistika ng Serbisyo',
            'stat-spent' => 'Kabuuang Gastos',
            'tab-trans' => 'Kasaysayan ng Transaksyon',
            'tab-comp' => 'Aking mga Reklamo',
            
            // Services Page
            'page-title' => 'Serbisyong Online',
            'page-subtitle' => 'Mag-request ng dokumento online',
            'select-service' => 'Pumili ng Serbisyo',
            'lbl-name' => 'Buong Pangalan',
            'lbl-qty' => 'Dami',
            'lbl-purpose' => 'Layunin',
            'lbl-payment' => 'Paraan ng Pagbabayad',
            'lbl-delivery' => 'Paraan ng Pagkuha',
            'lbl-date' => 'Petsa',
            'lbl-time' => 'Oras',
            'opt-cash' => 'Bayad ng Cash',
            'opt-pickup' => 'Kukunin sa Hall',
            'opt-delivery' => 'Ipadadala sa Bahay',
            'btn-back' => 'Bumalik',
            'btn-preview' => 'I-preview ang Dokumento',
            
            // Service Cards
            'srv-clearance-t' => 'Barangay Clearance',
            'srv-clearance-d' => 'Katibayan ng magandang asal',
            'srv-residency-t' => 'Certificate of Residency',
            'srv-residency-d' => 'Patunay ng pagtira sa barangay',
            'srv-permit-t' => 'Business Permit',
            'srv-permit-d' => 'Permit para sa mga negosyo',
            'srv-indigency-t' => 'Indigency Certificate',
            'srv-indigency-d' => 'Katibayan ng katayuang indigent',
            'srv-free' => 'LIBRE',
            
            // Modals
            'qr-title' => 'I-scan para magbayad gamit ang GCash',
            'qr-acc-name' => 'Pangalan ng Account',
            'qr-acc-num' => 'Numero ng Account',
            'btn-cancel' => 'Kanselahin',
            'ty-title' => 'Salamat!',
            'ty-msg' => 'Ang inyong request ay matagumpay na naipadala at kasalukuyang pinoproseso.',
            'btn-back-srv' => 'Bumalik sa Serbisyo',
            
            // Campaigns
            'campaigns-title' => 'Aktibong Programa',
            'campaigns-subtitle' => 'Manatiling updated sa aming mga programa',
            'no-campaigns' => 'Walang aktibong programa sa ngayon.',
        ]
    ];
    
    return $translations[$lang][$key] ?? $key;
}
?>