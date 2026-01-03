<?php
session_start();
include '../config/db.php';
if(!isset($_GET['id'])){ header('Location: index.php'); exit; }
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$p = mysqli_fetch_assoc($q);
if(!$p){ header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($p['nama_produk']); ?> - Toko Aksesoris Mobil</title>
  <link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<!-- Page Header -->
<section class="hero" style="padding: 30px 20px;">
  <div class="container">
    <h2><?= htmlspecialchars($p['nama_produk']); ?></h2>
    <p>Detail produk lengkap</p>
  </div>
</section>

<div class="container">
  <div class="product-detail">
    <div class="product-detail-image">
      <img src="/aksesoris_mobil/assets/img/<?= $p['gambar']; ?>" alt="<?= htmlspecialchars($p['nama_produk']); ?>">
    </div>
    <div class="product-detail-info">
      <h2><?= $p['nama_produk']; ?></h2>
      <div class="product-detail-price">Rp <?= number_format($p['harga']); ?></div>
      
      <div class="product-meta">
        <div class="product-meta-item">
          <span class="product-meta-label">Stok Tersedia</span>
          <span class="product-meta-value"><?= intval($p['stok']); ?> unit</span>
        </div>
        <div class="product-meta-item">
          <span class="product-meta-label">Kategori</span>
          <span class="product-meta-value"><?= htmlspecialchars($p['kategori_id'] ?? 'Umum'); ?></span>
        </div>
      </div>
      
      <div class="product-detail-description">
        <h4 style="margin-bottom: 12px;">Deskripsi Produk</h4>
        <p><?= nl2br(htmlspecialchars($p['deskripsi'])); ?></p>
      </div>
      
      <div class="card-actions" style="margin-top: 24px;">
        <a href="/aksesoris_mobil/public" class="btn btn-secondary">&laquo; Kembali</a>
        <button class="btn btn-primary" onclick="alert('Fitur keranjang belanja akan segera hadir!')">Tambah ke Keranjang</button>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

