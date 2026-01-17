<?php
// Database configuration
$host = 'localhost';
$dbname = 'aksesoris_mobil';
$username = 'root'; // Change as per your setup
$password = ''; // Change as per your setup

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
