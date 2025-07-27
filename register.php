<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Validate inputs
    if (empty($full_name)) $errors[] = 'Full name is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
    if (empty($password)) $errors[] = 'Password is required.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match.';
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) $errors[] = 'Email already registered.';
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone, address, user_type) VALUES (?, ?, ?, ?, ?, 'resident')");
        if ($stmt->execute([$full_name, $email, $hashed_password, $phone, $address])) {
            $success = true;
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack | Waste Management Registration</title>
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

        .register-container {
            position: relative;
            z-index: 2;
            animation: slideInRight 1s ease-out;
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .register-header p {
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

        .form-control.textarea {
            padding: 15px;
            height: 100px;
            resize: vertical;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.6);
        }

        .btn-register:active {
            transform: translateY(0);
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

        .alert-success {
            background-color: var(--success);
        }

        .alert a {
            color: white;
            text-decoration: underline;
        }

        .alert a:hover {
            text-decoration: none;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .login-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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
        
        <!-- Right Panel with Registration Form -->
        <div class="right-panel">
            <div class="register-container">
                <div class="register-header">
                    <h2>Create an Account</h2>
                    <p>Join EcoTrack to manage waste effectively</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        Registration successful! <a href="login.php">Login here</a>.
                    </div>
                <?php else: ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="registerForm" action="register.php" method="POST">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Enter your full name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-with-icon">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone number">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <div class="input-with-icon">
                                <i class="fas fa-home input-icon"></i>
                                <textarea id="address" name="address" class="form-control textarea" placeholder="Enter your address"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-register">Register</button>
                        
                        <div class="login-link">
                            Already have an account? <a href="login.php">Login here</a>
                        </div>
                    </form>
                <?php endif; ?>
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
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const btn = document.querySelector('.btn-register');
            
            // Client-side validation
            if (!fullName || !email || !password || !confirmPassword) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
            
            // Add loading effect
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';
            btn.disabled = true;
        });
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
        });
    </script>
</body>
</html>