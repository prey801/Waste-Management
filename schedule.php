<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

require_once 'config/db.php';

// Initialize variables
$success_message = '';
$error_message = '';

// Handle form submission for adding new schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
    $zone = trim($_POST['zone']);
    $collection_day = trim($_POST['collection_day']);
    $collection_time = trim($_POST['collection_time']);
    $notes = trim($_POST['notes'] ?? '');

    if (empty($zone) || empty($collection_day) || empty($collection_time)) {
        $error_message = 'Zone, day, and time are required fields.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO schedule (zone, collection_day, collection_time, notes) 
                                 VALUES (?, ?, ?, ?)");
            $stmt->execute([$zone, $collection_day, $collection_time, $notes]);
            $success_message = 'Schedule added successfully!';
        } catch (PDOException $e) {
            $error_message = 'Error adding schedule: ' . $e->getMessage();
        }
    }
}

// Handle schedule deletion
if (isset($_GET['delete'])) {
    $schedule_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM schedule WHERE id = ?");
        $stmt->execute([$schedule_id]);
        $success_message = 'Schedule deleted successfully!';
    } catch (PDOException $e) {
        $error_message = 'Error deleting schedule: ' . $e->getMessage();
    }
}

// Get all schedules
$stmt = $pdo->query("SELECT * FROM schedule ORDER BY 
                    FIELD(collection_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
                    collection_time");
$schedules = $stmt->fetchAll();

// Days of week for dropdown
$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack | Waste Collection Schedule</title>
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
            overflow: auto;
        }

        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
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
            width: 600px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            padding: 40px;
            display: flex;
            flex-direction: column;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: auto;
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

        .schedule-container {
            position: relative;
            z-index: 2;
            animation: slideInRight 1s ease-out;
        }

        .schedule-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .schedule-header h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .add-schedule-form {
            margin-bottom: 40px;
        }

        .add-schedule-form h3 {
            font-size: 1.5rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 150px;
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
            height: 80px;
            resize: vertical;
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

        .schedule-table {
            margin-bottom: 40px;
        }

        .schedule-table h3 {
            font-size: 1.5rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--surface);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: var(--primary-light);
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .btn-danger {
            padding: 8px 16px;
            background: var(--error);
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .btn-danger:hover {
            background: #d32f2f;
            transform: translateY(-2px);
        }

        .zone-map {
            margin-bottom: 40px;
        }

        .zone-map h3 {
            font-size: 1.5rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        .map-placeholder img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: var(--shadow-sm);
        }

        .map-placeholder .note {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 10px;
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
                max-width: 700px;
                margin: 0 auto;
            }
        }

        @media (max-width: 576px) {
            .right-panel {
                padding: 20px;
            }

            .form-row {
                flex-direction: column;
            }

            table {
                display: block;
                overflow-x: auto;
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
        
        <!-- Right Panel with Schedule Content -->
        <div class="right-panel">
            <div class="schedule-container">
                <div class="schedule-header">
                    <h2>Waste Collection Schedule</h2>
                </div>
                
                <!-- Display messages -->
                <?php if ($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <!-- Add Schedule Form (Admin Only) -->
                <?php if (isAdmin()): ?>
                    <div class="add-schedule-form">
                        <h3>Add New Schedule</h3>
                        <form id="scheduleForm" method="POST" action="schedule.php">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="zone">Zone/Area</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-map-marker-alt input-icon"></i>
                                        <input type="text" id="zone" name="zone" class="form-control" placeholder="Enter zone/area" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="collection_day">Collection Day</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-calendar-day input-icon"></i>
                                        <select id="collection_day" name="collection_day" class="form-control" required>
                                            <?php foreach ($days_of_week as $day): ?>
                                                <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="collection_time">Collection Time</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-clock input-icon"></i>
                                        <input type="time" id="collection_time" name="collection_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes (Optional)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-sticky-note input-icon"></i>
                                    <textarea id="notes" name="notes" class="form-control textarea" placeholder="Enter any notes"></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" name="add_schedule" class="btn-primary">Add Schedule</button>
                        </form>
                    </div>
                <?php endif; ?>
                
                <!-- Schedule Table -->
                <div class="schedule-table">
                    <h3>Current Collection Schedule</h3>
                    
                    <?php if (empty($schedules)): ?>
                        <p>No collection schedules available.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Zone/Area</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Notes</th>
                                    <?php if (isAdmin()): ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($schedules as $schedule): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($schedule['zone']); ?></td>
                                        <td><?php echo htmlspecialchars($schedule['collection_day']); ?></td>
                                        <td><?php echo date('h:i A', strtotime($schedule['collection_time'])); ?></td>
                                        <td><?php echo htmlspecialchars($schedule['notes']); ?></td>
                                        <?php if (isAdmin()): ?>
                                            <td>
                                                <a href="schedule.php?delete=<?php echo $schedule['id']; ?>" 
                                                   class="btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this schedule?')">
                                                    Delete
                                                </a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <!-- Zone Map Section -->
                <div class="zone-map">
                    <h3>Collection Zones Map</h3>
                    <div class="map-placeholder">
                        <img src="assets/images/zone-map-placeholder.jpg" alt="Collection Zones Map">
                        <p class="note">Note: This is a placeholder for a real zone map implementation.</p>
                    </div>
                </div>
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
        const scheduleForm = document.getElementById('scheduleForm');
        if (scheduleForm) {
            scheduleForm.addEventListener('submit', function(e) {
                const zone = document.getElementById('zone').value;
                const collectionDay = document.getElementById('collection_day').value;
                const collectionTime = document.getElementById('collection_time').value;
                const btn = document.querySelector('.btn-primary');
                
                // Client-side validation
                if (!zone || !collectionDay || !collectionTime) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Add loading effect
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Schedule...';
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