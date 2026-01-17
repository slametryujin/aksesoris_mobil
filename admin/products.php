<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: products.php');
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = $_POST['image'];

    if (isset($_POST['id']) && $_POST['id']) {
        // Update
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $stock, $image, $id]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $image]);
    }

    header('Location: products.php');
    exit;
}

// Get products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();

// Get product for edit
$edit_product = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $edit_product = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin</title>
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
                <h2>Kelola Produk</h2>

                <form method="post" class="product-form">
                    <?php if ($edit_product): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="name">Nama Produk:</label>
                        <input type="text" id="name" name="name" value="<?php echo $edit_product['name'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi:</label>
                        <textarea id="description" name="description" required><?php echo $edit_product['description'] ?? ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga:</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo $edit_product['price'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stock">Stok:</label>
                        <input type="number" id="stock" name="stock" value="<?php echo $edit_product['stock'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Gambar (nama file):</label>
                        <input type="text" id="image" name="image" value="<?php echo $edit_product['image'] ?? ''; ?>" required>
                    </div>
                    <button type="submit" class="btn"><?php echo $edit_product ? 'Update' : 'Tambah'; ?> Produk</button>
                    <?php if ($edit_product): ?>
                        <a href="products.php" class="btn">Batal</a>
                    <?php endif; ?>
                </form>

                <h3>Daftar Produk</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                <td><?php echo $product['stock']; ?></td>
                                <td>
                                    <a href="products.php?edit=<?php echo $product['id']; ?>" class="btn">Edit</a>
                                    <a href="products.php?delete=<?php echo $product['id']; ?>" class="btn" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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
