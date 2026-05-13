<?php
$page_title = 'Online Services';
$page_css = 'services.css';
$page_js = 'services.js';
require_once '../includes/init.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Get services from database
$services = getServices();
?>

<main class="container">
    <!-- Service Selection -->
    <div id="serviceSelection">
        <header class="text-center" style="margin-bottom: 2rem;">
            <h2><?php echo __('page-title'); ?></h2>
            <p><?php echo __('page-subtitle'); ?></p>
        </header>
        
        <h3 style="margin-bottom: 1.5rem;"><?php echo __('select-service'); ?></h3>
        
        <div class="grid grid-cols-4">
            <?php foreach ($services as $service): ?>
            <div onclick="showForm('<?php echo $service['service_name']; ?>', <?php echo $service['base_price']; ?>)" class="card card-hover service-card">
                <div class="service-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <h4><?php echo $service['service_name']; ?></h4>
                <p><?php echo $service['description'] ?? ''; ?></p>
                <?php if ($service['base_price'] == 0): ?>
                    <p class="service-price free">FREE</p>
                <?php else: ?>
                    <p class="service-price">₱<?php echo $service['base_price']; ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Request Form (hidden by default) -->
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
                            <option value="delivery">Home Delivery</option>
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