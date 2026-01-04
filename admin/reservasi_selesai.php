<?php
session_start();
include "../Koneksi2.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) $_GET['id'];

mysqli_query($koneksi, "
    UPDATE reservasi
    SET status = 'Selesai'
    WHERE reservasi_id = '$id'
");

header("Location: dashboard.php?menu=reservasi");
exit;
