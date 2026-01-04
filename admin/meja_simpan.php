<?php
include "../Koneksi2.php";

$nomor_meja = $_POST['nomor_meja'];
$kapasitas  = $_POST['kapasitas'];
$status     = $_POST['status'];

mysqli_query($koneksi,"
  INSERT INTO meja (nomor_meja, kapasitas, status)
  VALUES ('$nomor_meja', '$kapasitas', '$status')
");

header("Location: dashboard.php?menu=meja");
exit;
