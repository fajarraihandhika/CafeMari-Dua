<?php
session_start();
include "../Koneksi2.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

/* ==========================
   1. AMBIL DATA FORM
   ========================== */
$nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
$tanggal = $_POST['tanggal'];
$jam     = $_POST['jam'];
$meja    = $_POST['meja_id'];
$paket_id= $_POST['paket_id'];
$email   = $_POST['email'];

/* ==========================
   2. CEK / SIMPAN PELANGGAN
   ========================== */
$cekPelanggan = mysqli_query($koneksi,"
    SELECT pelanggan_id 
    FROM pelanggan 
    WHERE nama_pelanggan='$nama'
");

if(mysqli_num_rows($cekPelanggan) > 0){
    $p = mysqli_fetch_assoc($cekPelanggan);
    $pelanggan_id = $p['pelanggan_id'];
}else{
    mysqli_query($koneksi,"
        INSERT INTO pelanggan (nama_pelanggan,email)
        VALUES ('$nama','$email')
    ");
    $pelanggan_id = mysqli_insert_id($koneksi);
}

/* ==========================
   3. CEK MEJA BENTROK
   ========================== */
$cekMeja = mysqli_query($koneksi,"
    SELECT 1 FROM reservasi
    WHERE meja_id='$meja'
    AND tanggal_reservasi='$tanggal'
    AND status IN ('Booked','Confirmed')
");

if(mysqli_num_rows($cekMeja) > 0){
    $_SESSION['swal'] = [
        'icon'  => 'error',
        'title' => 'Meja Tidak Tersedia',
        'text'  => 'Meja sudah dibooking di tanggal tersebut.'
    ];
    header("Location: dashboard.php?menu=reservasi");
    exit;
}

/* ==========================
   4. AMBIL HARGA PAKET
   ========================== */
$getHarga = mysqli_query($koneksi,"
    SELECT harga FROM paket_reservasi
    WHERE paket_id='$paket_id'
");

if(mysqli_num_rows($getHarga) == 0){
    die("Paket tidak valid");
}

$p = mysqli_fetch_assoc($getHarga);
$harga = $p['harga'];

/* ==========================
   5. SIMPAN RESERVASI
   ========================== */
$kode = 'RSV-' . strtoupper(substr(md5(time()),0,6));
$id_user = $_SESSION['user_id'];


mysqli_query($koneksi,"
    INSERT INTO reservasi
    (pelanggan_id, meja_id, paket_id, tanggal_reservasi, jam_reservasi, total_bayar, status, kode_reservasi,user_id)
    VALUES
    ('$pelanggan_id','$meja','$paket_id','$tanggal','$jam','$harga','Pending','$kode','$id_user')
");

/* ==========================
   6. KIRIM EMAIL HTML
   ========================== */
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'cafemaridua@gmail.com';      // GANTI
    $mail->Password   = 'csov uqcz ytuo btde';         // APP PASSWORD
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('cafemaridua@gmail.com', 'Cafe Mari-Dua');
    $mail->addAddress($email, $nama);

    $mail->isHTML(true);
    $mail->Subject = 'Bukti Reservasi Café Mari-Dua';

    $mail->Body = "
    <html>
    <body style='background:#f5efe6;font-family:Arial'>
      <div style='max-width:600px;margin:auto;background:#fff;border-radius:12px;overflow:hidden'>
        <div style='background:#4e342e;color:#fff;padding:20px;text-align:center'>
          <h2>☕ Café Mari-Dua</h2>
          <p>Bukti Reservasi Online</p>
        </div>
        <div style='padding:25px;color:#3e2723'>
          <p>Halo <strong>$nama</strong>,</p>
          <p>Reservasi kamu berhasil dibuat dengan detail berikut:</p>

          <table width='100%' cellpadding='8'>
            <tr><td>Kode Reservasi</td><td><strong>$kode</strong></td></tr>
            <tr><td>Meja</td><td>Meja $meja</td></tr>
            <tr><td>Tanggal</td><td>$tanggal</td></tr>
            <tr><td>Jam</td><td>$jam</td></tr>
            <tr><td>Status</td><td style='color:#ff9800'><strong>Pending</strong></td></tr>
          </table>

          <p style='margin-top:20px'>
            Tunjukkan email ini ke kasir saat datang.
          </p>

          <p>Salam hangat,<br><strong>Café Mari-Dua</strong></p>
        </div>
      </div>
    </body>
    </html>
    ";

    $mail->send();

} catch (Exception $e) {
    $_SESSION['swal'] = [
        'icon'  => 'error',
        'title' => 'Email Gagal',
        'text'  => 'Reservasi tersimpan, tapi email gagal dikirim.'
    ];
    header("Location: dashboard.php?menu=reservasi");
    exit;
}

/* ==========================
   7. SWEET ALERT SUKSES
   ========================== */
$_SESSION['swal'] = [
    'icon'  => 'success',
    'title' => 'Reservasi Berhasil!',
    'text'  => 'Reservasi berhasil dibuat. Silakan cek email sebagai bukti reservasi.'
];

/* ==========================
   8. REDIRECT
   ========================== */
header("Location: dashboard.php?menu=reservasi");
exit;
