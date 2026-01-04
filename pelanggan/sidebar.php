<div class="sidebar">
    <h4>☕ Mari-Dua</h4>

    <a href="dashboard.php" 
       class="<?= empty($_GET['menu']) ? 'active' : '' ?>">
        🏠 Dashboard
    </a>

    <a href="dashboard.php?menu=reservasi"
       class="<?= ($_GET['menu'] ?? '')=='reservasi' ? 'active' : '' ?>">
        🪑 Reservasi
    </a>

    <a href="dashboard.php?menu=riwayat"
       class="<?= ($_GET['menu'] ?? '')=='riwayat' ? 'active' : '' ?>">
        📑 Riwayat Reservasi
    </a>

    <a href="dashboard.php?menu=menu"
       class="<?= ($_GET['menu'] ?? '')=='menu' ? 'active' : '' ?>">
        🍽️ Menu Café
    </a>

    <a href="../logout.php" class="text-warning">
        🚪 Logout
    </a>
</div>
