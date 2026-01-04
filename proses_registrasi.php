<?php
include "Koneksi2.php";

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($koneksi, "INSERT INTO users (nama,email,password,role)
VALUES ('$nama','$email','$password','user')");

header("Location: login.php");
