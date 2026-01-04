<?php
/**
 * ====================================
 * CHECKOUT - PAYMENT METHOD SELECTION
 * ====================================
 * Halaman pemilihan metode pembayaran
 */

session_start();
include "../Koneksi2.php";
// DEBUG: Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// DEBUG: Cek tabel meja
$test_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM meja");
if (!$test_query) {
    die("Error query meja: " . mysqli_error($koneksi));
}
$test = mysqli_fetch_assoc($test_query);
echo "<!-- DEBUG: Total meja = " . $test['total'] . " -->";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['toast_message'] = '❌ Silakan login terlebih dahulu';
    $_SESSION['toast_type'] = 'error';
    header("Location: ../login.php");
    exit;
}

/**
 * ====================================
 * VALIDASI CART
 * ====================================
 */
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['toast_message'] = '❌ Keranjang belanja kosong!';
    $_SESSION['toast_type'] = 'error';
    header("Location: ../pelanggan/dashboard.php?menu=menu");
    exit;
}

/**
 * ====================================
 * HITUNG TOTAL
 * ====================================
 */
$total = 0;
$total_items = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal = $item['harga'] * $item['qty'];
    $total += $subtotal;
    $total_items += $item['qty'];
}

// Pajak dan biaya layanan (opsional)
$tax_rate = 0.10; // 10%
$service_rate = 0.05; // 5%
$tax = $total * $tax_rate;
$service_charge = $total * $service_rate;
$grand_total = $total + $tax + $service_charge;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Pilih Pembayaran | Coffee Shop</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold-primary: #d4a574;
            --gold-light: #f0d9b5;
            --dark-bg: #0d0d0d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 50%, #1a1a1a 100%);
            color: #fff;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .checkout-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .checkout-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .checkout-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.1rem;
        }

        /* Main Layout */
        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        /* Payment Methods Section */
        .payment-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--gold-primary);
        }

        /* Payment Method Cards */
        .payment-methods {
            display: grid;
            gap: 15px;
        }

        .payment-method {
            background: rgba(255, 255, 255, 0.03);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .payment-method:hover {
            border-color: var(--gold-primary);
            background: rgba(212, 165, 116, 0.1);
        }

        .payment-method.active {
            border-color: var(--gold-primary);
            background: rgba(212, 165, 116, 0.15);
        }

        .payment-method input[type="radio"] {
            width: 20px;
            height: 20px;
            accent-color: var(--gold-primary);
        }

        .payment-icon {
            width: 50px;
            height: 50px;
            background: rgba(212, 165, 116, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--gold-primary);
        }

        .payment-info {
            flex: 1;
        }

        .payment-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .payment-desc {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Order Summary */
        .order-summary {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.6);
        }

        .summary-value {
            font-weight: 600;
        }

        .summary-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--gold-primary);
        }

        .summary-total .summary-label {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
        }

        .summary-total .summary-value {
            font-size: 1.8rem;
            font-family: 'Playfair Display', serif;
            color: var(--gold-primary);
        }

        /* Table Number */
        .table-selection {
            margin-top: 20px;
        }

        .table-selection label {
            display: block;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        .table-selection select {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .table-selection select option {
            background: #1a1a1a;
            color: #fff;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            color: #0d0d0d;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-secondary:hover {
            border-color: var(--gold-primary);
            color: var(--gold-primary);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }

        @media (max-width: 640px) {
            .checkout-header h1 {
                font-size: 2rem;
            }

            .payment-section,
            .order-summary {
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Header -->
        <div class="checkout-header">
            <h1>Checkout Pembayaran</h1>
            <p>Pilih metode pembayaran yang Anda inginkan</p>
        </div>

        <!-- Main Layout -->
        <div class="checkout-layout">
            
            <!-- Payment Methods -->
            <div class="payment-section">
                <h2 class="section-title">
                    <i class="bi bi-credit-card"></i>
                    Metode Pembayaran
                </h2>

                <form id="checkoutForm" method="POST" action="process_payment.php">
                    <input type="hidden" name="total" value="<?= $grand_total ?>">
                    
                    <div class="payment-methods">
                        
                        <!-- Cash -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="cash" required>
                            <div class="payment-icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="payment-info">
                                <div class="payment-name">Cash / Tunai</div>
                                <div class="payment-desc">Bayar langsung di kasir</div>
                            </div>
                        </label>

                        <!-- Transfer Bank -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="bank_transfer" required>
                            <div class="payment-icon">
                                <i class="bi bi-bank"></i>
                            </div>
                            <div class="payment-info">
                                <div class="payment-name">Transfer Bank</div>
                                <div class="payment-desc">BCA, Mandiri, BNI, BRI</div>
                            </div>
                        </label>

                        <!-- E-Wallet -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="e_wallet" required>
                            <div class="payment-icon">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div class="payment-info">
                                <div class="payment-name">E-Wallet</div>
                                <div class="payment-desc">GoPay, OVO, Dana, ShopeePay</div>
                            </div>
                        </label>

                        <!-- QRIS -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="qris" required>
                            <div class="payment-icon">
                                <i class="bi bi-qr-code"></i>
                            </div>
                            <div class="payment-info">
                                <div class="payment-name">QRIS</div>
                                <div class="payment-desc">Scan QR untuk bayar</div>
                            </div>
                        </label>

                        <!-- Debit/Credit Card -->
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="card" required>
                            <div class="payment-icon">
                                <i class="bi bi-credit-card-2-front"></i>
                            </div>
                            <div class="payment-info">
                                <div class="payment-name">Kartu Debit/Kredit</div>
                                <div class="payment-desc">Visa, Mastercard, JCB</div>
                            </div>
                        </label>

                    </div>

                    <!-- Table Selection -->
                    <div class="table-selection">
                        <label for="meja_id">
                            <i class="bi bi-table"></i> Nomor Meja Anda <span style="color: var(--gold-primary);">*</span>
                        </label>
                        <select name="meja_id" id="meja_id" required>
    <option value="">-- Pilih Nomor Meja Anda --</option>
    <?php
    // DEBUG
    echo "<!-- DEBUG: Start query meja -->";
    
    $meja_query = mysqli_query($koneksi, "SELECT * FROM meja");
    
    if (!$meja_query) {
        echo "<!-- ERROR: " . mysqli_error($koneksi) . " -->";
        echo "<option disabled>Error loading tables</option>";
    } else {
        $count = mysqli_num_rows($meja_query);
        echo "<!-- DEBUG: Found $count ms -->";
        
        if ($count == 0) {
            echo "<option disabled>Tidak ada data meja</option>";
        } else {
            while ($meja = mysqli_fetch_assoc($meja_query)) {
                echo "<!-- DEBUG: Meja {$meja['nomor_meja']} -->";
                echo "<option value='{$meja['meja_id']}'> Meja {$meja['nomor_meja']}</option>";
            }
        }
    }
    ?>
</select>
                        <small style="color: rgba(255,255,255,0.5); font-size: 0.85rem; margin-top: 8px; display: block;">
                            💡 Pilih nomor meja tempat Anda duduk saat ini
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="button-group">
                        <a href="../cart.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="bi bi-check-circle"></i>
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>

            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="section-title">
                    <i class="bi bi-receipt"></i>
                    Ringkasan Pesanan
                </h2>

                <div class="summary-item">
                    <span class="summary-label">Total Item</span>
                    <span class="summary-value"><?= $total_items ?> item</span>
                </div>

                <div class="summary-item">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>

                <div class="summary-item">
                    <span class="summary-label">Pajak (10%)</span>
                    <span class="summary-value">Rp <?= number_format($tax, 0, ',', '.') ?></span>
                </div>

                <div class="summary-item">
                    <span class="summary-label">Biaya Layanan (5%)</span>
                    <span class="summary-value">Rp <?= number_format($service_charge, 0, ',', '.') ?></span>
                </div>

                <div class="summary-item summary-total">
                    <span class="summary-label">Total Pembayaran</span>
                    <span class="summary-value">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                </div>

                <!-- Items List -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <h3 style="font-size: 1rem; margin-bottom: 15px; color: rgba(255,255,255,0.8);">
                        <i class="bi bi-bag-check"></i> Item Pesanan
                    </h3>
                    <?php foreach ($_SESSION['cart'] as $menu_id => $item): ?>
                        <div style="display: flex; justify-content: space-between; padding: 10px 0; font-size: 0.9rem;">
                            <span style="color: rgba(255,255,255,0.7);">
                                <?= htmlspecialchars($item['nama']) ?> x<?= $item['qty'] ?>
                            </span>
                            <span style="color: #fff; font-weight: 500;">
                                Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    const mejaId = document.getElementById('meja_id').value;

    // DEBUG
    console.log('=== CHECKOUT DEBUG ===');
    console.log('Payment Method:', paymentMethod ? paymentMethod.value : 'TIDAK DIPILIH');
    console.log('Meja ID:', mejaId);
    console.log('Meja ID empty?', mejaId === '');
    console.log('Meja select element:', document.getElementById('meja_id'));

    if (!paymentMethod) {
        e.preventDefault();
        alert('⚠️ Silakan pilih metode pembayaran!');
        return false;
    }

    if (!mejaId || mejaId === '') {
        e.preventDefault();
        console.error('MEJA ID KOSONG!');
        alert('⚠️ Silakan pilih nomor meja!');
        return false;
    }

    // Show loading
    const btnSubmit = document.getElementById('btnSubmit');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
});
        // Highlight selected payment method
        document.querySelectorAll('.payment-method input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-method').forEach(method => {
                    method.classList.remove('active');
                });
                this.closest('.payment-method').classList.add('active');
            });
        });

        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            const mejaId = document.getElementById('meja_id').value;

            if (!paymentMethod) {
                e.preventDefault();
                alert('⚠️ Silakan pilih metode pembayaran!');
                return false;
            }

            if (!mejaId) {
                e.preventDefault();
                alert('⚠️ Silakan pilih nomor meja!');
                return false;
            }

            // Show loading
            const btnSubmit = document.getElementById('btnSubmit');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        });
    </script>

</body>
</html>