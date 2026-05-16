<?php
require_once '../includes/core/init.php';
$services = getServices();

$serviceIcons = [
    'Barangay Clearance' => 'fa-file-signature',
    'Certificate of Residency' => 'fa-home',
    'Business Permit' => 'fa-store',
    'Indigency Certificate' => 'fa-hand-holding-heart',
    'Police Clearance' => 'fa-shield-alt',
    'Cedula (Community Tax)' => 'fa-receipt',
    'Certificate of Good Moral' => 'fa-scroll',
    'First Time Job Seeker' => 'fa-user-graduate',
    'Barangay ID' => 'fa-id-card',
    'Health Certificate' => 'fa-heartbeat',
    'Building Permit' => 'fa-hard-hat',
    'Travel Pass' => 'fa-passport'
];

$processingTimes = [
    'Barangay Clearance' => '1-2 days',
    'Certificate of Residency' => 'Same day',
    'Business Permit' => '3-5 days',
    'Indigency Certificate' => 'Same day',
    'Police Clearance' => '1-2 days',
    'Cedula (Community Tax)' => '15 minutes',
    'Certificate of Good Moral' => '1 day',
    'First Time Job Seeker' => 'Same day',
    'Barangay ID' => '1 week',
    'Health Certificate' => '2-3 days',
    'Building Permit' => '1-2 weeks',
    'Travel Pass' => 'Same day'
];

$popularServices = ['Barangay Clearance', 'Certificate of Residency', 'Police Clearance'];
$newServices = ['First Time Job Seeker', 'Barangay ID'];

$userData = null;
if (isLoggedIn()) {
    if (isResident()) {
        $household_id = $_SESSION['household_id'];
        $household = getHouseholdById($household_id);
        $residents = getResidentsByHouseholdId($household_id);
        $userData = [
            'email' => $household['email'],
            'name' => ($residents[0]['first_name'] ?? '') . ' ' . ($residents[0]['last_name'] ?? ''),
            'address' => $household['address'] ?? '',
            'contact' => $residents[0]['contact_no'] ?? ''
        ];
    }
}

include '../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/services.css">
<?php include '../includes/layouts/navbar.php'; ?>

<main class="container">
    <div id="serviceSelection">
        <div class="service-header">
            <h2><?php echo __('page-title'); ?></h2>
            <p><?php echo __('page-subtitle'); ?></p>
        </div>
        
        <h3 class="section-subtitle"><?php echo __('select-service'); ?></h3>
        
        <div class="services-grid">
            <?php foreach ($services as $service): 
                $name = $service['service_name'];
                $icon = $serviceIcons[$name] ?? 'fa-file-alt';
                $processingTime = $processingTimes[$name] ?? '2-3 days';
                $isPopular = in_array($name, $popularServices);
                $isNew = in_array($name, $newServices);
            ?>
            <div onclick="openServiceModal('<?php echo addslashes($name); ?>', <?php echo $service['base_price']; ?>)" class="card card-hover service-card">
                <?php if ($isPopular): ?>
                    <span class="service-badge popular">Popular</span>
                <?php elseif ($isNew): ?>
                    <span class="service-badge new">New</span>
                <?php endif; ?>
                
                <div class="service-icon">
                    <i class="fas <?php echo $icon; ?>"></i>
                </div>
                <h4><?php echo $name; ?></h4>
                <p class="service-description"><?php echo $service['description'] ?? ''; ?></p>
                <?php if ($service['base_price'] == 0): ?>
                    <p class="service-price free">FREE</p>
                <?php else: ?>
                    <p class="service-price">₱<?php echo number_format($service['base_price'], 2); ?></p>
                <?php endif; ?>
                <div class="processing-time">
                    <i class="fas fa-clock"></i>
                    <span><?php echo $processingTime; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- Service Request Modal -->
<div id="serviceModal" class="modal-overlay hidden">
    <div class="modal-content modal-service">
        <div class="modal-header">
            <h3 id="modalServiceName">Request Service</h3>
            <button class="close-modal" onclick="closeServiceModal()">&times;</button>
        </div>
        <form id="serviceRequestForm">
            <div class="modal-body">
                <input type="hidden" id="modalServicePrice" name="service_price">
                <input type="hidden" id="modalServiceNameHidden" name="service_name">
                
                <?php if (!isLoggedIn()): ?>
                <div class="login-prompt">
                    <i class="fas fa-lock"></i>
                    <p>Please login to request a service</p>
                    <a href="/barangay-residence-system/pages/login.php" class="btn btn-primary">Login Now</a>
                </div>
                <?php else: ?>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" id="reqEmail" name="email" class="form-input" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" id="reqName" name="name" class="form-input" value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" id="reqAddress" name="address" class="form-input" value="<?php echo htmlspecialchars($userData['address'] ?? ''); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="tel" id="reqContact" name="contact" class="form-input" value="<?php echo htmlspecialchars($userData['contact'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="reqQty" name="qty" class="form-input" value="1" min="1" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Purpose</label>
                    <textarea id="reqPurpose" name="purpose" class="form-input" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select id="reqPayment" name="payment_method" class="form-input">
                            <option value="cash">Cash (Pay at Barangay Hall)</option>
                            <option value="gcash">GCash (Online Payment)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Method</label>
                        <select id="reqDelivery" name="delivery_method" class="form-input">
                            <option value="pickup">Pick-up at Barangay Hall</option>
                            <option value="delivery">Home Delivery (+₱50)</option>
                        </select>
                    </div>
                </div>
                
                <div class="price-summary">
                    <div class="summary-row">
                        <span>Service Fee:</span>
                        <span id="summaryServiceFee">₱0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Quantity:</span>
                        <span id="summaryQtyDisplay">1</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span id="summaryDeliveryFee">₱0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Amount:</span>
                        <span id="summaryTotal">₱0.00</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeServiceModal()">Cancel</button>
                <?php if (isLoggedIn()): ?>
                <button type="submit" class="btn btn-primary">Submit Request</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- GCash QR Modal -->
<div id="gcashModal" class="modal-overlay hidden">
    <div class="modal-content modal-gcash">
        <div class="modal-header">
            <h3><i class="fab fa-gcash"></i> GCash Payment</h3>
            <button class="close-modal" onclick="closeGcashModal()">&times;</button>
        </div>
        <div class="modal-body text-center">
            <div class="qr-container">
                <img src="/barangay-residence-system/assets/images/qr.jpg" alt="GCash QR Code" class="qr-code">
            </div>
            <div class="payment-details">
                <p><strong>Account Name:</strong> Brgy. San Francisco</p>
                <p><strong>Account Number:</strong> 0994 556 6094</p>
                <p><strong>Amount to Pay:</strong> <span id="gcashAmount">₱0.00</span></p>
            </div>
            <div class="payment-options">
                <button class="btn btn-gcash" onclick="openGcashApp()">
                    <i class="fab fa-gcash"></i> Open GCash App
                </button>
                <p class="helper-text">Tap to open GCash app and send payment</p>
            </div>
            <div class="payment-instructions">
                <i class="fas fa-info-circle"></i>
                <p>Option 1: Click "Open GCash App" to pay directly<br>
                Option 2: Scan the QR code using your GCash app<br>
                After payment, click "I Have Paid" to complete your request.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="closeGcashModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="confirmGcashPayment()">I Have Paid</button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal-overlay hidden">
    <div class="modal-content modal-success">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3>Request Submitted!</h3>
        <p>Your service request has been submitted successfully. You can track its status in your dashboard.</p>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeSuccessModal()">OK</button>
        </div>
    </div>
</div>

<script src="/barangay-residence-system/assets/js/pages/services.js"></script>
<?php include '../includes/layouts/footer.php'; ?>