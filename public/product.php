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
<html>
<head>
<title><?= htmlspecialchars($p['nama_produk']); ?> - Toko Aksesoris Mobil</title>
<link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <div class="product-detail">
    <img src="/aksesoris_mobil/assets/img/<?= $p['gambar']; ?>" alt="<?= htmlspecialchars($p['nama_produk']); ?>">
    <div>
      <h2><?= $p['nama_produk']; ?></h2>
      <p><?= nl2br(htmlspecialchars($p['deskripsi'])); ?></p>
      <p><strong>Harga:</strong> Rp <?= number_format($p['harga']); ?></p>
      <p><strong>Stok:</strong> <?= intval($p['stok']); ?></p>
      <p><a href="/aksesoris_mobil/public">&laquo; Kembali ke daftar produk</a></p>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
