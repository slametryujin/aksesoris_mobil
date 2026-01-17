<?php
include 'config.php';
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Calculate total
$total = 0;
$cart_items = [];
$ids = array_keys($_SESSION['cart']);
$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    $quantity = $_SESSION['cart'][$product['id']];
    $subtotal = $product['price'] * $quantity;
    $total += $subtotal;
    $cart_items[] = $product;
}

// Process order
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Insert order
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, customer_address, customer_phone, total_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $address, $phone, $total]);
    $order_id = $pdo->lastInsertId();

    // Insert order items
    foreach ($cart_items as $product) {
        $quantity = $_SESSION['cart'][$product['id']];
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product['id'], $quantity, $product['price']]);
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to success page
    header('Location: order_success.php?order_id=' . $order_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Aksesoris Mobil</title>
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
                    <li><a href="cart.php">Keranjang</a></li>
                    <li><a href="contact.php">Kontak</a></li>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="checkout">
            <div class="container">
                <h2>Checkout</h2>
                <div class="checkout-content">
                    <div class="order-summary">
                        <h3>Ringkasan Pesanan</h3>
                        <?php foreach ($cart_items as $product): ?>
                            <div class="summary-item">
                                <span><?php echo $product['name']; ?> x <?php echo $_SESSION['cart'][$product['id']]; ?></span>
                                <span>Rp <?php echo number_format($product['price'] * $_SESSION['cart'][$product['id']], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="summary-total">
                            <strong>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                        </div>
                    </div>
                    <form method="post" class="checkout-form">
                        <h3>Informasi Pengiriman</h3>
                        <div class="form-group">
                            <label for="name">Nama Lengkap:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon:</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat:</label>
                            <textarea id="address" name="address" required></textarea>
                        </div>
                        <button type="submit" class="btn">Pesan Sekarang</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Aksesoris Mobil. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script src="js/chatbot.js"></script>
</body>
</html>
