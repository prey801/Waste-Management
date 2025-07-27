<?php
require_once 'includes/auth.php';
?>

<?php require_once 'includes/header.php'; ?>

<style>
    /* Reuse EcoTrack theme styles */
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

    html, body {
        margin: 0;
        padding: 0;
        height: 100vh;
        background: linear-gradient(135deg, #1b5e20 0%, #0d47a1 100%);
        overflow: hidden; /* Prevent body scrollbars */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-container {
        display: flex;
        width: 100%;
        height: calc(100vh - 80px - 80px); /* Subtract header (~80px) and footer (~80px) heights */
        position: relative;
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

    .right-panel {
        width: 600px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        padding: 40px;
        box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow-y: auto; /* Allow scrolling within right panel */
        height: 100%; /* Fill available height */
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

    .index-container {
        position: relative;
        z-index: 2;
        animation: slideInRight 1s ease-out;
        min-height: 100%; /* Ensure content fills right panel */
        display: flex;
        flex-direction: column;
        justify-content: center; /* Center content vertically */
    }

    /* Hero Section */
    .hero {
        text-align: center;
        margin-bottom: 40px;
    }

    .hero h2 {
        font-size: 2rem;
        color: var(--primary-dark);
        margin-bottom: 15px;
    }

    .hero p {
        font-size: 1.1rem;
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary {
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(46, 125, 50, 0.6);
    }

    .btn-secondary {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-3px);
    }

    /* Features Section */
    .features {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .feature {
        background: var(--surface);
        padding: 20px;
        border-radius: 10px;
        box-shadow: var(--shadow-sm);
        text-align: center;
        flex: 1;
        min-width: 200px;
        transition: var(--transition);
    }

    .feature:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .feature i {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 15px;
    }

    .feature h3 {
        font-size: 1.2rem;
        color: var(--primary-dark);
        margin-bottom: 10px;
    }

    .feature p {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    /* How It Works Section */
    .how-it-works {
        text-align: center;
    }

    .how-it-works h2 {
        font-size: 1.8rem;
        color: var(--primary-dark);
        margin-bottom: 20px;
    }

    .how-it-works ol {
        list-style-position: inside;
        padding: 0;
        font-size: 1rem;
        color: var(--text);
    }

    .how-it-works li {
        margin-bottom: 10px;
        padding: 10px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 8px;
    }

    /* Animations */
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

    /* Responsive Design */
    @media (max-width: 992px) {
        .left-panel {
            display: none;
        }

        .right-panel {
            width: 100%;
            max-width: none; /* Full width on smaller screens */
        }

        .index-container {
            justify-content: flex-start; /* Align content to top on smaller screens */
        }
    }

    @media (max-width: 576px) {
        .right-panel {
            padding: 20px;
        }

        .features {
            flex-direction: column;
            align-items: center;
        }

        .hero h2 {
            font-size: 1.5rem;
        }

        .hero p {
            font-size: 1rem;
        }

        .cta-buttons {
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
            text-align: center;
        }

        .how-it-works h2 {
            font-size: 1.5rem;
        }

        .how-it-works li {
            font-size: 0.9rem;
        }
    }
</style>

<div class="main-container">
    <!-- Left Panel with Animation -->
    <div class="left-panel">
        <div class="background-animation" id="particles"></div>
        <div class="content">
            <div class="logo">
                <i class="fas fa-recycle"></i>
            </div>
            <h1 class="title">EcoTrack Waste Management</h1>
            <p class="subtitle">Transforming waste management through community engagement</p>
        </div>
    </div>

    <!-- Right Panel with Homepage Content -->
    <div class="right-panel">
        <div class="index-container">
            <section class="hero">
                <h2>Improving Waste Management Through Community Feedback</h2>
                <p>Report waste issues, track resolution progress, and view collection schedules in your area.</p>
                <?php if (!isLoggedIn()): ?>
                    <div class="cta-buttons">
                        <a href="register.php" class="btn btn-primary">Register Now</a>
                        <a href="login.php" class="btn btn-secondary">Login</a>
                    </div>
                <?php endif; ?>
            </section>

            <section class="features">
                <div class="feature">
                    <i class="fas fa-comment-alt"></i>
                    <h3>Submit Feedback</h3>
                    <p>Report uncollected garbage, illegal dumping, or other waste issues in your area.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <h3>Track Status</h3>
                    <p>Monitor the progress of your reported issues from submission to resolution.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>View Schedules</h3>
                    <p>Access waste collection schedules for your neighborhood.</p>
                </div>
            </section>

            <section class="how-it-works">
                <h2>How It Works</h2>
                <ol>
                    <li>Register for an account or log in</li>
                    <li>Submit feedback about a waste issue</li>
                    <li>Track the status of your report</li>
                    <li>Receive notifications when resolved</li>
                </ol>
            </section>
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

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        createParticles();
    });
</script>


</main>
</body>
</html>