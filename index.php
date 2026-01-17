<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aksesoris Mobil - Toko Online</title>
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
        <section class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.3)), url('images/luxury-car-hero.jpg'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="hero-content">
                    <h1>Excellence in Automotive Parts</h1>
                    <p>Discover premium automotive accessories crafted for performance and style. Elevate your driving experience with our curated collection.</p>
                    <a href="products.php" class="btn btn-primary">Explore Collection</a>
                </div>
            </div>
        </section>

        <section class="featured-products">
            <div class="container">
                <h2>Featured Products</h2>
                <div class="product-grid">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM products LIMIT 6");
                    while ($product = $stmt->fetch()) {
                        echo "<div class='product-card'>";
                        echo "<img src='images/{$product['image']}' alt='{$product['name']}'>";
                        echo "<div class='content'>";
                        echo "<h3>{$product['name']}</h3>";
                        echo "<p>{$product['description']}</p>";
                        echo "<div class='price'>Rp " . number_format($product['price'], 0, ',', '.') . "</div>";
                        echo "<div style='display: flex; gap: 10px; flex-wrap: wrap; margin-top: 1rem;'>";
                        echo "<a href='product_detail.php?id={$product['id']}' class='btn'>View Details</a>";
                        echo "<button ";
                        echo "onclick=\"addToCart({$product['id']})\" ";
                        echo "class='btn btn-primary btn-add-cart-debug' ";
                        echo "data-product-id='{$product['id']}' ";
                        echo "data-product-name='" . htmlspecialchars($product['name']) . "' ";
                        echo "data-product-price='{$product['price']}' ";
                        echo ">Add to Cart</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
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

