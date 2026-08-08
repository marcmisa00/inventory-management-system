<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login</title>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background shapes */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }

        body::before {
            width: 300px;
            height: 300px;
            background: #ffffff;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        body::after {
            width: 400px;
            height: 400px;
            background: #ffffff;
            bottom: -150px;
            left: -150px;
            animation-delay: -5s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -30px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: rise linear infinite;
        }

        @keyframes rise {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0;
            }
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 
                        0 0 0 1px rgba(255, 255, 255, 0.1);
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        }

        /* Logo/Brand Section */
        .brand {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            color: white;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .brand h2 {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .brand p {
            color: #6b7280;
            font-size: 14px;
            margin-top: 4px;
            font-weight: 500;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
            letter-spacing: 0.3px;
        }

        label i {
            margin-right: 8px;
            color: #667eea;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group .input-icon {
            position: absolute;
            left: 14px;
            color: #9ca3af;
            font-size: 16px;
            transition: color 0.3s;
            pointer-events: none;
        }

        .input-group input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f9fafb;
            font-family: 'Inter', sans-serif;
            outline: none;
        }

        .input-group input:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .input-group input:focus + .input-icon,
        .input-group input:focus ~ .input-icon {
            color: #667eea;
        }

        .input-group input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        /* Toggle password visibility */
        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
            padding: 0;
            transition: color 0.3s;
            width: auto;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        /* Remember me and forgot password */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            color: #4b5563;
        }

        .remember input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
            cursor: pointer;
            border-radius: 4px;
        }

        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 16px;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn i {
            margin-right: 10px;
        }

        /* Loading spinner */
        .login-btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-btn .spinner {
            display: none;
            animation: spin 1s linear infinite;
        }

        .login-btn.loading .spinner {
            display: inline-block;
        }

        .login-btn.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Alert messages */
        .alert {
            background: #fef2f2;
            border: 2px solid #fca5a5;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.5s ease;
        }

        .alert i {
            font-size: 18px;
            color: #dc2626;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sign up link */
        .signup-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }

        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
                border-radius: 20px;
            }

            .brand h2 {
                font-size: 24px;
            }

            .brand-icon {
                width: 60px;
                height: 60px;
                font-size: 26px;
            }

            .input-group input {
                padding: 12px 12px 12px 40px;
                font-size: 14px;
            }

            .login-btn {
                padding: 14px;
                font-size: 15px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            body::before,
            body::after {
                display: none;
            }
        }

        @media (max-width: 380px) {
            .login-card {
                padding: 24px 16px;
            }

            .brand h2 {
                font-size: 20px;
            }

            .brand-icon {
                width: 50px;
                height: 50px;
                font-size: 22px;
            }

            .form-group {
                margin-bottom: 16px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .login-card {
                background: rgba(31, 41, 55, 0.95);
            }

            .brand p {
                color: #9ca3af;
            }

            label {
                color: #e5e7eb;
            }

            .input-group input {
                background: #1f2937;
                border-color: #374151;
                color: #e5e7eb;
            }

            .input-group input:focus {
                background: #1f2937;
                border-color: #667eea;
            }

            .input-group input::placeholder {
                color: #6b7280;
            }

            .remember {
                color: #d1d5db;
            }

            .signup-link {
                border-top-color: #374151;
                color: #9ca3af;
            }

            .alert {
                background: #1f2937;
                border-color: #991b1b;
                color: #fca5a5;
            }
        }
    </style>
</head>
<body>

<!-- Floating Particles -->
<div class="particles" id="particles"></div>

<div class="login-wrapper">
    <div class="login-card">

        <!-- Brand Section -->
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <h2>IT Inventory</h2>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <div class="input-group">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Enter your username"
                        required
                        autofocus
                    >
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="input-group">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Remember Me</span>
                </label>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <span class="btn-text">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </span>
                <span class="spinner">
                    <i class="fas fa-spinner"></i>
                </span>
            </button>

        </form>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'fas fa-eye';
        }
    }

    // Form submission loading state
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('loginBtn');
        btn.classList.add('loading');
        // Disable button to prevent multiple submissions
        btn.disabled = true;
    });

    // Create floating particles
    function createParticles() {
        const container = document.getElementById('particles');
        const count = 20;
        
        for (let i = 0; i < count; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            const size = Math.random() * 8 + 4;
            const duration = Math.random() * 15 + 10;
            const delay = Math.random() * 10;
            const left = Math.random() * 100;
            
            particle.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                left: ${left}%;
                animation-duration: ${duration}s;
                animation-delay: ${delay}s;
            `;
            
            container.appendChild(particle);
        }
    }

    // Initialize particles on load
    createParticles();

    // Handle Enter key for login
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const form = document.getElementById('loginForm');
            if (form) {
                form.submit();
            }
        }
    });

    // Add shake animation to alert on error
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        alertElement.style.animation = 'slideDown 0.5s ease, shake 0.5s ease 0.5s';
    }

    // Add shake keyframe dynamically
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    `;
    document.head.appendChild(style);

    // Auto-focus username on load
    window.addEventListener('load', function() {
        document.getElementById('username').focus();
    });
</script>

</body>
</html>