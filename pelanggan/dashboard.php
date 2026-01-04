<?php
session_start();
include "../Koneksi2.php";

// ============================================
// CEK AUTENTIKASI USER
// ============================================
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

// ============================================
// QUERY MENU FAVORIT (6 TERMAHAL)
// ============================================
$q_menu = mysqli_query($koneksi, "
    SELECT name, price, category, image 
    FROM menu 
    ORDER BY price DESC
    LIMIT 6
");

// ============================================
// NAVIGASI MENU
// ============================================
$allowed_menus = ['home', 'reservasi', 'riwayat', 'menu'];
$menu = isset($_GET['menu']) && in_array($_GET['menu'], $allowed_menus) 
        ? $_GET['menu'] 
        : 'home';

// Get user initial for avatar
$user_initial = strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Café Mari-Dua</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary-dark: #1a1a1a;
            --secondary-dark: #2d2d2d;
            --accent-gold: #d4a574;
            --accent-gold-light: #e8c9a8;
            --accent-gold-dark: #b8956a;
            --text-light: #f5f5f5;
            --text-muted: #a0a0a0;
            --glass-bg: rgba(45, 45, 45, 0.8);
            --glass-border: rgba(212, 165, 116, 0.2);
            --sidebar-width: 280px;
            --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== BASE STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            min-height: 100vh;
            color: var(--text-light);
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(212, 165, 116, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(212, 165, 116, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(212, 165, 116, 0.03) 0%, transparent 30%);
            pointer-events: none;
            z-index: 0;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, rgba(26, 26, 26, 0.95) 0%, rgba(45, 45, 45, 0.95) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            position: fixed;
            left: 0;
            top: 0;
            padding: 30px 20px;
            z-index: 900;
            display: flex;
            flex-direction: column;
        }

        /* Logo Section */
        .sidebar-logo {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--glass-border);
            margin-bottom: 30px;
        }

        .sidebar-logo .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.3);
        }

        .sidebar-logo h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-light);
            margin: 0;
            letter-spacing: 1px;
        }

        .sidebar-logo span {
            font-size: 0.75rem;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border-radius: 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--accent-gold), transparent);
            opacity: 0.1;
            transition: var(--transition-smooth);
        }

        .nav-link:hover {
            color: var(--text-light);
            transform: translateX(5px);
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.05));
            color: var(--accent-gold);
            border: 1px solid var(--glass-border);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: var(--accent-gold);
            border-radius: 0 4px 4px 0;
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* User Profile in Sidebar */
        .sidebar-user {
            padding-top: 20px;
            border-top: 1px solid var(--glass-border);
            margin-top: auto;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: rgba(212, 165, 116, 0.1);
            border-radius: 14px;
            border: 1px solid var(--glass-border);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary-dark);
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-light);
        }

        .user-info span {
            font-size: 0.75rem;
            color: var(--accent-gold);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            color: #ef4444;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition-smooth);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.5);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
    margin-left: var(--sidebar-width);
    padding: 40px;
    position: relative;
    z-index: 1;  /* Tetap rendah, biar modal bisa di atas */
    min-height: 100vh;
    transform: none !important;  /* Penting untuk modal */
}

        /* ===== WELCOME HERO ===== */
        .welcome-hero {
            background: linear-gradient(135deg, rgba(45, 45, 45, 0.9), rgba(26, 26, 26, 0.9)),
                        url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200') center/cover;
            border-radius: 24px;
            padding: 50px 40px;
            margin-bottom: 40px;
            border: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
        }

        .welcome-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(212, 165, 116, 0.15), transparent 70%);
            pointer-events: none;
        }

        .welcome-hero .greeting {
            font-size: 1rem;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .welcome-hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--text-light), var(--accent-gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-hero p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 500px;
            line-height: 1.8;
        }

        .welcome-hero .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
            padding: 14px 30px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-dark));
            color: var(--primary-dark);
            text-decoration: none;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.3);
        }

        .welcome-hero .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 165, 116, 0.4);
        }

        /* ===== STATS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 50px;
        }

        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent-gold-dark));
            opacity: 0;
            transition: var(--transition-smooth);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent-gold);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.05));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            border: 1px solid var(--glass-border);
        }

        .stat-card h5 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-light);
        }

        .stat-card p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ===== SECTION HEADER ===== */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .section-header h3 {
            font-size: 1.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header h3::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, var(--glass-border), transparent);
            margin-left: 20px;
        }

        .view-all-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--accent-gold);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition-smooth);
        }

        .view-all-btn:hover {
            background: rgba(212, 165, 116, 0.1);
            border-color: var(--accent-gold);
            color: var(--accent-gold);
        }

        /* ===== MENU CARDS ===== */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .menu-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            overflow: hidden;
            transition: var(--transition-smooth);
            position: relative;
        }

        .menu-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--accent-gold);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3),
                        0 0 40px rgba(212, 165, 116, 0.1);
        }

        .menu-card-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .menu-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-smooth);
        }

        .menu-card:hover .menu-card-image img {
            transform: scale(1.1);
        }

        .menu-card-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, var(--secondary-dark), transparent);
            pointer-events: none;
        }

        .menu-category {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 8px 16px;
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
        }

        .menu-card-body {
            padding: 25px;
        }

        .menu-card-body h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-light);
        }

        .menu-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .menu-price span {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--accent-gold);
        }

        .menu-price small {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .menu-card-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.1));
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
        }

        .menu-card-btn:hover {
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-dark));
            color: var(--primary-dark);
            border-color: transparent;
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            padding: 40px 0 20px;
            margin-top: 60px;
            border-top: 1px solid var(--glass-border);
        }

        .footer p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .footer .heart {
            color: var(--accent-gold);
        }

        /* ===== ANIMATIONS ===== */
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

        .animate-fade-in {
            animation: fadeInUp 0.6s ease forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-gold-dark);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-gold);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .menu-grid,
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: var(--transition-smooth);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                transform: none !important;
            }

            .mobile-toggle {
                display: flex !important;
            }

            .welcome-hero h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .stats-grid,
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .welcome-hero {
                padding: 30px 25px;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            width: 50px;
            height: 50px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--accent-gold);
            font-size: 1.3rem;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

    </style>
</head>

<body>
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="logo-icon">☕</div>
            <h4>Mari-Dua</h4>
            <span>Coffee & Eatery</span>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="dashboard.php?menu=home" class="nav-link <?= ($menu == 'home') ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="dashboard.php?menu=reservasi" class="nav-link <?= ($menu == 'reservasi') ? 'active' : '' ?>">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>Reservasi Meja</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="dashboard.php?menu=menu" class="nav-link <?= ($menu == 'menu') ? 'active' : '' ?>">
                    <i class="bi bi-cup-hot-fill"></i>
                    <span>Menu Café</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="dashboard.php?menu=riwayat" class="nav-link <?= ($menu == 'riwayat') ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Reservasi</span>
                </a>
            </div>
        </nav>

        <!-- User Profile -->
        <div class="sidebar-user">
            <div class="user-card">
                <div class="user-avatar"><?= $user_initial; ?></div>
                <div class="user-info">
                    <h6><?= htmlspecialchars($_SESSION['nama']); ?></h6>
                    <span>Member</span>
                </div>
            </div>
            <a href="../logout.php" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
        <?php
        switch ($menu) {
            case 'reservasi':
                include "reservasi.php";
                break;

            case 'riwayat':
                include "riwayat_reservasi.php";
                break;

            case 'menu':
                include "../menu_user.php";
                break;

            default:
        ?>
                <!-- ===== WELCOME HERO ===== -->
                <section class="welcome-hero animate-fade-in">
                    <p class="greeting">✨ Selamat Datang Kembali</p>
                    <h1>Halo, <?= htmlspecialchars($_SESSION['nama']); ?>!</h1>
                    <p>
                        Nikmati pengalaman kopi terbaik di Café Mari-Dua. 
                        Pesan meja favorit Anda dan jelajahi menu premium kami.
                    </p>
                    <a href="dashboard.php?menu=reservasi" class="cta-btn">
                        <i class="bi bi-calendar-plus"></i>
                        Reservasi Sekarang
                    </a>
                </section>

                <!-- ===== STATS CARDS ===== -->
                <section class="stats-grid">
                    <div class="stat-card animate-fade-in delay-1">
                        <div class="stat-icon">🪑</div>
                        <h5>Reservasi Instan</h5>
                        <p>Booking meja hanya dalam 1 menit</p>
                    </div>

                    <div class="stat-card animate-fade-in delay-2">
                        <div class="stat-icon">☕</div>
                        <h5>Premium Coffee</h5>
                        <p>Biji kopi pilihan dari Nusantara</p>
                    </div>

                    <div class="stat-card animate-fade-in delay-3">
                        <div class="stat-icon">⭐</div>
                        <h5>Suasana Nyaman</h5>
                        <p>Cocok untuk kerja & bersantai</p>
                    </div>
                </section>

                <!-- ===== MENU SECTION ===== -->
                <section>
                    <div class="section-header">
                        <h3>
                            <i class="bi bi-fire" style="color: var(--accent-gold);"></i>
                            Menu Favorit
                        </h3>
                        <a href="dashboard.php?menu=menu" class="view-all-btn">
                            Lihat Semua
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="menu-grid">
                        <?php 
                        $delay = 3;
                        while ($m = mysqli_fetch_assoc($q_menu)) : 
                            $delay++;
                        ?>
                            <div class="menu-card animate-fade-in delay-<?= min($delay, 6); ?>">
                                <div class="menu-card-image">
                                    <img src="<?= htmlspecialchars($m['image']); ?>" 
                                         alt="<?= htmlspecialchars($m['name']); ?>">
                                    <span class="menu-category">
                                        <?= htmlspecialchars($m['category']); ?>
                                    </span>
                                </div>
                                <div class="menu-card-body">
                                    <h5><?= htmlspecialchars($m['name']); ?></h5>
                                    <div class="menu-price">
                                        <span>Rp <?= number_format($m['price'], 0, ',', '.'); ?></span>
                                    </div>
                                    <a href="dashboard.php?menu=menu" class="menu-card-btn">
                                        <i class="bi bi-eye"></i>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>

                <!-- ===== FOOTER ===== -->
                <footer class="footer">
                    <p>
                        ☕ © <?= date('Y'); ?> Café Mari-Dua · Crafted with 
                        <span class="heart">♥</span> in Indonesia
                    </p>
                </footer>
        <?php } ?>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile Sidebar Toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('active');
        }

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-fade-in').forEach(el => {
            el.style.opacity = '0';
            observer.observe(el);
        });
    </script>
</body>
</html>
