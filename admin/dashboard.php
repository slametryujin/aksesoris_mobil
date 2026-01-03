<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin</title>
<link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>Dashboard Admin</h2>
  <p><a href="tambah_produk.php">Tambah Produk</a> | <a href="logout.php">Logout</a></p>
  <table class="admin-table">
    <thead><tr><th>ID</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Gambar</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php
    $q = mysqli_query($conn,"SELECT * FROM produk ORDER BY id DESC");
    while($r=mysqli_fetch_assoc($q)){
      echo '<tr>';
      echo '<td>'.$r['id'].'</td>';
      echo '<td>'.htmlspecialchars($r['nama_produk']).'</td>';
      echo '<td>'.number_format($r['harga']).'</td>';
      echo '<td>'.intval($r['stok']).'</td>';
      echo '<td><img src="/aksesoris_mobil/assets/img/'.htmlspecialchars($r['gambar']).'" style="width:80px;height:50px;object-fit:cover"></td>';
      echo '<td class="admin-actions"><a href="edit_produk.php?id='.$r['id'].'">Edit</a><a href="delete_produk.php?id='.$r['id'].'" onclick="return confirm(\'Hapus produk ini?\')">Hapus</a></td>';
      echo '</tr>';
    }
    ?>
    </tbody>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
