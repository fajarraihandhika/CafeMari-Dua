<?php
include "../Koneksi2.php";

$kode = $_GET['kode'] ?? '';

$q = mysqli_query($koneksi,"
  SELECT r.*, p.nama_pelanggan, m.nomor_meja
  FROM reservasi r
  JOIN pelanggan p ON r.pelanggan_id=p.pelanggan_id
  JOIN meja m ON r.meja_id=m.meja_id
  WHERE r.kode_reservasi='$kode'
");

$d = mysqli_fetch_assoc($q);
if(!$d) die("Reservasi tidak ditemukan");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Bukti Reservasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white text-center">
      ☕ Bukti Reservasi Online
    </div>
    <div class="card-body">

      <h5 class="text-center mb-3">Kode Reservasi</h5>
      <h3 class="text-center text-primary fw-bold"><?= $d['kode_reservasi'] ?></h3>

      <hr>

      <table class="table">
        <tr><th>Nama</th><td><?= $d['nama_pelanggan'] ?></td></tr>
        <tr><th>Tanggal</th><td><?= $d['tanggal_reservasi'] ?></td></tr>
        <tr><th>Jam</th><td><?= $d['jam_reservasi'] ?></td></tr>
        <tr><th>Meja</th><td>Meja <?= $d['nomor_meja'] ?></td></tr>
        <tr><th>Status</th><td>
          <span class="badge bg-warning"><?= $d['status'] ?></span>
        </td></tr>
      </table>

      <div class="alert alert-info text-center">
        Tunjukkan halaman ini ke kasir
      </div>

    </div>
  </div>
</div>

</body>
</html>
