<?php
include "../Koneksi2.php";

$tanggal = $_GET['tanggal'] ?? '';

if(!$tanggal){
  echo "<option value=''>Pilih tanggal dulu</option>";
  exit;
}

$q = mysqli_query($koneksi,"
  SELECT * FROM meja
  WHERE meja_id NOT IN (
    SELECT meja_id FROM reservasi
    WHERE status between 'Booked' and 'Pending'
    AND tanggal_reservasi='$tanggal'
  )
");

if(mysqli_num_rows($q)==0){
  echo "<option value=''>Semua meja penuh</option>";
}else{
  echo "<option value=''>-- Pilih Meja --</option>";
  while($m=mysqli_fetch_assoc($q)){
    echo "<option value='$m[meja_id]'>
            Meja $m[nomor_meja] ($m[kapasitas] orang)
          </option>";
  }
}
