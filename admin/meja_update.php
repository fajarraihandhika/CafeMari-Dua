<?php
session_start();
include "../Koneksi2.php";

/* PROTEKSI ADMIN */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

/* AMBIL DATA */
$id = (int) $_POST['id'];
$nomor_meja = mysqli_real_escape_string($koneksi, $_POST['nomor_meja']);
$kapasitas  = (int) $_POST['kapasitas'];

/* UPDATE DATA */
$update = mysqli_query($koneksi, "
  UPDATE meja SET
    nomor_meja = '$nomor_meja',
    kapasitas  = '$kapasitas'
  WHERE meja_id = '$id'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Update Meja</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if ($update) { ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: 'Data meja berhasil diperbarui.',
  confirmButtonColor: '#4e342e'
}).then(() => {
  window.location.href = 'dashboard.php?menu=meja';
});
</script>
<?php } else { ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: 'Data meja gagal diperbarui.',
  confirmButtonColor: '#d33'
}).then(() => {
  window.history.back();
});
</script>
<?php } ?>

</body>
</html>
