<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Debug View</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-debug-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 100px;
        }
        
        .cart-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .cart-header h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .empty-cart-state {
            text-align: center;
            padding: 60px 40px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .empty-cart-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .empty-cart-state h3 {
            font-size: 1.8rem;
            color: var(--white);
            margin-bottom: 15px;
        }
        
        .empty-cart-state p {
            color: var(--silver);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        
        .browse-products-btn {
            display: inline-block;
            padding: 15px 40px;
            background: var(--accent-red);
            border: 2px solid var(--accent-red);
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .browse-products-btn:hover {
            background: #b71c1c;
            border-color: #b71c1c;
            transform: translateY(-2px);
        }
        
        /* Debug Panel - Bottom Left */
        .debug-panel {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid #333;
            border-radius: 12px;
            padding: 20px 25px;
            min-width: 300px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
            z-index: 9999;
        }
        
        .debug-panel-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #333;
        }
        
        .debug-panel-header h4 {
            color: #0f0;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .debug-panel-header span {
            font-size: 16px;
        }
        
        .debug-field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .debug-field:last-of-type {
            border-bottom: none;
        }
        
        .debug-label {
            color: #888;
            font-size: 0.85rem;
            font-family: 'Courier New', monospace;
        }
        
        .debug-value {
            font-size: 0.9rem;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .debug-value.error {
            color: #ff5252;
        }
        
        .debug-value.success {
            color: #69f0ae;
        }
        
        .debug-value.warning {
            color: #ff9800;
        }
        
        .debug-helper {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #333;
            font-size: 0.75rem;
            color: #666;
            font-family: 'Courier New', monospace;
        }
        
        .debug-helper span {
            color: #888;
        }
        
        /* Cart failure banner */
        .cart-failure-banner {
            margin-top: 30px;
            padding: 20px 25px;
            background: rgba(211, 47, 47, 0.1);
            border: 1px dashed var(--accent-red);
            border-radius: 10px;
            text-align: center;
        }
        
        .cart-failure-banner p {
            color: #ff8a80;
            font-size: 0.95rem;
            margin: 0;
        }
        
        /* Reference products */
        .reference-products {
            margin-top: 60px;
        }
        
        .reference-products h3 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--silver);
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
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
        
        .product-card h4 {
            color: var(--white);
            font-size: 1.1rem;
            margin-bottom: 8px;
        }
        
        .product-card .price {
            color: var(--accent-red);
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .add-cart-btn {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--silver);
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .add-cart-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--silver);
            color: var(--white);
        }
        
        .add-cart-btn.error {
            background: rgba(211, 47, 47, 0.2);
            border-color: var(--accent-red);
            color: #ff8a80;
        }
        
        /* Page warning */
        .page-warning {
            position: fixed;
            top: 100px;
            right: 30px;
            background: rgba(211, 47, 47, 0.9);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: pulse 2s infinite;
            z-index: 9998;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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
                    <li><a href="cart_debug.php">Debug</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="page-warning">
        <span>⚠️</span>
        <span>Simulated Failure State</span>
    </div>

    <main>
        <div class="cart-debug-container">
            <div class="cart-header">
                <h2>Shopping Cart</h2>
            </div>

            <div class="empty-cart-state">
                <div class="empty-cart-icon">🛒</div>
                <h3>Your cart is empty</h3>
                <p>Add some premium automotive parts to get started!</p>
                <a href="products.php" class="browse-products-btn">Browse Products</a>
                
                <div class="cart-failure-banner">
                    <p>⚠️ Previous add-to-cart attempts failed. Cart session was not updated.</p>
                </div>
            </div>

            <div class="reference-products">
                <h3>Try Adding These Products</h3>
                <div class="product-grid">
                    <div class="product-card">
                        <img src="images/steering-wheel.jpg" alt="Racing Steering Wheel" onerror="this.src='images/placeholder.jpg'">
                        <div class="content">
                            <h4>Premium Racing Steering Wheel</h4>
                            <div class="price">Rp 2.500.000</div>
                            <button class="add-cart-btn error" onclick="attemptAddToCart()">+ Add to Cart (Will Fail)</button>
                        </div>
                    </div>
                    
                    <div class="product-card">
                        <img src="images/placeholder.jpg" alt="Leather Seat Cover">
                        <div class="content">
                            <h4>Leather Seat Cover</h4>
                            <div class="price">Rp 850.000</div>
                            <button class="add-cart-btn error" onclick="attemptAddToCart()">+ Add to Cart (Will Fail)</button>
                        </div>
                    </div>
                    
                    <div class="product-card">
                        <img src="images/placeholder.jpg" alt="LED Interior Lights">
                        <div class="content">
                            <h4>LED Interior Lights Kit</h4>
                            <div class="price">Rp 450.000</div>
                            <button class="add-cart-btn error" onclick="attemptAddToCart()">+ Add to Cart (Will Fail)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="debug-panel">
        <div class="debug-panel-header">
            <span>🔍</span>
            <h4>CART DEBUG INFO</h4>
        </div>
        
        <div class="debug-field">
            <span class="debug-label">Items in Cart</span>
            <span class="debug-value error">0</span>
        </div>
        
        <div class="debug-field">
            <span class="debug-label">Session Status</span>
            <span class="debug-value success">Active</span>
        </div>
        
        <div class="debug-field">
            <span class="debug-label">Add to Cart</span>
            <span class="debug-value warning">Simulating Failure</span>
        </div>
        
        <div class="debug-field">
            <span class="debug-label">Error Code</span>
            <span class="debug-value error">ERR_MISSING_PRODUCT_ID</span>
        </div>
        
        <p class="debug-helper">
            <span>💡</span> Check console (F12) for detailed error logs
        </p>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2023 LuxeAuto Parts. Crafted with precision.</p>
        </div>
    </footer>

    <script>
        console.log('═══════════════════════════════════════');
        console.log('  SHOPPING CART - DEBUG VIEW');
        console.log('═══════════════════════════════════════');
        console.log('Cart State: EMPTY');
        console.log('Error Code: ERR_MISSING_PRODUCT_ID');
        console.log('Debug Panel: Visible (bottom-left)');
        console.log('═══════════════════════════════════════');

        function attemptAddToCart() {
            console.log('═══════════════════════════════════════');
            console.log('Attempting to add product to cart');
            console.log('Result: FAILED - Simulated Error');
            console.log('Error: Product ID validation failed');
            console.log('═══════════════════════════════════════');
            
            showToast();
            
            const panel = document.querySelector('.debug-panel');
            panel.style.borderColor = '#ff5252';
            setTimeout(() => {
                panel.style.borderColor = '#333';
            }, 500);
        }
        
        function showToast() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position:fixed;top:100px;right:30px;z-index:10000;';
            document.body.appendChild(container);
            
            const toast = document.createElement('div');
            toast.style.cssText = 'background:rgba(211,47,47,0.95);color:white;padding:16px 24px;border-radius:10px;min-width:320px;border-left:4px solid #ff5252;font-family:Roboto,sans-serif;';
            toast.innerHTML = '<div style="display:flex;align-items:center;gap:12px;"><span style="font-size:20px;">❌</span><div><div style="font-weight:500;">Failed to add item to cart. Please try again.</div><div style="font-size:12px;font-family:monospace;color:#ff8a80;margin-top:5px;">Code: ERR_MISSING_PRODUCT_ID</div></div></div>';
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(50px)';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>

