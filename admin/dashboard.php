<?php
require_once '../../includes/auth.php';
redirectIfNotAdmin();

require_once '../../config/db.php';

// Get stats
$total_feedback = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
$pending_feedback = $pdo->query("SELECT COUNT(*) FROM feedback WHERE status = 'pending'")->fetchColumn();
$resolved_feedback = $pdo->query("SELECT COUNT(*) FROM feedback WHERE status = 'resolved'")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Get recent feedback
$stmt = $pdo->query("SELECT f.*, u.full_name 
                    FROM feedback f 
                    JOIN users u ON f.user_id = u.id 
                    ORDER BY f.created_at DESC 
                    LIMIT 5");
$recent_feedback = $stmt->fetchAll();

// Get unread notifications count
$unread_notifications = $pdo->query("SELECT COUNT(*) FROM notifications WHERE user_id = {$_SESSION['user_id']} AND is_read = FALSE")->fetchColumn();
?>

<?php require_once '../../includes/header.php'; ?>
<section class="admin-dashboard">
    <h2>Admin Dashboard</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Feedback</h3>
            <p><?php echo $total_feedback; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Feedback</h3>
            <p><?php echo $pending_feedback; ?></p>
        </div>
        <div class="stat-card">
            <h3>Resolved Feedback</h3>
            <p><?php echo $resolved_feedback; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
    </div>
    
    <div class="recent-feedback">
        <h3>Recent Feedback</h3>
        <?php if (empty($recent_feedback)): ?>
            <p>No recent feedback.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_feedback as $feedback): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($feedback['full_name']); ?></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $feedback['category'])); ?></td>
                            <td>
                                <span class="status-badge <?php echo $feedback['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $feedback['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($feedback['created_at'])); ?></td>
                            <td>
                                <a href="/admin/manage_feedback.php?id=<?php echo $feedback['id']; ?>" class="btn btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
<?php require_once '../../includes/footer.php'; ?>