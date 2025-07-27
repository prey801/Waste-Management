<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

require_once 'config/db.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    
    // Handle file upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/images/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_ext;
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        }
    }
    
    if (empty($description) || empty($location)) {
        $error = 'Description and location are required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO feedback (user_id, category, description, location, image_path) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $category, $description, $location, $image_path])) {
            $success = true;
            
            // Create notification for admin
            $message = "New feedback submitted by {$_SESSION['full_name']}";
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) 
                                 SELECT id, ? FROM users WHERE user_type = 'admin'");
            $stmt->execute([$message]);
        } else {
            $error = 'Failed to submit feedback. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack | Submit Waste Management Feedback</title>
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

        .feedback-container {
            position: relative;
            z-index: 2;
            animation: slideInRight 1s ease-out;
        }

        .feedback-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .feedback-header h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .feedback-header p {
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
            padding: 12px 12px 12px 40px;
            border: 2px solid var(--border);
            border-radius: 10px;
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
            padding: 12px;
            height: 100px;
            resize: vertical;
        }

        .form-control.file-input {
            padding: 10px;
            height: auto;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.6);
        }

        .btn-primary:active {
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
        
        <!-- Right Panel with Feedback Form -->
        <div class="right-panel">
            <div class="feedback-container">
                <div class="feedback-header">
                    <h2>Submit Waste Management Feedback</h2>
                    <p>Help us improve by reporting waste issues</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        Your feedback has been submitted successfully! 
                        <a href="/status.php">Track your feedback status</a>.
                    </div>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="alert"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form id="feedbackForm" method="POST" action="feedback.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="category">Issue Category</label>
                            <div class="input-with-icon">
                                <i class="fas fa-list input-icon"></i>
                                <select id="category" name="category" class="form-control" required>
                                    <option value="uncollected_garbage">Uncollected Garbage</option>
                                    <option value="illegal_dumping">Illegal Dumping</option>
                                    <option value="overflowing_bins">Overflowing Bins</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <div class="input-with-icon">
                                <i class="fas fa-comment input-icon"></i>
                                <textarea id="description" name="description" class="form-control textarea" placeholder="Describe the issue" required></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location</label>
                            <div class="input-with-icon">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <textarea id="location" name="location" class="form-control textarea" placeholder="Enter the location" required></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Upload Image (Optional)</label>
                            <div class="input-with-icon">
                                <i class="fas fa-image input-icon"></i>
                                <input type="file" id="image" name="image" class="form-control file-input" accept="image/*">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary">Submit Feedback</button>
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
        const feedbackForm = document.getElementById('feedbackForm');
        if (feedbackForm) {
            feedbackForm.addEventListener('submit', function(e) {
                const category = document.getElementById('category').value;
                const description = document.getElementById('description').value;
                const location = document.getElementById('location').value;
                const btn = document.querySelector('.btn-primary');
                
                // Client-side validation
                if (!category || !description || !location) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Add loading effect
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                btn.disabled = true;
            });
        }
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
        });
    </script>
</body>
</html>