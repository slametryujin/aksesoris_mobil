<?php
include 'config.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    header('Location: index.php');
    exit;
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Aksesoris Mobil</title>
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
        <section class="success">
            <div class="container">
                <h2>Pesanan Berhasil!</h2>
                <p>Terima kasih atas pesanan Anda. Pesanan Anda telah diterima dengan nomor pesanan: <strong><?php echo $order_id; ?></strong></p>
                <p>Kami akan segera memproses pesanan Anda dan menghubungi Anda untuk konfirmasi pengiriman.</p>
                <div class="order-details">
                    <h3>Detail Pesanan</h3>
                    <p><strong>Nama:</strong> <?php echo $order['customer_name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
                    <p><strong>Telepon:</strong> <?php echo $order['customer_phone']; ?></p>
                    <p><strong>Alamat:</strong> <?php echo $order['customer_address']; ?></p>
                    <p><strong>Total:</strong> Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></p>
                </div>
                <a href="index.php" class="btn">Kembali ke Home</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Aksesoris Mobil. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
