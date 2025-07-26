<?php
require_once __DIR__ . '/../includes/auth.php';
redirectIfNotLoggedIn();

// Only waste team members and admins should access this page
if (!isWasteTeam() && !isAdmin()) {
    header('Location: /index.php');
    exit();
}

require_once __DIR__ . '/../config/db.php';

// Get user's tasks
$user_id = $_SESSION['user_id'];
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Base query
$query = "SELECT t.*, f.description, f.location, f.category, f.status as feedback_status, 
          u.full_name as reporter_name, a.full_name as assigned_by_name
          FROM tasks t
          JOIN feedback f ON t.feedback_id = f.id
          JOIN users u ON f.user_id = u.id
          LEFT JOIN users a ON t.assigned_by = a.id
          WHERE ";

// Add condition based on user role
if (isAdmin()) {
    $query .= "1=1"; // Admins see all tasks
} else {
    $query .= "t.team_id = :user_id"; // Waste team members see only their tasks
}

// Add status filter
if ($status_filter !== 'all') {
    $query .= " AND t.status = :status";
}

$query .= " ORDER BY t.created_at DESC";

$stmt = $pdo->prepare($query);

// Bind parameters
if (isAdmin()) {
    if ($status_filter !== 'all') {
        $stmt->bindParam(':status', $status_filter);
    }
} else {
    $stmt->bindParam(':user_id', $user_id);
    if ($status_filter !== 'all') {
        $stmt->bindParam(':status', $status_filter);
    }
}

$stmt->execute();
$tasks = $stmt->fetchAll();

// Handle task status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['new_status'];
    $notes = $_POST['notes'] ?? null;

    $update_stmt = $pdo->prepare("UPDATE tasks SET status = :status, notes = :notes WHERE id = :id");
    $update_stmt->bindParam(':status', $new_status);
    $update_stmt->bindParam(':notes', $notes);
    $update_stmt->bindParam(':id', $task_id);

    if ($update_stmt->execute()) {
        // Update feedback status if task is completed
        if ($new_status === 'completed') {
            $feedback_update = $pdo->prepare("UPDATE feedback SET status = 'resolved' WHERE id = (SELECT feedback_id FROM tasks WHERE id = :id)");
            $feedback_update->bindParam(':id', $task_id);
            $feedback_update->execute();

            // Create notification for the reporter
            $notification = $pdo->prepare("INSERT INTO notifications (user_id, message) 
                                         SELECT f.user_id, 'Your feedback about ' || f.category || ' has been resolved' 
                                         FROM feedback f 
                                         JOIN tasks t ON f.id = t.feedback_id 
                                         WHERE t.id = :id");
            $notification->bindParam(':id', $task_id);
            $notification->execute();
        }
        
        header("Location: tasks.php?status=$status_filter");
        exit();
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>
<section class="tasks-section">
    <h2><?php echo isAdmin() ? 'All Tasks' : 'My Tasks'; ?></h2>
    
    <!-- Status Filter -->
    <div class="status-filter">
        <form method="GET">
            <label for="status">Filter by status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Tasks</option>
                <option value="assigned" <?php echo $status_filter === 'assigned' ? 'selected' : ''; ?>>Assigned</option>
                <option value="in_progress" <?php echo $status_filter === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
        </form>
    </div>
    
    <!-- Tasks List -->
    <div class="tasks-list">
        <?php if (empty($tasks)): ?>
            <p>No tasks found.</p>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <div class="task-header">
                        <h3><?php echo ucfirst(str_replace('_', ' ', $task['category'])); ?></h3>
                        <span class="status-badge <?php echo $task['status']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?>
                        </span>
                    </div>
                    
                    <div class="task-body">
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($task['description']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($task['location']); ?></p>
                        <p><strong>Reporter:</strong> <?php echo htmlspecialchars($task['reporter_name']); ?></p>
                        <p><strong>Assigned By:</strong> <?php echo htmlspecialchars($task['assigned_by_name'] ?? 'System'); ?></p>
                        <p><strong>Assigned On:</strong> <?php echo date('M j, Y g:i a', strtotime($task['created_at'])); ?></p>
                        
                        <?php if ($task['notes']): ?>
                            <p><strong>Notes:</strong> <?php echo htmlspecialchars($task['notes']); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($task['completed_at']): ?>
                            <p><strong>Completed On:</strong> <?php echo date('M j, Y g:i a', strtotime($task['completed_at'])); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Status Update Form -->
                    <?php if ($task['status'] !== 'completed'): ?>
                        <div class="task-actions">
                            <form method="POST">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                
                                <div class="form-group">
                                    <label for="new_status">Update Status:</label>
                                    <select name="new_status" id="new_status" required>
                                        <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="notes">Notes (Optional):</label>
                                    <textarea name="notes" id="notes" rows="2"><?php echo htmlspecialchars($task['notes'] ?? ''); ?></textarea>
                                </div>
                                
                                <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>