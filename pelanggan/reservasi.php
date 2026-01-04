<?php
// JANGAN session_start lagi
// JANGAN include sidebar
?>

<style>
/* ===== CSS VARIABLES (Sync dengan Dashboard) ===== */
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
    --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== ANIMATIONS ===== */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shimmer {
    0% { background-position: -200% center; }
    100% { background-position: 200% center; }
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(212, 165, 116, 0.3); }
    50% { box-shadow: 0 0 40px rgba(212, 165, 116, 0.5); }
}

.fade-up {
    animation: fadeUp 0.6s ease forwards;
}

.delay-1 { animation-delay: 0.1s; opacity: 0; }
.delay-2 { animation-delay: 0.2s; opacity: 0; }
.delay-3 { animation-delay: 0.3s; opacity: 0; }

/* ===== HEADER SECTION ===== */
.reservasi-header {
    background: linear-gradient(135deg, rgba(45, 45, 45, 0.95), rgba(26, 26, 26, 0.95)),
                url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=1200') center/cover;
    border-radius: 24px;
    padding: 50px 40px;
    margin-bottom: 40px;
    border: 1px solid var(--glass-border);
    position: relative;
    overflow: hidden;
}

.reservasi-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(212, 165, 116, 0.15), transparent 70%);
    pointer-events: none;
}

.reservasi-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
}

.reservasi-header .header-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-dark));
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.3);
    animation: pulse-glow 3s ease-in-out infinite;
}

.reservasi-header .subtitle {
    font-size: 0.9rem;
    color: var(--accent-gold);
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 10px;
}

.reservasi-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    background: linear-gradient(135deg, var(--text-light), var(--accent-gold-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.reservasi-header p {
    font-size: 1.05rem;
    color: var(--text-muted);
    max-width: 500px;
    line-height: 1.8;
}

/* ===== FEATURES BAR ===== */
.features-bar {
    display: flex;
    gap: 30px;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid var(--glass-border);
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.feature-item i {
    width: 40px;
    height: 40px;
    background: rgba(212, 165, 116, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-gold);
    font-size: 1.1rem;
}

.feature-item span {
    font-size: 0.9rem;
    color: var(--text-muted);
}

/* ===== FORM CARD ===== */
.reservasi-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 45px;
    position: relative;
    overflow: hidden;
}

.reservasi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-gold), var(--accent-gold-dark), var(--accent-gold));
    background-size: 200% auto;
    animation: shimmer 3s linear infinite;
}

.form-title {
    text-align: center;
    margin-bottom: 35px;
}

.form-title h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    color: var(--text-light);
    margin-bottom: 8px;
}

.form-title p {
    font-size: 0.9rem;
    color: var(--text-muted);
}

/* ===== FORM ELEMENTS ===== */
.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--accent-gold-light);
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.form-label i {
    color: var(--accent-gold);
    font-size: 1rem;
}

.form-control {
    width: 100%;
    background: rgba(26, 26, 26, 0.6);
    border: 1px solid var(--glass-border);
    border-radius: 14px;
    padding: 16px 20px;
    color: var(--text-light);
    font-size: 1rem;
    transition: var(--transition-smooth);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-gold);
    background: rgba(26, 26, 26, 0.8);
    box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.15),
                0 10px 30px rgba(0, 0, 0, 0.2);
}

.form-control:hover {
    border-color: rgba(212, 165, 116, 0.5);
}

/* Select Dropdown */
select.form-control {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23d4a574' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6,9 12,15 18,9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 18px;
    padding-right: 50px;
}

select.form-control option {
    background: var(--secondary-dark);
    color: var(--text-light);
    padding: 15px;
}

select.form-control:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Date & Time inputs */
input[type="date"],
input[type="time"] {
    color-scheme: dark;
}

/* ===== ROW GRID ===== */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

/* ===== SUBMIT BUTTON ===== */
.btn-submit {
    width: 100%;
    padding: 18px 30px;
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-dark));
    border: none;
    border-radius: 16px;
    color: var(--primary-dark);
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-top: 15px;
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: 0.5s;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(212, 165, 116, 0.4);
}

.btn-submit:hover::before {
    left: 100%;
}

.btn-submit:active {
    transform: translateY(0);
}

.btn-submit i {
    font-size: 1.2rem;
}

/* ===== INFO CARD ===== */
.info-card {
    background: rgba(212, 165, 116, 0.1);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 20px;
    margin-top: 25px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.info-card i {
    color: var(--accent-gold);
    font-size: 1.3rem;
    margin-top: 2px;
}

.info-card p {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.6;
}

.info-card strong {
    color: var(--accent-gold-light);
}

/* ===== LOADING STATE ===== */
.form-control.loading {
    background-image: linear-gradient(90deg, 
        rgba(26, 26, 26, 0.6) 25%, 
        rgba(45, 45, 45, 0.8) 50%, 
        rgba(26, 26, 26, 0.6) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .reservasi-header {
        padding: 35px 25px;
    }
    
    .reservasi-header h2 {
        font-size: 1.8rem;
    }
    
    .features-bar {
        flex-direction: column;
        gap: 15px;
    }
    
    .reservasi-card {
        padding: 30px 25px;
    }
}
</style>

<!-- ===== HEADER SECTION ===== -->
<section class="reservasi-header fade-up">
    <div class="header-icon">🪑</div>
    <p class="subtitle">✨ Book Your Table</p>
    <h2>Reservasi Meja</h2>
    <p>
        Nikmati suasana premium Café Mari-Dua dengan reservasi online. 
        Pilih meja favorit dan waktu terbaik untuk pengalaman tak terlupakan.
    </p>
    
    <div class="features-bar">
        <div class="feature-item">
            <i class="bi bi-clock"></i>
            <span>Konfirmasi Instan</span>
        </div>
        <div class="feature-item">
            <i class="bi bi-shield-check"></i>
            <span>Reservasi Aman</span>
        </div>
        <div class="feature-item">
            <i class="bi bi-stars"></i>
            <span>Meja Premium</span>
        </div>
    </div>
</section>

<!-- ===== FORM SECTION ===== -->
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <div class="reservasi-card fade-up delay-1">
            
            <div class="form-title">
                <h4>☕ Form Reservasi</h4>
                <p>Lengkapi data berikut untuk melakukan reservasi meja</p>
            </div>

            <form action="simpan.php" method="POST" id="reservasiForm">
                
                <!-- Nama Lengkap -->
                <div class="form-group fade-up delay-1">
                    <label class="form-label">
                        <i class="bi bi-person-fill"></i>
                        Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        name="nama" 
                        class="form-control" 
                        placeholder="Masukkan nama lengkap Anda"
                        required
                    >
                </div>

                <!-- Email -->
                <div class="form-group fade-up delay-1">
                    <label class="form-label">
                        <i class="bi bi-envelope-fill"></i>
                        Alamat Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="contoh@email.com"
                        required
                    >
                </div>

                <!-- Tanggal & Jam -->
                <div class="form-row fade-up delay-2">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calendar-event-fill"></i>
                            Tanggal Reservasi
                        </label>
                        <input 
                            type="date" 
                            id="tanggal" 
                            name="tanggal" 
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-clock-fill"></i>
                            Jam Reservasi
                        </label>
                        <input 
                            type="time" 
                            name="jam" 
                            class="form-control"
                            required
                        >
                    </div>
                </div>

                <!-- Pilih Meja -->
                <div class="form-group fade-up delay-2">
                    <label class="form-label">
                        <i class="bi bi-grid-fill"></i>
                        Pilih Meja
                    </label>
                    <select name="meja_id" id="meja" class="form-control" disabled required>
                        <option value="">📅 Pilih tanggal terlebih dahulu</option>
                    </select>
                </div>

                <!-- Paket Reservasi -->
                <div class="form-group fade-up delay-3">
                    <label class="form-label">
                        <i class="bi bi-box-seam-fill"></i>
                        Paket Reservasi
                    </label>
                    <select name="paket_id" class="form-control" required>
                        <option value="">-- Pilih Paket --</option>
                        <?php
                        $q = mysqli_query($koneksi, "SELECT * FROM paket_reservasi ORDER BY harga ASC");
                        while ($p = mysqli_fetch_assoc($q)) {
                            $harga_format = number_format($p['harga'], 0, ',', '.');
                            echo "<option value='" . htmlspecialchars($p['paket_id']) . "'>
                                    {$p['nama_paket']} — Rp {$harga_format}
                                  </option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit fade-up delay-3">
                    <i class="bi bi-calendar-check-fill"></i>
                    Konfirmasi Reservasi
                </button>

                <!-- Info Card -->
                <div class="info-card fade-up delay-3">
                    <i class="bi bi-info-circle-fill"></i>
                    <p>
                        <strong>Catatan:</strong> Reservasi akan otomatis dibatalkan jika Anda tidak datang 
                        dalam waktu 30 menit dari jam yang ditentukan. Silakan hubungi kami jika ada perubahan jadwal.
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('tanggal').setAttribute('min', today);

// Handle tanggal change
document.getElementById('tanggal').addEventListener('change', function() {
    const mejaSelect = document.getElementById('meja');
    
    // Show loading state
    mejaSelect.innerHTML = '<option value="">⏳ Memuat meja tersedia...</option>';
    mejaSelect.classList.add('loading');
    mejaSelect.disabled = true;
    
    // Fetch available tables
    fetch('ajax_meja.php?tanggal=' + this.value)
        .then(res => res.text())
        .then(data => {
            mejaSelect.innerHTML = data;
            mejaSelect.classList.remove('loading');
            mejaSelect.disabled = false;
        })
        .catch(error => {
            mejaSelect.innerHTML = '<option value="">❌ Gagal memuat data</option>';
            mejaSelect.classList.remove('loading');
            console.error('Error:', error);
        });
});

// Form validation visual feedback
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value && this.checkValidity()) {
            this.style.borderColor = 'rgba(34, 197, 94, 0.5)';
        }
    });
    
    input.addEventListener('focus', function() {
        this.style.borderColor = '';
    });
});

// Animate elements on load
document.querySelectorAll('.fade-up').forEach((el, index) => {
    el.style.opacity = '0';
    setTimeout(() => {
        el.style.opacity = '1';
    }, 100 * index);
});
</script>
