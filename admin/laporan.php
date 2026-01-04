<?php
include "../Koneksi2.php";

$q = mysqli_query($koneksi, "
    SELECT r.*, 
        p.nama_pelanggan,
        m.nomor_meja,
        pr.nama_paket
    FROM reservasi r
    JOIN pelanggan p ON r.pelanggan_id = p.pelanggan_id
    JOIN meja m ON r.meja_id = m.meja_id
    JOIN paket_reservasi pr ON r.paket_id = pr.paket_id
    ORDER BY r.waktu_pemesanan DESC
");

$total_reservasi = mysqli_num_rows($q);

// Hitung statistik
$total_pendapatan = 0;
$count_pending = 0;
$count_booked = 0;
$count_selesai = 0;
$count_canceled = 0;

mysqli_data_seek($q, 0);
while($stat = mysqli_fetch_assoc($q)) {
    $total_pendapatan += $stat['total_bayar'];
    if($stat['status'] == 'Pending') $count_pending++;
    if($stat['status'] == 'Booked') $count_booked++;
    if($stat['status'] == 'Selesai') $count_selesai++;
    if($stat['status'] == 'Canceled') $count_canceled++;
}
mysqli_data_seek($q, 0);
?>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- ===== LAPORAN RESERVASI SECTION ===== -->
<section class="laporan-section">
    
    <!-- Section Header -->
    <div class="section-header">
        <div class="header-title">
            <h2>
                <i class="bi bi-file-earmark-bar-graph"></i>
                Laporan Reservasi
            </h2>
            <p class="header-subtitle">Pantau dan analisis data reservasi café Anda</p>
        </div>
        
        <div class="header-actions">
            <button class="btn-action" onclick="window.print()">
                <i class="bi bi-printer"></i>
                <span>Print</span>
            </button>
            <button class="btn-action" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Export Excel</span>
            </button>
        </div>
    </div>
    
    <!-- Stats Overview -->
    <div class="stats-overview">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Reservasi</span>
                <span class="stat-value"><?= $total_reservasi ?></span>
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Pendapatan</span>
                <span class="stat-value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></span>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Pending</span>
                <span class="stat-value"><?= $count_pending ?></span>
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Selesai</span>
                <span class="stat-value"><?= $count_selesai ?></span>
            </div>
        </div>
    </div>
    
    <!-- Filter & Search Bar -->
    <div class="filter-bar">
        <div class="filter-tabs">
            <button class="filter-btn active" data-filter="all">
                <span>🍽️ Semua</span>
            </button>
            <button class="filter-btn" data-filter="Pending">
                <span>🕐 Pending</span>
            </button>
            <button class="filter-btn" data-filter="Booked">
                <span>📅 Booked</span>
            </button>
            <button class="filter-btn" data-filter="Selesai">
                <span>✅ Selesai</span>
            </button>
            <button class="filter-btn" data-filter="Canceled">
                <span>❌ Canceled</span>
            </button>
        </div>
        
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari pelanggan, meja, paket...">
        </div>
    </div>
    
    <!-- Data Counter -->
    <div class="data-counter">
        <span id="resultCount"><?= $total_reservasi ?></span> data ditemukan
    </div>

    <!-- Data Table Card -->
    <div class="table-card">
        
        <?php if($total_reservasi == 0): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h4>Belum Ada Data</h4>
                <p>Belum ada data reservasi yang tersedia</p>
            </div>
        <?php else: ?>
            
            <!-- Table Wrapper -->
            <div class="table-wrapper">
                <table class="data-table" id="reservasiTable">
                    <thead>
                        <tr>
                            <th class="th-no">No</th>
                            <th class="th-customer">
                                <i class="bi bi-person"></i>
                                Pelanggan
                            </th>
                            <th class="th-table">
                                <i class="bi bi-grid-1x2"></i>
                                Meja
                            </th>
                            <th class="th-package">
                                <i class="bi bi-box-seam"></i>
                                Paket
                            </th>
                            <th class="th-date">
                                <i class="bi bi-calendar3"></i>
                                Tanggal
                            </th>
                            <th class="th-time">
                                <i class="bi bi-clock"></i>
                                Jam
                            </th>
                            <th class="th-payment">
                                <i class="bi bi-cash-coin"></i>
                                Total Bayar
                            </th>
                            <th class="th-status">
                                <i class="bi bi-flag"></i>
                                Status
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php 
                        $no = 1; 
                        while($d = mysqli_fetch_assoc($q)): 
                            $status_class = match($d['status']){
                                'Pending' => 'pending',
                                'Booked' => 'booked',
                                'Selesai' => 'selesai',
                                'Canceled' => 'canceled',
                                default => 'default'
                            };
                            $status_icon = match($d['status']){
                                'Pending' => 'bi-hourglass-split',
                                'Booked' => 'bi-calendar-check',
                                'Selesai' => 'bi-check-circle-fill',
                                'Canceled' => 'bi-x-circle-fill',
                                default => 'bi-question-circle'
                            };
                        ?>
                        <tr class="table-row" data-status="<?= $d['status'] ?>" style="--delay: <?= $no ?>">
                            <td class="td-no"><?= $no++ ?></td>
                            
                            <td class="td-customer">
                                <div class="customer-cell">
                                    <div class="customer-avatar">
                                        <?= strtoupper(substr($d['nama_pelanggan'], 0, 1)) ?>
                                    </div>
                                    <span class="customer-name"><?= htmlspecialchars($d['nama_pelanggan']) ?></span>
                                </div>
                            </td>
                            
                            <td class="td-table">
                                <span class="table-badge">
                                    <i class="bi bi-grid-1x2"></i>
                                     <?= $d['nomor_meja'] ?>
                                </span>
                            </td>
                            
                            <td class="td-package">
                                <span class="package-name">
                                <i class="bi bi-box2-heart-fill"></i>
                                    <?= htmlspecialchars($d['nama_paket']) ?>
                                </span>
                            </td>
                            
                            <td class="td-date">
                                <div class="date-display">
                                    <span class="date-text">
                                    <i class="bi bi-calendar-date"></i>
                                        <?= date('d M Y', strtotime($d['tanggal_reservasi'])) ?>
                                    </span>
                                </div>
                            </td>
                            
                            <td class="td-time">
                                <span class="time-badge">
                                    <i class="bi bi-clock"></i>
                                    <?= date('H:i', strtotime($d['jam_reservasi'])) ?>
                                </span>
                            </td>
                            
                            <td class="td-payment">
                                <span class="payment-amount">
                                    Rp <?= number_format($d['total_bayar'], 0, ',', '.') ?>
                                </span>
                            </td>
                            
                            <td class="td-status">
                                <span class="status-badge <?= $status_class ?>">
                                    <i class="bi <?= $status_icon ?>"></i>
                                    <?= $d['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
        <?php endif; ?>
        
    </div>
    
</section>

<style>
/* ===== LAPORAN SECTION ===== */
.laporan-section {
    padding: 40px;
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== SECTION HEADER ===== */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.header-title h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 8px 0;
}

.header-title h2 i {
    color: #d4a574;
}

.header-subtitle {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.95rem;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.btn-action {
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    color: rgba(255, 255, 255, 0.8);
    font-family: 'Poppins', sans-serif;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: linear-gradient(135deg, #d4a574, #f0d9b5);
    border-color: transparent;
    color: #0d0d0d;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(212, 165, 116, 0.4);
}

/* ===== STATS OVERVIEW ===== */
.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: rgba(212, 165, 116, 0.4);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-card.primary .stat-icon {
    background: rgba(212, 165, 116, 0.15);
    color: #d4a574;
}

.stat-card.success .stat-icon {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}

.stat-card.warning .stat-icon {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.stat-card.info .stat-icon {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 4px;
}

.stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
}

/* ===== FILTER BAR ===== */
.filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}

.filter-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    color: rgba(255, 255, 255, 0.7);
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.filter-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #d4a574 0%, #f0d9b5 50%, #d4a574 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 0;
}

.filter-btn span {
    position: relative;
    z-index: 1;
}

.filter-btn:hover {
    border-color: #d4a574;
    color: #d4a574;
    transform: translateY(-2px);
}

.filter-btn.active {
    border-color: transparent;
    color: #0d0d0d;
    font-weight: 600;
    box-shadow: 0 5px 20px rgba(212, 165, 116, 0.4);
}

.filter-btn.active::before {
    opacity: 1;
}

.search-box {
    position: relative;
    width: 300px;
}

.search-box i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.5);
}

.search-box input {
    width: 100%;
    padding: 12px 20px 12px 50px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.search-box input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.search-box input:focus {
    outline: none;
    border-color: #d4a574;
    box-shadow: 0 0 20px rgba(212, 165, 116, 0.2);
}

/* ===== DATA COUNTER ===== */
.data-counter {
    margin-bottom: 25px;
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.9rem;
}

.data-counter span {
    color: #d4a574;
    font-weight: 600;
}

/* ===== TABLE CARD ===== */
.table-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    overflow: hidden;
}

/* ===== TABLE WRAPPER ===== */
.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: rgba(212, 165, 116, 0.1);
    position: sticky;
    top: 0;
    z-index: 10;
}

.data-table th {
    padding: 18px 15px;
    text-align: left;
    font-family: 'Poppins', sans-serif;
    font-size: 0.8rem;
    font-weight: 600;
    color: #d4a574;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.data-table th i {
    margin-right: 6px;
    opacity: 0.7;
}

.data-table tbody tr {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    animation: fadeInRow 0.5s ease backwards;
    animation-delay: calc(var(--delay, 0) * 0.03s);
}

@keyframes fadeInRow {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.data-table tbody tr:hover {
    background: rgba(212, 165, 116, 0.05);
}

.data-table td {
    padding: 18px 15px;
    vertical-align: middle;
}

/* Table Cell Styles */
.td-no {
    color: rgba(255, 255, 255, 0.5);
    font-weight: 500;
    width: 50px;
    text-align: center;
}

.customer-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #d4a574, #f0d9b5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1rem;
    color: #0d0d0d;
    flex-shrink: 0;
}

.customer-name {
    color: #fff;
    font-weight: 500;
}

.table-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
    font-size: 0.85rem;
}

.table-badge i {
    color: #d4a574;
}

.package-name {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.date-text {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.time-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(212, 165, 116, 0.1);
    border-radius: 8px;
    color: #d4a574;
    font-size: 0.85rem;
    font-weight: 500;
}

.payment-amount {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 700;
    
    /* Efek gradient hanya untuk layar */
    background: linear-gradient(135deg, #d4a574 0%, #f0d9b5 50%, #d4a574 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent; /* fallback untuk browser lama */
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-badge.pending {
    background: rgba(156, 163, 175, 0.15);
    color: #9ca3af;
}

.status-badge.booked {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.status-badge.selesai {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}

.status-badge.canceled {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    background: rgba(212, 165, 116, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon i {
    font-size: 3.5rem;
    color: #d4a574;
    opacity: 0.5;
}

.empty-state h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    color: #fff;
    margin-bottom: 10px;
}

.empty-state p {
    color: rgba(255, 255, 255, 0.5);
    font-size: 1rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 992px) {
    .laporan-section {
        padding: 25px 20px;
    }
    
    .stats-overview {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .btn-action {
        flex: 1;
        justify-content: center;
    }
    
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-tabs {
        overflow-x: auto;
        padding-bottom: 10px;
    }
    
    .search-box {
        width: 100%;
    }
    
    .data-table th,
    .data-table td {
        padding: 12px 10px;
    }
}

@media (max-width: 480px) {
    .stats-overview {
        grid-template-columns: 1fr;
    }
    
    .stat-value {
        font-size: 1.3rem;
    }
    
    .customer-cell {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* ===== PRINT STYLES ===== */
@page {
    /* Ukuran kertas A4, margin minimal agar konten lebih lebar */
    size: A4 landscape;
    margin: 10mm 8mm 10mm 8mm; /* atas | kanan | bawah | kiri */
}
@media print {
    .laporan-section {
        padding: 20px;
    }
    
    .header-actions,
    .filter-bar {
        display: none !important;
    }
    
    .table-card {
        border: 1px solid #ddd;
        box-shadow: none;
    }
    
    .data-table {
        color: #000;
    }
    
    .data-table thead {
        -webkit-text-fill-color: initial; /* kembalikan fill */
        color: #d4a574; /* atau #000 untuk hitam kontras tinggi di kertas putih */
        background: none !important; /* hilangkan gradient */
        -webkit-background-clip: initial;
        background-clip: initial;
    }
    .payment-amount {
        /* Matikan efek gradient & transparan */
        -webkit-text-fill-color: initial !important;
        color: #d4a574 !important;            /* Warna gold solid (terbaca jelas di kertas) */
        /* Alternatif: color: #000 !important;  jika ingin hitam pekat untuk kontras maksimal */
        
        background: none !important;
        -webkit-background-clip: initial !important;
        background-clip: initial !important;
    }
    .data-table tbody tr:nth-child(even) {
        background: #f9f9f9 !important;
    }
    @media print {
    /* ... kode print sebelumnya tetap ada ... */

    /* Card utama laporan */
    .table-card,
    .laporan-section > .card,
    .card {
        border: 2px solid #000 !important;     /* border tebal hitam di luar card */
        box-shadow: none !important;
        background: white !important;
        padding: 15px !important;
    }

    /* Tabel reservasi */
    .data-table {
        width: 100% !important;
        border-collapse: collapse !important;
        border: 2px solid #000 !important;     /* border luar tabel tebal */
        font-size: 11pt !important;
    }

    /* Header tabel */
    .data-table thead th {
        border: 1px solid #000 !important;     /* border setiap cell header */
        background: #e0e0e0 !important;         /* abu-abu muda agar header menonjol */
        color: #000 !important;
        font-weight: bold !important;
        padding: 12px 8px !important;
        text-align: center !important;

        /* Matikan gradient text */
        -webkit-text-fill-color: initial !important;
        color: #000 !important;
        background-image: none !important;
    }

    /* Body tabel - border setiap cell */
    .data-table tbody td {
        border: 1px solid #333 !important;     /* garis antar cell lebih tebal dari biasa */
        padding: 10px 8px !important;
        color: #000 !important;
    }

    /* Garis bawah ekstra untuk setiap baris (opsional, agar lebih jelas) */
    .data-table tbody tr {
        border-bottom: 2px solid #000 !important;
    }

    /* Zebra stripe tetap ada tapi dengan garis yang jelas */
    .data-table tbody tr:nth-child(even) {
        background-color: #f0f0f0 !important;
    }

    /* Jika ada summary/total row di bawah tabel */
    .total-row,
    .summary-row {
        border-top: 3px double #000 !important;
        font-weight: bold !important;
        background: #d0d0d0 !important;
    }

    /* Elemen statistik (kartu total reservasi, pendapatan, dll.) */
    .stat-card,
    .info-card {
        border: 1px solid #000 !important;
        padding: 15px !important;
        margin-bottom: 20px !important;
        page-break-inside: avoid;
    }

    /* Judul laporan */
    .section-title,
    h2, h3 {
        border-bottom: 2px solid #000 !important;
        padding-bottom: 8px !important;
        margin-bottom: 20px !important;
        color: #000 !important;
    }
}
}
</style>

<script>
lucide.createIcons();
document.addEventListener('DOMContentLoaded', function() {
    
    // Filter functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('.table-row');
    const resultCount = document.getElementById('resultCount');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            let count = 0;
            
            rows.forEach((row, index) => {
                row.style.animation = 'none';
                row.offsetHeight;
                
                if (filter === 'all' || row.dataset.status === filter) {
                    row.style.display = '';
                    row.style.setProperty('--delay', index);
                    row.style.animation = 'fadeInRow 0.5s ease forwards';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            resultCount.textContent = count;
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let count = 0;
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });
        
        resultCount.textContent = count;
    });
    
});

// Export to Excel function
function exportToExcel() {
    const table = document.getElementById('reservasiTable');
    let html = table.outerHTML;
    
    const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    const downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    downloadLink.href = url;
    downloadLink.download = 'laporan_reservasi_' + new Date().getTime() + '.xls';
    downloadLink.click();
    document.body.removeChild(downloadLink);
    
    showToast('✅ Data berhasil diexport ke Excel');
}

// Toast Notification
function showToast(message) {
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 15px 25px;
        background: rgba(212, 165, 116, 0.95);
        color: #0d0d0d;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        z-index: 9999999;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease;
    `;
    toast.innerHTML = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>