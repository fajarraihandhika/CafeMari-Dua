<?php
include "../Koneksi2.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['paket_id'];
    $nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
    $jumlah_orang = (int)$_POST['jumlah_orang'];
    $harga = (int)$_POST['harga'];
    
    $update = mysqli_query(
        $koneksi,
        "UPDATE paket_reservasi SET
            jumlah_orang = '$jumlah_orang',
            harga  = $harga
         WHERE paket_id = $id"
    );

    if ($update) {
        echo "<script>
            alert('Paket berhasil diperbarui!');
            window.location.href = 'dashboard.php?menu=paket';
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui data!');
            history.back();
        </script>";
    }
}


?>

<style>
/* ===== PAKET PAGE STYLES ===== */

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

/* Stats Badge */
.stats-badge {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--glass-bg, rgba(255, 255, 255, 0.03));
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 16px;
    padding: 16px 24px;
}

.stats-badge-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--gold-primary, #d4a574), var(--gold-dark, #a67c52));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(212, 165, 116, 0.3);
}

.stats-badge-icon i {
    width: 24px;
    height: 24px;
    color: var(--bg-primary, #0f0f0f);
}

.stats-badge-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary, #ffffff);
    margin: 0;
    line-height: 1;
}

.stats-badge-info span {
    font-size: 0.8rem;
    color: var(--text-muted, #6b6b6b);
}

/* Glass Card */
.glass-card {
  background: var(--glass-bg, rgba(255, 255, 255, 0.03));
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 24px;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out 0.2s backwards;
}

.glass-card:hover {
    border-color: rgba(212, 165, 116, 0.2);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
}

.glass-card-header {
  background: linear-gradient(135deg, rgba(212, 165, 116, 0.15) 0%, rgba(212, 165, 116, 0.05) 100%);
    border-bottom: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    padding: 20px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.glass-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary, #ffffff);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.glass-card-header h3 i {
    color: var(--gold-primary, #d4a574);
    width: 22px;
    height: 22px;
}

.glass-card-body {
    padding: 28px;
}

/* Form Styles */
.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--text-primary, #ffffff);
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.form-label i {
    width: 16px;
    height: 16px;
    color: var(--gold-primary, #d4a574);
    margin-right: 8px;
}

.form-input {
    width: 100%;
    background: var(--bg-secondary, #1a1a1a);
    border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    border-radius: 14px;
    padding: 14px 18px;
    color: var(--text-primary, #ffffff);
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--gold-primary, #d4a574);
    box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.15);
    background: var(--bg-tertiary, #252525);
}

.form-input::placeholder {
    color: var(--text-muted, #6b6b6b);
}

/* Input with icon */
.input-icon-wrapper {
    position: relative;
}

.input-icon-wrapper .form-input {
    padding-left: 48px;
}

.input-icon-wrapper .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    color: var(--text-muted, #6b6b6b);
    pointer-events: none;
    transition: all 0.3s ease;
}

.input-icon-wrapper .form-input:focus + .input-icon,
.input-icon-wrapper:focus-within .input-icon {
    color: var(--gold-primary, #d4a574);
}

/* Currency Input */
.currency-input {
    position: relative;
}

.currency-input .currency-symbol {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: var(--gold-primary, #d4a574);
    font-size: 0.9rem;
}

.currency-input .form-input {
    padding-left: 50px;
}

/* Submit Button */
.btn-submit {
    width: 100%;
    background: linear-gradient(135deg, var(--gold-primary, #d4a574), var(--gold-dark, #a67c52));
    border: none;
    border-radius: 14px;
    padding: 16px 24px;
    color: var(--bg-primary, #0f0f0f);
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.4s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 8px;
    box-shadow: 0 8px 24px rgba(212, 165, 116, 0.3);
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(212, 165, 116, 0.4);
}

.btn-submit:active {
    transform: translateY(-1px);
}

.btn-submit i {
    width: 20px;
    height: 20px;
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.paket-table {
    width: 100%;
    border-collapse: collapse;
}

.paket-table thead {
    background: var(--bg-secondary, #1a1a1a);
}

.paket-table th {
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

.paket-table th.text-center {
    text-align: center;
}

.paket-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--glass-border, rgba(255, 255, 255, 0.05));
}

.paket-table tbody tr:last-child {
    border-bottom: none;
}

.paket-table tbody tr:hover {
    background: var(--glass-hover, rgba(255, 255, 255, 0.03));
}

.paket-table td {
    padding: 20px;
    color: var(--text-primary, #ffffff);
    vertical-align: middle;
}

.paket-table td.text-center {
    text-align: center;
}

/* Row Number */
.row-number {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.05));
    border: 1px solid rgba(212, 165, 116, 0.2);
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--gold-primary, #d4a574);
}

/* Paket Name Display */
.paket-name {
    display: flex;
    align-items: center;
    gap: 14px;
}

.paket-icon {
    width: 46px;
    height: 46px;
    background: linear-gradient(135deg, rgba(96, 165, 250, 0.2), rgba(96, 165, 250, 0.05));
    border: 1px solid rgba(96, 165, 250, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.paket-icon i {
    width: 22px;
    height: 22px;
    color: #60a5fa;
}

.paket-info h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary, #ffffff);
    margin: 0 0 4px 0;
}

.paket-info span {
    font-size: 0.8rem;
    color: var(--text-muted, #6b6b6b);
    display: flex;
    align-items: center;
    gap: 6px;
}

.paket-info span i {
    width: 14px;
    height: 14px;
}

/* Price Display */
.price-display {
    display: flex;
    align-items: center;
    gap: 4px;
}

.price-display .currency {
    font-size: 0.8rem;
    color: var(--text-muted, #6b6b6b);
}

.price-display .amount {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--gold-light, #e8c4a0);
}

/* Delete Button */
.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: linear-gradient(135deg, rgba(248, 113, 113, 0.2), rgba(248, 113, 113, 0.05));
    border: 1px solid rgba(248, 113, 113, 0.3);
    border-radius: 12px;
    color: #f87171;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-delete:hover {
    background: #f87171;
    color: var(--bg-primary, #0f0f0f);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(248, 113, 113, 0.3);
}

.btn-delete i {
    width: 16px;
    height: 16px;
}

/* Edit Button */
.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(251, 191, 36, 0.05));
    border: 1px solid rgba(251, 191, 36, 0.3);
    border-radius: 12px;
    color: #fbbf24;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-edit:hover {
    background: #fbbf24;
    color: var(--bg-primary, #0f0f0f);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(251, 191, 36, 0.3);
}

.btn-edit i {
    width: 16px;
    height: 16px;
}

/* Action Buttons Container */
.action-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
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

/* Card Footer */
.glass-card-footer {
    background: var(--bg-secondary, #1a1a1a);
    border-top: 1px solid var(--glass-border, rgba(255, 255, 255, 0.08));
    padding: 16px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.footer-info {
    color: var(--text-muted, #6b6b6b);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-info i {
    width: 16px;
    height: 16px;
    color: var(--gold-primary, #d4a574);
}

.footer-info strong {
    color: var(--text-primary, #ffffff);
}

/* Tips Card */
.tips-card {
    background: linear-gradient(135deg, rgba(96, 165, 250, 0.1), rgba(96, 165, 250, 0.02));
    border: 1px solid rgba(96, 165, 250, 0.2);
    border-radius: 16px;
    padding: 20px;
    margin-top: 24px;
}

.tips-card h5 {
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem;
    color: #60a5fa;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tips-card h5 i {
    width: 18px;
    height: 18px;
}

.tips-card p {
    font-size: 0.85rem;
    color: var(--text-secondary, #a0a0a0);
    margin: 0;
    line-height: 1.6;
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

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.col-lg-4 .glass-card {
    animation: fadeInLeft 0.6s ease-out 0.2s backwards;
}

.col-lg-8 .glass-card {
    animation: fadeInRight 0.6s ease-out 0.3s backwards;
}

.paket-table tbody tr {
    animation: fadeInUp 0.4s ease-out backwards;
}

.paket-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
.paket-table tbody tr:nth-child(2) { animation-delay: 0.15s; }
.paket-table tbody tr:nth-child(3) { animation-delay: 0.2s; }
.paket-table tbody tr:nth-child(4) { animation-delay: 0.25s; }
.paket-table tbody tr:nth-child(5) { animation-delay: 0.3s; }

/* Responsive */
@media (max-width: 992px) {
    .page-title-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .stats-badge {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-delete,
    .btn-edit {
        width: 100%;
        justify-content: center;
    }
}
</style>
<div class="page-header">
    <div class="page-title-wrapper">
        <div>
            <h2 class="page-title">
                <i data-lucide="package"></i>
                Paket Reservasi
            </h2>
            <p class="page-subtitle">Kelola Paket reservasi untuk pelanggan café Anda</p>
        </div>
        
        <div class="stats-badge">
            <div class="stats-badge-icon">
                <i data-lucide="layers"></i>
            </div>
            <div class="stats-badge-info">
                <h3><?= $total_paket ?></h3>
                <span>Total Paket Tersedia</span>
            </div>
        </div>
    </div>
</div>
<!-- MAIN CONTENT -->
        <div class="glass-card">
            <div class="glass-card-header">
                <h3>
                    <i data-lucide="plus-circle"></i>
                    Edit Paket 
                </h3>
            </div>
            <?php
include "../Koneksi2.php";

$id = (int) $_GET['id']; 

$sql = mysqli_query($koneksi, "SELECT * FROM paket_reservasi WHERE paket_id = $id");
$d   = mysqli_fetch_assoc($sql);

// Optional safety
if (!$d) {
    echo "<script>
        alert('Data Paket tidak ditemukan!');
        window.location.href='dashboard.php?menu=paket';
    </script>";
    exit;
}
?>

            <div class="glass-card-body">
                <form method="post" action="paket_update.php" id="formPaket">
                    <div class="form-group">
                        <label class="form-label">
                            <i data-lucide="package"></i>
                            Nama Paket
                        </label>
                        <input type="hidden" name="id" value="<?= $d['paket_id'] ?>">
                        <div class="input-icon-wrapper">
                            <input type="text" 
                                   name="nama_paket" 
                                   value="<?= $d['nama_paket'] ?>"
                                   class="form-input" 
                                   required>
                            <i data-lucide="package" class="input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i data-lucide="user"></i>
                            Orang
                        </label>
                        <div class="input-icon-wrapper">
                            <input type="text" 
                                   name="jumlah_orang" 
                                   value="<?= $d['jumlah_orang'] ?>"
                                   class="form-input" 
                                   required>
                            <i data-lucide="user" class="input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i data-lucide="banknote"></i>
                            Harga
                        </label>
                        <div class="input-icon-wrapper">
                            <input type="number" 
                                   name="harga" 
                                   value="<?= $d['harga'] ?>"
                                   class="form-input" 
                                   min="1"
                                   required>
                            <i data-lucide="banknote" class="input-icon"></i>
                        </div>
                    </div>      
                    <button type="submit" class="btn-submit">
                        <i data-lucide="save"></i>
                        Simpan Paket
                    </button>
                </form>
                
                <div class="tips-card">
                    <h5>
                        <i data-lucide="lightbulb"></i>
                        Tips
                    </h5>
                    <p>Buat nama Paket yang menarik dan deskriptif. Contoh: "Paket" VIP", "Sofa Indoor", dll.</p>
                </div>
            </div> 
        </div>
    </div>
    <script>
// Re-initialize Lucide icons
lucide.createIcons();

// Form validation animation
document.getElementById('formPaket').addEventListener('submit', function(e) {
    const btn = this.querySelector('.btn-submit');
    btn.innerHTML = '<i data-lucide="loader" class="animate-spin"></i> Menyimpan...';
    btn.disabled = true;
});

// Add focus effects
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });
});
</script>
