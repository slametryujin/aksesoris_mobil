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
  $qimg = mysqli_query($conn,"SELECT gambar FROM produk WHERE id=$id");
  $row = mysqli_fetch_assoc($qimg);
  $gambar = $row['gambar'];
  if(!empty($_FILES['gambar']['name'])){
    $newfile = time().'_'.basename($_FILES['gambar']['name']);
    move_uploaded_file($_FILES['gambar']['tmp_name'],'../assets/img/'.$newfile);
    // remove old
    if(!empty($gambar) && file_exists('../assets/img/'.$gambar)) unlink('../assets/img/'.$gambar);
    $gambar = $newfile;
  }
  $sql = "UPDATE produk SET nama_produk='$nama',harga='$harga',stok='$stok',deskripsi='$desk',gambar='$gambar' WHERE id=$id";
  if(mysqli_query($conn,$sql)){ header('Location: dashboard.php'); exit; } else { $msg='Gagal update: '.mysqli_error($conn); }
}
$q = mysqli_query($conn,"SELECT * FROM produk WHERE id=$id");
$p = mysqli_fetch_assoc($q);
if(!$p){ header('Location: dashboard.php'); exit; }
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Produk</title>
<link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>Edit Produk</h2>
  <?php if($msg) echo '<p style="color:red">'.htmlspecialchars($msg).'</p>'; ?>
  <form method="post" enctype="multipart/form-data">
    <div class="form-group"><label>Nama Produk</label><input type="text" name="nama" value="<?= htmlspecialchars($p['nama_produk']); ?>" required></div>
    <div class="form-group"><label>Harga</label><input type="number" name="harga" value="<?= intval($p['harga']); ?>" required></div>
    <div class="form-group"><label>Stok</label><input type="number" name="stok" value="<?= intval($p['stok']); ?>" required></div>
    <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="5"><?= htmlspecialchars($p['deskripsi']); ?></textarea></div>
    <div class="form-group"><label>Gambar saat ini</label><br><img src="/aksesoris_mobil/assets/img/<?= htmlspecialchars($p['gambar']); ?>" style="width:120px;height:80px;object-fit:cover"></div>
    <div class="form-group"><label>Ganti Gambar (opsional)</label><input type="file" name="gambar" accept="image/*"></div>
    <button name="update">Update</button>
  </form>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
