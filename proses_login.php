<?php
session_start();
include "Koneksi2.php";


$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($query);

if ($user) {
    if (password_verify($password, $user['password'])) {

        // SET SESSION
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];

        // REDIRECT SESUAI ROLE
        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
            exit;
        } else {
            header("Location: pelanggan/dashboard.php");
            exit;
        }

    } else {
        echo "<script>alert('Password salah');location='login.php';</script>";
    }
} else {
    echo "<script>alert('Email tidak terdaftar');location='login.php';</script>";
}
