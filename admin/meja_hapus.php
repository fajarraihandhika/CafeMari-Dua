<?php
include "../Koneksi2.php";
mysqli_query($koneksi,"DELETE FROM meja WHERE meja_id='$_GET[id]'");
header("location:dashboard.php?menu=meja");
