<?php
session_start();
include "../Koneksi2.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id     = (int) $_GET['id'];
$status = $_GET['status'];

$allowed = ['Booked','Canceled','Selesai'];
if (!in_array($status, $allowed)) {
    die("Status tidak valid");
}

mysqli_query($koneksi, "
  UPDATE reservasi
  SET status='$status'
  WHERE reservasi_id='$id'
");

header("Location: dashboard.php?menu=reservasi");
exit;

