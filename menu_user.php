<?php
include "Koneksi2.php";
// Query untuk mengambil SEMUA menu
$q_menu = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY category, name");
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'qty')) : 0;
?>

<!-- ===== MENU SECTION ===== -->
<section class="content-section">
    
    <!-- Section Header dengan Category Tabs -->
    <div class="section-header">
        <h2>
            <i class="bi bi-grid-3x3-gap-fill"></i>
            Menu Kami
        </h2>
        
        <!-- Category Filter Tabs -->
        <div class="category-tabs">
            <button class="tab-btn active" data-category="all">
                <span>🍽️ Semua</span>
            </button>
            <button class="tab-btn" data-category="Kopi">
                <span>☕ Kopi</span>
            </button>
            <button class="tab-btn" data-category="Non-Kopi">
                <span>🧃 Non-Kopi</span>
            </button>
            <button class="tab-btn" data-category="Makanan">
                <span>🍔 Makanan</span>
            </button>
        </div>
    </div>
    
    <!-- Menu Counter -->
    <div class="menu-counter">
        <span id="menuCount">0</span> menu ditemukan
    </div>

    <!-- Menu Grid -->
    <div class="menu-grid" id="menuGrid">
        <?php 
        $index = 0;
        while ($m = mysqli_fetch_assoc($q_menu)) : 
            $index++;
        ?>
        <div class="menu-card" data-category="<?= htmlspecialchars($m['category']); ?>" style="--delay: <?= min($index, 20); ?>">
            
            <!-- Card Image -->
            <div class="menu-card-image">
                <img src="<?= htmlspecialchars($m['image']); ?>" 
                     alt="<?= htmlspecialchars($m['name']); ?>"
                     loading="lazy">
                <span class="menu-category"><?= htmlspecialchars($m['category']); ?></span>
                <button class="favorite-btn" title="Tambah ke Favorit">
                    <i class="bi bi-heart"></i>
                </button>
            </div>
            
            <!-- Card Body -->
            <div class="menu-card-body">
                <h5><?= htmlspecialchars($m['name']); ?></h5>
                <p class="menu-description">Sajian spesial dari CafeMari-Dua</p>
                
                <div class="menu-footer">
                    <div class="menu-price">
                        <small>Harga</small>
                        <span>Rp <?= number_format($m['price'], 0, ',', '.'); ?></span>
                    </div>
                    <button 
                        class="menu-card-btn btn-order"
                        data-id="<?= $m['id']; ?>"
                        data-nama="<?= htmlspecialchars($m['name']); ?>"
                        data-harga="<?= number_format($m['price'],0,',','.'); ?>"
                    >
                        <i class="bi bi-bag-plus"></i>
                        Pesan
                    </button>
                </div>
            </div>
            
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Empty State (jika tidak ada menu) -->
    <div class="empty-state" id="emptyState" style="display: none;">
        <i class="bi bi-cup-hot"></i>
        <h4>Tidak ada menu</h4>
        <p>Menu dengan kategori ini belum tersedia</p>
    </div>

</section>

<!-- MODAL PESAN - Dipindah ke body untuk menghindari konflik z-index -->
<div class="custom-modal-overlay" id="customModalOverlay">
    <div class="custom-modal" id="customOrderModal">
        <form action="../proses/add_to_cart.php" method="POST" class="custom-modal-content">

            <div class="custom-modal-header">
                <h5 class="custom-modal-title" id="modalNamaMenu">Nama Menu</h5>
                <button type="button" class="custom-modal-close" id="closeModalBtn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="custom-modal-body">
                <input type="hidden" name="menu_id" id="modalMenuId">

                <div class="form-group">
                    <label>Harga</label>
                    <input type="text" class="custom-form-control" id="modalHarga" readonly>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="qty" value="1" min="1" class="custom-form-control" required>
                </div>
            </div>

            <div class="custom-modal-footer">
                <button type="submit" class="custom-btn-submit">
                    <i class="bi bi-cart-plus"></i>
                    Tambah ke Keranjang
                </button>
            </div>

        </form>
    </div>
</div>


<style>
/* ===== CONTENT SECTION ===== */
.content-section {
    padding: 40px;
}

/* ===== SECTION HEADER ===== */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}

.section-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
}

.section-header h2 i {
    color: var(--gold-primary, #d4a574);
}

/* ===== CATEGORY TABS ===== */
.category-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.tab-btn {
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

.tab-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #d4a574 0%, #f0d9b5 50%, #d4a574 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 0;
}

.tab-btn span {
    position: relative;
    z-index: 1;
}

.tab-btn:hover {
    border-color: #d4a574;
    color: #d4a574;
    transform: translateY(-2px);
}

.tab-btn.active {
    border-color: transparent;
    color: #0d0d0d;
    font-weight: 600;
    box-shadow: 0 5px 20px rgba(212, 165, 116, 0.4);
}

.tab-btn.active::before {
    opacity: 1;
}

/* ===== MENU COUNTER ===== */
.menu-counter {
    margin-bottom: 25px;
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.9rem;
}

.menu-counter span {
    color: #d4a574;
    font-weight: 600;
}

/* ===== MENU GRID ===== */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

/* ===== MENU CARD ===== */
.menu-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 0.6s ease backwards;
    animation-delay: calc(var(--delay, 0) * 0.05s);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.menu-card:hover {
    transform: translateY(-10px) scale(1.02);
    border-color: rgba(212, 165, 116, 0.4);
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.4),
        0 0 50px rgba(212, 165, 116, 0.15);
}

/* Card Image */
.menu-card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.menu-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.menu-card:hover .menu-card-image img {
    transform: scale(1.15);
}

.menu-card-image::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, rgba(13, 13, 13, 0.9), transparent);
    pointer-events: none;
}

/* Category Badge */
.menu-category {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 8px 16px;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #d4a574;
    z-index: 2;
}

/* Favorite Button */
.favorite-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 42px;
    height: 42px;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 2;
    transition: all 0.3s ease;
    color: #fff;
}

.favorite-btn:hover {
    background: #d4a574;
    color: #0d0d0d;
    transform: scale(1.1);
}

.favorite-btn.active {
    background: #d4a574;
    color: #0d0d0d;
}

.favorite-btn.active i::before {
    content: "\f415"; /* bi-heart-fill */
}

/* Card Body */
.menu-card-body {
    padding: 25px;
}

.menu-card-body h5 {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: #fff;
    transition: color 0.3s ease;
}

.menu-card:hover .menu-card-body h5 {
    color: #d4a574;
}

.menu-description {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.5);
    margin: 0 0 20px 0;
    line-height: 1.5;
}

/* Menu Footer */
.menu-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.menu-price {
    display: flex;
    flex-direction: column;
}

.menu-price small {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.menu-price span {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 700;
    background: linear-gradient(135deg, #d4a574 0%, #f0d9b5 50%, #d4a574 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Order Button */
.menu-card-btn {
    padding: 12px 20px;
    background: transparent;
    border: 2px solid #d4a574;
    border-radius: 50px;
    color: #d4a574;
    font-family: 'Poppins', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.menu-card-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #d4a574, #f0d9b5);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 0;
}

.menu-card-btn i,
.menu-card-btn span {
    position: relative;
    z-index: 1;
}

.menu-card-btn:hover {
    color: #0d0d0d;
    box-shadow: 0 5px 20px rgba(212, 165, 116, 0.4);
}

.menu-card-btn:hover::before {
    transform: translateX(0);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: rgba(255, 255, 255, 0.5);
}

.empty-state i {
    font-size: 4rem;
    color: #d4a574;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: #fff;
    margin-bottom: 10px;
}

.empty-state p {
    font-size: 0.9rem;
}

/* ===== CUSTOM MODAL (Ganti Bootstrap Modal) ===== */
.custom-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    z-index: 999999;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.custom-modal-overlay.show {
    display: flex;
    opacity: 1;
}

.custom-modal {
    max-width: 500px;
    width: 90%;
    transform: scale(0.9) translateY(20px);
    transition: transform 0.3s ease;
}

.custom-modal-overlay.show .custom-modal {
    transform: scale(1) translateY(0);
}

.custom-modal-content {
    background: rgba(20, 20, 20, 0.98);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(212, 165, 116, 0.3);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
}

.custom-modal-header {
    padding: 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.custom-modal-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: #d4a574;
    margin: 0;
}

.custom-modal-close {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.custom-modal-close:hover {
    background: #d4a574;
    color: #0d0d0d;
    transform: rotate(90deg);
}

.custom-modal-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin-bottom: 8px;
    font-weight: 500;
}

.custom-form-control {
    width: 100%;
    padding: 12px 15px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.custom-form-control:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.08);
    border-color: #d4a574;
    box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.15);
}

.custom-form-control:read-only {
    opacity: 0.7;
    cursor: not-allowed;
}

.custom-modal-footer {
    padding: 20px 25px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.custom-btn-submit {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #d4a574, #f0d9b5);
    border: none;
    border-radius: 12px;
    color: #0d0d0d;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.custom-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
}

.custom-btn-submit:active {
    transform: translateY(0);
}

/* Toast Animation Styles */
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

.toast-notification {
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
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .content-section {
        padding: 25px 20px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .section-header h2 {
        font-size: 1.5rem;
    }
    
    .category-tabs {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 10px;
        flex-wrap: nowrap;
    }
    
    .tab-btn {
        padding: 10px 18px;
        font-size: 0.8rem;
        white-space: nowrap;
    }
    
    .menu-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .custom-modal {
        width: 95%;
    }
    
    .custom-modal-title {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .menu-grid {
        grid-template-columns: 1fr;
    }
    
    .menu-footer {
        flex-direction: column;
        align-items: stretch;
    }
    
    .menu-card-btn {
        justify-content: center;
    }
    
    .custom-modal-header {
        padding: 20px;
    }
    
    .custom-modal-body {
        padding: 20px;
    }
    
    .custom-modal-footer {
        padding: 15px 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Elements
    const tabBtns = document.querySelectorAll('.tab-btn');
    const menuCards = document.querySelectorAll('.menu-card');
    const menuCount = document.getElementById('menuCount');
    const emptyState = document.getElementById('emptyState');
    const menuGrid = document.getElementById('menuGrid');
    
    // Custom Modal Elements
    const modalOverlay = document.getElementById('customModalOverlay');
    const orderModal = document.getElementById('customOrderModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalMenuId = document.getElementById('modalMenuId');
    const modalNamaMenu = document.getElementById('modalNamaMenu');
    const modalHarga = document.getElementById('modalHarga');
    
    // Update menu count
    function updateCount() {
        let count = 0;
        menuCards.forEach(card => {
            if (card.style.display !== 'none') count++;
        });
        menuCount.textContent = count;
        
        // Show/hide empty state
        if (count === 0) {
            emptyState.style.display = 'block';
            menuGrid.style.display = 'none';
        } else {
            emptyState.style.display = 'none';
            menuGrid.style.display = 'grid';
        }
    }
    
    // Initial count
    menuCount.textContent = menuCards.length;
    
    // Category Filter
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active tab
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            // Filter cards with animation
            let visibleIndex = 0;
            menuCards.forEach((card) => {
                const cardCategory = card.dataset.category;
                
                // Reset animation
                card.style.animation = 'none';
                card.offsetHeight; // Trigger reflow
                
                if (category === 'all' || cardCategory === category) {
                    card.style.display = 'block';
                    card.style.setProperty('--delay', Math.min(visibleIndex, 20));
                    card.style.animation = 'fadeInUp 0.5s ease forwards';
                    visibleIndex++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update count after filter
            setTimeout(updateCount, 100);
        });
    });
    
    // Favorite Button Toggle
    const favoriteBtns = document.querySelectorAll('.favorite-btn');
    favoriteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            
            if (this.classList.contains('active')) {
                icon.className = 'bi bi-heart-fill';
                showToast('Ditambahkan ke favorit! ❤️');
            } else {
                icon.className = 'bi bi-heart';
                showToast('Dihapus dari favorit');
            }
        });
    });
    
    // Order Button - Open Custom Modal
    const orderBtns = document.querySelectorAll('.btn-order');
    orderBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Set modal data
            modalMenuId.value = this.dataset.id;
            modalNamaMenu.textContent = this.dataset.nama;
            modalHarga.value = 'Rp ' + this.dataset.harga;
            
            // Reset quantity
            const qtyInput = orderModal.querySelector('input[name="qty"]');
            if (qtyInput) qtyInput.value = 1;
            
            // Show modal
            modalOverlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    });
    
    // Close Modal Function
    function closeModal() {
        modalOverlay.classList.remove('show');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Close button click
    closeModalBtn.addEventListener('click', closeModal);
    
    // Click outside modal to close
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalOverlay.classList.contains('show')) {
            closeModal();
        }
    });
    
    // Form submit handler (optional - untuk feedback)
    const modalForm = orderModal.querySelector('form');
    if (modalForm) {
        modalForm.addEventListener('submit', function(e) {
            // Optional: Show loading state
            const submitBtn = this.querySelector('.custom-btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
            submitBtn.disabled = true;
            
            // Form akan submit secara normal ke proses/add_to_cart.php
            // Jika ingin menggunakan AJAX, uncomment kode di bawah:
            /*
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('proses/add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    showToast('✅ Berhasil ditambahkan ke keranjang!');
                    // Update cart count if needed
                } else {
                    showToast('❌ Gagal menambahkan ke keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('❌ Terjadi kesalahan');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
            */
        });
    }
    
    // Toast Notification Function
    function showToast(message) {
        // Remove existing toast
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        // Create toast
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = message;
        toast.style.animation = 'slideIn 0.3s ease';
        
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
});
</script>