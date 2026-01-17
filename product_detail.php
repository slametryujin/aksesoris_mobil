<?php
include 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Aksesoris Mobil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1>Aksesoris Mobil</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Produk</a></li>
                    <li><a href="cart.php">
                        Keranjang
                        <span id="cart-counter" class="cart-counter" style="display: none;">0</span>
                    </a></li>
                    <li><a href="contact.php">Kontak</a></li>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="product-detail">
            <div class="container">
                <div class="product-info">
                    <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    <div class="details">
                        <h2><?php echo $product['name']; ?></h2>
                        <p class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        <p class="stock">Stok: <?php echo $product['stock']; ?></p>
                        <p class="description"><?php echo $product['description']; ?></p>
                        <button 
                            onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, event)" 
                            class="btn btn-primary btn-add-cart-debug"
                            data-product-id="<?php echo $product['id']; ?>"
                            data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                            data-product-price="<?php echo $product['price']; ?>"
                        >
                            Tambah ke Keranjang
                        </button>
                        
                        <!-- Debug Info Panel (visible in debug mode) -->
                        <div class="debug-panel" id="product-debug-panel" style="display: none;">
                            <h4>🔍 Debug Info</h4>
                            <div class="debug-item">
                                <span class="debug-label">Product ID:</span>
                                <span class="debug-value"><?php echo $product['id']; ?></span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Product Name:</span>
                                <span class="debug-value"><?php echo htmlspecialchars($product['name']); ?></span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Price:</span>
                                <span class="debug-value">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Stock:</span>
                                <span class="debug-value"><?php echo $product['stock']; ?></span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Cart Count:</span>
                                <span class="debug-value" id="debug-cart-count">0</span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Status:</span>
                                <span class="debug-error" id="debug-cart-status">EMPTY</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Error Boundary Container (shown when errors occur) -->
                <div id="error-boundary-container"></div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Aksesoris Mobil. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Show debug panel in development
        document.addEventListener('DOMContentLoaded', function() {
            const debugPanel = document.getElementById('product-debug-panel');
            if (debugPanel && window.location.search.includes('debug=1')) {
                debugPanel.style.display = 'block';
            }
            
            // Update debug info when cart changes
            updateDebugInfo();
        });
        
        function updateDebugInfo() {
            const debugCartCount = document.getElementById('debug-cart-count');
            const debugCartStatus = document.getElementById('debug-cart-status');
            
            if (debugCartCount) {
                debugCartCount.textContent = getCartCount();
            }
            
            if (debugCartStatus) {
                const count = getCartCount();
                if (count === 0) {
                    debugCartStatus.textContent = 'EMPTY';
                    debugCartStatus.className = 'debug-error';
                } else {
                    debugCartStatus.textContent = 'HAS ITEMS';
                    debugCartStatus.className = 'debug-success';
                }
            }
        }
        
        // Override updateCartCounter to also update debug panel
        const originalUpdateCartCounter = updateCartCounter;
        function updateCartCounter() {
            originalUpdateCartCounter();
            updateDebugInfo();
            updateCheckoutButtonState();
        }
    </script>
</body>
</html>

