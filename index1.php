<?php
include "Koneksi2.php";
/// Query untuk ambil menu terlaris berdasarkan total qty dari detail transaksi
$query = "
    SELECT 
        m.id,
        m.name,
        m.price,
        m.image,                    -- ← kolom foto
        COALESCE(SUM(dt.qty), 0) AS total_terjual
    FROM menu m
    LEFT JOIN detail_transaksi dt ON m.id = dt.menu_id
    GROUP BY m.id
    ORDER BY total_terjual DESC
    LIMIT 3
";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error query menu favorit: " . mysqli_error($koneksi));
}

$menu_favorit = [];
while ($row = mysqli_fetch_assoc($result)) {
    $menu_favorit[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cafe Mari-Dua</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap & AOS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
    overflow-x: hidden;
    color: #f0d9b5;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Playfair Display', serif;
}

/* ================= NAVBAR ================= */
.navbar {
    background: rgba(13, 13, 13, 0.8) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    transition: all .5s cubic-bezier(0.4, 0, 0.2, 1);
    border-bottom: 1px solid rgba(212, 165, 116, 0.1);
}

.navbar.scrolled {
    background: rgba(13, 13, 13, 0.95) !important;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
    border-bottom: 1px solid rgba(212, 165, 116, 0.3);
}

.navbar-brand {
    color: #d4a574 !important;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1.8rem;
    letter-spacing: 2px;
    text-shadow: 0 2px 10px rgba(212, 165, 116, 0.3);
}

.nav-link {
    color: rgba(240, 217, 181, 0.8) !important;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 0.85rem;
    padding: 0.5rem 1.2rem !important;
    position: relative;
    transition: all .3s ease;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #d4a574, #f0d9b5);
    transition: all .3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after {
    width: 80%;
}

.nav-link:hover,
.nav-link.active {
    color: #d4a574 !important;
    background: rgba(212, 165, 116, 0.15);
}

/* ================= HERO ================= */
.hero {
    min-height: 100vh;
    background:
        linear-gradient(135deg, rgba(13, 13, 13, 0.85), rgba(26, 26, 26, 0.75)),
        url('https://images.unsplash.com/photo-1509042239860-f550ce710b93');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #f0d9b5;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 50%, rgba(212, 165, 116, 0.15) 0%, transparent 50%);
    animation: pulse 8s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}

.hero h1 {
    font-size: 5rem;
    font-weight: 700;
    letter-spacing: 3px;
    background: linear-gradient(135deg, #d4a574 0%, #f0d9b5 50%, #d4a574 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1.5rem;
    text-shadow: 0 0 60px rgba(212, 165, 116, 0.3);
}

.hero .lead {
    font-size: 1.3rem;
    letter-spacing: 2px;
    color: #f0d9b5;
    font-weight: 300;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-divider {
    width: 80px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #d4a574, transparent);
    margin: 2rem auto;
}

/* ================= BUTTON ================= */
.btn-coffee {
    background: linear-gradient(135deg, #d4a574 0%, #b8975a 100%);
    color: #1a1a1a;
    border: none;
    padding: 1rem 3rem;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-size: 0.9rem;
    border-radius: 50px;
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.4);
    transition: all .4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.btn-coffee::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width .6s, height .6s;
}

.btn-coffee:hover::before {
    width: 300px;
    height: 300px;
}

.btn-coffee:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(212, 165, 116, 0.6);
}

/* ================= HIGHLIGHT CARDS ================= */
.highlight-section {
    background: linear-gradient(135deg, #0d0d0d 0%, #1a1a1a 100%);
    margin-top: -80px;
    position: relative;
    z-index: 10;
    padding: 100px 0;
}

.highlight-card {
    background: rgba(26, 26, 26, 0.4);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(212, 165, 116, 0.2);
    border-radius: 20px;
    padding: 2.5rem 1.5rem;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
    transition: all .4s ease;
}

.highlight-card:nth-child(odd) {
    background: rgba(26, 26, 26, 0.6);
}

.highlight-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #d4a574, #f0d9b5);
    transform: scaleX(0);
    transition: transform .4s ease;
}

.highlight-card:hover::before {
    transform: scaleX(1);
}

.highlight-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
}

.highlight-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #d4a574;
}

.highlight-card h6 {
    color: #f0d9b5;
    font-weight: 600;
    letter-spacing: 1px;
}

/* ================= STATS SECTION ================= */
.stats-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
    position: relative;
    overflow: hidden;
    padding: 100px 0;
}

.stats-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(212, 165, 116, 0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.stats-card h1 {
    font-size: 4rem;
    font-weight: 700;
    background: linear-gradient(135deg, #d4a574 0%, #f0d9b5 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stats-card p {
    color: rgba(240, 217, 181, 0.7);
    font-size: 1rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 300;
}

/* ================= MENU SECTION ================= */
.menu-section {
    background: linear-gradient(135deg, #0d0d0d 0%, #1a1a1a 100%);
    padding: 100px 0;
}

.section-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: #d4a574;
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
}

.section-subtitle {
    color: rgba(240, 217, 181, 0.8);
    font-weight: 300;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.title-divider {
    width: 100px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #d4a574, transparent);
    margin: 1.5rem auto 3rem;
}

.menu-card {
    background: rgba(26, 26, 26, 0.4);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(212, 165, 116, 0.2);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
    transition: all .5s ease;
    height: 100%;
}

.menu-card:nth-child(odd) {
    background: rgba(26, 26, 26, 0.6);
}

.menu-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.8);
}

.menu-card-img-wrapper {
    position: relative;
    overflow: hidden;
    height: 280px;
}

.menu-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .6s ease;
}

.menu-card:hover img {
    transform: scale(1.15);
}

.menu-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(13, 13, 13, 0.8) 0%, transparent 60%);
    opacity: 0;
    transition: opacity .4s ease;
}

.menu-card:hover .menu-card-overlay {
    opacity: 1;
}

.menu-card-body {
    padding: 2rem;
}

.menu-card h5 {
    color: #f0d9b5;
    font-weight: 600;
    font-size: 1.4rem;
    margin-bottom: 0.5rem;
}

.menu-card-price {
    color: #d4a574;
    font-weight: 600;
    font-size: 1.2rem;
}

/* ================= TESTIMONIAL SECTION ================= */
.testimonial-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
    padding: 100px 0;
}

.testimonial-card {
    background: rgba(26, 26, 26, 0.4);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid rgba(212, 165, 116, 0.2);
    transition: all .4s ease;
}

.testimonial-card:nth-child(even) {
    background: rgba(26, 26, 26, 0.6);
}

.testimonial-card:hover {
    transform: translateY(-10px);
    background: rgba(26, 26, 26, 0.6);
    border-color: rgba(212, 165, 116, 0.4);
}

.testimonial-text {
    color: #f0d9b5;
    font-style: italic;
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.testimonial-author {
    color: #d4a574;
    font-weight: 600;
    font-size: 1rem;
}

.testimonial-role {
    color: rgba(240, 217, 181, 0.7);
    font-size: 0.9rem;
}

.quote-icon {
    font-size: 4rem;
    color: rgba(212, 165, 116, 0.2);
    margin-bottom: 1rem;
}

/* ================= CTA SECTION ================= */
.cta {
    background: linear-gradient(135deg, #d4a574 0%, #b8975a 100%);
    position: relative;
    overflow: hidden;
    padding: 100px 0;
}

.cta::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.cta h2 {
    font-size: 3rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 1rem;
}

.cta p {
    color: rgba(26, 26, 26, 0.9);
    font-size: 1.2rem;
}

.btn-cta {
    background: #1a1a1a;
    color: #d4a574;
    padding: 1rem 3rem;
    border-radius: 50px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    transition: all .4s ease;
    border: 2px solid transparent;
}

.btn-cta:hover {
    background: transparent;
    color: #1a1a1a;
    border-color: #1a1a1a;
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
}

/* ================= GALLERY SECTION ================= */
.gallery-section {
    background: linear-gradient(135deg, #0d0d0d 0%, #1a1a1a 100%);
    padding: 100px 0;
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    height: 300px;
    cursor: pointer;
}

.gallery-item:nth-child(odd) .gallery-overlay {
    background: linear-gradient(135deg, rgba(212, 165, 116, 0.85), rgba(184, 151, 90, 0.85));
}

.gallery-item:nth-child(even) .gallery-overlay {
    background: linear-gradient(135deg, rgba(184, 151, 90, 0.9), rgba(212, 165, 116, 0.9));
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .6s ease;
}

.gallery-item:hover img {
    transform: scale(1.2);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(212, 165, 116, 0.9), rgba(184, 151, 90, 0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .4s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-text {
    color: #1a1a1a;
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
}

/* ================= FOOTER ================= */
footer {
    background: #0d0d0d;
    color: rgba(240, 217, 181, 0.7);
    border-top: 1px solid rgba(212, 165, 116, 0.2);
    padding: 80px 0;
}

.footer-brand {
    color: #d4a574;
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 1rem;
}

.footer-link {
    color: rgba(240, 217, 181, 0.7);
    text-decoration: none;
    transition: color .3s ease;
}

.footer-link:hover {
    color: #d4a574;
}

/* ================= SCROLL TO TOP ================= */
.scroll-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #d4a574, #b8975a);
    color: #1a1a1a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all .4s ease;
    z-index: 1000;
    box-shadow: 0 10px 30px rgba(212, 165, 116, 0.5);
}

.scroll-top.visible {
    opacity: 1;
    visibility: visible;
}

.scroll-top:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(212, 165, 116, 0.7);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .hero h1 { font-size: 3rem; }
    .section-title { font-size: 2.5rem; }
    .cta h2 { font-size: 2rem; }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">MARI-DUA</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav" style="background: rgba(212,175,55,0.2);">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="#gallery">Galeri</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimonial">Testimoni</a></li>        
        <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
        <?php if(isset($_SESSION['role'])): ?>
            <?php if($_SESSION['role']=='user'): ?>
              <li class="nav-item"><a class="nav-link" href="pelanggan/dashboard.php">Dashboard</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="admin/dashboard.php">Admin</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <div class="container" data-aos="fade-up">
      <h1>CAFE MARI-DUA</h1>
      <div class="hero-divider"></div>
      <p class="lead">Pengalaman Kopi Premium dalam Suasana yang Elegan</p>
      <a href="#menu" class="btn btn-coffee btn-lg mt-4">Jelajahi Menu Kami</a>
    </div>
  </div>
</section>

<!-- HIGHLIGHT -->

<!-- STATS -->
<section class="stats-section py-5">
  <div class="container py-4">
    <div class="row text-center g-4">
      <div class="col-md-3 stats-card" data-aos="fade-up">
        <h1 class="counter" data-target="35">0</h1>
        <p>Menu Tersedia</p>
      </div>
      <div class="col-md-3 stats-card" data-aos="fade-up" data-aos-delay="100">
        <h1 class="counter" data-target="20">0</h1>
        <p>Meja Nyaman</p>
      </div>
      <div class="col-md-3 stats-card" data-aos="fade-up" data-aos-delay="200">
        <h1 class="counter" data-target="1200">0</h1>
        <p>Pelanggan Puas</p>
      </div>
      <div class="col-md-3 stats-card" data-aos="fade-up" data-aos-delay="300">
        <h1 class="counter" data-target="5">0</h1>
        <p>Tahun Berpengalaman</p>
      </div>
    </div>
  </div>
</section>
<!-- ABOUT -->
<section id="about" class="py-5">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-md-6" data-aos="fade-right">
        <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31" alt="About" style="border-radius: 20px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
      </div>
      <div class="col-md-6" data-aos="fade-left">
        <p class="section-subtitle">Our Story</p>
        <h2 class="section-title mb-4">Tentang Mari-Dua</h2>
        <p style="color:rgb(249, 240, 225); line-height: 1.8; font-size: 1.05rem;">
          Cafe Mari-Dua hadir untuk menghadirkan pengalaman kopi premium dalam suasana yang hangat dan elegan. Kami menggunakan biji kopi pilihan terbaik yang dipanggang sempurna untuk menciptakan cita rasa yang tak terlupakan.
        </p>
        <p style="color:rgb(249, 240, 225); line-height: 1.8; font-size: 1.05rem;">
          Dengan desain interior modern yang memadukan kenyamanan dan estetika, kami menciptakan ruang sempurna untuk bekerja, bersantai, atau bertemu dengan orang-orang tercinta.
        </p>
      </div>
      
    </div>
  </div>
</section>
<!-- MENU -->
<section id="menu" class="menu-section py-5">
  <div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="section-title">Best Sellers</h2>
    </div>

    <?php if (empty($menu_favorit)): ?>
      <div class="text-center py-5">
        <p class="text-muted fs-5">Belum ada data transaksi. Semua menu sama favoritnya! ☕</p>
      </div>
    <?php else: ?>
      <div class="row g-4">
      <?php 
$delay = 0;
$rank  = 1;

foreach ($menu_favorit as $menu): 
    // Format harga ke Rupiah
    $harga = "Rp " . number_format($menu['price'], 0, ',', '.');

    // AMBIL ID DARI DATA MENU (BUKAN $_GET)
    $menu_id = $menu['id'];

    $query = mysqli_query($koneksi, "SELECT image FROM menu WHERE id = '$menu_id'");
    $data  = mysqli_fetch_assoc($query);

    $foto = $data['image'];
?>

    <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="<?= $delay ?>">
        <div class="menu-card position-relative">
            <!-- Badge Ranking untuk 3 menu terlaris -->
            <?php if ($rank <= 3): ?>
                <div class="position-absolute top-0 start-0 m-3 z-3">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-trophy-fill me-1"></i> #<?= $rank ?> Terlaris
                    </span>
                </div>
            <?php endif; ?>

            <div class="menu-card-img-wrapper">
                <img src="<?= $foto ?>" 
                     alt="<?= htmlspecialchars($menu['name']) ?>" 
                     class="w-100 h-100 object-fit-cover"
                     loading="lazy">
                <div class="menu-card-overlay"></div>
            </div>

            <div class="menu-card-body text-center">
                <h5 class="mb-2"><?= htmlspecialchars($menu['name']) ?></h5>
                <p class="menu-card-price fw-bold mb-2"><?= $harga ?></p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-bag-check-fill text-warning me-1"></i>
                    <?= number_format($menu['total_terjual']) ?> kali dipesan
                </p>
            </div>
        </div>
    </div>
<?php 
    $delay += 100;
    $rank++;
endforeach; 
?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- GALLERY -->
<section id="gallery" class="gallery-section py-5">
  <div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <p class="section-subtitle">Our Atmosphere</p>
      <h2 class="section-title">Galeri Cafe</h2>
      <div class="title-divider"></div>
    </div>
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up">
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24" alt="Interior">
          <div class="gallery-overlay">
            <span class="gallery-text">Interior</span>
          </div>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb" alt="Barista">
          <div class="gallery-overlay">
            <span class="gallery-text">Barista</span>
          </div>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="gallery-item">
          <img src="  https://images.unsplash.com/photo-1689075326462-581d7705c0ef?q=80&w=2189&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Exterior">
          <div class="gallery-overlay">
            <span class="gallery-text">Exterior</span>
          </div>
        </div>
      </div>
    
  </div>
</section>
<!-- TESTIMONIAL -->
<section id="testimonial" class="testimonial-section py-5">
  <div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <p class="section-subtitle" style="color: #999;">What They Say</p>
      <h2 class="section-title" style="color: #f5f5f5;">Testimoni Pelanggan</h2>
      <div class="title-divider"></div>
    </div>
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up">
        <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p class="testimonial-text">Tempat yang sempurna untuk bekerja sambil menikmati kopi terbaik di kota. Suasananya sangat nyaman!</p>
          <p class="testimonial-author mb-0">Sarah Wijaya</p>
          <p class="testimonial-role">Digital Marketer</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
      <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p class="testimonial-text">WFC-able soalnya tersedia wifi dan jaringan nya baguuss jugaa. Menu nya beragam.</p>
          <p class="testimonial-author mb-0">Hala</p>
          <p class="testimonial-role">Content Creator</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
      <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p class="testimonial-text">Tempat nya bagus bangett, makanan dan minumannya recommended!!</p>
          <p class="testimonial-author mb-0">Mahesa</p>
          <p class="testimonial-role">Photographer</p>
        </div>
      </div>
        
        </section>
<!-- CTA -->


<section class="cta py-5 text-center">
  <div class="cta-content">
    <div class="container py-5" data-aos="zoom-in">
      <h2>Siap Merasakan Pengalaman Istimewa?</h2>
      <p class="mb-4">Reservasi meja Anda sekarang dan nikmati momen berharga bersama kami</p>
      
      <?php
      // Ganti 'user_id' dengan nama session yang Anda gunakan saat login (misal: $_SESSION['user_id'], $_SESSION['logged_in'], dll.)
      // Contoh umum: setelah login sukses, Anda set $_SESSION['user_id'] = $id_user;
      $is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
      
      if ($is_logged_in) {
          // Jika sudah login → langsung ke halaman reservasi
          $reservasi_url = 'pelanggan/reservasi.php'; // Ganti dengan nama file halaman reservasi Anda
          echo '<a href="' . $reservasi_url . '" class="btn btn-cta btn-lg">Reservasi Sekarang</a>';
      } else {
          // Jika belum login → ke halaman login, dengan parameter return_url agar setelah login balik ke reservasi
          $current_page = urlencode('reservasi.php'); // atau gunakan $_SERVER['REQUEST_URI'] jika CTA di halaman lain
          $login_url = 'login.php?redirect_to=' . $current_page;
          echo '<a href="' . $login_url . '" class="btn btn-cta btn-lg">Reservasi Sekarang</a>';
      }
      ?>
    </div>
  </div>
</section>
<!-- CONTACT -->
<section id="contact" class="py-5 bg-light">
  <div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <p class="section-subtitle">Get In Touch</p>
      <h2 class="section-title">Hubungi Kami</h2>
      <div class="title-divider"></div>
    </div>
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up">
        <div class="text-center p-4">
          <div style="font-size: 3rem; color: #d4af37;">📍</div>
          <h5 style="color: #2c2c2c; margin-top: 1rem;">Lokasi</h5>
          <p class="text-muted">Jl. Kopi Premium No.7<br>Jakarta Selatan, 15518</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="text-center p-4">
          <div style="font-size: 3rem; color: #d4af37;">📞</div>
          <h5 style="color: #2c2c2c; margin-top: 1rem;">Telepon</h5>
          <p class="text-muted">0812-3456-7890<br>021-1234-5678</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="text-center p-4">
          <div style="font-size: 3rem; color: #d4af37;">✉️</div>
          <h5 style="color: #2c2c2c; margin-top: 1rem;">Email</h5>
          <p class="text-muted">info@cafemaridua.com<br>reservasi@cafemaridua.com</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4" data-aos="fade-up">
        <h3 class="footer-brand">MARI-DUA</h3>
        <p style="color: #666; line-height: 1.8;">Pengalaman kopi premium dalam suasana yang elegan dan nyaman.</p>
      </div>
      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <h5 style="color: #d4af37; margin-bottom: 1.5rem;">Quick Links</h5>
        <ul style="list-style: none; padding: 0;">
          <li style="margin-bottom: 0.5rem;"><a href="#" class="footer-link">Home</a></li>
          <li style="margin-bottom: 0.5rem;"><a href="#menu" class="footer-link">Menu</a></li>
          <li style="margin-bottom: 0.5rem;"><a href="#about" class="footer-link">Tentang</a></li>
          <li style="margin-bottom: 0.5rem;"><a href="#contact" class="footer-link">Kontak</a></li>
        </ul>
      </div>
      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
        <h5 style="color: #d4af37; margin-bottom: 1.5rem;">Jam Operasional</h5>
        <p style="color: #666;">Senin - Jumat: 08:00 - 23:00<br>Sabtu - Minggu: 09:00 - 00:00</p>
      </div>
    </div>
    <hr style="border-color: rgba(212,175,55,0.2); margin: 2rem 0;">
    <div class="text-center">
      <p style="color: #666; margin: 0;">&copy; 2025 Cafe Mari-Dua. All Rights Reserved.</p>
    </div>
  </div>
</footer>

<!-- SCROLL TO TOP -->
<div class="scroll-top" id="scrollTop">
  <span style="font-size: 1.5rem;">↑</span>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
// Initialize AOS
AOS.init({ 
  duration: 1000, 
  once: true, 
  easing: 'ease-out-cubic',
  offset: 100
});

// Navbar scroll effect
window.addEventListener("scroll", () => {
  document.querySelector(".navbar").classList.toggle("scrolled", window.scrollY > 50);
  
  // Scroll to top button
  const scrollTop = document.getElementById('scrollTop');
  scrollTop.classList.toggle('visible', window.scrollY > 300);
});

// Scroll to top functionality
document.getElementById('scrollTop').addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Counter animation
const counters = document.querySelectorAll('.counter');

const runCounter = () => {
  counters.forEach(counter => {
    const target = +counter.getAttribute('data-target');
    let current = 0;
    const increment = target / 100;

    const updateCounter = () => {
      current += increment;
      if (current < target) {
        counter.innerText = Math.ceil(current);
        requestAnimationFrame(updateCounter);
      } else {
        counter.innerText = target;
      }
    };

    updateCounter();
  });
};

// Run counter once on scroll
let counterPlayed = false;
window.addEventListener('scroll', () => {
  const section = document.querySelector('.stats-section');
  if (section && !counterPlayed && section.getBoundingClientRect().top < window.innerHeight) {
    runCounter();
    counterPlayed = true;
  }
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});
</script>

</body>
</html>
</section>

