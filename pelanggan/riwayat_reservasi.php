<?php
include "../Koneksi2.php";
$id_user = $_SESSION['user_id'];

// Query untuk mengambil data reservasi
$q = mysqli_query($koneksi,"
    SELECT r.*, p.nama_pelanggan, m.nomor_meja, pr.nama_paket
    FROM reservasi r
    JOIN pelanggan p ON r.pelanggan_id=p.pelanggan_id
    JOIN meja m ON r.meja_id=m.meja_id
    JOIN paket_reservasi pr ON r.paket_id=pr.paket_id
    WHERE r.user_id='$id_user'
    ORDER BY r.waktu_pemesanan DESC
");

$total_reservasi = mysqli_num_rows($q);

// Hitung statistik
$q_stats = mysqli_query($koneksi,"
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status='Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status='Booked' THEN 1 ELSE 0 END) as booked,
        SUM(CASE WHEN status='Selesai' THEN 1 ELSE 0 END) as selesai,
        SUM(CASE WHEN status='Canceled' THEN 1 ELSE 0 END) as canceled
    FROM reservasi WHERE user_id='$id_user'
");
$stats = mysqli_fetch_assoc($q_stats);
?>

<!-- ===== RIWAYAT RESERVASI SECTION ===== -->
<section class="reservasi-section">
    
    <!-- Section Header -->
    <div class="section-header">
        <div class="header-title">
            <h2>
                <i class="bi bi-journal-bookmark-fill"></i>
                Riwayat Reservasi
            </h2>
            <p class="header-subtitle">Kelola dan pantau semua reservasi Anda</p>
        </div>
        
        <a href="dashboard.php?menu=reservasi" class="btn-new-reservasi">
            <i class="bi bi-plus-lg"></i>
            <span>Reservasi Baru</span>
        </a>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['total'] ?? 0 ?></h3>
                <p>Total Reservasi</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['pending'] ?? 0 ?></h3>
                <p>Menunggu</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon booked">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['booked'] ?? 0 ?></h3>
                <p>Dikonfirmasi</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon canceled">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['canceled'] ?? 0 ?></h3>
                <p>Dibatalkan</p>
            </div>
        </div>
    </div>
    
    <!-- Filter & Search Bar -->
    <div class="filter-bar">
        <div class="filter-tabs">
            <button class="filter-btn active" data-filter="all">
                <span>Semua</span>
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
            <input type="text" id="searchInput" placeholder="Cari reservasi...">
        </div>
    </div>
    
    <!-- Reservasi Content -->
    <div class="reservasi-container glass">
        
        <!-- Table Header Info -->
        <div class="table-header">
            <div class="result-count">
                <span id="resultCount"><?= $total_reservasi ?></span> reservasi ditemukan
            </div>
            <div class="view-toggle">
                <button class="view-btn active" data-view="table" title="Table View">
                    <i class="bi bi-list-ul"></i>
                </button>
                <button class="view-btn" data-view="card" title="Card View">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
            </div>
        </div>
        
        <!-- Table View -->
        <div class="table-view" id="tableView">
            <?php if($total_reservasi == 0): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h4>Belum Ada Reservasi</h4>
                    <p>Anda belum memiliki riwayat reservasi. Mulai buat reservasi pertama Anda!</p>
                    <a href="dashboard.php?menu=reservasi" class="btn-empty-action">
                        <i class="bi bi-plus-circle"></i>
                        Buat Reservasi
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="reservasi-table">
                        <thead>
                            <tr>
                                <th class="th-no">No</th>
                                <th class="th-date">
                                    <i class="bi bi-calendar3"></i>
                                    Tanggal
                                </th>
                                <th class="th-time">
                                    <i class="bi bi-clock"></i>
                                    Jam
                                </th>
                                <th class="th-name">
                                    <i class="bi bi-person"></i>
                                    Nama
                                </th>
                                <th class="th-table">
                                    <i class="bi bi-grid-1x2"></i>
                                    Meja
                                </th>
                                <th class="th-package">
                                    <i class="bi bi-box"></i>
                                    Paket
                                </th>
                                <th class="th-created">
                                    <i class="bi bi-clock-history"></i>
                                    Dibuat
                                </th>
                                <th class="th-status">Status</th>
                                <th class="th-action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            mysqli_data_seek($q, 0);
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
                                    'Booked' => 'bi-check-circle-fill',
                                    'Selesai' => 'bi-check-circle-fill',
                                    'Canceled' => 'bi-x-circle-fill',
                                    default => 'bi-question-circle'
                                };
                            ?>
                            <tr class="reservasi-row" data-status="<?= $d['status'] ?>" style="--delay: <?= $no ?>">
                                <td class="td-no"><?= $no++ ?></td>
                                <td class="td-date">
                                    <div class="date-display">
                                        <span class="date-day"><?= date('d', strtotime($d['tanggal_reservasi'])) ?></span>
                                        <span class="date-month"><?= date('M Y', strtotime($d['tanggal_reservasi'])) ?></span>
                                    </div>
                                </td>
                                <td class="td-time">
                                    <span class="time-badge">
                                        <i class="bi bi-clock"></i>
                                        <?= date('H:i', strtotime($d['jam_reservasi'])) ?>
                                    </span>
                                </td>
                                <td class="td-name">
                                    <div class="customer-info">
                                        <div class="customer-avatar">
                                            <?= strtoupper(substr($d['nama_pelanggan'], 0, 1)) ?>
                                        </div>
                                        <span><?= htmlspecialchars($d['nama_pelanggan']) ?></span>
                                    </div>
                                </td>
                                <td class="td-table">
                                    <span class="table-number">
                                        <i class=""></i><?= $d['nomor_meja'] ?>
                                    </span>
                                </td>
                                <td class="td-package">
                                    <span class="package-badge">
                                        <?= htmlspecialchars($d['nama_paket']) ?>
                                    </span>
                                </td>
                                <td class="td-created">
                                    <span class="created-date">
                                        <?= date('d M Y', strtotime($d['waktu_pemesanan'])) ?>
                                    </span>
                                    <span class="created-time">
                                        <?= date('H:i', strtotime($d['waktu_pemesanan'])) ?>
                                    </span>
                                </td>
                                <td class="td-status">
                                    <span class="status-badge <?= $status_class ?>">
                                        <i class="bi <?= $status_icon ?>"></i>
                                        <?= $d['status'] ?>
                                    </span>
                                </td>
                                <td class="td-action">
                                    <div class="action-buttons">
                                        <button class="action-btn view" title="Lihat Detail" data-id="<?= $d['reservasi_id'] ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php if($d['status'] == 'Pending'): ?>
                                        <button class="action-btn cancel" title="Batalkan" data-id="<?= $d['reservasi_id'] ?>">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Card View -->
        <div class="card-view" id="cardView" style="display: none;">
            <div class="reservasi-cards">
                <?php 
                mysqli_data_seek($q, 0);
                $no = 1;
                while($d = mysqli_fetch_assoc($q)): 
                    $status_class = match($d['status']){
                        'Pending' => 'pending',
                        'Booked' => 'booked',
                        'Selesai' => 'selesai',
                        'Canceled' => 'canceled',
                        default => 'default'
                    };
                ?>
                <div class="reservasi-card" data-status="<?= $d['status'] ?>" style="--delay: <?= $no++ ?>">
                    <div class="card-header">
                        <div class="card-date">
                            <span class="day"><?= date('d', strtotime($d['tanggal_reservasi'])) ?></span>
                            <span class="month"><?= date('M', strtotime($d['tanggal_reservasi'])) ?></span>
                        </div>
                        <span class="status-badge <?= $status_class ?>">
                            <?= $d['status'] ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="card-customer">
                            <div class="customer-avatar">
                                <?= strtoupper(substr($d['nama_pelanggan'], 0, 1)) ?>
                            </div>
                            <div class="customer-detail">
                                <h5><?= htmlspecialchars($d['nama_pelanggan']) ?></h5>
                                <p><?= htmlspecialchars($d['nama_paket']) ?></p>
                            </div>
                        </div>
                        <div class="card-info">
                            <div class="info-item">
                                <i class="bi bi-clock"></i>
                                <span><?= date('H:i', strtotime($d['jam_reservasi'])) ?> WIB</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-grid-1x2"></i>
                                <span>Meja #<?= $d['nomor_meja'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="created-info">
                            <i class="bi bi-calendar-plus"></i>
                            <?= date('d M Y, H:i', strtotime($d['waktu_pemesanan'])) ?>
                        </span>
                        <button class="btn-detail" data-id="<?= $d['reservasi_id'] ?>">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        
    </div>
    
</section>

<style>
/* ===== CSS VARIABLES ===== */
:root {
    --bg-dark: #0d0d0d;
    --bg-dark-secondary: #1a1a1a;
    --bg-dark-tertiary: #252525;
    --gold-primary: #d4a574;
    --gold-light: #e8c9a8;
    --gold-dark: #b8956a;
    --gold-gradient: linear-gradient(135deg, #d4a574 0%, #f0d9b5 50%, #d4a574 100%);
    --glass-bg: rgba(255, 255, 255, 0.05);
    --glass-border: rgba(255, 255, 255, 0.1);
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.7);
    --text-muted: rgba(255, 255, 255, 0.5);
    
    /* Status Colors */
    --status-pending: #f59e0b;
    --status-pending-bg: rgba(245, 158, 11, 0.15);
    --status-selesai: #10b981;
    --status-booked: #10b981;
    --status-booked-bg: rgba(16, 185, 129, 0.15);
    --status-canceled: #ef4444;
    --status-canceled-bg: rgba(239, 68, 68, 0.15);
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
/* ===== RESERVASI SECTION ===== */
.reservasi-section {
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
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 8px 0;
}

.header-title h2 i {
    color: var(--gold-primary);
}

.header-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
    margin: 0;
}

.btn-new-reservasi {
    padding: 14px 28px;
    background: var(--gold-gradient);
    border: none;
    border-radius: 50px;
    color: var(--bg-dark);
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(212, 165, 116, 0.3);
}

.btn-new-reservasi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
    color: var(--bg-dark);
}

/* ===== STATS GRID ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: rgba(212, 165, 116, 0.3);
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
}

.stat-icon.total {
    background: rgba(212, 165, 116, 0.15);
    color: var(--gold-primary);
}

.stat-icon.pending {
    background: var(--status-pending-bg);
    color: var(--status-pending);
}

.stat-icon.booked {
    background: var(--status-booked-bg);
    color: var(--status-booked);
}

.stat-icon.canceled {
    background: var(--status-canceled-bg);
    color: var(--status-canceled);
}

.stat-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.stat-info p {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin: 0;
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
    padding: 10px 22px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 50px;
    color: var(--text-secondary);
    font-family: 'Poppins', sans-serif;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    border-color: var(--gold-primary);
    color: var(--gold-primary);
}

.filter-btn.active {
    background: var(--gold-gradient);
    border-color: transparent;
    color: var(--bg-dark);
    font-weight: 600;
}

.search-box {
    position: relative;
    width: 280px;
}

.search-box i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}

.search-box input {
    width: 100%;
    padding: 12px 20px 12px 50px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 50px;
    color: var(--text-primary);
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.search-box input::placeholder {
    color: var(--text-muted);
}

.search-box input:focus {
    outline: none;
    border-color: var(--gold-primary);
    box-shadow: 0 0 20px rgba(212, 165, 116, 0.2);
}

/* ===== RESERVASI CONTAINER ===== */
.reservasi-container {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 25px;
    overflow: hidden;
}

/* Table Header */
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--glass-border);
}

.result-count {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.result-count span {
    color: var(--gold-primary);
    font-weight: 600;
}

.view-toggle {
    display: flex;
    gap: 8px;
}

.view-btn {
    width: 40px;
    height: 40px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 10px;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.view-btn:hover,
.view-btn.active {
    background: var(--gold-primary);
    color: var(--bg-dark);
    border-color: transparent;
}

/* ===== RESERVASI TABLE ===== */
.table-responsive {
    overflow-x: auto;
}

.reservasi-table {
    width: 100%;
    border-collapse: collapse;
}

.reservasi-table thead tr {
    background: rgba(212, 165, 116, 0.1);
}

.reservasi-table th {
    padding: 16px 15px;
    text-align: left;
    font-family: 'Poppins', sans-serif;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--gold-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.reservasi-table th i {
    margin-right: 6px;
    opacity: 0.7;
}

.reservasi-table tbody tr {
    border-bottom: 1px solid var(--glass-border);
    transition: all 0.3s ease;
    animation: fadeInRow 0.5s ease backwards;
    animation-delay: calc(var(--delay, 0) * 0.05s);
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

.reservasi-table tbody tr:hover {
    background: rgba(212, 165, 116, 0.05);
}

.reservasi-table td {
    padding: 18px 15px;
    vertical-align: middle;
    
}

/* Table Cell Styles */
.td-no {
    color: var(--text-muted);
    font-weight: 500;
    width: 50px;
}

.date-display {
    display: flex;
    flex-direction: column;
}

.date-day {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--gold-primary);
    line-height: 1;
}

.date-month {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
}

.time-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(212, 165, 116, 0.1);
    border-radius: 8px;
    color: var(--gold-light);
    font-size: 0.85rem;
    font-weight: 500;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    background: var(--gold-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1rem;
    color: var(--bg-dark);
}

.table-number {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.05));
    border: 1px solid rgba(212, 165, 116, 0.3);
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 600;
    color: var(--gold-primary, #d4a574);
}


.table-number i {
    color: var(--gold-primary);
    margin-right: 2px;
}

.package-badge {
    display: inline-block;
    padding: 6px 14px;
    background: rgba(212, 165, 116, 0.15);
    border-radius: 8px;
    color: var(--gold-light);
    font-size: 0.85rem;
    font-weight: 500;
}

.created-date {
    display: block;
    color: var(--text-primary);
    font-size: 0.85rem;
}

.created-time {
    display: block;
    color: var(--text-muted);
    font-size: 0.75rem;
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
}

.status-badge.pending {
    background: var(--status-pending-bg);
    color: var(--status-pending);
}

.status-badge.booked {
    background: var(--status-booked-bg);
    color: var(--status-booked);
}

.status-badge.canceled {
    background: var(--status-canceled-bg);
    color: var(--status-canceled);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.action-btn.view {
    background: rgba(212, 165, 116, 0.15);
    color: var(--gold-primary);
}

.action-btn.view:hover {
    background: var(--gold-primary);
    color: var(--bg-dark);
    transform: scale(1.1);
}

.action-btn.cancel {
    background: var(--status-canceled-bg);
    color: var(--status-canceled);
}

.action-btn.cancel:hover {
    background: var(--status-canceled);
    color: white;
    transform: scale(1.1);
}

/* ===== CARD VIEW ===== */
.reservasi-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

.reservasi-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s ease;
    animation: fadeInCard 0.5s ease backwards;
    animation-delay: calc(var(--delay, 0) * 0.1s);
}

@keyframes fadeInCard {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.reservasi-card:hover {
    transform: translateY(-8px);
    border-color: rgba(212, 165, 116, 0.4);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

.reservasi-card .card-header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    background: rgba(212, 165, 116, 0.05);
    border-bottom: 1px solid var(--glass-border);
}

.reservasi-card .card-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: var(--gold-gradient);
    padding: 12px 18px;
    border-radius: 12px;
}

.reservasi-card .card-date .day {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--bg-dark);
    line-height: 1;
}

.reservasi-card .card-date .month {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--bg-dark);
    text-transform: uppercase;
}

.reservasi-card .card-body {
    padding: 20px;
}

.card-customer {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.card-customer .customer-avatar {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
}

.customer-detail h5 {
    margin: 0 0 4px 0;
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    color: var(--text-primary);
}

.customer-detail p {
    margin: 0;
    font-size: 0.85rem;
    color: var(--gold-primary);
}

.card-info {
    display: flex;
    gap: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background: var(--glass-bg);
    border-radius: 10px;
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.info-item i {
    color: var(--gold-primary);
}

.reservasi-card .card-footer {
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--glass-border);
    background: rgba(0, 0, 0, 0.2);
}

.created-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.btn-detail {
    width: 40px;
    height: 40px;
    background: var(--gold-gradient);
    border: none;
    border-radius: 50%;
    color: var(--bg-dark);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-detail:hover {
    transform: scale(1.1);
    box-shadow: 0 5px 20px rgba(212, 165, 116, 0.4);
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
    color: var(--gold-primary);
    opacity: 0.5;
}

.empty-state h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.empty-state p {
    color: var(--text-muted);
    font-size: 1rem;
    margin-bottom: 30px;
}

.btn-empty-action {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 15px 30px;
    background: var(--gold-gradient);
    border-radius: 50px;
    color: var(--bg-dark);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-empty-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
    color: var(--bg-dark);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 992px) {
    .reservasi-section {
        padding: 25px 20px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-new-reservasi {
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
    
    .reservasi-table th,
    .reservasi-table td {
        padding: 12px 10px;
    }
    
    .th-created,
    .td-created {
        display: none;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .reservasi-cards {
        grid-template-columns: 1fr;
    }
    
    .card-info {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
document.addEventListener('DOMContentLoaded', function() {
    
    // Filter functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('.reservasi-row');
    const cards = document.querySelectorAll('.reservasi-card');
    const resultCount = document.getElementById('resultCount');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            let count = 0;
            
            // Filter table rows
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
            
            // Filter cards
            cards.forEach((card, index) => {
                card.style.animation = 'none';
                card.offsetHeight;
                
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = '';
                    card.style.setProperty('--delay', index);
                    card.style.animation = 'fadeInCard 0.5s ease forwards';
                } else {
                    card.style.display = 'none';
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
        
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        
        resultCount.textContent = count;
    });
    
    // View toggle
    const viewBtns = document.querySelectorAll('.view-btn');
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            if (view === 'table') {
                tableView.style.display = 'block';
                cardView.style.display = 'none';
            } else {
                tableView.style.display = 'none';
                cardView.style.display = 'block';
            }
        });
    });
    
    // Action buttons
    document.querySelectorAll('.action-btn.view, .btn-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            // Implement view detail functionality
            alert('Lihat detail reservasi #' + id);
        });
    });
    
    document.querySelectorAll('.action-btn.cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')) {
                // Implement cancel functionality
                alert('Reservasi #' + id + ' dibatalkan');
            }
        });
    });
    
});
</script>
    