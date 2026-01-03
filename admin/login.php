<?php
session_start();
include '../config/db.php';
$error='';
if(isset($_POST['login'])){
  $email = mysqli_real_escape_string($conn,$_POST['email']);
  $pass = mysqli_real_escape_string($conn,$_POST['password']);
  $res = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password=MD5('$pass')");
  if(mysqli_num_rows($res)==1){
    $_SESSION['admin']=true;
    header('Location: dashboard.php'); exit;
  }else{ $error='Login gagal - cek email & password'; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Toko Aksesoris Mobil</title>
  <link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
  
  <div class="container" style="max-width: 420px;">
    <div class="form-container" style="margin: 0; box-shadow: var(--shadow-lg);">
      <div style="text-align: center; margin-bottom: 32px;">
        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px;">🔐</div>
        <h2 style="margin-bottom: 8px;">Login Admin</h2>
        <p class="text-muted">Masukkan kredensial Anda untuk mengakses dashboard</p>
      </div>

      <?php if($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" id="email" name="email" placeholder="Masukkan email" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Masukkan password" required>
        </div>
        
        <button type="submit" name="login" class="btn btn-primary btn-block" style="margin-top: 8px;">Masuk</button>
      </form>

      <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--gray-200);">
        <a href="/aksesoris_mobil/public" class="text-muted" style="font-size: 14px;">&laquo; Kembali ke Beranda</a>
      </div>
    </div>
  </div>

</body>
</html>

