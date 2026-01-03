<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }
if(!isset($_GET['id'])){ header('Location: dashboard.php'); exit; }
$id = intval($_GET['id']);
$msg='';
if(isset($_POST['update'])){
  $nama = mysqli_real_escape_string($conn,$_POST['nama']);
  $harga = intval($_POST['harga']);
  $stok = intval($_POST['stok']);
  $desk = mysqli_real_escape_string($conn,$_POST['deskripsi']);
  $kategori_id = isset($_POST['kategori']) ? intval($_POST['kategori']) : NULL;
  $qimg = mysqli_query($conn,"SELECT gambar FROM produk WHERE id=$id");
  $row = mysqli_fetch_assoc($qimg);
  $gambar = $row['gambar'];
  if(!empty($_FILES['gambar']['name'])){
    $newfile = time().'_'.basename($_FILES['gambar']['name']);
    move_uploaded_file($_FILES['gambar']['tmp_name'],'../assets/img/'.$newfile);
    // Hapus gambar lama jika ada
    if(!empty($gambar) && file_exists('../assets/img/'.$gambar)) unlink('../assets/img/'.$gambar);
    $gambar = $newfile;
  }
  $sql = "UPDATE produk SET nama_produk='$nama',harga='$harga',stok='$stok',deskripsi='$desk',gambar='$gambar', kategori_id=" . ($kategori_id? $kategori_id : 'NULL') . " WHERE id=$id";
  if(mysqli_query($conn,$sql)){ header('Location: dashboard.php'); exit; } else { $msg='Gagal update: '.mysqli_error($conn); }
}
$q = mysqli_query($conn,"SELECT * FROM produk WHERE id=$id");
$p = mysqli_fetch_assoc($q);
if(!$p){ header('Location: dashboard.php'); exit; }

// fetch kategori list
$katRes = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama");
$kategories = [];
while($kc = mysqli_fetch_assoc($katRes)) $kategories[] = $kc;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk - Toko Aksesoris Mobil</title>
  <link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container" style="padding-top: 32px; padding-bottom: 40px;">
  <div class="page-header" style="text-align: left; padding: 0 0 32px 0;">
    <h2>Edit Produk</h2>
    <p class="text-muted">Perbarui informasi produk di bawah ini</p>
  </div>

  <?php if($msg): ?>
    <div class="alert alert-error"><?= htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <div class="form-container" style="max-width: 700px;">
    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nama">Nama Produk *</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($p['nama_produk']); ?>" required>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label for="kategori">Kategori</label>
          <select id="kategori" name="kategori">
            <option value="0">-- Pilih Kategori --</option>
            <?php foreach($kategories as $k): 
              $sel = ($p['kategori_id']==$k['id']) ? 'selected' : ''; ?>
              <option value="<?= $k['id']; ?>" <?= $sel; ?>><?= htmlspecialchars($k['nama']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="harga">Harga (Rp) *</label>
          <input type="number" id="harga" name="harga" value="<?= intval($p['harga']); ?>" min="0" required>
        </div>
      </div>

      <div class="form-group">
        <label for="stok">Stok *</label>
        <input type="number" id="stok" name="stok" value="<?= intval($p['stok']); ?>" min="0" required>
      </div>

      <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="5"><?= htmlspecialchars($p['deskripsi']); ?></textarea>
      </div>

      <div class="form-group">
        <label>Gambar Saat Ini</label>
        <?php if(!empty($p['gambar'])): ?>
          <div style="margin-bottom: 12px;">
            <img src="/aksesoris_mobil/assets/img/<?= htmlspecialchars($p['gambar']); ?>" alt="Gambar produk" style="max-width: 200px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
          </div>
        <?php else: ?>
          <p class="text-muted">Tidak ada gambar</p>
        <?php endif; ?>
        
        <label for="gambar">Ganti Gambar (opsional)</label>
        <input type="file" id="gambar" name="gambar" accept="image/*" style="padding: 12px;">
        <small class="text-muted" style="display: block; margin-top: 8px;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
      </div>

      <div class="form-actions" style="justify-content: flex-start;">
        <button type="submit" name="update" class="btn btn-primary">Update Produk</button>
        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

