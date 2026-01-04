<?php
session_start();

// Hitung total items di cart
$cart_total_items = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
$is_cart_empty = !isset($_SESSION['cart']) || empty($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | Coffee Shop</title>
    
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
            --card-bg: rgba(255, 255, 255, 0.05);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 50%, #1a1a1a 100%);
            color: #fff;
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Effects */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(212, 165, 116, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* ===== HEADER ===== */
        .cart-header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cart-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
        }

        .cart-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(212, 165, 116, 0.2);
            border: 1px solid var(--gold-primary);
            border-radius: 50px;
            color: var(--gold-primary);
            font-weight: 600;
            margin-top: 10px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .empty-cart-icon {
            font-size: 6rem;
            color: var(--gold-primary);
            opacity: 0.3;
            margin-bottom: 30px;
        }

        .empty-cart h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .empty-cart p {
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 30px;
        }

        .btn-back-menu {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 35px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            color: #0d0d0d;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-back-menu:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
        }

        /* ===== CART ITEMS ===== */
        .cart-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 25px;
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            align-items: center;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            border-color: rgba(212, 165, 116, 0.4);
            transform: translateX(5px);
        }

        .item-image {
            width: 100px;
            height: 100px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--gold-primary);
        }

        .item-category {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(212, 165, 116, 0.2);
            border-radius: 50px;
            font-size: 0.75rem;
            color: var(--gold-primary);
            margin-bottom: 10px;
        }

        .item-price {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .item-price strong {
            color: #fff;
            font-weight: 600;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: flex-end;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 5px;
        }

        .qty-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: transparent;
            color: var(--gold-primary);
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: var(--gold-primary);
            color: #0d0d0d;
        }

        .qty-input {
            width: 60px;
            text-align: center;
            background: transparent;
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .qty-input:focus {
            outline: none;
        }

        /* Chrome, Safari, Edge, Opera */
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .qty-input[type=number] {
            -moz-appearance: textfield;
        }

        .btn-remove {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid rgba(255, 100, 100, 0.3);
            border-radius: 50px;
            color: #ff6464;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-remove:hover {
            background: rgba(255, 100, 100, 0.2);
            border-color: #ff6464;
        }

        .item-subtotal {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gold-primary);
        }

        /* ===== SUMMARY SIDEBAR ===== */
        .cart-summary {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .summary-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
        }

        .summary-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--gold-primary);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-row:last-of-type {
            border-bottom: none;
            padding-top: 20px;
            margin-top: 10px;
            border-top: 2px solid var(--gold-primary);
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        .summary-value {
            font-weight: 600;
            color: #fff;
        }

        .summary-total {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold-primary);
        }

        .btn-checkout {
            width: 100%;
            padding: 18px;
            margin-top: 25px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            border: none;
            border-radius: 15px;
            color: #0d0d0d;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 165, 116, 0.4);
        }

        .continue-shopping {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            padding: 12px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .continue-shopping:hover {
            border-color: var(--gold-primary);
            color: var(--gold-primary);
            background: rgba(212, 165, 116, 0.1);
        }

        /* ===== TOAST NOTIFICATION ===== */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 15px 25px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .toast-success {
            background: rgba(76, 175, 80, 0.95);
            color: #fff;
        }

        .toast-error {
            background: rgba(244, 67, 54, 0.95);
            color: #fff;
        }

        .toast-warning {
            background: rgba(255, 152, 0, 0.95);
            color: #fff;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .cart-content {
                grid-template-columns: 1fr;
            }

            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .cart-header h1 {
                font-size: 2rem;
            }

            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }

            .item-image {
                width: 80px;
                height: 80px;
            }

            .item-actions {
                grid-column: 2;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            .item-name {
                font-size: 1.1rem;
            }

            .summary-card {
                padding: 25px;
            }
        }

        @media (max-width: 480px) {
            .cart-header h1 {
                font-size: 1.5rem;
            }

            .empty-cart-icon {
                font-size: 4rem;
            }

            .cart-item {
                padding: 20px;
            }

            .item-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .summary-title {
                font-size: 1.2rem;
            }

            .summary-total {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        
        <!-- Header -->
        <div class="cart-header">
            <h1><i class="bi bi-cart3"></i> Keranjang Belanja</h1>
            <p>Periksa pesanan Anda sebelum checkout</p>
            <?php if (!$is_cart_empty): ?>
                <span class="cart-badge">
                    <i class="bi bi-bag-check-fill"></i>
                    <?= $cart_total_items ?> Item
                </span>
            <?php endif; ?>
        </div>

        <?php if ($is_cart_empty): ?>
            
            <!-- Empty State -->
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="bi bi-cart-x"></i>
                </div>
                <h2>Keranjang Anda Kosong</h2>
                <p>Sepertinya Anda belum menambahkan item apapun ke keranjang</p>
                <a href="pelanggan/dashboard.php?menu=menu" class="btn-back-menu">
                    <i class="bi bi-arrow-left"></i>
                    Lihat Menu
                </a>
            </div>

        <?php else: ?>
            
            <!-- Cart Content -->
            <div class="cart-content">
                
                <!-- Cart Items -->
                <div class="cart-items">
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item):
                        $subtotal = $item['harga'] * $item['qty'];
                        $total += $subtotal;
                    ?>
                    <div class="cart-item" data-item-id="<?= $id ?>">
                        
                        <!-- Item Image -->
                        <div class="item-image">
                            <img src="<?= htmlspecialchars($item['image'] ?? 'uploads/default.jpg') ?>" 
                                 alt="<?= htmlspecialchars($item['nama']) ?>">
                        </div>

                        <!-- Item Details -->
                        <div class="item-details">
                            <div class="item-name"><?= htmlspecialchars($item['nama']) ?></div>
                            <span class="item-category">
                                <?= htmlspecialchars($item['category'] ?? 'Menu') ?>
                            </span>
                            <div class="item-price">
                                <strong>Rp <?= number_format($item['harga'], 0, ',', '.') ?></strong> / item
                            </div>
                        </div>

                        <!-- Item Actions -->
                        <div class="item-actions">
                            <!-- Quantity Controls -->
                            <form action="proses/update_cart.php" method="POST" class="qty-form">
                                <input type="hidden" name="menu_id" value="<?= $id ?>">
                                <div class="qty-controls">
                                    <button type="button" class="qty-btn qty-minus" data-action="minus">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" 
                                           name="qty" 
                                           class="qty-input" 
                                           value="<?= $item['qty'] ?>" 
                                           min="1" 
                                           max="99"
                                           readonly>
                                    <button type="button" class="qty-btn qty-plus" data-action="plus">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </form>

                            <!-- Subtotal -->
                            <div class="item-subtotal">
                                Rp <?= number_format($subtotal, 0, ',', '.') ?>
                            </div>

                            <!-- Remove Button -->
                            <button class="btn-remove" 
                                    onclick="removeItem('<?= $id ?>', '<?= htmlspecialchars($item['nama']) ?>')">
                                <i class="bi bi-trash3"></i>
                                Hapus
                            </button>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Summary Sidebar -->
                <div class="cart-summary">
                    <div class="summary-card">
                        <h3 class="summary-title">Ringkasan Pesanan</h3>

                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Total Item</span>
                            <span class="summary-value"><?= $cart_total_items ?> item</span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Total Pembayaran</span>
                            <span class="summary-total">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>

                        <form action="proses/checkout.php" method="POST">
                            <input type="hidden" name="total" value="<?= $total ?>">
                            <button type="submit" class="btn-checkout">
                                <i class="bi bi-credit-card"></i>
                                Checkout Sekarang
                            </button>
                        </form>

                        <a href="pelanggan/dashboard.php?menu=menu" class="continue-shopping">
                            <i class="bi bi-arrow-left"></i>
                            Lanjut Belanja
                        </a>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </div>

    <script>
        // Toast Notification Function
        function showToast(message, type = 'success') {
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) existingToast.remove();
            
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Quantity Controls
        document.querySelectorAll('.qty-form').forEach(form => {
            const minusBtn = form.querySelector('.qty-minus');
            const plusBtn = form.querySelector('.qty-plus');
            const input = form.querySelector('.qty-input');
            
            minusBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    form.submit();
                }
            });
            
            plusBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                if (value < 99) {
                    input.value = value + 1;
                    form.submit();
                }
            });
        });

        // Remove Item Function
        function removeItem(itemId, itemName) {
            if (confirm(`Hapus "${itemName}" dari keranjang?`)) {
                window.location.href = `proses/update_cart.php?hapus=${itemId}`;
            }
        }

        // Display Toast Message from Session
        <?php if (isset($_SESSION['toast_message'])): ?>
            showToast('<?= $_SESSION['toast_message'] ?>', '<?= $_SESSION['toast_type'] ?? 'success' ?>');
            <?php 
                unset($_SESSION['toast_message']); 
                unset($_SESSION['toast_type']); 
            ?>
        <?php endif; ?>
    </script>

</body>
</html>