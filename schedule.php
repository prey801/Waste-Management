<?php
require_once  'includes/auth.php';
redirectIfNotLoggedIn();

require_once  'config/db.php';

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

<?php require_once _DIR_ . '/includes/header.php'; ?>

<section class="schedule-management">
    <h2>Waste Collection Schedule</h2>
    
    <!-- Display messages -->
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <!-- Add Schedule Form (Admin Only) -->
    <?php if (isAdmin()): ?>
    <div class="add-schedule-form">
        <h3>Add New Schedule</h3>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="zone">Zone/Area:</label>
                    <input type="text" id="zone" name="zone" required>
                </div>
                
                <div class="form-group">
                    <label for="collection_day">Collection Day:</label>
                    <select id="collection_day" name="collection_day" required>
                        <?php foreach ($days_of_week as $day): ?>
                            <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="collection_time">Collection Time:</label>
                    <input type="time" id="collection_time" name="collection_time" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="notes">Notes (Optional):</label>
                <textarea id="notes" name="notes" rows="2"></textarea>
            </div>
            
            <button type="submit" name="add_schedule" class="btn btn-primary">Add Schedule</button>
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
                                <td class="actions">
                                    <a href="schedule.php?delete=<?php echo $schedule['id']; ?>" 
                                       class="btn btn-danger" 
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
            <!-- In a real implementation, you would embed a real map here -->
            <img src="assets/images/zone-map-placeholder.jpg" alt="Collection Zones Map" style="max-width: 100%;">
            <p class="note">Note: This is a placeholder for a real zone map implementation.</p>
        </div>
    </div>
</section>

<?php require_once  'includes/footer.php'; ?>
