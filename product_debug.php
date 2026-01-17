<?php
include 'config.php';

// Get products for the listing
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
$stmt = $pdo->prepare($query);
$stmt->execute(['%' . $search . '%', '%' . $search . '%']);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Network Error Test</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .test-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 100px;
        }
        
        .test-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .test-header h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .test-scenario-box {
            background: linear-gradient(135deg, rgba(211, 47, 47, 0.1), rgba(0, 0, 0, 0.3));
            border: 2px dashed var(--accent-red);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .test-scenario-box h3 {
            color: #ff5252;
            margin-bottom: 10px;
        }
        
        .test-scenario-box p {
            color: var(--silver);
            margin-bottom: 15px;
        }
        
        .scenario-badge {
            display: inline-block;
            background: rgba(211, 47, 47, 0.2);
            border: 1px solid var(--accent-red);
            color: #ff8a80;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-family: monospace;
        }
        
        .test-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        
        .test-btn {
            padding: 12px 24px;
            border: 2px solid;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .test-btn.failure {
            background: rgba(211, 47, 47, 0.1);
            border-color: var(--accent-red);
            color: #ff8a80;
        }
        
        .test-btn.failure:hover {
            background: rgba(211, 47, 47, 0.3);
            border-color: #ff1744;
        }
        
        .test-btn.failure.active {
            background: var(--accent-red);
            color: white;
            animation: pulse 1.5s infinite;
        }
        
        .test-btn.reset {
            background: rgba(76, 175, 80, 0.1);
            border-color: #4caf50;
            color: #a5d6a7;
        }
        
        .test-btn.reset:hover {
            background: rgba(76, 175, 80, 0.3);
            border-color: #69f0ae;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            border-color: var(--silver);
            transform: translateY(-5px);
        }
        
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .product-card .content {
            padding: 20px;
        }
        
        .product-card h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: var(--white);
        }
        
        .product-card p {
            color: var(--silver);
            font-size: 0.9rem;
            margin-bottom: 12px;
        }
        
        .product-card .price {
            color: var(--accent-red);
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .product-card .btn {
            width: 100%;
            padding: 12px;
            margin-bottom: 8px;
        }
        
        .product-card .btn-primary {
            background: var(--accent-red);
            border: 2px solid var(--accent-red);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .product-card .btn-primary:hover {
            background: #b71c1c;
            border-color: #b71c1c;
        }
        
        .product-card .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .product-card .btn-primary.loading {
            position: relative;
            color: transparent;
        }
        
        .product-card .btn-primary.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .product-card .btn-primary.success {
            background: #4caf50;
            border-color: #69f0ae;
        }
        
        .product-card .btn-primary.error-state {
            background: #d32f2f;
            border: 2px solid #ff5252;
            animation: shake 0.5s ease-in-out;
        }
        
        /* Debug panel for this test page */
        .network-debug-panel {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid #333;
            border-radius: 12px;
            padding: 20px 25px;
            min-width: 320px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            z-index: 9999;
        }
        
        .network-debug-panel h4 {
            color: #ff5252;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .network-debug-panel .field {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }
        
        .network-debug-panel .label {
            color: #888;
        }
        
        .network-debug-panel .value {
            color: #fff;
            font-weight: bold;
        }
        
        .network-debug-panel .value.error {
            color: #ff5252;
        }
        
        .network-debug-panel .value.success {
            color: #69f0ae;
        }
        
        .network-debug-panel .value.warning {
            color: #ff9800;
        }
        
        /* Cart state indicator */
        .cart-state-indicator {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid #333;
            border-radius: 12px;
            padding: 20px 25px;
            min-width: 280px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            z-index: 9999;
        }
        
        .cart-state-indicator h4 {
            color: #69f0ae;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cart-state-indicator .state-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }
        
        .cart-state-indicator .state-label {
            color: #888;
        }
        
        .cart-state-indicator .state-value {
            color: #fff;
        }
        
        /* Toast container positioning for this page */
        #toast-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1>LuxeAuto Parts</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="product_debug.php" class="active">Debug Test</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="test-container">
            <div class="test-header">
                <h2>🛒 Add to Cart - Network Error Test</h2>
                <p style="color: var(--silver);">Test the network error scenario and recovery flow</p>
            </div>

            <!-- Test Scenario Description -->
            <div class="test-scenario-box">
                <h3>🔴 Network Error Scenario</h3>
                <p>Click "Add to Cart" to simulate a network failure. The system will detect the error,
                   show a retry-friendly interface, and recover gracefully when the network is available.</p>
                <span class="scenario-badge">Test Mode: Network Failure Simulation</span>
            </div>

            <!-- Test Controls -->
            <div class="test-controls">
                <button class="test-btn failure" id="enable-failure" onclick="enableNetworkFailure()">
                    🔴 Enable Network Failure
                </button>
                <button class="test-btn reset" onclick="disableNetworkFailure()">
                    🟢 Reset to Normal
                </button>
            </div>

            <!-- Product Grid -->
            <div class="product-grid">
                <?php if (empty($products)): ?>
                    <p style="text-align: center; color: var(--silver);">No products found.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <img src="images/<?php echo $product['image']; ?>" 
                                 alt="<?php echo $product['name']; ?>"
                                 onerror="this.src='images/placeholder.jpg'">
                            <div class="content">
                                <h3><?php echo $product['name']; ?></h3>
                                <p><?php echo substr($product['description'], 0, 80); ?>...</p>
                                <div class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                                
                                <!-- Add to Cart Button with Error Handling -->
                                <button 
                                    onclick="addToCartWithNetworkError(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, event)"
                                    class="btn btn-primary btn-add-cart"
                                    id="btn-product-<?php echo $product['id']; ?>"
                                    data-product-id="<?php echo $product['id']; ?>"
                                    data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-product-price="<?php echo $product['price']; ?>"
                                >
                                    + Add to Cart
                                </button>
                                
                                <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Network Debug Panel -->
    <div class="network-debug-panel">
        <h4>🔍 Network Debug</h4>
        <div class="field">
            <span class="label">Failure Mode</span>
            <span class="value error" id="debug-failure-mode">DISABLED</span>
        </div>
        <div class="field">
            <span class="label">Network Status</span>
            <span class="value success" id="debug-network-status">ONLINE</span>
        </div>
        <div class="field">
            <span class="label">Last Request</span>
            <span class="value" id="debug-last-request">-</span>
        </div>
        <div class="field">
            <span class="label">Last Response</span>
            <span class="value" id="debug-last-response">-</span>
        </div>
        <div class="field">
            <span class="label">Error Code</span>
            <span class="value" id="debug-error-code">NONE</span>
        </div>
        <p style="color: #666; font-size: 11px; margin-top: 12px; font-family: monospace;">
            💡 Check console (F12) for detailed logs
        </p>
    </div>

    <!-- Cart State Indicator -->
    <div class="cart-state-indicator">
        <h4>🛒 Cart State</h4>
        <div class="state-row">
            <span class="state-label">Items in Cart</span>
            <span class="state-value" id="cart-items-count">0</span>
        </div>
        <div class="state-row">
            <span class="state-label">Total Quantity</span>
            <span class="state-value" id="cart-total-qty">0</span>
        </div>
        <div class="state-row">
            <span class="state-label">Last Updated</span>
            <span class="state-value" id="cart-last-updated">-</span>
        </div>
        <div class="state-row">
            <span class="state-label">Status</span>
            <span class="state-value" id="cart-status">SYNCED</span>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2023 LuxeAuto Parts. Crafted with precision.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Network failure simulation state
        let networkFailureEnabled = false;
        let pendingRetry = null;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('═══════════════════════════════════════');
            console.log('  NETWORK ERROR TEST PAGE');
            console.log('═══════════════════════════════════════');
            console.log('Instructions:');
            console.log('1. Click "Enable Network Failure" to simulate errors');
            console.log('2. Click any "Add to Cart" button');
            console.log('3. Observe the error handling and retry flow');
            console.log('4. Click "Reset to Normal" to recover');
            console.log('═══════════════════════════════════════');
            
            // Fetch initial cart count
            fetchCartCount();
        });
        
        // Enable network failure simulation
        function enableNetworkFailure() {
            networkFailureEnabled = true;
            document.getElementById('enable-failure').classList.add('active');
            document.getElementById('debug-failure-mode').textContent = 'ENABLED';
            document.getElementById('debug-failure-mode').classList.remove('error');
            document.getElementById('debug-failure-mode').classList.add('warning');
            document.getElementById('debug-network-status').textContent = 'UNSTABLE';
            document.getElementById('debug-network-status').classList.remove('success');
            document.getElementById('debug-network-status').classList.add('warning');
            
            console.log('⚠️  Network failure simulation ENABLED');
            showToastNotification('Network failure simulation enabled. Add to cart will fail.', 'error', 'SIMULATION_ENABLED');
        }
        
        // Disable network failure simulation (recovery)
        function disableNetworkFailure() {
            networkFailureEnabled = false;
            document.getElementById('enable-failure').classList.remove('active');
            document.getElementById('debug-failure-mode').textContent = 'DISABLED';
            document.getElementById('debug-failure-mode').classList.remove('warning');
            document.getElementById('debug-failure-mode').classList.add('error');
            document.getElementById('debug-network-status').textContent = 'ONLINE';
            document.getElementById('debug-network-status').classList.remove('warning');
            document.getElementById('debug-network-status').classList.add('success');
            document.getElementById('debug-error-code').textContent = 'NONE';
            
            console.log('✅ Network failure simulation DISABLED - System recovered');
            showToastNotification('System recovered. Add to cart will work normally.', 'success');
            
            // Retry any pending operations
            if (pendingRetry) {
                console.log('🔄 Retrying pending operation...');
                const retryFn = pendingRetry;
                pendingRetry = null;
                retryFn();
            }
        }
        
        // Add to Cart with Network Error Handling
        function addToCartWithNetworkError(productId, productName, productPrice, event) {
            console.log('═══════════════════════════════════════');
            console.log('  ADD TO CART - Network Error Test');
            console.log('═══════════════════════════════════════');
            console.log('Product ID:', productId);
            console.log('Product Name:', productName);
            console.log('Network Failure Enabled:', networkFailureEnabled);
            
            // Get button element
            const button = event.target.closest('.btn-primary');
            const originalText = button.textContent;
            
            // Update debug panel
            document.getElementById('debug-last-request').textContent = 
                `ADD_TO_CART(${productId})`;
            
            // ========================================
            // LOADING STATE: Start
            // ========================================
            console.log('⏳ Starting add to cart process...');
            button.disabled = true;
            button.classList.add('loading');
            button.textContent = 'Adding...';
            
            // ========================================
            // NETWORK FAILURE SIMULATION
            // ========================================
            if (networkFailureEnabled) {
                console.log('⚠️  SIMULATING NETWORK FAILURE');
                
                // Simulate network delay
                setTimeout(() => {
                    // ========================================
                    // ERROR DETECTED: Stop loading, no optimistic update
                    // ========================================
                    console.log('🚨 Network error detected!');
                    
                    // Stop loading state immediately
                    button.classList.remove('loading');
                    button.disabled = false;
                    
                    // DO NOT update cart badge optimistically
                    // Cart state remains unchanged
                    
                    // Update button to retry state
                    button.textContent = 'Add to Cart (Retry)';
                    button.classList.add('error-state');
                    button.style.background = '#d32f2f';
                    button.style.border = '2px solid #ff5252';
                    
                    // Store retry function
                    pendingRetry = function() {
                        button.classList.remove('error-state');
                        button.style.background = '';
                        button.style.border = '';
                        addToCartWithNetworkError(productId, productName, productPrice, event);
                    };
                    
                    // Update debug panel
                    document.getElementById('debug-last-response').textContent = 'FAILED';
                    document.getElementById('debug-error-code').textContent = 'ERR_NETWORK_ERROR';
                    document.getElementById('debug-error-code').classList.add('error');
                    
                    // Show error toast
                    showToastNotification(
                        'Network error. Please check your connection.',
                        'error',
                        'ERR_NETWORK_ERROR'
                    );
                    
                    // Reset button error state after animation
                    setTimeout(() => {
                        button.classList.remove('error-state');
                    }, 500);
                    
                    console.log('═══════════════════════════════════════');
                    console.log('Result: FAILED - Network Error');
                    console.log('User can retry without page reload');
                    console.log('═══════════════════════════════════════');
                    
                }, 1500); // Simulate 1.5s network delay
                
                return;
            }
            
            // ========================================
            // NORMAL PATH: Send request to server
            // ========================================
            console.log('✅ Sending request to server...');
            
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('product_id', productId);
            formData.append('quantity', 1);
            
            fetch('api/cart_handler.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('📦 Server Response:', data);
                
                // Update debug panel
                document.getElementById('debug-last-response').textContent = 
                    data.success ? 'SUCCESS' : 'ERROR';
                document.getElementById('debug-error-code').textContent = 
                    data.error_code || 'NONE';
                
                if (data.success) {
                    // SUCCESS: Update UI with real data from server
                    button.classList.remove('loading');
                    button.textContent = '✓ Added!';
                    button.classList.add('success');
                    
                    // Sync cart badge ONLY after server confirmation
                    updateCartCounterDisplay(data.data.cart_count);
                    updateCartStateDisplay(data.data.cart_count);
                    
                    // Reset button after delay
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('success');
                        button.disabled = false;
                    }, 1500);
                    
                    showToastNotification(
                        `${productName} berhasil ditambahkan ke keranjang!`,
                        'success'
                    );
                    
                    console.log('═══════════════════════════════════════');
                    console.log('Result: SUCCESS');
                    console.log('Cart synced with server');
                    console.log('═══════════════════════════════════════');
                } else {
                    // Server error
                    throw new Error(data.message || 'Server error');
                }
            })
            .catch(error => {
                console.error('❌ Network error:', error);
                
                // Stop loading immediately
                button.classList.remove('loading');
                button.disabled = false;
                
                // DO NOT update cart badge optimistically
                
                // Change to retry state
                button.textContent = 'Add to Cart (Retry)';
                button.classList.add('error-state');
                button.style.background = '#d32f2f';
                button.style.border = '2px solid #ff5252';
                
                // Store retry function
                pendingRetry = function() {
                    button.classList.remove('error-state');
                    button.style.background = '';
                    button.style.border = '';
                    addToCartWithNetworkError(productId, productName, productPrice, event);
                };
                
                // Update debug panel
                document.getElementById('debug-last-response').textContent = 'FAILED';
                document.getElementById('debug-error-code').textContent = 'ERR_NETWORK_ERROR';
                document.getElementById('debug-error-code').classList.add('error');
                
                // Show error toast
                showToastNotification(
                    'Network error. Please check your connection.',
                    'error',
                    'ERR_NETWORK_ERROR'
                );
                
                // Reset button error state
                setTimeout(() => {
                    button.classList.remove('error-state');
                }, 500);
                
                console.log('═══════════════════════════════════════');
                console.log('Result: FAILED - Network Error');
                console.log('═══════════════════════════════════════');
            });
        }
        
        // Update cart state display
        function updateCartStateDisplay(count) {
            document.getElementById('cart-items-count').textContent = count;
            document.getElementById('cart-total-qty').textContent = count;
            document.getElementById('cart-last-updated').textContent = 
                new Date().toLocaleTimeString();
            document.getElementById('cart-status').textContent = 'SYNCED';
            document.getElementById('cart-status').className = 'state-value success';
        }
        
        // Override updateCartCounter to also update debug panel
        const originalUpdateCartCounterDisplay = updateCartCounterDisplay;
        function updateCartCounterDisplay(count) {
            originalUpdateCartCounterDisplay(count);
        }
    </script>
</body>
</html>

