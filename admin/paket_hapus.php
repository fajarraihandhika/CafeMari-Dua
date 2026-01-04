<?php
include "../Koneksi2.php";
mysqli_query($koneksi,"DELETE FROM paket_reservasi WHERE paket_id='$_GET[id]'");
header("location:dashboard.php?menu=paket");
