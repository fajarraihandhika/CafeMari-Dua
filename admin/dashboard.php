<?php

include "../Koneksi2.php";
include "auth_admin.php";
/* ================= STATISTIK ================= */
$total_reservasi = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) total FROM reservasi")
)['total'];

$hari_ini = date('Y-m-d');

$stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total FROM reservasi WHERE DATE(tanggal_reservasi) = ?");
mysqli_stmt_bind_param($stmt, "s", $hari_ini);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $total);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$reservasi_hari_ini = (int)$total;

$total_meja = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) total FROM meja")
)['total'];

$total_paket = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) total FROM paket_reservasi")
)['total'];

/* ================= GRAFIK BULANAN ================= */
$tahun_sekarang = date('Y');
$q_bulan = mysqli_query($koneksi, "
    SELECT MONTH(tanggal_reservasi) bulan, COUNT(*) total 
    FROM reservasi 
    WHERE YEAR(tanggal_reservasi) = '$tahun_sekarang'
    GROUP BY bulan
");

$data_bulan = array_fill(1, 12, 0);
while ($r = mysqli_fetch_assoc($q_bulan)) {
    $data_bulan[(int)$r['bulan']] = $r['total'];
}

/* ================= GRAFIK TAHUNAN ================= */
$q_tahun = mysqli_query($koneksi, "
    SELECT YEAR(tanggal_reservasi) tahun, COUNT(*) total 
    FROM reservasi 
    GROUP BY tahun 
    ORDER BY tahun ASC
");

$data_tahun = [];
$label_tahun = [];
while ($r = mysqli_fetch_assoc($q_tahun)) {
    $label_tahun[] = $r['tahun'];
    $data_tahun[] = $r['total'];
}

/* ================= STATUS ================= */
$status = [];
$q_status = mysqli_query($koneksi, "
    SELECT status, COUNT(*) total 
    FROM reservasi 
    GROUP BY status
");
while ($r = mysqli_fetch_assoc($q_status)) {
    $status[strtolower($r['status'])] = $r['total'];
}

/* ================= WARNA STATUS (Updated for dark theme) ================= */
$warna_status = [];
foreach ($status as $key => $val) {
    if (in_array($key, ['konfirmasi', 'selesai'])) {
        $warna_status[] = '#4ade80';
    } elseif ($key == 'pending') {
        $warna_status[] = '#fbbf24';
    } elseif ($key == 'booked') {
        $warna_status[] = '#60a5fa';
    } else {
        $warna_status[] = '#f87171';
    }
}

$menu = $_GET['menu'] ?? 'home';

// Get admin name (you can replace this with session data)
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_initial = strtoupper(substr($admin_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | Café Mari-Dua</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Chart.js -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --bg-primary: #0f0f0f;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #252525;
            --gold-primary: #d4a574;
            --gold-secondary: #c9956c;
            --gold-light: #e8c4a0;
            --gold-dark: #a67c52;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-muted: #6b6b6b;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-hover: rgba(255, 255, 255, 0.06);
            --shadow-gold: rgba(212, 165, 116, 0.15);
            --shadow-dark: rgba(0, 0, 0, 0.4);
            --sidebar-width: 280px;
            --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== GLOBAL RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(212, 165, 116, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(212, 165, 116, 0.05) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(30, 30, 30, 0.5) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--gold-primary), var(--gold-dark));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--gold-light), var(--gold-primary));
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(20, 20, 20, 0.95) 0%, rgba(15, 15, 15, 0.98) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            padding: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: var(--transition-smooth);
        }

        .sidebar-header {
            padding: 28px 24px;
            border-bottom: 1px solid var(--glass-border);
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.1) 0%, transparent 100%);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 24px var(--shadow-gold);
            transition: var(--transition-smooth);
        }

        .sidebar-brand:hover .brand-logo {
            transform: rotate(-10deg) scale(1.05);
            box-shadow: 0 12px 32px var(--shadow-gold);
        }

        .brand-text h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            letter-spacing: 0.5px;
        }

        .brand-text span {
            font-size: 0.75rem;
            color: var(--gold-primary);
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* User Profile in Sidebar */
        .sidebar-profile {
            padding: 20px 24px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-avatar {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--bg-primary);
            box-shadow: 0 4px 16px var(--shadow-gold);
            position: relative;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #4ade80;
            border: 2px solid var(--bg-secondary);
            border-radius: 50%;
        }

        .user-info h6 {
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .user-info span {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Navigation Menu */
        .sidebar-nav {
            padding: 20px 16px;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin-bottom: 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            margin-bottom: 6px;
            border-radius: 12px;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, var(--gold-primary), var(--gold-dark));
            border-radius: 0 4px 4px 0;
            transition: var(--transition-smooth);
        }

        .nav-item:hover {
            background: var(--glass-hover);
            color: var(--text-primary);
            transform: translateX(4px);
        }

        .nav-item:hover::before {
            height: 60%;
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.15) 0%, rgba(212, 165, 116, 0.05) 100%);
            color: var(--gold-primary);
            border: 1px solid rgba(212, 165, 116, 0.2);
        }

        .nav-item.active::before {
            height: 70%;
            box-shadow: 0 0 20px var(--shadow-gold);
        }

        .nav-item i {
            width: 22px;
            height: 22px;
            stroke-width: 1.8;
            transition: var(--transition-smooth);
        }

        .nav-item:hover i {
            transform: scale(1.1);
        }

        .nav-item.active i {
            color: var(--gold-primary);
            filter: drop-shadow(0 0 8px var(--shadow-gold));
        }

        .nav-item.logout {
            color: #f87171;
            margin-top: 20px;
        }

        .nav-item.logout:hover {
            background: rgba(248, 113, 113, 0.1);
            color: #fca5a5;
        }

        .nav-item.logout::before {
            background: linear-gradient(180deg, #f87171, #dc2626);
        }

        /* ===== MOBILE TOGGLE ===== */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            border: none;
            border-radius: 14px;
            z-index: 1100;
            cursor: pointer;
            box-shadow: 0 8px 24px var(--shadow-gold);
            transition: var(--transition-smooth);
        }

        .mobile-toggle:hover {
            transform: scale(1.05);
        }

        .mobile-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--bg-primary);
            margin: 5px auto;
            border-radius: 2px;
            transition: var(--transition-smooth);
        }

        .mobile-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 32px 40px;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.2) 0%, rgba(169, 132, 93, 0.1) 50%, transparent 100%);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(ellipse, rgba(212, 165, 116, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-section::after {
            content: '☕';
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 120px;
            opacity: 0.1;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-greeting {
            font-size: 0.9rem;
            color: var(--gold-primary);
            font-weight: 500;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 12px;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--gold-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            max-width: 500px;
        }

        .hero-date {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            padding: 10px 18px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .hero-date i {
            color: var(--gold-primary);
            width: 18px;
            height: 18px;
        }

        /* ===== PAGE TITLE ===== */
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 28px;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-primary), transparent);
            border-radius: 2px;
        }

        .page-title i {
            color: var(--gold-primary);
            width: 28px;
            height: 28px;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 28px 24px;
            text-align: center;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold-primary), transparent);
            opacity: 0;
            transition: var(--transition-smooth);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(212, 165, 116, 0.1) 0%, transparent 60%);
            opacity: 0;
            transition: var(--transition-smooth);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(212, 165, 116, 0.3);
            box-shadow: 
                0 20px 40px var(--shadow-dark),
                0 0 40px var(--shadow-gold);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover::after {
            opacity: 1;
            top: -50%;
            left: -50%;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 18px;
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.2) 0%, rgba(212, 165, 116, 0.05) 100%);
            border: 1px solid rgba(212, 165, 116, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
            position: relative;
            z-index: 1;
        }

        .stat-icon i {
            color: var(--gold-primary);
            width: 28px;
            height: 28px;
            stroke-width: 1.8;
        }

        .stat-card:hover .stat-icon {
            transform: rotateY(180deg) scale(1.1);
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            box-shadow: 0 8px 24px var(--shadow-gold);
        }

        .stat-card:hover .stat-icon i {
            color: var(--bg-primary);
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1;
            position: relative;
            z-index: 1;
            transition: var(--transition-smooth);
        }

        .stat-card:hover .stat-value {
            color: var(--gold-primary);
            text-shadow: 0 0 30px var(--shadow-gold);
        }

        .stat-trend {
            margin-top: 14px;
            font-size: 0.8rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            position: relative;
            z-index: 1;
            opacity: 0;
            transform: translateY(10px);
            transition: var(--transition-smooth);
        }

        .stat-card:hover .stat-trend {
            opacity: 1;
            transform: translateY(0);
        }

        .stat-trend.up {
            color: #4ade80;
        }

        .stat-trend.down {
            color: #f87171;
        }

        .stat-trend i {
            width: 16px;
            height: 16px;
        }

        /* ===== CHART CARDS ===== */
        .chart-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 28px;
            transition: var(--transition-smooth);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
        }

        .chart-card:hover {
            border-color: rgba(212, 165, 116, 0.2);
            box-shadow: 
                0 25px 50px var(--shadow-dark),
                0 0 50px rgba(212, 165, 116, 0.05);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--glass-border);
        }

        .chart-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .chart-title i {
            color: var(--gold-primary);
            width: 22px;
            height: 22px;
        }

        .chart-filter {
            display: flex;
            gap: 8px;
        }

        .chart-filter button {
            padding: 8px 18px;
            border: 1px solid var(--glass-border);
            background: transparent;
            color: var(--text-secondary);
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition-smooth);
            font-family: 'Poppins', sans-serif;
        }

        .chart-filter button:hover {
            border-color: var(--gold-primary);
            color: var(--gold-primary);
            transform: translateY(-2px);
        }

        .chart-filter button.active {
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            border-color: transparent;
            color: var(--bg-primary);
            box-shadow: 0 4px 16px var(--shadow-gold);
        }

        /* ===== ACTIVITY CARD ===== */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 18px 0;
            border-bottom: 1px solid var(--glass-border);
            transition: var(--transition-smooth);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: var(--glass-hover);
            margin: 0 -16px;
            padding: 18px 16px;
            border-radius: 12px;
        }

        .activity-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.05));
            border: 1px solid rgba(212, 165, 116, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .activity-icon i {
            color: var(--gold-primary);
            width: 20px;
            height: 20px;
        }

        .activity-icon.success {
            background: linear-gradient(135deg, rgba(74, 222, 128, 0.2), rgba(74, 222, 128, 0.05));
            border-color: rgba(74, 222, 128, 0.2);
        }

        .activity-icon.success i {
            color: #4ade80;
        }

        .activity-icon.warning {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(251, 191, 36, 0.05));
            border-color: rgba(251, 191, 36, 0.2);
        }

        .activity-icon.warning i {
            color: #fbbf24;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .activity-desc {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .activity-time {
            color: var(--text-muted);
            font-size: 0.75rem;
            white-space: nowrap;
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .hero-section {
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card {
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .chart-card {
            animation: fadeInUp 0.6s ease-out backwards;
            animation-delay: 0.5s;
        }

        .nav-item {
            animation: slideInLeft 0.4s ease-out backwards;
        }

        .nav-item:nth-child(1) { animation-delay: 0.1s; }
        .nav-item:nth-child(2) { animation-delay: 0.15s; }
        .nav-item:nth-child(3) { animation-delay: 0.2s; }
        .nav-item:nth-child(4) { animation-delay: 0.25s; }
        .nav-item:nth-child(5) { animation-delay: 0.3s; }
        .nav-item:nth-child(6) { animation-delay: 0.35s; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .main-content {
                padding: 24px;
            }
            
            .hero-section::after {
                font-size: 80px;
                right: 30px;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-toggle {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            
            .hero-section::after {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 80px 16px 24px;
            }
            
            .hero-section {
                padding: 28px 24px;
            }
            
            .hero-title {
                font-size: 1.6rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .chart-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }
        }

        /* ===== OVERLAY ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            transition: var(--transition-smooth);
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
    </style>
</head>

<body>

<!-- Mobile Toggle Button -->
<button class="mobile-toggle" onclick="toggleSidebar()">
    <span></span>
    <span></span>
    <span></span>
</button>

<!-- Sidebar Overlay (for mobile) -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <a href="dashboard.php?menu=home" class="sidebar-brand">
            <div class="brand-logo">☕</div>
            <div class="brand-text">
                <h4>Café Mari-Dua</h4>
                <span>Admin Panel</span>
            </div>
        </a>
    </div>
    
    <div class="sidebar-profile">
        <div class="user-avatar"><?= $admin_initial ?></div>
        <div class="user-info">
            <h6><?= htmlspecialchars($admin_name) ?></h6>
            <span>Administrator</span>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Menu Utama</div>
            
            <a href="dashboard.php?menu=home" class="nav-item <?= ($menu == 'home') ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
            <a href="dashboard.php?menu=reservasi" class="nav-item <?= ($menu == 'reservasi') ? 'active' : '' ?>">
                <i data-lucide="calendar-check"></i>
                <span>Kelola Reservasi</span>
            </a>
            <a href="dashboard.php?menu=meja" class="nav-item <?= ($menu == 'meja') ? 'active' : '' ?>">
                <i data-lucide="armchair"></i>
                <span>Kelola Meja</span>
            </a>
            <a href="dashboard.php?menu=paket" class="nav-item <?= ($menu == 'paket') ? 'active' : '' ?>">
                <i data-lucide="package"></i>
                <span>Paket Reservasi</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Laporan</div>
            
            <a href="dashboard.php?menu=laporan" class="nav-item <?= ($menu == 'laporan') ? 'active' : '' ?>">
                <i data-lucide="bar-chart-3"></i>
                <span>Laporan</span>
            </a>
        </div>

        <div class="nav-section">
           
            
            <a href="dashboard.php?menu=laporant" class="nav-item <?= ($menu == 'laporant') ? 'active' : '' ?>">
                <i data-lucide="bar-chart-3"></i>
                <span>Laporan Transaksi</span>
            </a>
        </div>
        
        <div class="nav-section">
            <a href="../logout.php" class="nav-item logout">
                <i data-lucide="log-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <?php
    switch ($menu) {
        case 'meja':
            include "meja.php";
            break;
        case 'meja_edit':
            include "meja_edit.php";
            break;
        case 'reservasi':
            include "reservasi.php";
            break;
        case 'paket':
            include "paket.php";
            break;
            case 'paket_edit':
                include "paket_edit.php";
                break;
        case 'laporan':
            include "laporan.php";
            break;
            case 'laporant':
                include "laporan_transaksi.php";
                break;
        default:
    ?>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <p class="hero-greeting">
                <span>👋</span> Selamat Datang Kembali
            </p>
            <h1 class="hero-title">Dashboard Admin</h1>
            <p class="hero-subtitle">
                Pantau dan kelola reservasi café Anda dengan mudah. Semua data dalam satu tampilan.
            </p>
            <div class="hero-date">
                <i data-lucide="calendar"></i>
                <span><?= strftime('%A, %d %B %Y', strtotime($hari_ini)) ?></span>
            </div>
        </div>
    </section>
    
    <!-- STATISTIK CARDS -->
    <div class="row g-4 mb-4">
        <?php
        $box = [
            ['Total Reservasi', $total_reservasi, 'clipboard-list', '+12', true],
            ['Reservasi Hari Ini', $reservasi_hari_ini, 'calendar-days', '+5', true],
            ['Total Meja', $total_meja, 'armchair', '0', false],
            ['Total Paket', $total_paket, 'package', '+8', true],
        ];
        
        foreach ($box as $b) {
        ?>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i data-lucide="<?= $b[2] ?>"></i>
                </div>
                <p class="stat-label"><?= $b[0] ?></p>
                <h2 class="stat-value"><?= $b[1] ?></h2>
                <div class="stat-trend <?= ($b[4]) ? 'up' : '' ?> <?= (strpos($b[3], '-') !== false) ? 'down' : '' ?>">
                    <?php if($b[4] && $b[3] != '0'): ?>
                    <i data-lucide="trending-up"></i>
                    <?php elseif(strpos($b[3], '-') !== false): ?>
                    <i data-lucide="trending-down"></i>
                    <?php else: ?>
                    <i data-lucide="minus"></i>
                    <?php endif; ?>
                    <span><?= $b[3] ?>% dari bulan lalu</span>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <!-- CHARTS -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i data-lucide="trending-up"></i>
                        Tren Reservasi
                    </h6>
                    <div class="chart-filter">
                        <button class="active" onclick="switchChart('bulan')">Bulanan</button>
                        <button onclick="switchChart('tahun')">Tahunan</button>
                    </div>
                </div>
                <canvas id="chartBulan"></canvas>
                <canvas id="chartTahun" style="display:none;"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i data-lucide="pie-chart"></i>
                        Status Reservasi
                    </h6>
                </div>
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>
    
    <!-- RECENT ACTIVITY -->
    <div class="row g-4">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i data-lucide="activity"></i>
                        Aktivitas Terbaru
                    </h6>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i data-lucide="plus-circle"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">Reservasi Baru</p>
                        <p class="activity-desc">Meja 5 • 19:00 • Atas nama: Budi Santoso</p>
                    </div>
                    <span class="activity-time">5 menit lalu</span>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon success">
                        <i data-lucide="check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">Pembayaran Dikonfirmasi</p>
                        <p class="activity-desc">Reservasi #12345 • Rp 500.000</p>
                    </div>
                    <span class="activity-time">15 menit lalu</span>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon warning">
                        <i data-lucide="clock"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-title">Reservasi Selesai</p>
                        <p class="activity-desc">Meja 3 • Atas nama: Siti Nurhaliza</p>
                    </div>
                    <span class="activity-time">1 jam lalu</span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('active');
            toggle.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        // Chart.js Configuration with Dark Theme
        Chart.defaults.color = '#a0a0a0';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.05)';
        
        const goldGradient = (ctx) => {
            const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(212, 165, 116, 0.3)');
            gradient.addColorStop(1, 'rgba(212, 165, 116, 0.02)');
            return gradient;
        };
        
        // Data untuk charts
        const dataBulan = <?= json_encode(array_values($data_bulan)) ?>;
        const dataTahun = <?= json_encode($data_tahun) ?>;
        const labelTahun = <?= json_encode($label_tahun) ?>;
        
        // Chart Bulan
        const chartBulanCtx = document.getElementById('chartBulan');
        const chartBulan = new Chart(chartBulanCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Reservasi Tahun <?= $tahun_sekarang ?>',
                    data: dataBulan,
                    borderColor: '#d4a574',
                    backgroundColor: goldGradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#d4a574',
                    pointBorderColor: '#0f0f0f',
                    pointBorderWidth: 3,
                    pointHoverBackgroundColor: '#e8c4a0',
                    pointHoverBorderColor: '#0f0f0f',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Poppins',
                                size: 12,
                                weight: '500'
                            },
                            color: '#a0a0a0',
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 26, 26, 0.95)',
                        titleColor: '#d4a574',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(212, 165, 116, 0.3)',
                        borderWidth: 1,
                        padding: 14,
                        titleFont: {
                            family: 'Playfair Display',
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            family: 'Poppins',
                            size: 13
                        },
                        cornerRadius: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: 'Poppins',
                                size: 11,
                                weight: '400'
                            },
                            color: '#6b6b6b',
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Poppins',
                                size: 11,
                                weight: '400'
                            },
                            color: '#6b6b6b',
                            padding: 10
                        }
                    }
                }
            }
        });
        
        // Chart Tahun
        const chartTahun = new Chart(document.getElementById('chartTahun'), {
            type: 'bar',
            data: {
                labels: labelTahun,
                datasets: [{
                    label: 'Total Reservasi per Tahun',
                    data: dataTahun,
                    backgroundColor: 'rgba(212, 165, 116, 0.7)',
                    borderColor: '#d4a574',
                    borderWidth: 2,
                    borderRadius: 10,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(232, 196, 160, 0.9)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Poppins',
                                size: 12,
                                weight: '500'
                            },
                            color: '#a0a0a0',
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'rectRounded'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 26, 26, 0.95)',
                        titleColor: '#d4a574',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(212, 165, 116, 0.3)',
                        borderWidth: 1,
                        padding: 14,
                        titleFont: {
                            family: 'Playfair Display',
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            family: 'Poppins',
                            size: 13
                        },
                        cornerRadius: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: 'Poppins',
                                size: 11,
                                weight: '400'
                            },
                            color: '#6b6b6b',
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Poppins',
                                size: 11,
                                weight: '400'
                            },
                            color: '#6b6b6b',
                            padding: 10
                        }
                    }
                }
            }
        });
        
        // Chart Status (Doughnut)
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_map('ucfirst', array_keys($status))) ?>,
                datasets: [{
                    data: <?= json_encode(array_values($status)) ?>,
                    backgroundColor: <?= json_encode($warna_status) ?>,
                    borderWidth: 0,
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#0f0f0f',
                    hoverOffset: 15,
                    spacing: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                family: 'Poppins',
                                size: 12,
                                weight: '500'
                            },
                            color: '#a0a0a0',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 26, 26, 0.95)',
                        titleColor: '#d4a574',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(212, 165, 116, 0.3)',
                        borderWidth: 1,
                        padding: 14,
                        titleFont: {
                            family: 'Playfair Display',
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            family: 'Poppins',
                            size: 13
                        },
                        cornerRadius: 10,
                        displayColors: true
                    }
                }
            }
        });
        
        // Function to switch between charts
        function switchChart(type) {
            const buttons = document.querySelectorAll('.chart-filter button');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            if (type === 'bulan') {
                document.getElementById('chartBulan').style.display = 'block';
                document.getElementById('chartTahun').style.display = 'none';
                buttons[0].classList.add('active');
            } else {
                document.getElementById('chartBulan').style.display = 'none';
                document.getElementById('chartTahun').style.display = 'block';
                buttons[1].classList.add('active');
            }
        }
        
        // Re-initialize icons after dynamic content
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    
    <?php
        } // end switch default
    ?>
</div>

</body>
</html>
