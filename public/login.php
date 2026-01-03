<?php
session_start();
include '../config/db.php';
if(isset($_POST['login'])){
$email=$_POST['email'];
$pass=md5($_POST['password']);
$q=mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$pass'");
if(mysqli_num_rows($q)>0){
$_SESSION['user']=$email;
header("Location: index.php");
}else{ echo "Login gagal"; }
}
?>
<form method="post">
<input type="email" name="email" placeholder="Email">
<input type="password" name="password" placeholder="Password">
<button name="login">Login</button>
</form>