<?php
/**
 * ====================================
 * TRANSACTION SUCCESS PAGE - CAFE MARI-DUA
 * ====================================
 */

session_start();
include "../Koneksi2.php";

if (!isset($_SESSION['user_id'], $_SESSION['last_transaction'])) {
    header("Location: ../pelanggan/dashboard.php?menu=menu");
    exit;
}

$transaction    = $_SESSION['last_transaction'];
$transaksi_id   = (int) $transaction['id'];
$order_id       = htmlspecialchars($transaction['order_id'], ENT_QUOTES, 'UTF-8');
$total          = (float) $transaction['total'];
$items_count    = (int) $transaction['items_count'];
$payment_method = htmlspecialchars($transaction['payment_method'], ENT_QUOTES, 'UTF-8');
$payment_status = htmlspecialchars($transaction['payment_status'], ENT_QUOTES, 'UTF-8');

$payment_names = [
    'cash'          => 'Cash / Tunai',
    'bank_transfer' => 'Transfer Bank',
    'e_wallet'      => 'E-Wallet',
    'qris'          => 'QRIS',
    'card'          => 'Kartu Debit/Kredit'
];
$payment_name = $payment_names[$payment_method] ?? 'Tidak Diketahui';

$meja_info = '';
if (!empty($transaction['meja_id'])) {
    $meja_id = (int) $transaction['meja_id'];
    $stmt = mysqli_prepare($koneksi, "SELECT nomor_meja FROM meja WHERE meja_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $meja_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($meja = mysqli_fetch_assoc($result)) {
        $meja_info = 'Meja ' . htmlspecialchars($meja['nomor_meja'], ENT_QUOTES, 'UTF-8');
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Berhasil | Cafe Mari-Dua</title>
    
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

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #f0d9b5;
            position: relative;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        /* ═══════════════════════════════════
           ANIMATED BACKGROUND
           ═══════════════════════════════════ */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(212, 165, 116, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(212, 165, 116, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(184, 151, 90, 0.05) 0%, transparent 60%);
            animation: bgPulse 8s ease-in-out infinite;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes bgPulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Floating particles */
        .particle {
            position: fixed;
            width: 4px;
            height: 4px;
            background: rgba(212, 165, 116, 0.3);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .particle:nth-child(1) { top: 10%; left: 10%; animation: float 6s ease-in-out infinite; }
        .particle:nth-child(2) { top: 20%; left: 80%; animation: float 8s ease-in-out infinite 1s; }
        .particle:nth-child(3) { top: 60%; left: 15%; animation: float 7s ease-in-out infinite 2s; }
        .particle:nth-child(4) { top: 70%; left: 85%; animation: float 9s ease-in-out infinite 0.5s; }
        .particle:nth-child(5) { top: 40%; left: 50%; animation: float 6s ease-in-out infinite 1.5s; }
        .particle:nth-child(6) { top: 85%; left: 30%; animation: float 8s ease-in-out infinite 2.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.3; }
            50% { transform: translateY(-20px) scale(1.5); opacity: 0.8; }
        }

        /* ═══════════════════════════════════
           MAIN CONTAINER
           ═══════════════════════════════════ */
        .success-container {
            max-width: 480px;
            width: 100%;
            background: rgba(26, 26, 26, 0.4);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(212, 165, 116, 0.2);
            border-radius: 20px;
            padding: 35px 30px;
            text-align: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
            animation: containerAppear 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .success-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d4a574, #f0d9b5, #d4a574);
            border-radius: 20px 20px 0 0;
        }

        @keyframes containerAppear {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(30px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* ═══════════════════════════════════
           SUCCESS ICON
           ═══════════════════════════════════ */
        .success-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, #d4a574 0%, #b8975a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
            animation: iconPulse 2s ease-in-out infinite;
            position: relative;
        }

        .success-icon::after {
            content: '';
            position: absolute;
            inset: -8px;
            border: 2px solid rgba(212, 165, 116, 0.3);
            border-radius: 50%;
            animation: ringExpand 2s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4); }
            50% { box-shadow: 0 15px 40px rgba(212, 165, 116, 0.6); }
        }

        @keyframes ringExpand {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.2; }
        }

        .success-icon i {
            font-size: 2.8rem;
            color: #1a1a1a;
            animation: checkAppear 0.5s ease 0.3s backwards;
        }

        @keyframes checkAppear {
            from {
                opacity: 0;
                transform: scale(0) rotate(-45deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        /* ═══════════════════════════════════
           TYPOGRAPHY
           ═══════════════════════════════════ */
        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #d4a574 0%, #f0d9b5 50%, #d4a574 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleSlide 0.6s ease 0.2s backwards;
        }

        @keyframes titleSlide {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success-divider {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #d4a574, transparent);
            margin: 0 auto 15px;
        }

        /* ═══════════════════════════════════
           PAYMENT BADGE
           ═══════════════════════════════════ */
        .payment-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .payment-badge.pending {
            background: rgba(255, 152, 0, 0.15);
            border: 1px solid rgba(255, 152, 0, 0.4);
            color: #ffb74d;
        }

        .payment-badge.paid {
            background: rgba(76, 175, 80, 0.15);
            border: 1px solid rgba(76, 175, 80, 0.4);
            color: #81c784;
        }

        .success-message {
            font-size: 0.95rem;
            color: rgba(240, 217, 181, 0.8);
            margin-bottom: 25px;
            line-height: 1.6;
            font-weight: 300;
        }

        /* ═══════════════════════════════════
           TRANSACTION DETAILS
           ═══════════════════════════════════ */
        .transaction-details {
            background: rgba(13, 13, 13, 0.4);
            border: 1px solid rgba(212, 165, 116, 0.15);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(212, 165, 116, 0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-top: 15px;
            margin-top: 10px;
            border-top: 2px solid rgba(212, 165, 116, 0.3);
        }

        .detail-label {
            color: rgba(240, 217, 181, 0.6);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-label i {
            color: #d4a574;
            font-size: 1rem;
        }

        .detail-value {
            font-weight: 600;
            color: #f0d9b5;
            font-size: 0.9rem;
        }

        .detail-total {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #d4a574, #f0d9b5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ═══════════════════════════════════
           BUTTONS
           ═══════════════════════════════════ */
        .button-group {
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d4a574 0%, #b8975a 100%);
            color: #1a1a1a;
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 165, 116, 0.6);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid rgba(212, 165, 116, 0.3);
            color: #d4a574;
        }

        .btn-secondary:hover {
            border-color: #d4a574;
            background: rgba(212, 165, 116, 0.1);
            transform: translateY(-3px);
        }

        /* ═══════════════════════════════════
           CONFETTI
           ═══════════════════════════════════ */
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            top: -20px;
            z-index: 100;
            pointer-events: none;
        }

        @keyframes confettiFall {
            0% {
                opacity: 1;
                transform: translateY(0) rotate(0deg) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateY(100vh) rotate(720deg) scale(0.5);
            }
        }

        /* ═══════════════════════════════════
           RESPONSIVE
           ═══════════════════════════════════ */
        @media (max-width: 520px) {
            .success-container {
                padding: 30px 20px;
            }

            .success-icon {
                width: 80px;
                height: 80px;
            }

            .success-icon i {
                font-size: 2.2rem;
            }

            .success-title {
                font-size: 1.6rem;
            }

            .button-group {
                flex-direction: column;
            }

            .detail-total {
                font-size: 1.3rem;
            }
        }

        @media (max-height: 700px) {
            .success-container {
                padding: 25px 20px;
            }
            
            .success-icon {
                width: 70px;
                height: 70px;
                margin-bottom: 20px;
            }
            
            .success-icon i {
                font-size: 2rem;
            }
            
            .success-title {
                font-size: 1.5rem;
            }
            
            .transaction-details {
                padding: 15px;
            }
            
            .detail-row {
                padding: 8px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Particles -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <main class="success-container">
        
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>

        <!-- Title -->
        <h1 class="success-title">Transaksi Berhasil!</h1>
        <div class="success-divider"></div>
        
        <!-- Payment Status Badge -->
        <div class="payment-badge <?= $payment_status === 'paid' ? 'paid' : 'pending' ?>">
            <?php if ($payment_status === 'waiting_payment'): ?>
                <i class="bi bi-clock me-1"></i> Menunggu Pembayaran
            <?php elseif ($payment_status === 'paid'): ?>
                <i class="bi bi-check-circle me-1"></i> Pembayaran Berhasil
            <?php else: ?>
                <i class="bi bi-receipt me-1"></i> Pembayaran di Kasir
            <?php endif; ?>
        </div>

        <!-- Success Message -->
        <p class="success-message">
            <?php if ($payment_method === 'cash'): ?>
                Terima kasih atas pesanan Anda.<br>
                Silakan selesaikan pembayaran di kasir.
            <?php else: ?>
                Pesanan Anda telah dikonfirmasi.<br>
                Mohon selesaikan pembayaran sesuai instruksi.
            <?php endif; ?>
        </p>

        <!-- Transaction Details -->
        <div class="transaction-details">
            <div class="detail-row">
                <span class="detail-label">
                    <i class="bi bi-receipt"></i> No. Pesanan
                </span>
                <span class="detail-value"><?= $order_id ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="bi bi-hash"></i> ID Transaksi
                </span>
                <span class="detail-value">#<?= str_pad($transaksi_id, 6, '0', STR_PAD_LEFT) ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="bi bi-calendar3"></i> Tanggal
                </span>
                <span class="detail-value"><?= date('d M Y, H:i') ?></span>
            </div>

            <?php if ($meja_info): ?>
            <div class="detail-row">
                <span class="detail-label">
                    <i class="bi bi-grid-3x3"></i> Nomor Meja
                </span>
                <span class="detail-value"><?= $meja_info ?></span>
            </div>
            <?php endif; ?>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="bi bi-credit-card"></i> Pembayaran
                </span>
                <span class="detail-value"><?= $payment_name ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="bi bi-bag"></i> Total Item
                </span>
                <span class="detail-value"><?= $items_count ?> item</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total Pembayaran</span>
                <span class="detail-value detail-total">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="button-group">
            <a href="../pelanggan/dashboard.php?menu=menu" class="btn btn-primary">
                <i class="bi bi-house-door"></i>
                Menu
            </a>
            <a href="../pelanggan/dashboard.php?menu=riwayat" class="btn btn-secondary">
                <i class="bi bi-clock-history"></i>
                Riwayat
            </a>
        </div>

    </main>

    <script>
        // Confetti Effect
        function createConfetti() {
            const colors = ['#d4a574', '#f0d9b5', '#b8975a', '#fff', '#ffd700'];
            const shapes = ['square', 'circle'];
            
            for (let i = 0; i < 60; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = shapes[Math.floor(Math.random() * shapes.length)] === 'circle' ? '50%' : '2px';
                confetti.style.width = (Math.random() * 8 + 5) + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.animation = `confettiFall ${2 + Math.random() * 3}s linear ${Math.random() * 2}s forwards`;
                
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 5000);
            }
        }

        // Run confetti on load
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 300);
        });
    </script>

</body>
</html>
