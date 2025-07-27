<?php
// includes/header.php
require_once __DIR__ . '/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack Waste Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Header Styles */
        .modern-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 3;
        }

        .header-container {
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .logo .fa-recycle {
            color: var(--accent);
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
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            .modern-header .header-container {
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
                background: var(--primary-dark);
                padding: 1rem;
                z-index: 3;
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
</head>
<body>
    <header class="modern-header">
        <div class="header-container">
            <div class="logo">
                <h1><i class="fas fa-recycle"></i> EcoTrack</h1>
            </div>
            <nav class="main-nav">
                <input type="checkbox" id="nav-toggle" class="nav-toggle">
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
    <main class="main-container">