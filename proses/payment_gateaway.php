<?php
/**
 * ====================================
 * PAYMENT GATEWAY PAGE
 * ====================================
 * Halaman untuk menampilkan informasi pembayaran
 * dan simulasi payment gateway
 */

session_start();
include "../Koneksi2.php";

if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_transaction'])) {
    header("Location: ../pelanggan/dashboard.php?menu=menu");
    exit;
}

$transaction = $_SESSION['last_transaction'];
$payment_method = $transaction['payment_method'];
$order_id = $transaction['order_id'];
$total = $transaction['total'];

// Map payment method ke nama
$payment_names = [
    'bank_transfer' => 'Transfer Bank',
    'e_wallet' => 'E-Wallet',
    'qris' => 'QRIS',
    'card' => 'Kartu Debit/Kredit'
];

$payment_name = $payment_names[$payment_method] ?? 'Unknown';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - <?= $payment_name ?> | Coffee Shop</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            max-width: 600px;
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 40px;
            text-align: center;
            animation: fadeInScale 0.5s ease;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .payment-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #0d0d0d;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
        }

        .payment-info {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: rgba(255, 255, 255, 0.6);
        }

        .info-value {
            font-weight: 600;
            color: #fff;
        }

        .total-amount {
            font-size: 2rem;
            font-family: 'Playfair Display', serif;
            color: var(--gold-primary);
        }

        /* QR Code Display */
        .qr-code {
            margin: 30px 0;
            padding: 30px;
            background: #fff;
            border-radius: 20px;
            display: inline-block;
        }

        .qr-code img {
            width: 250px;
            height: 250px;
        }

        /* Bank Account Info */
        .bank-info {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .bank-item {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .bank-item:last-child {
            border-bottom: none;
        }

        .bank-name {
            font-weight: 600;
            color: var(--gold-primary);
            margin-bottom: 5px;
        }

        .account-number {
            font-size: 1.2rem;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .copy-btn {
            background: rgba(212, 165, 116, 0.2);
            border: none;
            color: var(--gold-primary);
            padding: 5px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: var(--gold-primary);
            color: #0d0d0d;
        }

        .account-name {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        /* Timer */
        .payment-timer {
            background: rgba(255, 87, 34, 0.2);
            border: 1px solid rgba(255, 87, 34, 0.5);
            border-radius: 15px;
            padding: 15px;
            margin: 20px 0;
        }

        .timer-text {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
        }

        .timer-value {
            font-size: 2rem;
            font-weight: 700;
            color: #ff5722;
            font-family: 'Courier New', monospace;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
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

        .btn-secondary {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-secondary:hover {
            border-color: var(--gold-primary);
            color: var(--gold-primary);
        }

        @media (max-width: 640px) {
            .payment-container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.6rem;
            }

            .qr-code img {
                width: 200px;
                height: 200px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="payment-container">
        
        <div class="payment-icon">
            <?php if ($payment_method === 'qris'): ?>
                <i class="bi bi-qr-code"></i>
            <?php elseif ($payment_method === 'bank_transfer'): ?>
                <i class="bi bi-bank"></i>
            <?php elseif ($payment_method === 'e_wallet'): ?>
                <i class="bi bi-wallet2"></i>
            <?php else: ?>
                <i class="bi bi-credit-card"></i>
            <?php endif; ?>
        </div>

        <h1>Menunggu Pembayaran</h1>
        <p class="subtitle">Selesaikan pembayaran Anda dengan <?= $payment_name ?></p>

        <!-- Payment Timer -->
        <div class="payment-timer">
            <div class="timer-text">Selesaikan pembayaran dalam</div>
            <div class="timer-value" id="countdown">23:59</div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">No. Pesanan</span>
                <span class="info-value"><?= $order_id ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Pembayaran</span>
                <span class="info-value"><?= $payment_name ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Pembayaran</span>
                <span class="total-amount">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Payment Method Specific Content -->
        <?php if ($payment_method === 'qris'): ?>
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= urlencode($order_id) ?>" 
                     alt="QR Code">
            </div>
            <p style="color: rgba(255,255,255,0.6); margin-top: 15px;">
                Scan QR code di atas menggunakan aplikasi pembayaran Anda
            </p>

        <?php elseif ($payment_method === 'bank_transfer'): ?>
            <div class="bank-info">
                <div class="bank-item">
                    <div class="bank-name">BCA</div>
                    <div class="account-number">
                        1234567890
                        <button class="copy-btn" onclick="copyToClipboard('1234567890')">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                    <div class="account-name">a.n. Coffee Shop Indonesia</div>
                </div>
                <div class="bank-item">
                    <div class="bank-name">Mandiri</div>
                    <div class="account-number">
                        9876543210
                        <button class="copy-btn" onclick="copyToClipboard('9876543210')">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                    <div class="account-name">a.n. Coffee Shop Indonesia</div>
                </div>
            </div>
            <p style="color: rgba(255,255,255,0.6); margin-top: 15px; font-size: 0.9rem;">
                Transfer ke salah satu rekening di atas dengan jumlah yang tertera
            </p>

        <?php elseif ($payment_method === 'e_wallet'): ?>
            <div class="bank-info">
                <div class="bank-item">
                    <div class="bank-name">GoPay</div>
                    <div class="account-number">
                        0812-3456-7890
                        <button class="copy-btn" onclick="copyToClipboard('081234567890')">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                </div>
                <div class="bank-item">
                    <div class="bank-name">OVO / Dana / ShopeePay</div>
                    <div class="account-number">
                        0856-7890-1234
                        <button class="copy-btn" onclick="copyToClipboard('085678901234')">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <p style="color: rgba(255,255,255,0.7); padding: 20px;">
                Anda akan diarahkan ke halaman pembayaran kartu.<br>
                Mohon tunggu...
            </p>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="button-group">
            <button onclick="confirmPayment()" class="btn btn-primary">
                <i class="bi bi-check-circle"></i>
                Saya Sudah Bayar
            </button>
            <a href="../pelanggan/dashboard.php?menu=menu" class="btn btn-secondary">
                <i class="bi bi-house-door"></i>
                Kembali
            </a>
        </div>

    </div>

    <script>
        // Countdown Timer (24 hours)
        let timeLeft = 24 * 60 * 60; // 24 hours in seconds

        function updateCountdown() {
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;

            document.getElementById('countdown').textContent = 
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft > 0) {
                timeLeft--;
            } else {
                clearInterval(countdownInterval);
                alert('⏰ Waktu pembayaran habis! Transaksi dibatalkan.');
                window.location.href = '../pelanggan/dashboard.php?menu=menu';
            }
        }

        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();

        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Nomor rekening berhasil disalin!');
            });
        }

        // Confirm payment
        function confirmPayment() {
            if (confirm('Apakah Anda yakin sudah menyelesaikan pembayaran?')) {
                window.location.href = 'success.php';
            }
        }
    </script>

</body>
</html>