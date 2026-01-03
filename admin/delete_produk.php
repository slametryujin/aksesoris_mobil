<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }
if(!isset($_GET['id'])){ header('Location: dashboard.php'); exit; }
$id = intval($_GET['id']);
$q = mysqli_query($conn,"SELECT gambar FROM produk WHERE id=$id");
if($q && mysqli_num_rows($q)>0){
  $r = mysqli_fetch_assoc($q);
  if(!empty($r['gambar']) && file_exists('../assets/img/'.$r['gambar'])) unlink('../assets/img/'.$r['gambar']);
  mysqli_query($conn,"DELETE FROM produk WHERE id=$id");
}
header('Location: dashboard.php'); exit;
