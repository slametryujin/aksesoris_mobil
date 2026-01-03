<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }

// Get stats
$totalProduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
$totalStok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM produk"))['total'];
$totalKategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Toko Aksesoris Mobil</title>
  <link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container" style="padding-top: 32px; padding-bottom: 40px;">
  <div class="page-header" style="text-align: left; padding: 0 0 32px 0;">
    <h2>Dashboard Admin</h2>
    <p>Kelola produk dan informasi toko Anda</p>
  </div>

  <!-- Stats Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-card-icon" style="background: rgba(52, 152, 219, 0.15); color: var(--secondary-color);">📦</div>
      <div class="stat-card-value"><?= $totalProduk; ?></div>
      <div class="stat-card-label">Total Produk</div>
    </div>
    <div class="stat-card">
      <div class="stat-card-icon" style="background: rgba(39, 174, 96, 0.15); color: var(--success-color);">📊</div>
      <div class="stat-card-value"><?= number_format($totalStok); ?></div>
      <div class="stat-card-label">Total Stok</div>
    </div>
    <div class="stat-card">
      <div class="stat-card-icon" style="background: rgba(155, 89, 182, 0.15); color: #9b59b6;">📁</div>
      <div class="stat-card-value"><?= $totalKategori; ?></div>
      <div class="stat-card-label">Kategori</div>
    </div>
  </div>

  <!-- Actions Bar -->
  <div class="flex justify-between items-center mb-3" style="margin-bottom: 20px;">
    <h3 style="font-size: 20px;">Daftar Produk</h3>
    <a href="tambah_produk.php" class="btn btn-primary">+ Tambah Produk</a>
  </div>

  <!-- Admin Table -->
  <div class="admin-table-container">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width: 60px;">ID</th>
          <th style="width: 100px;">Gambar</th>
          <th>Nama Produk</th>
          <th>Kategori</th>
          <th style="width: 120px;">Harga</th>
          <th style="width: 80px;">Stok</th>
          <th style="width: 120px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $q = mysqli_query($conn,"SELECT p.*, k.nama as kategori_nama FROM produk p LEFT JOIN kategori k ON p.kategori_id=k.id ORDER BY p.id DESC");
        if(mysqli_num_rows($q) == 0):
        ?>
        <tr>
          <td colspan="7" style="text-align: center; padding: 40px; color: var(--gray-600);">
            Belum ada produk. <a href="tambah_produk.php">Tambah produk pertama</a>
          </td>
        </tr>
        <?php else: ?>
          <?php while($r=mysqli_fetch_assoc($q)): ?>
            <tr>
              <td><?= $r['id']; ?></td>
              <td>
                <img src="/aksesoris_mobil/assets/img/<?= htmlspecialchars($r['gambar']); ?>" alt="<?= htmlspecialchars($r['nama_produk']); ?>">
              </td>
              <td><?= htmlspecialchars($r['nama_produk']); ?></td>
              <td><?= htmlspecialchars($r['kategori_nama'] ?? '-'); ?></td>
              <td>Rp <?= number_format($r['harga']); ?></td>
              <td><?= intval($r['stok']); ?></td>
              <td class="admin-actions">
                <a href="edit_produk.php?id=<?= $r['id']; ?>" class="edit-btn">Edit</a>
                <a href="delete_produk.php?id=<?= $r['id']; ?>" class="delete-btn" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Logout Button -->
  <div style="margin-top: 32px; text-align: right;">
    <a href="logout.php" class="btn btn-secondary">Logout</a>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

