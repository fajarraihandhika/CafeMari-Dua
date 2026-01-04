<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Café Mari-Dua</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts: Playfair Display + Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           RESET & BASE STYLES
           ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            color: #f0d9b5;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        /* ============================================
           ANIMATED BACKGROUND - SUBTLE GOLD PARTICLES
           ============================================ */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .gold-particle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, #d4a574 0%, rgba(212, 165, 116, 0.2) 70%, transparent 100%);
            animation: floatParticles 25s infinite linear;
            opacity: 0.4;
        }

        .particle1 { width: 80px; height: 80px; left: 10%; top: 20%; animation-delay: 0s; }
        .particle2 { width: 120px; height: 120px; right: 15%; top: 60%; animation-delay: 5s; }
        .particle3 { width: 60px; height: 60px; left: 60%; top: 80%; animation-delay: 10s; }
        .particle4 { width: 100px; height: 100px; right: 40%; top: 30%; animation-delay: 15s; }

        @keyframes floatParticles {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.4; }
            90% { opacity: 0.4; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        /* ============================================
           NAVBAR
           ============================================ */
        .navbar {
            background: rgba(13, 13, 13, 0.8) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
            padding: 1rem 0;
            transition: all 0.4s ease;
            border-bottom: 1px solid rgba(212, 165, 116, 0.1);
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            background: rgba(13, 13, 13, 0.95) !important;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #d4a574 !important;
            text-shadow: 0 2px 10px rgba(212, 165, 116, 0.3);
        }

        .navbar-brand:hover {
            color: #f0d9b5 !important;
        }

        .nav-link {
            color: rgba(240, 217, 181, 0.8) !important;
            margin: 0 0.5rem;
            padding: 0.6rem 1.2rem !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #d4a574 !important;
            background: rgba(212, 165, 116, 0.15);
        }

        /* ============================================
           REGISTER CONTAINER
           ============================================ */
        .register-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 20px 100px;
        }

        /* ============================================
           REGISTER CARD - GLASSMORPHISM
           ============================================ */
        .register-card {
            background: rgba(26, 26, 26, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 28px;
            border: 1px solid rgba(212, 165, 116, 0.2);
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
            max-width: 500px;
            width: 100%;
            animation: cardEntry 0.8s ease;
        }

        @keyframes cardEntry {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================
           CARD HEADER
           ============================================ */
        .register-header {
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.2), rgba(212, 165, 116, 0.1));
            padding: 3rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(212, 165, 116, 0.2);
        }

        .register-header .icon-container {
            width: 90px;
            height: 90px;
            background: rgba(13, 13, 13, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: #d4a574;
            border: 2px solid rgba(212, 165, 116, 0.4);
        }

        .register-header h2 {
            font-size: 2.2rem;
            font-weight: 600;
            color: #d4a574;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #f0d9b5;
            opacity: 0.9;
        }

        /* ============================================
           CARD BODY
           ============================================ */
        .register-body {
            padding: 3rem 2.5rem;
        }

        /* ============================================
           FORM STYLES
           ============================================ */
        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #d4a574;
            font-size: 1.2rem;
            z-index: 2;
        }

        .form-control {
            background: rgba(13, 13, 13, 0.5);
            border: 1px solid rgba(212, 165, 116, 0.3);
            border-radius: 14px;
            padding: 1rem 1rem 1rem 3.2rem;
            color: #f0d9b5;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(240, 217, 181, 0.6);
        }

        .form-control:focus {
            background: rgba(13, 13, 13, 0.7);
            border-color: #d4a574;
            box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.2);
            color: #f0d9b5;
        }

        /* Password Strength */
        .password-strength {
            height: 4px;
            background: rgba(212, 165, 116, 0.2);
            border-radius: 2px;
        }

        .password-strength-bar {
            background: #d4a574;
        }

        .password-text {
            color: #f0d9b5;
            font-size: 0.8rem;
        }

        /* ============================================
           SUBMIT BUTTON
           ============================================ */
        .btn-register {
            background: linear-gradient(135deg, #d4a574, #b8975a);
            border: none;
            color: #1a1a1a;
            padding: 1rem;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(212, 165, 116, 0.4);
        }

        .btn-register:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(212, 165, 116, 0.5);
        }

        /* ============================================
           DIVIDER & SOCIAL
           ============================================ */
        .divider span {
            color: #f0d9b5;
            background: rgba(26, 26, 26, 0.4);
        }

        .btn-social {
            background: rgba(13, 13, 13, 0.5);
            border: 1px solid rgba(212, 165, 116, 0.3);
            color: #d4a574;
        }

        .btn-social:hover {
            background: rgba(212, 165, 116, 0.2);
            border-color: #d4a574;
        }

        .login-link {
            color: #f0d9b5;
            border-top: 1px solid rgba(212, 165, 116, 0.2);
        }

        .login-link a {
            color: #d4a574;
        }

        .login-link a:hover {
            color: #f0d9b5;
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            background: rgba(13, 13, 13, 0.9);
            backdrop-filter: blur(10px);
            color: #f0d9b5;
            border-top: 1px solid rgba(212, 165, 116, 0.1);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .register-header {
                padding: 2rem 1.5rem;
            }
            .register-body {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="gold-particle particle1"></div>
        <div class="gold-particle particle2"></div>
        <div class="gold-particle particle3"></div>
        <div class="gold-particle particle4"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                ☕ Café Mari-Dua
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index1.php"><i class="bi bi-house-door me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
                    <li class="nav-item"><a class="nav-link active" href="register.php"><i class="bi bi-person-plus me-1"></i>Daftar</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Register Container -->
    <main class="register-container">
        <div class="register-card" id="registerCard">
            <header class="register-header">
                <div class="icon-container">☕</div>
                <h2>Bergabung Bersama Kami</h2>
                <p>Daftar untuk pengalaman kopi terbaik</p>
            </header>

            <div class="register-body">
                <form method="POST" action="proses_registrasi.php" id="registerForm">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap" required>
                            <i class="bi bi-person"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Alamat Email" required>
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi" required>
                            <i class="bi bi-lock"></i>
                        </div>
                        <div class="password-strength"><div class="password-strength-bar" id="strengthBar"></div></div>
                        <div class="password-text" id="strengthText"></div>
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Konfirmasi Kata Sandi" required>
                            <i class="bi bi-lock-fill"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-register" id="submitBtn">
                        <span class="btn-text"><i class="bi bi-person-plus-fill"></i> Buat Akun</span>
                    </button>

                    <div class="divider my-4"><span>atau daftar dengan</span></div>

                    <div class="social-buttons d-flex justify-content-center gap-3">
                        <button type="button" class="btn-social google"><i class="bi bi-google"></i></button>
                        <button type="button" class="btn-social facebook"><i class="bi bi-facebook"></i></button>
                        <button type="button" class="btn-social twitter"><i class="bi bi-twitter-x"></i></button>
                    </div>

                    <div class="login-link text-center mt-4 pt-3">
                        Sudah punya akun? <a href="login.php">Masuk Sekarang</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container text-center">
            <span>☕ © <?php echo date('Y'); ?> Café Mari-Dua</span><br>
            <small>Brewed with ❤️ & Premium Coffee Beans</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS (dipertahankan fungsi utama) -->
    <script>
        // Password Strength
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthBar.className = 'password-strength-bar';
            strengthText.className = 'password-text';

            if (password.length === 0) {
                strengthText.textContent = '';
                strengthBar.style.width = '0';
            } else if (strength <= 2) {
                strengthBar.style.width = '33%';
                strengthText.textContent = '🔴 Lemah';
            } else if (strength <= 4) {
                strengthBar.style.width = '66%';
                strengthText.textContent = '🟡 Sedang';
            } else {
                strengthBar.style.width = '100%';
                strengthText.textContent = '🟢 Kuat';
            }
        });

        // Form Submission
        const registerForm = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        registerForm.addEventListener('submit', function(e) {
            if (passwordInput.value !== document.getElementById('confirmPassword').value) {
                e.preventDefault();
                alert('Password tidak cocok!');
            } else {
                submitBtn.classList.add('loading');
            }
        });

        // Navbar Scroll
        window.addEventListener('scroll', () => {
            document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
</body>
</html>