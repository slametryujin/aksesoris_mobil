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
<html>
<head>
<title>Login Admin</title>
<link rel="stylesheet" href="/aksesoris_mobil/assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container">
  <h2>Login Admin</h2>
  <?php if($error) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
  <form method="post">
    <div class="form-group"><label>Email</label><input type="text" name="email"></div>
    <div class="form-group"><label>Password</label><input type="password" name="password"></div>
    <button name="login">Masuk</button>
  </form>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
