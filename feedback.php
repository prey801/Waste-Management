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

<?php require_once 'includes/header.php'; ?>
<section class="feedback-form">
    <h2>Submit Waste Management Feedback</h2>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            Your feedback has been submitted successfully! 
            <a href="/status.php">Track your feedback status</a>.
        </div>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/feedback.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="category">Issue Category</label>
                <select id="category" name="category" required>
                    <option value="uncollected_garbage">Uncollected Garbage</option>
                    <option value="illegal_dumping">Illegal Dumping</option>
                    <option value="overflowing_bins">Overflowing Bins</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <textarea id="location" name="location" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Upload Image (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    <?php endif; ?>
</section>
<?php require_once 'includes/footer.php'; ?>