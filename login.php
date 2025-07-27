<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        
        // Redirect based on user type
        if ($user['user_type'] === 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($user['user_type'] === 'waste_team') {
            header('Location: admin/tasks.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack | Waste Management Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --primary-light: #4caf50;
            --accent: #2196f3;
            --background: #f5f7fa;
            --surface: #ffffff;
            --text: #333333;
            --text-secondary: #666666;
            --border: #e0e0e0;
            --success: #4caf50;
            --error: #f44336;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.15);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        body {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, #1b5e20 0%, #0d47a1 100%);
            overflow: hidden;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .left-panel {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
        }

        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.7;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            animation: float 15s infinite linear;
        }

        .content {
            max-width: 600px;
            text-align: center;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }

        .logo {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: white;
        }

        .logo i {
            background: linear-gradient(45deg, var(--primary-light), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, #e0f7fa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .subtitle {
            font-size: 1.4rem;
            margin-bottom: 30px;
            opacity: 0.9;
            font-weight: 300;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .feature {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            width: 180px;
            transition: var(--transition);
        }

        .feature:hover {
            transform: translateY(-10px) scale(1.05);
            background: rgba(255, 255, 255, 0.2);
        }

        .feature i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary-light);
        }

        .feature h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .right-panel {
            width: 450px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(46,125,50,0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 2;
            animation: slideInRight 1s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text);
            padding-left: 10px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
            outline: none;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .separator {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: var(--text-secondary);
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .separator span {
            padding: 0 15px;
        }

        .social-login {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .social-btn.google {
            color: #DB4437;
        }

        .social-btn.facebook {
            color: #4267B2;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .register-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert {
            background-color: var(--error);
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 992px) {
            .left-panel {
                display: none;
            }
            
            .right-panel {
                width: 100%;
                max-width: 500px;
                margin: 0 auto;
            }
        }

        @media (max-width: 576px) {
            .right-panel {
                padding: 40px 20px;
            }
            
            .social-login {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Panel with Animation -->
        <div class="left-panel">
            <div class="background-animation" id="particles"></div>
            <div class="content">
                <div class="logo">
                    <i class="fas fa-recycle"></i>
                </div>
                <h1 class="title">EcoTrack Waste Management</h1>
                <p class="subtitle">Transforming waste management through community engagement</p>
                
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-bolt"></i>
                        <h3>Real-time Reporting</h3>
                        <p>Instantly report waste issues in your area</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-map-marked-alt"></i>
                        <h3>Smart Tracking</h3>
                        <p>Monitor waste collection progress</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-chart-line"></i>
                        <h3>Data Insights</h3>
                        <p>Access waste management analytics</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel with Login Form -->
        <div class="right-panel">
            <div class="login-container">
                <div class="login-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to your EcoTrack account</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form id="loginForm" action="login.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                    </div>
                    
                    <div class="options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="btn-login">Sign In</button>
                    
                    <div class="separator">
                        <span>or continue with</span>
                    </div>
                    
                    <div class="social-login">
                        <button type="button" class="social-btn google">
                            <i class="fab fa-google"></i> Google
                        </button>
                        <button type="button" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                    </div>
                    
                    <div class="register-link">
                        Don't have an account? <a href="register.php">Register now</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Create background particles
        function createParticles() {
            const container = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const size = Math.random() * 100 + 20;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                const opacity = Math.random() * 0.4 + 0.1;
                particle.style.backgroundColor = `rgba(255, 255, 255, ${opacity})`;
                
                const duration = Math.random() * 20 + 10;
                particle.style.animation = `float ${duration}s infinite linear`;
                
                particle.style.animationDelay = `${Math.random() * 5}s`;
                
                container.appendChild(particle);
            }
        }
        
        // Form submission with client-side validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.querySelector('.btn-login');
            
            // Client-side validation
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }
            
            // Add loading effect
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            btn.disabled = true;
        });
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            // Add hover effect to social buttons
            const socialBtns = document.querySelectorAll('.social-btn');
            socialBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
        });
    </script>
</body>
</html>