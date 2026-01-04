<?php
session_start();
include "../Koneksi2.php";

/* PROTEKSI ADMIN */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

/* AMBIL DATA */
$id = (int)$_POST['id'];
$nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
$jumlah_orang = (int)$_POST['jumlah_orang'];
$harga = (int)$_POST['harga'];

/* UPDATE DATA */
$update = mysqli_query(
    $koneksi,
    "UPDATE paket_reservasi SET
        jumlah_orang = '$jumlah_orang',
        harga  = $harga
     WHERE paket_id = $id"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Update Paket</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if ($update) { ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: 'Data Paket berhasil diperbarui.',
  confirmButtonColor: '#4e342e'
}).then(() => {
  window.location.href = 'dashboard.php?menu=paket';
});
</script>
<?php } else { ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: 'Data Paket gagal diperbarui.',
  confirmButtonColor: '#d33'
}).then(() => {
  window.history.back();
});
</script>
<?php } ?>

</body>
</html>
