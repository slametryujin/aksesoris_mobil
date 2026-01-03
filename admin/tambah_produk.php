<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }
$msg='';
if(isset($_POST['simpan'])){
	$nama = mysqli_real_escape_string($conn,$_POST['nama']);
	$harga = intval($_POST['harga']);
	$stok = intval($_POST['stok']);
	$desk = mysqli_real_escape_string($conn,$_POST['deskripsi']);
	$gambar = '';
	$kategori_id = isset($_POST['kategori']) ? intval($_POST['kategori']) : NULL;
	if(!empty($_FILES['gambar']['name'])){
		$gambar = time().'_'.basename($_FILES['gambar']['name']);
		move_uploaded_file($_FILES['gambar']['tmp_name'],"../assets/img/".$gambar);
	}
	$sql = "INSERT INTO produk (nama_produk,harga,stok,deskripsi,gambar,kategori_id) VALUES ('$nama','$harga','$stok','$desk','$gambar'," . ($kategori_id? $kategori_id : 'NULL') . ")";
	if(mysqli_query($conn,$sql)){
		header('Location: dashboard.php'); exit;
	}else{
		$msg = 'Gagal menyimpan produk: '.mysqli_error($conn);
	}
}

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
  <title>Tambah Produk - Toko Aksesoris Mobil</title>
  <link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container" style="padding-top: 32px; padding-bottom: 40px;">
  <div class="page-header" style="text-align: left; padding: 0 0 32px 0;">
    <h2>Tambah Produk Baru</h2>
    <p class="text-muted">Isi informasi produk di bawah ini</p>
  </div>

  <?php if($msg): ?>
    <div class="alert alert-error"><?= htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <div class="form-container" style="max-width: 700px;">
    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nama">Nama Produk *</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan nama produk" required>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label for="kategori">Kategori</label>
          <select id="kategori" name="kategori">
            <option value="0">-- Pilih Kategori --</option>
            <?php foreach($kategories as $k): ?>
              <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="harga">Harga (Rp) *</label>
          <input type="number" id="harga" name="harga" placeholder="0" min="0" required>
        </div>
      </div>

      <div class="form-group">
        <label for="stok">Stok *</label>
        <input type="number" id="stok" name="stok" placeholder="0" min="0" required>
      </div>

      <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Deskripsikan produk..."></textarea>
      </div>

      <div class="form-group">
        <label for="gambar">Gambar Produk</label>
        <input type="file" id="gambar" name="gambar" accept="image/*" style="padding: 12px;">
        <small class="text-muted" style="display: block; margin-top: 8px;">Format yang didukung: JPG, PNG, GIF. Maksimal 5MB.</small>
      </div>

      <div class="form-actions" style="justify-content: flex-start;">
        <button type="submit" name="simpan" class="btn btn-primary">Simpan Produk</button>
        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

