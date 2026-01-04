<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top"
     style="background-color:#4e342e;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php">☕ Cafe Mari-Dua</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item"><a class="nav-link" href="../index1.php">Home</a></li>
                

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') { ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../logout.php">Logout</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../logout.php">Logout</a>
                    </li>
                <?php } ?>

            </ul>
        </div>
    </div>
</nav>
