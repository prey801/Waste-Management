<?php
session_start();
require_once __DIR__ . 'includes/function.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Management Feedback System</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Waste Management Feedback System</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="/index.php">Home</a></li>

                    <?php if (isLoggedIn()): ?>
                        <li><a href="/feedback.php">Submit Feedback</a></li>
                        <li><a href="/status.php">Check Status</a></li>
                        <li><a href="/schedule.php">Collection Schedule</a></li>

                        <?php if (isAdmin()): ?>
                            <li><a href="/admin/dashboard.php">Admin Dashboard</a></li>
                        <?php elseif (isWasteTeam()): ?>
                            <li><a href="/admin/tasks.php">My Tasks</a></li>
                        <?php endif; ?>

                        <li><a href="/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login.php">Login</a></li>
                        <li><a href="/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
<?php