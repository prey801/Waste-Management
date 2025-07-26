<?php
// Remove or correct this line:
// require_once __DIR__ . 'includes/function.php';

// Instead, if you need to include auth.php, use:
require_once __DIR__ . '/auth.php';
// Ensure the auth.php file is included for user authentication functions
// Ensure the header is included at the top of your pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Management Feedback System</title>
    <link rel="stylesheet" href="assets\css\style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <style>
        /* Modern Header Styles */
.modern-header {
    background: linear-gradient(135deg, #1e4d92, #2c3e50);
    padding: 1rem 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.logo h1 {
    color: #ffffff;
    font-size: 1.8rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo .fa-recycle {
    color: #2ecc71;
    font-size: 2rem;
}

.main-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
}

.main-nav a {
    color: #ffffff;
    text-decoration: none;
    font-size: 1rem;
    padding: 8px 15px;
    border-radius: 5px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.main-nav a:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.main-nav i {
    font-size: 1.1rem;
}

/* Responsive Layout */
@media (min-width: 768px) {
    .modern-header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .nav-toggle-label {
        display: none;
    }
}

/* Mobile Navigation */
@media (max-width: 767px) {
    .main-nav ul {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #2c3e50;
        padding: 1rem;
    }
    
    .nav-toggle:checked ~ ul {
        display: flex;
    }
    
    .nav-toggle {
        display: none;
    }
    
    .nav-toggle-label {
        display: block;
        cursor: pointer;
        padding: 1rem;
    }
    
    .nav-toggle-label span,
    .nav-toggle-label span::before,
    .nav-toggle-label span::after {
        display: block;
        background: white;
        height: 2px;
        width: 25px;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .nav-toggle-label span::before,
    .nav-toggle-label span::after {
        content: '';
        position: absolute;
    }
    
    .nav-toggle-label span::before {
        bottom: 8px;
    }
    
    .nav-toggle-label span::after {
        top: 8px;
    }
}
    </style>
    <header class="modern-header">
        <div class="container">
            <div class="logo">
                <h1><i class="fas fa-recycle"></i> Waste Management</h1>
            </div>
            <nav class="main-nav">
                <label for="nav-toggle" class="nav-toggle-label">
                    <span></span>
                </label>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>

                    <?php if (isLoggedIn()): ?>
                        <li><a href="feedback.php"><i class="fas fa-comment"></i> Submit Feedback</a></li>
                        <li><a href="status.php"><i class="fas fa-tasks"></i> Check Status</a></li>
                        <li><a href="schedule.php"><i class="fas fa-calendar"></i> Collection Schedule</a></li>

                        <?php if (isAdmin()): ?>
                            <li><a href="admin/dashboard.php"><i class="fas fa-user-shield"></i> Admin Dashboard</a></li>
                        <?php elseif (isWasteTeam()): ?>
                            <li><a href="admin/tasks.php"><i class="fas fa-clipboard-list"></i> My Tasks</a></li>
                        <?php endif; ?>

                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
<?php