<?php
// Development-friendly DB connect with error reporting enabled
error_reporting(E_ALL);
ini_set('display_errors', '1');

$conn = mysqli_connect("localhost","root","","aksesoris_mobil");
if(!$conn){
	die("Koneksi database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');
?>