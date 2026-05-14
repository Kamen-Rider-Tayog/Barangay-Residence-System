<?php
require_once '../includes/init.php';
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

include '../includes/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/services.css">
<?php include '../includes/navbar.php'; ?>

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
            <div onclick="showForm('<?php echo $name; ?>', <?php echo $service['base_price']; ?>)" class="card card-hover service-card">
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

    <div id="serviceForm" class="hidden-section">
        <div class="card form-card">
            <h3 id="formTitle" class="form-title">Request Document</h3>
            <form id="mainRequestForm" method="POST" action="submit_request.php">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" id="inputEmail" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" id="inputName" name="name" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" id="inputAddress" name="address" class="form-input" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="tel" id="inputContact" name="contact" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="inputQty" name="qty" class="form-input" value="1" min="1" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Purpose</label>
                    <textarea id="inputPurpose" name="purpose" class="form-input" rows="4" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select id="inputPayment" name="payment_method" class="form-input">
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Method</label>
                        <select id="inputDelivery" name="delivery_method" class="form-input">
                            <option value="pickup">Pick-up</option>
                            <option value="delivery">Home Delivery (+₱50)</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex" style="gap: 1rem; margin-top: 1rem;">
                    <button type="button" onclick="hideForm()" class="btn">Back</button>
                    <button type="submit" class="btn btn-primary">Preview Document</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>