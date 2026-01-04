<?php
include "../Koneksi2.php"; 

$nama_paket  = $_POST['nama_paket'];
$jumlah_orang = $_POST['jumlah_orang'];
$harga    = $_POST['harga'];

$query = "INSERT INTO paket_reservasi (nama_paket, jumlah_orang, harga )
          VALUES ('$nama_paket', '$jumlah_orang', '$harga')";

$simpan = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
if ($simpan) {
    echo "
        <script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Paket berhasil disimpan',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location='dashboard.php';
        });
        </script>
    ";
} else {
    $error = mysqli_error($koneksi);
    echo "
        <script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Gagal menyimpan Data Barang: $error',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        </script>
    ";
}
?>

</body>
</html>
