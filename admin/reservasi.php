<?php
// JANGAN session_start
// JANGAN include koneksi (sudah di dashboard.php)
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    
/* ===== RESERVASI PAGE STYLES ===== */

/* Page Header */
.page-header {
    margin-bottom: 32px;
    animation: fadeInUp 0.6s ease-out;
}

.page-title-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary, #ffffff);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 14px;
}

.page-title i {
    color: var(--gold-primary, #d4a574);
    width: 32px;
    height: 32px;
}

.page-subtitle {
    color: var(--text-secondary, #a0a0a0);
    font-size: 0.95rem;
    margin-top: 8px;
}

/* Stats Mini Cards */
.stats-mini {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.stat-mini-card {
    background: var(--glass-bg, rgba(255, 255, 255, 0.03));
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 14px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
}

.stat-mini-card:hover {
    border-color: rgba(212, 165, 116, 0.3);
    transform: translateY(-2px);
}

.stat-mini-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-mini-icon.pending {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.stat-mini-icon.booked {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.stat-mini-icon.selesai {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
}

.stat-mini-icon.canceled {
    background: rgba(248, 113, 113, 0.2);
    color: #f87171;
}

.stat-mini-icon i {
    width: 20px;
    height: 20px;
}

.stat-mini-info h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary, #ffffff);
    margin: 0;
    line-height: 1;
}

.stat-mini-info span {
    font-size: 0.75rem;
    color: var(--text-muted, #6b6b6b);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Main Card */
.reservasi-card {
    background: var(--glass-bg, rgba(255, 255, 255, 0.03));
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 24px;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out 0.2s backwards;
}

.reservasi-card-header {
    background: linear-gradient(135deg, rgba(212, 165, 116, 0.15) 0%, rgba(212, 165, 116, 0.05) 100%);
    border-bottom: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    padding: 20px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.card-header-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary, #ffffff);
    margin: 0;
}

.card-header-title i {
    color: var(--gold-primary, #d4a574);
    width: 22px;
    height: 22px;
}

/* Search & Filter */
.filter-section {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
}

.search-box input {
    background: var(--bg-secondary, #1a1a1a);
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 12px;
    padding: 10px 16px 10px 42px;
    color: var(--text-primary, #ffffff);
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    width: 250px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: var(--gold-primary, #d4a574);
    box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
}

.search-box input::placeholder {
    color: var(--text-muted, #6b6b6b);
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted, #6b6b6b);
    width: 18px;
    height: 18px;
    pointer-events: none;
}

.filter-select {
    background: var(--bg-secondary, #1a1a1a);
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 12px;
    padding: 10px 16px;
    color: var(--text-primary, #ffffff);
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: var(--gold-primary, #d4a574);
}

/* Table Container */
.reservasi-card-body {
    padding: 0;
}

.table-container {
    overflow-x: auto;
}

/* Custom Table */
.reservasi-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.reservasi-table thead {
    background: var(--bg-secondary, #1a1a1a);
}

.reservasi-table th {
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: var(--text-secondary, #a0a0a0);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    white-space: nowrap;
}

.reservasi-table th.text-center {
    text-align: center;
}

.reservasi-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--glass-border, rgba(255, 255, 255, 0.05));
}

.reservasi-table tbody tr:last-child {
    border-bottom: none;
}

.reservasi-table tbody tr:hover {
    background: var(--glass-hover, rgba(255, 255, 255, 0.03));
}

.reservasi-table td {
    padding: 18px 20px;
    color: var(--text-primary, #ffffff);
    vertical-align: middle;
}

.reservasi-table td.text-center {
    text-align: center;
}

/* Row Number */
.row-number {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.05));
    border: 1px solid rgba(212, 165, 116, 0.2);
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--gold-primary, #d4a574);
}

/* Date & Time Display */
.date-display {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.date-display .date {
    font-weight: 600;
    color: var(--text-primary, #ffffff);
}

.date-display .day {
    font-size: 0.75rem;
    color: var(--text-muted, #6b6b6b);
}

.time-display {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--glass-bg, rgba(255, 255, 255, 0.03));
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    padding: 6px 12px;
    border-radius: 8px;
}

.time-display i {
    width: 14px;
    height: 14px;
    color: var(--gold-primary, #d4a574);
}

/* Customer Info */
.customer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, var(--gold-primary, #d4a574), var(--gold-dark, #a67c52));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--bg-primary, #0f0f0f);
}

.customer-name {
    font-weight: 500;
}

/* Meja Badge */
.meja-badge {
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

.meja-badge i {
    width: 16px;
    height: 16px;
}

/* Paket Display */
.paket-display {
    display: flex;
    align-items: center;
    gap: 8px;
}

.paket-icon {
    width: 32px;
    height: 32px;
    background: rgba(96, 165, 250, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.paket-icon i {
    width: 16px;
    height: 16px;
    color: #60a5fa;
}

/* Total Display */
.total-display {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1rem;
    color: var(--gold-light, #e8c4a0);
}

.total-display small {
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
    font-size: 0.75rem;
    color: var(--text-muted, #6b6b6b);
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge i {
    width: 14px;
    height: 14px;
}

.status-badge.pending {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
    border: 1px solid rgba(156, 163, 175, 0.3);
}

.status-badge.booked {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-badge.selesai {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.status-badge.canceled {
    background: rgba(248, 113, 113, 0.2);
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    white-space: nowrap;
}

.btn-action i {
    width: 16px;
    height: 16px;
}

.btn-action.confirm {
    background: linear-gradient(135deg, rgba(74, 222, 128, 0.2), rgba(74, 222, 128, 0.1));
    color: #4ade80;
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.btn-action.confirm:hover {
    background: #4ade80;
    color: #0f0f0f;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(74, 222, 128, 0.3);
}

.btn-action.cancel {
    background: linear-gradient(135deg, rgba(248, 113, 113, 0.2), rgba(248, 113, 113, 0.1));
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.3);
}

.btn-action.cancel:hover {
    background: #f87171;
    color: #0f0f0f;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(248, 113, 113, 0.3);
}

.btn-action.complete {
    background: linear-gradient(135deg, rgba(96, 165, 250, 0.2), rgba(96, 165, 250, 0.1));
    color: #60a5fa;
    border: 1px solid rgba(96, 165, 250, 0.3);
}

.btn-action.complete:hover {
    background: #60a5fa;
    color: #0f0f0f;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(96, 165, 250, 0.3);
}

.no-action {
    color: var(--text-muted, #6b6b6b);
    font-style: italic;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: var(--glass-bg, rgba(255, 255, 255, 0.03));
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-state-icon i {
    width: 36px;
    height: 36px;
    color: var(--text-muted, #6b6b6b);
}

.empty-state h4 {
    font-family: 'Playfair Display', serif;
    color: var(--text-primary, #ffffff);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--text-muted, #6b6b6b);
}

/* Footer / Pagination */
.reservasi-card-footer {
    background: var(--bg-secondary, #1a1a1a);
    border-top: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    padding: 16px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.showing-info {
    color: var(--text-muted, #6b6b6b);
    font-size: 0.85rem;
}

.showing-info strong {
    color: var(--text-primary, #ffffff);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.reservasi-table tbody tr {
    animation: fadeInUp 0.4s ease-out backwards;
}

.reservasi-table tbody tr:nth-child(1) { animation-delay: 0.05s; }
.reservasi-table tbody tr:nth-child(2) { animation-delay: 0.1s; }
.reservasi-table tbody tr:nth-child(3) { animation-delay: 0.15s; }
.reservasi-table tbody tr:nth-child(4) { animation-delay: 0.2s; }
.reservasi-table tbody tr:nth-child(5) { animation-delay: 0.25s; }
.reservasi-table tbody tr:nth-child(6) { animation-delay: 0.3s; }
.reservasi-table tbody tr:nth-child(7) { animation-delay: 0.35s; }
.reservasi-table tbody tr:nth-child(8) { animation-delay: 0.4s; }
.reservasi-table tbody tr:nth-child(9) { animation-delay: 0.45s; }
.reservasi-table tbody tr:nth-child(10) { animation-delay: 0.5s; }

/* Responsive */
@media (max-width: 768px) {
    .page-title-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .stats-mini {
        width: 100%;
    }
    
    .stat-mini-card {
        flex: 1;
        min-width: 140px;
    }
    
    .filter-section {
        width: 100%;
    }
    
    .search-box input {
        width: 100%;
    }
    
    .reservasi-card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-action {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php
// Count statistics
$count_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM reservasi WHERE status='Pending'"))['total'];
$count_booked = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM reservasi WHERE status='Booked'"))['total'];
$count_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM reservasi WHERE status='Selesai'"))['total'];
$count_canceled = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM reservasi WHERE status='Canceled'"))['total'];
$total_reservasi = $count_pending + $count_booked + $count_selesai + $count_canceled;
?>

<!-- Page Header -->
<div class="page-header">
    <div class="page-title-wrapper">
        <div>
            <h2 class="page-title">
                <i data-lucide="calendar-check"></i>
                Kelola Reservasi
            </h2>
            <p class="page-subtitle">Kelola semua reservasi pelanggan café Anda</p>
        </div>
        
        <div class="stats-mini">
            <div class="stat-mini-card">
                <div class="stat-mini-icon pending">
                    <i data-lucide="clock"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?= $count_pending ?></h4>
                    <span>Pending</span>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon booked">
                    <i data-lucide="bookmark-check"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?= $count_booked ?></h4>
                    <span>Booked</span>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon selesai">
                    <i data-lucide="check-circle"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?= $count_selesai ?></h4>
                    <span>Selesai</span>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon canceled">
                    <i data-lucide="x-circle"></i>
                </div>
                <div class="stat-mini-info">
                    <h4><?= $count_canceled ?></h4>
                    <span>Batal</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="reservasi-card">
    <div class="reservasi-card-header">
        <h3 class="card-header-title">
            <i data-lucide="list"></i>
            Data Reservasi Pelanggan
        </h3>
        
        <div class="filter-section">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" id="searchInput" placeholder="Cari pelanggan..." onkeyup="searchTable()">
            </div>
            <select class="filter-select" id="statusFilter" onchange="filterStatus()">
                <option value="">Semua Status</option>
                <option value="Pending">Pending</option>
                <option value="Booked">Booked</option>
                <option value="Selesai">Selesai</option>
                <option value="Canceled">Canceled</option>
            </select>
        </div>
    </div>

    <div class="reservasi-card-body">
        <div class="table-container">
            <table class="reservasi-table" id="reservasiTable">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Pelanggan</th>
                        <th>Meja</th>
                        <th>Paket</th>
                        <th>Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($koneksi,"
                    SELECT r.*, 
                           p.nama_pelanggan,
                           m.nomor_meja,
                           pr.nama_paket
                    FROM reservasi r
                    JOIN pelanggan p ON r.pelanggan_id=p.pelanggan_id
                    JOIN meja m ON r.meja_id=m.meja_id
                    JOIN paket_reservasi pr ON r.paket_id=pr.paket_id
                    ORDER BY r.waktu_pemesanan DESC
                ");

                if(mysqli_num_rows($q) > 0) {
                    while($d = mysqli_fetch_assoc($q)){
                        $initial = strtoupper(substr($d['nama_pelanggan'], 0, 1));
                        $tanggal = date('d M Y', strtotime($d['tanggal_reservasi']));
                        $hari = date('l', strtotime($d['tanggal_reservasi']));
                        
                        // Translate day to Indonesian
                        $days_id = [
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin', 
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu'
                        ];
                        $hari_id = $days_id[$hari] ?? $hari;
                ?>
                    <tr data-status="<?= $d['status'] ?>">
                        <td class="text-center">
                            <span class="row-number"><?= $no++ ?></span>
                        </td>
                        <td>
                            <div class="date-display">
                                <span class="date"><?= $tanggal ?></span>
                                <span class="day"><?= $hari_id ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="time-display">
                                <i data-lucide="clock"></i>
                                <?= date('H:i', strtotime($d['jam_reservasi'])) ?>
                            </span>
                        </td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar"><?= $initial ?></div>
                                <span class="customer-name"><?= htmlspecialchars($d['nama_pelanggan']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="meja-badge">
                                <i data-lucide="armchair"></i>
                                Meja <?= $d['nomor_meja'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="paket-display">
                                <div class="paket-icon">
                                    <i data-lucide="package"></i>
                                </div>
                                <span><?= htmlspecialchars($d['nama_paket']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="total-display">
                                <small>Rp</small> <?= number_format($d['total_bayar'], 0, ',', '.') ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php
                            $status_lower = strtolower($d['status']);
                            $status_icon = [
                                'pending' => 'clock',
                                'booked' => 'bookmark-check',
                                'selesai' => 'check-circle',
                                'canceled' => 'x-circle'
                            ];
                            $icon = $status_icon[$status_lower] ?? 'help-circle';
                            ?>
                            <span class="status-badge <?= $status_lower ?>">
                                <i data-lucide="<?= $icon ?>"></i>
                                <?= $d['status'] ?>
                            </span>
                        </td>
                        <td class="text-center">
    <div class="action-buttons d-flex justify-content-center gap-2 flex-wrap">
        <?php if($d['status'] == 'Pending'): ?>
            <button class="btn-action confirm btn btn-outline-warning btn-sm px-4 py-2" 
                    data-href="update_status.php?id=<?= $d['reservasi_id'] ?>&status=Booked"
                    data-title="Konfirmasi Reservasi?"
                    data-text="Reservasi akan dikonfirmasi dan status menjadi Booked">
                <i data-lucide="check" class="me-1"></i>
                Confirm
            </button>
            <button class="btn-action cancel btn btn-outline-danger btn-sm px-4 py-2" 
                    data-href="update_status.php?id=<?= $d['reservasi_id'] ?>&status=Canceled"
                    data-title="Batalkan Reservasi?"
                    data-text="Reservasi akan dibatalkan permanen">
                <i data-lucide="x" class="me-1"></i>
                Cancel
            </button>
        <?php elseif($d['status'] == 'Booked'): ?>
            <button class="btn-action complete btn btn-outline-success btn-sm px-4 py-2" 
                    data-href="reservasi_selesai.php?id=<?= $d['reservasi_id'] ?>"
                    data-title="Selesaikan Reservasi?"
                    data-text="Reservasi akan ditandai sebagai Selesai">
                <i data-lucide="check-check" class="me-1"></i>
                Selesai
            </button>
        <?php else: ?>
            <span class="text-muted small">— No Action —</span>
        <?php endif; ?>
    </div>
</td>
                    </tr>
                <?php 
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="inbox"></i>
                                </div>
                                <h4>Belum Ada Reservasi</h4>
                                <p>Data reservasi akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="reservasi-card-footer">
        <div class="showing-info">
            Menampilkan <strong><?= $total_reservasi ?></strong> reservasi
        </div>
    </div>
</div>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
// Re-initialize Lucide icons
lucide.createIcons();
// SweetAlert untuk semua action button
document.querySelectorAll('.btn-action.confirm, .btn-action.cancel, .btn-action.complete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const href = this.getAttribute('data-href');
            const title = this.getAttribute('data-title');
            const text = this.getAttribute('data-text');
            const icon = this.classList.contains('confirm') ? 'question' :
                         this.classList.contains('complete') ? 'success' : 'warning';

            const confirmColor = this.classList.contains('confirm') ? '#d4a574' :
                                 this.classList.contains('complete') ? '#10b981' : '#ef4444';

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#1a1a1a',
                color: '#f0d9b5',
                backdrop: 'rgba(0,0,0,0.8)',
                customClass: {
                    popup: 'rounded-4 border border-secondary shadow-lg',
                    title: 'fw-bold',
                    confirmButton: 'btn btn-warning text-dark px-4 py-2 mx-2',
                    cancelButton: 'btn btn-secondary px-4 py-2 mx-2'
                },
                buttonsStyling: false,
                confirmButtonColor: confirmColor,
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
// Search function
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('reservasiTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell) {
                const text = cell.textContent || cell.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}

// Filter by status
function filterStatus() {
    const select = document.getElementById('statusFilter');
    const filter = select.value;
    const table = document.getElementById('reservasiTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const status = rows[i].getAttribute('data-status');
        
        if (filter === '' || status === filter) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}
</script>
