<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

require_once 'config/db.php';

// Get user's feedback
$stmt = $pdo->prepare("SELECT f.*, t.status as task_status, t.completed_at 
                      FROM feedback f 
                      LEFT JOIN tasks t ON f.id = t.feedback_id 
                      WHERE f.user_id = ? 
                      ORDER BY f.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$feedback_items = $stmt->fetchAll();
?>

<?php require_once 'includes/header.php'; ?>
<section class="feedback-status">
    <h2>Your Feedback Status</h2>
    
    <?php if (empty($feedback_items)): ?>
        <p>You haven't submitted any feedback yet. <a href="/feedback.php">Submit your first feedback</a>.</p>
    <?php else: ?>
        <div class="feedback-list">
            <?php foreach ($feedback_items as $item): ?>
                <div class="feedback-item">
                    <div class="feedback-header">
                        <h3><?php echo ucfirst(str_replace('_', ' ', $item['category'])); ?></h3>
                        <span class="status-badge <?php echo $item['status']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $item['status'])); ?>
                        </span>
                        <?php if ($item['task_status'] === 'completed'): ?>
                            <span class="status-badge completed">
                                Completed
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="feedback-body">
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location']); ?></p>
                        <p><strong>Submitted:</strong> <?php echo date('M j, Y g:i a', strtotime($item['created_at'])); ?></p>
                        <?php if ($item['completed_at']): ?>
                            <p><strong>Completed:</strong> <?php echo date('M j, Y g:i a', strtotime($item['completed_at'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($item['image_path']): ?>
                        <div class="feedback-image">
                            <img src="/<?php echo $item['image_path']; ?>" alt="Feedback Image">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require_once 'includes/footer.php'; ?>