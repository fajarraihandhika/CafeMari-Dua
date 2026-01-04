<?php
/**
 * ====================================
 * PAYMENT PROCESSOR
 * ====================================
 * Memproses pembayaran dan menyimpan transaksi
 */

session_start();
include "../Koneksi2.php";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['toast_message'] = '❌ Silakan login terlebih dahulu';
    $_SESSION['toast_type'] = 'error';
    header("Location: ../login.php");
    exit;
}

$user_id      = (int) $_SESSION['user_id'];
$pelanggan_id = (int) $_SESSION['user_id'];

/**
 * ====================================
 * VALIDASI AWAL
 * ====================================
 */

// Cek apakah cart kosong
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['toast_message'] = '❌ Keranjang belanja kosong!';
    $_SESSION['toast_type'] = 'error';
    header("Location: ../pelanggan/dashboard.php?menu=menu");
    exit;
}

// Cek apakah request method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['toast_message'] = '❌ Invalid request method';
    $_SESSION['toast_type'] = 'error';
    header("Location: checkout.php");
    exit;
}

// Validasi payment method
if (!isset($_POST['payment_method']) || empty($_POST['payment_method'])) {
    $_SESSION['toast_message'] = '❌ Silakan pilih metode pembayaran';
    $_SESSION['toast_type'] = 'error';
    header("Location: checkout.php");
    exit;
}

// Validasi meja
if (!isset($_POST['meja_id']) || empty($_POST['meja_id'])) {
    $_SESSION['toast_message'] = '❌ Silakan pilih nomor meja';
    $_SESSION['toast_type'] = 'error';
    header("Location: checkout.php");
    exit;
}

$payment_method = mysqli_real_escape_string($koneksi, $_POST['payment_method']);
$meja_id = (int) $_POST['meja_id'];
$total = (int) $_POST['total'];

// Validasi total
if ($total <= 0) {
    $_SESSION['toast_message'] = '❌ Total transaksi tidak valid';
    $_SESSION['toast_type'] = 'error';
    header("Location: checkout.php");
    exit;
}

/**
 * ====================================
 * HITUNG ULANG TOTAL (Validasi)
 * ====================================
 */
$calculated_subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $calculated_subtotal += ($item['harga'] * $item['qty']);
}

// Hitung pajak dan biaya layanan
$tax = $calculated_subtotal * 0.10;
$service_charge = $calculated_subtotal * 0.05;
$calculated_total = $calculated_subtotal + $tax + $service_charge;

// Validasi total (toleransi Rp 10 untuk pembulatan)
if (abs($total - $calculated_total) > 10) {
    $_SESSION['toast_message'] = '❌ Total transaksi tidak sesuai';
    $_SESSION['toast_type'] = 'error';
    header("Location: checkout.php");
    exit;
}

/**
 * ====================================
 * VALIDASI METODE PEMBAYARAN
 * ====================================
 */
$valid_payment_methods = ['cash', 'bank_transfer', 'e_wallet', 'qris', 'card'];
if (!in_array($payment_method, $valid_payment_methods)) {
    $_SESSION['toast_message'] = '❌ Metode pembayaran tidak valid';
    $_SESSION['toast_type'] = 'error';
    header("Location: checkout.php");
    exit;
}

/**
 * ====================================
 * PROSES TRANSAKSI
 * ====================================
 */

// Start transaction untuk keamanan data
mysqli_begin_transaction($koneksi);

try {
    // Generate ID transaksi unik
    $order_id = 'CAFE-' . time() . '-' . $user_id;
    
    // Tentukan status pembayaran berdasarkan metode
    $payment_status = ($payment_method === 'cash') ? 'pending' : 'waiting_payment';
    
    // 1. Simpan ke tabel transaksi
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO transaksi
        (id, user_id, pelanggan_id, meja_id, tanggal, total, status, payment_method, payment_status)
        VALUES (?, ?, ?, ?, NOW(), ?, 'pending', ?, ?)
    ");
    
    if (!$stmt) {
        throw new Exception("Error preparing transaksi statement: " . mysqli_error($koneksi));
    }
    
    mysqli_stmt_bind_param($stmt, "siiisss", $order_id, $user_id, $pelanggan_id, $meja_id, $total, $payment_method, $payment_status);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error executing transaksi: " . mysqli_stmt_error($stmt));
    }
    
    $transaksi_id = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt);
    
    // 2. Simpan detail transaksi
    $stmt_detail = mysqli_prepare($koneksi, "
        INSERT INTO detail_transaksi (user_id, pelanggan_id, transaksi_id, menu_id, qty, harga, subtotal)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    if (!$stmt_detail) {
        throw new Exception("Error preparing detail statement: " . mysqli_error($koneksi));
    }
    
    foreach ($_SESSION['cart'] as $menu_id => $item) {
        $qty = (int) $item['qty'];
        $harga = (int) $item['harga'];
        $subtotal = $qty * $harga;
        
        mysqli_stmt_bind_param(
            $stmt_detail,
            "iiiiiii",
            $user_id,
            $pelanggan_id,
            $transaksi_id,
            $menu_id,
            $qty,
            $harga,
            $subtotal
        );
        
        if (!mysqli_stmt_execute($stmt_detail)) {
            throw new Exception("Error inserting detail: " . mysqli_stmt_error($stmt_detail));
        }
    }
    
    mysqli_stmt_close($stmt_detail);
    
    // 3. Update status meja menjadi terisi (opsional - bisa dihapus jika tidak perlu)
    // Karena customer sudah duduk, status meja mungkin sudah terisi
    // Uncomment jika ingin auto-update status meja saat order
    /*
    $stmt_meja = mysqli_prepare($koneksi, "UPDATE meja SET status='terisi' WHERE id=?");
    mysqli_stmt_bind_param($stmt_meja, "i", $meja_id);
    mysqli_stmt_execute($stmt_meja);
    mysqli_stmt_close($stmt_meja);
    */
    
    // 4. Jika bukan cash, generate payment token/info
    $snap_token = null;
    if ($payment_method !== 'cash') {
        // Simulasi token pembayaran (dalam produksi, gunakan API Midtrans/Payment Gateway)
        $snap_token = 'TOKEN-' . strtoupper(substr(md5($order_id . time()), 0, 16));
        
        // Update snap_token di database
        $stmt_token = mysqli_prepare($koneksi, "UPDATE transaksi SET snap_token=? WHERE id=?");
        mysqli_stmt_bind_param($stmt_token, "si", $snap_token, $transaksi_id);
        mysqli_stmt_execute($stmt_token);
        mysqli_stmt_close($stmt_token);
    }
    
    // Commit transaction
    mysqli_commit($koneksi);
    
    // Simpan info transaksi untuk halaman success
    $_SESSION['last_transaction'] = [
        'id' => $transaksi_id,
        'order_id' => $order_id,
        'total' => $total,
        'items_count' => array_sum(array_column($_SESSION['cart'], 'qty')),
        'date' => date('Y-m-d H:i:s'),
        'payment_method' => $payment_method,
        'payment_status' => $payment_status,
        'snap_token' => $snap_token,
        'meja_id' => $meja_id
    ];
    
    // Kosongkan keranjang
    unset($_SESSION['cart']);
    unset($_SESSION['cart_total_items']);
    unset($_SESSION['cart_total_price']);
    
    // Redirect ke halaman sukses
    $_SESSION['toast_message'] = '✅ Transaksi berhasil dibuat!';
    $_SESSION['toast_type'] = 'success';
    
    // Redirect berdasarkan metode pembayaran
    if ($payment_method === 'cash') {
        header("Location: success.php");
    } else {
        header("Location: payment_gateway.php");
    }
    exit;
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($koneksi);
    
    error_log("Payment Error: " . $e->getMessage());
    
    $_SESSION['toast_message'] = '❌ Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage();
    $_SESSION['toast_type'] = 'error';
    header("Location: checkout.php");
    exit;
}
?>