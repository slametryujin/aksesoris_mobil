<?php
include 'config.php';

// Simple cart implementation using session
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart (for non-AJAX requests)
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
}

// Remove from cart
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header('Location: cart.php');
    exit;
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($product_id > 0) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header('Location: cart.php');
    exit;
}

// Get cart items
$cart_items = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    if (!empty($ids)) {
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $quantity = $_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            $cart_items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'image' => $product['image']
            ];
        }
    }
}

$cart_count = count($cart_items);
$total_quantity = array_sum($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Aksesoris Mobil</title>
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
                        <span id="cart-counter" class="cart-counter" style="display: <?php echo $total_quantity > 0 ? 'flex' : 'none'; ?>;">
                            <?php echo $total_quantity; ?>
                        </span>
                    </a></li>
                    <li><a href="contact.php">Kontak</a></li>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="cart">
            <div class="container">
                <h2>Shopping Cart</h2>
                <?php if (empty($cart_items)): ?>
                    <div class="empty-cart">
                        <h3>Your cart is empty</h3>
                        <p>Add some premium automotive parts to get started!</p>
                        <a href="products.php" class="btn btn-primary">Browse Products</a>
                        
                        <!-- Dynamic Debug Panel - Shows real cart state -->
                        <div class="debug-panel" style="margin-top: 2rem; position: relative;">
                            <h4>🔍 Cart Debug Info</h4>
                            <div class="debug-item">
                                <span class="debug-label">Items in Cart:</span>
                                <span class="debug-value"><?php echo $cart_count; ?></span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Total Quantity:</span>
                                <span class="debug-value"><?php echo $total_quantity; ?></span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Session Status:</span>
                                <span class="debug-value">Active</span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Session ID:</span>
                                <span class="debug-value" style="font-size: 10px;"><?php echo session_id(); ?></span>
                            </div>
                            <div class="debug-item">
                                <span class="debug-label">Status:</span>
                                <span class="<?php echo $total_quantity > 0 ? 'debug-success' : 'debug-warning'; ?>">
                                    <?php echo $total_quantity > 0 ? 'HAS ITEMS' : 'EMPTY'; ?>
                                </span>
                            </div>
                            <p style="color: #888; font-size: 11px; margin-top: 10px;">
                                Cart state is synchronized with server session
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="cart-content">
                        <div class="cart-items">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="cart-item">
                                    <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                    <div class="item-details">
                                        <h3><?php echo $item['name']; ?></h3>
                                        <div class="item-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
                                        <div class="quantity-controls">
                                            <button onclick="updateCartQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)" class="qty-btn">-</button>
                                            <span class="quantity"><?php echo $item['quantity']; ?></span>
                                            <button onclick="updateCartQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)" class="qty-btn">+</button>
                                        </div>
                                        <div class="item-subtotal">Subtotal: Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></div>
                                    </div>
                                    <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn remove-btn">Remove</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="cart-summary">
                            <div class="summary-card">
                                <h3>Order Summary</h3>
                                <div class="summary-row">
                                    <span>Subtotal (<?php echo $cart_count; ?> items)</span>
                                    <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <div class="summary-row">
                                    <span>Tax</span>
                                    <span>Rp <?php echo number_format($total * 0.1, 0, ',', '.'); ?></span>
                                </div>
                                <hr>
                                <div class="summary-row total">
                                    <span>Total</span>
                                    <span>Rp <?php echo number_format($total * 1.1, 0, ',', '.'); ?></span>
                                </div>
                                
                                <!-- Checkout button with disable logic -->
                                <a href="checkout.php" 
                                   class="btn btn-primary checkout-btn <?php echo $cart_count === 0 ? 'btn-disabled' : ''; ?>" 
                                   <?php echo $cart_count === 0 ? 'title="Add items to cart first" style="cursor: not-allowed;"' : ''; ?>
                                   onclick="<?php echo $cart_count === 0 ? 'event.preventDefault(); showToastNotification(\'Your cart is empty. Add items to proceed.\', \'error\');' : ''; ?>">
                                    <?php echo $cart_count === 0 ? 'Checkout (Disabled - Empty Cart)' : 'Proceed to Checkout'; ?>
                                </a>
                                
                                <a href="products.php" class="btn continue-shopping">Continue Shopping</a>
                                
                                <!-- Debug info for non-empty cart -->
                                <div class="debug-panel" style="margin-top: 1.5rem; position: relative;">
                                    <h4>🔍 Cart Debug Info</h4>
                                    <div class="debug-item">
                                        <span class="debug-label">Items:</span>
                                        <span class="debug-value"><?php echo $cart_count; ?></span>
                                    </div>
                                    <div class="debug-item">
                                        <span class="debug-label">Total Quantity:</span>
                                        <span class="debug-value"><?php echo $total_quantity; ?></span>
                                    </div>
                                    <div class="debug-item">
                                        <span class="debug-label">Total:</span>
                                        <span class="debug-value">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                                    </div>
                                    <div class="debug-item">
                                        <span class="debug-label">Checkout Status:</span>
                                        <span class="debug-success"><?php echo $cart_count > 0 ? 'ENABLED' : 'DISABLED'; ?></span>
                                    </div>
                                    <div class="debug-item">
                                        <span class="debug-label">Session:</span>
                                        <span class="debug-value" style="font-size: 10px;"><?php echo session_id(); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 LuxeAuto Parts. Crafted with precision.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script src="js/chatbot.js"></script>
    <script>
        // Update cart counter on page load from server
        document.addEventListener('DOMContentLoaded', function() {
            fetchCartCount();
            updateCheckoutButtonState();
        });
        
        function updateQuantity(productId, quantity) {
            // Send update request via API
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            fetch('api/cart_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>

