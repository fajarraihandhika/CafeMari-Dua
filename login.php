<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Café Mari-Dua</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold-primary: #d4a574;
            --gold-light: #f0d9b5;
            --dark-bg: #0d0d0d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 50%, #1a1a1a 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Effects */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(212, 165, 116, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
            animation: backgroundMove 20s linear infinite;
        }

        @keyframes backgroundMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Coffee Particles */
        .coffee-particle {
            position: fixed;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, 
                rgba(212, 165, 116, 0.15), 
                rgba(212, 165, 116, 0.05));
            pointer-events: none;
            z-index: 0;
        }

        .particle-1 { width: 150px; height: 150px; left: 10%; top: 20%; animation: float 20s infinite ease-in-out; }
        .particle-2 { width: 100px; height: 100px; right: 15%; top: 60%; animation: float 25s infinite ease-in-out 5s; }
        .particle-3 { width: 80px; height: 80px; left: 60%; top: 80%; animation: float 22s infinite ease-in-out 10s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 0.6; }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            max-width: 480px;
            width: 100%;
        }

        /* Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.5),
                0 0 100px rgba(212, 165, 116, 0.1);
            animation: cardEntry 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntry {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Card Header */
        .login-header {
            background: linear-gradient(135deg, rgba(212, 165, 116, 0.15), rgba(212, 165, 116, 0.05));
            padding: 3rem 2rem 2.5rem;
            text-align: center;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .icon-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            animation: iconPulse 2s infinite ease-in-out;
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.3);
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .login-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
        }

        /* Card Body */
        .login-body {
            padding: 2.5rem 2rem;
        }

        /* Alert Messages */
        .alert-custom {
            padding: 1rem 1.25rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: alertSlide 0.5s ease;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        @keyframes alertSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            color: #fff;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.15);
        }

        .form-control:focus + i {
            color: var(--gold-primary);
            transform: translateY(-50%) scale(1.1);
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            padding: 0;
            transition: all 0.3s ease;
            z-index: 2;
            font-size: 1.1rem;
        }

        .password-toggle:hover {
            color: var(--gold-primary);
            transform: translateY(-50%) scale(1.1);
        }

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--gold-primary);
            cursor: pointer;
        }

        .remember-me label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            cursor: pointer;
            user-select: none;
        }

        .forgot-password {
            color: var(--gold-primary);
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            border: none;
            border-radius: 15px;
            color: #0d0d0d;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 30px rgba(212, 165, 116, 0.3);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 165, 116, 0.4);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-text {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Loading State */
        .btn-login.loading .btn-text {
            opacity: 0;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            top: 50%;
            left: 50%;
            margin: -12px 0 0 -12px;
            border: 3px solid rgba(0, 0, 0, 0.3);
            border-top-color: #0d0d0d;
            border-radius: 50%;
            animation: buttonSpin 0.8s linear infinite;
        }

        @keyframes buttonSpin {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        }

        .divider span {
            padding: 0 1rem;
        }

        /* Social Buttons */
        .social-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-social {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-social:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-social.google:hover { 
            border-color: #ea4335; 
            color: #ea4335;
            background: rgba(234, 67, 53, 0.15);
        }
        
        .btn-social.facebook:hover { 
            border-color: #1877f2; 
            color: #1877f2;
            background: rgba(24, 119, 242, 0.15);
        }
        
        .btn-social.twitter:hover { 
            border-color: #1da1f2; 
            color: #1da1f2;
            background: rgba(29, 161, 242, 0.15);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
        }

        .register-link a {
            color: var(--gold-primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .register-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--gold-primary), var(--gold-light));
            transition: width 0.3s ease;
        }

        .register-link a:hover {
            color: var(--gold-light);
        }

        .register-link a:hover::after {
            width: 100%;
        }

        /* Back to Home Link */
        .back-home {
            position: fixed;
            top: 30px;
            left: 30px;
            z-index: 100;
        }

        .back-home a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-home a:hover {
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            border-color: transparent;
            color: #0d0d0d;
            transform: translateX(-5px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-card {
                margin: 0 1rem;
                border-radius: 24px;
            }

            .login-header {
                padding: 2.5rem 1.5rem 2rem;
            }

            .login-header h2 {
                font-size: 1.6rem;
            }

            .icon-container {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
            }

            .form-control {
                padding: 0.875rem 0.875rem 0.875rem 3rem;
            }

            .back-home {
                top: 20px;
                left: 20px;
            }

            .back-home a {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .login-header h2 {
                font-size: 1.4rem;
            }

            .btn-login {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
            }

            .social-buttons {
                gap: 0.75rem;
            }

            .btn-social {
                width: 45px;
                height: 45px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <!-- Background Particles -->
    <div class="coffee-particle particle-1"></div>
    <div class="coffee-particle particle-2"></div>
    <div class="coffee-particle particle-3"></div>

    <!-- Back to Home -->
    <div class="back-home">
        <a href="index1.php">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Login Container -->
    <main class="login-container">
        <div class="login-card">
            
            <!-- Card Header -->
            <header class="login-header">
                <div class="icon-container">
                    <i class="bi bi-cup-hot-fill"></i>
                </div>
                <h2>Selamat Datang Kembali</h2>
                <p>Masuk ke akun Anda untuk melanjutkan</p>
            </header>

            <!-- Card Body -->
            <div class="login-body">
                
                <!-- Alert Messages -->
                <?php if(isset($_GET['error'])): ?>
                <div class="alert-custom alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
                <?php endif; ?>

                <?php if(isset($_GET['success'])): ?>
                <div class="alert-custom alert-success">
                    <i class="bi bi-check-circle"></i>
                    <span><?php echo htmlspecialchars($_GET['success']); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" action="proses_login.php" id="loginForm">
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                class="form-control" 
                                placeholder="Alamat Email"
                                autocomplete="email"
                                required
                            >
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-control" 
                                placeholder="Kata Sandi"
                                autocomplete="current-password"
                                required
                            >
                            <i class="bi bi-lock"></i>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                        <a href="forgot_password.php" class="forgot-password">Lupa kata sandi?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="submitBtn">
                        <span class="btn-text">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Masuk
                        </span>
                    </button>

                    <!-- Divider -->
                    <div class="divider">
                        <span>atau masuk dengan</span>
                    </div>

                    <!-- Social Buttons -->
                    <div class="social-buttons">
                        <button type="button" class="btn-social google" title="Masuk dengan Google">
                            <i class="bi bi-google"></i>
                        </button>
                        <button type="button" class="btn-social facebook" title="Masuk dengan Facebook">
                            <i class="bi bi-facebook"></i>
                        </button>
                        <button type="button" class="btn-social twitter" title="Masuk dengan Twitter">
                            <i class="bi bi-twitter-x"></i>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="register-link">
                        Belum punya akun? <a href="register.php">Daftar Sekarang</a>
                    </div>
                    
                </form>
            </div>
            
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password Visibility Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });

        // Form Submission Handler
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');

        loginForm.addEventListener('submit', function(e) {
            submitBtn.classList.add('loading');
        });

        // Reset Loading State
        window.addEventListener('pageshow', function() {
            submitBtn.classList.remove('loading');
        });

        // Input Focus Enhancement
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.closest('.input-wrapper').classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.closest('.input-wrapper').classList.remove('focused');
            });
        });
    </script>

</body>
</html>