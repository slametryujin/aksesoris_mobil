<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get statistics
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_amount) FROM orders")->fetchColumn();
$low_stock_products = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 10")->fetchColumn();

// Get recent orders
$recent_orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Aksesoris Mobil</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <nav class="admin-nav">
            <div class="container">
                <h1>Admin Dashboard</h1>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="products.php">Kelola Produk</a></li>
                    <li><a href="orders.php">Lihat Pesanan</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>

        <main>
            <div class="container">
                <h2>Dashboard</h2>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Produk</h3>
                        <p><?php echo $total_products; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Pesanan</h3>
                        <p><?php echo $total_orders; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Pendapatan</h3>
                        <p>Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Produk Stok Rendah</h3>
                        <p><?php echo $low_stock_products; ?></p>
                    </div>
                </div>

                <h3>Pesanan Terbaru</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelanggan</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['customer_name']; ?></td>
                                <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                                <td><?php echo $order['created_at']; ?></td>
                                <td>
                                    <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn">Lihat Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
