<?php
include 'config.php';

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
    <title>Produk - Aksesoris Mobil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1>LuxeAuto Parts</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="cart.php">
                        Cart
                        <span id="cart-counter" class="cart-counter" style="display: none;">0</span>
                    </a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="products">
            <div class="container">
                <h2>Daftar Produk</h2>
                <div class="search-bar">
                    <form method="get">
                        <input type="text" name="search" id="search-input" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn" onclick="searchProducts()">Cari</button>
                    </form>
                </div>
                <div class="product-grid">
                    <?php if (empty($products)): ?>
                        <p>Tidak ada produk ditemukan.</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                <div class="content">
                                    <h3><?php echo $product['name']; ?></h3>
                                    <p><?php echo substr($product['description'], 0, 100); ?>...</p>
                                    <div class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                        <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn">Lihat Detail</a>
                                        <button 
                                            onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, event)" 
                                            class="btn btn-primary btn-add-cart-debug"
                                            data-product-id="<?php echo $product['id']; ?>"
                                            data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                                            data-product-price="<?php echo $product['price']; ?>"
                                        >
                                            + Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
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
</body>
</html>

