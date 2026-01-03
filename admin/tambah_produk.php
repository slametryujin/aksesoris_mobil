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
	if(!empty($_FILES['gambar']['name'])){
		$gambar = time().'_'.basename($_FILES['gambar']['name']);
		move_uploaded_file($_FILES['gambar']['tmp_name'],"../assets/img/".$gambar);
	}
	$sql = "INSERT INTO produk (nama_produk,harga,stok,deskripsi,gambar) VALUES ('$nama','$harga','$stok','$desk','$gambar')";
	if(mysqli_query($conn,$sql)){
		$msg = 'Produk berhasil ditambahkan.';
		header('Location: dashboard.php'); exit;
	}else{
		$msg = 'Gagal menyimpan produk: '.mysqli_error($conn);
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Tambah Produk</title>
<link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
	<h2>Tambah Produk</h2>
	<?php if($msg) echo '<p>'.htmlspecialchars($msg).'</p>'; ?>
	<form method="post" enctype="multipart/form-data">
		<div class="form-group"><label>Nama Produk</label><input type="text" name="nama" required></div>
		<div class="form-group"><label>Harga</label><input type="number" name="harga" required></div>
		<div class="form-group"><label>Stok</label><input type="number" name="stok" required></div>
		<div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="5"></textarea></div>
		<div class="form-group"><label>Gambar</label><input type="file" name="gambar" accept="image/*"></div>
		<button name="simpan">Simpan</button>
	</form>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>