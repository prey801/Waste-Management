<?php require_once 'includes/header.php'; ?>
<section class="hero">
    <h2>Improving Waste Management Through Community Feedback</h2>
    <p>Report waste issues, track resolution progress, and view collection schedules in your area.</p>
    <?php if (!isLoggedIn()): ?>
        <div class="cta-buttons">
            <a href="/register.php" class="btn btn-primary">Register Now</a>
            <a href="/login.php" class="btn btn-secondary">Login</a>
        </div>
    <?php endif; ?>
</section>

<section class="features">
    <div class="feature">
        <i class="fas fa-comment-alt"></i>
        <h3>Submit Feedback</h3>
        <p>Report uncollected garbage, illegal dumping, or other waste issues in your area.</p>
    </div>
    <div class="feature">
        <i class="fas fa-clock"></i>
        <h3>Track Status</h3>
        <p>Monitor the progress of your reported issues from submission to resolution.</p>
    </div>
    <div class="feature">
        <i class="fas fa-calendar-alt"></i>
        <h3>View Schedules</h3>
        <p>Access waste collection schedules for your neighborhood.</p>
    </div>
</section>

<section class="how-it-works">
    <h2>How It Works</h2>
    <ol>
        <li>Register for an account or log in</li>
        <li>Submit feedback about a waste issue</li>
        <li>Track the status of your report</li>
        <li>Receive notifications when resolved</li>
    </ol>
</section>
<?php require_once 'includes/footer.php'; ?>