<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack - User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            --warning: #ff9800;
            --error: #f44336;
            --info: #2196f3;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .profile-container {
            max-width: 1200px;
            width: 100%;
            margin: 30px auto;
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 30px;
        }

        .profile-card {
            background: var(--surface);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: var(--transition);
            animation: fadeIn 0.8s ease-out;
        }

        .profile-header {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            margin: 0 auto 20px;
            position: relative;
            transition: var(--transition);
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-edit {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--accent);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }

        .profile-name {
            color: white;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .profile-role {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-bottom: 15px;
        }

        .stats-badges {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            font-weight: 500;
        }

        .profile-body {
            padding: 30px;
        }

        .info-group {
            margin-bottom: 25px;
        }

        .info-group h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item {
            display: flex;
            margin-bottom: 15px;
        }

        .info-label {
            width: 120px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .info-value {
            flex: 1;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .action-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
        }

        .content-section {
            background: var(--surface);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 30px;
            margin-bottom: 30px;
            animation: slideUp 0.6s ease-out;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity-timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-line {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--border);
            margin-left: 11px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 30px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 2;
        }

        .timeline-content {
            background: rgba(46, 125, 50, 0.05);
            border-radius: 12px;
            padding: 15px;
            transition: var(--transition);
        }

        .timeline-content:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-sm);
        }

        .timeline-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .timeline-time {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .timeline-description {
            color: var(--text-secondary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: var(--surface);
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            display: flex;
            transition: var(--transition);
            border-left: 4px solid var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(46, 125, 50, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            font-size: 24px;
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
            outline: none;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: rgba(46, 125, 50, 0.1);
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 25px;
        }

        .tab {
            padding: 12px 25px;
            cursor: pointer;
            position: relative;
            font-weight: 500;
            color: var(--text-secondary);
            transition: var(--transition);
        }

        .tab.active {
            color: var(--primary);
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .tabs {
                flex-wrap: wrap;
            }
            
            .tab {
                flex: 1;
                text-align: center;
                padding: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Left Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Profile">
                    <div class="avatar-edit">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <h2 class="profile-name">June Mutiso</h2>
                <div class="profile-role">Resident User</div>
                
                <div class="stats-badges">
                    <div class="stat-badge">
                        <i class="fas fa-flag"></i>
                        <span>12 Reports</span>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-check-circle"></i>
                        <span>8 Resolved</span>
                    </div>
                </div>
            </div>
            
            <div class="profile-body">
                <div class="info-group">
                    <h3><i class="fas fa-user"></i> Personal Info</h3>
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">June Mutiso</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">june.mutiso@zetech.ac.ke</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">+254 712 345 678</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Address</div>
                        <div class="info-value">123 Greenview Estate, Nairobi</div>
                    </div>
                </div>
                
                <div class="info-group">
                    <h3><i class="fas fa-shield-alt"></i> Account</h3>
                    <div class="info-item">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">April 15, 2024</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Login</div>
                        <div class="info-value">Today, 10:24 AM</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span style="color: var(--success); font-weight: 500;">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        </div>
                    </div>
                </div>
                
                <button class="action-btn">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
            </div>
        </div>
        
        <!-- Right Content Area -->
        <div class="content-area">
            <div class="tabs">
                <div class="tab active" data-target="activity">Activity</div>
                <div class="tab" data-target="stats">Statistics</div>
                <div class="tab" data-target="settings">Settings</div>
            </div>
            
            <!-- Activity Tab -->
            <div id="activity" class="tab-content active">
                <div class="content-section">
                    <h2 class="section-title"><i class="fas fa-history"></i> Recent Activity</h2>
                    
                    <div class="activity-timeline">
                        <div class="timeline-line"></div>
                        
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Issue Resolved</div>
                                <div class="timeline-time">Today, 10:15 AM</div>
                                <div class="timeline-description">
                                    Your report about uncollected garbage in Zone 4B has been resolved.
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">New Report Submitted</div>
                                <div class="timeline-time">Yesterday, 3:45 PM</div>
                                <div class="timeline-description">
                                    Reported illegal dumping near Green Park. Status: In Progress
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Comment Added</div>
                                <div class="timeline-time">May 12, 2025, 9:30 AM</div>
                                <div class="timeline-description">
                                    Added additional information to your report in Zone 3A.
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Schedule Updated</div>
                                <div class="timeline-time">May 10, 2025, 2:15 PM</div>
                                <div class="timeline-description">
                                    Waste collection schedule for your area has been updated.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Tab -->
            <div id="stats" class="tab-content">
                <div class="content-section">
                    <h2 class="section-title"><i class="fas fa-chart-bar"></i> Your Statistics</h2>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">12</div>
                                <div class="stat-label">Total Reports</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">8</div>
                                <div class="stat-label">Resolved Issues</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">3</div>
                                <div class="stat-label">Pending Actions</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">92%</div>
                                <div class="stat-label">Satisfaction Rate</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; background: white; border-radius: 16px; padding: 20px; box-shadow: var(--shadow-sm);">
                        <h3 style="margin-bottom: 20px; color: var(--primary);">Reports by Category</h3>
                        <div style="display: flex; height: 200px; align-items: flex-end; gap: 15px; justify-content: center;">
                            <div style="background: var(--primary); width: 60px; height: 160px; border-radius: 10px; position: relative; animation: growBar 1s ease;">
                                <div style="position: absolute; bottom: -25px; width: 100%; text-align: center;">Uncollected</div>
                            </div>
                            <div style="background: var(--warning); width: 60px; height: 110px; border-radius: 10px; position: relative; animation: growBar 1s ease 0.2s;">
                                <div style="position: absolute; bottom: -25px; width: 100%; text-align: center;">Dumping</div>
                            </div>
                            <div style="background: var(--accent); width: 60px; height: 80px; border-radius: 10px; position: relative; animation: growBar 1s ease 0.4s;">
                                <div style="position: absolute; bottom: -25px; width: 100%; text-align: center;">Bins</div>
                            </div>
                            <div style="background: var(--error); width: 60px; height: 60px; border-radius: 10px; position: relative; animation: growBar 1s ease 0.6s;">
                                <div style="position: absolute; bottom: -25px; width: 100%; text-align: center;">Other</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Settings Tab -->
            <div id="settings" class="tab-content">
                <div class="content-section">
                    <h2 class="section-title"><i class="fas fa-cog"></i> Account Settings</h2>
                    
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" class="form-control" value="June Mutiso">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="form-control" value="june.mutiso@zetech.ac.ke">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" class="form-control" value="+254 712 345 678">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" class="form-control" rows="3">123 Greenview Estate, Nairobi</textarea>
                    </div>
                    
                    <h3 style="margin: 30px 0 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border); color: var(--primary);">
                        <i class="fas fa-lock"></i> Change Password
                    </h3>
                    
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" class="form-control">
                    </div>
                    
                    <div class="btn-group">
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <button class="btn btn-outline">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and content
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                tab.classList.add('active');
                
                // Show corresponding content
                const target = tab.getAttribute('data-target');
                document.getElementById(target).classList.add('active');
            });
        });

        // Animation for bar charts
        document.addEventListener('DOMContentLoaded', function() {
            // Animated bar charts
            const bars = document.querySelectorAll('[style*="animation: growBar"]');
            bars.forEach(bar => {
                // Store original height
                const originalHeight = bar.style.height;
                // Reset height for animation
                bar.style.height = '0';
                
                // Trigger reflow
                void bar.offsetHeight;
                
                // Animate to original height
                bar.style.transition = 'height 1s ease';
                bar.style.height = originalHeight;
            });
        });

        // Profile card animation on hover
        const profileCard = document.querySelector('.profile-card');
        profileCard.addEventListener('mouseenter', () => {
            profileCard.style.transform = 'translateY(-5px)';
            profileCard.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.2)';
        });
        
        profileCard.addEventListener('mouseleave', () => {
            profileCard.style.transform = '';
            profileCard.style.boxShadow = 'var(--shadow-lg)';
        });
    </script>
</body>
</html>