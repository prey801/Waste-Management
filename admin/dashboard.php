<?php require_once __DIR__ . '/../partials/header.php'; ?>

<section class="dashboard">
    <div class="dashboard-header animate-fade-in">
        <h2>EcoTrack Dashboard</h2>
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">142</div>
                    <div class="stat-label">Total Reports</div>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 12%
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">24</div>
                    <div class="stat-label">Pending Actions</div>
                </div>
                <div class="stat-trend down">
                    <i class="fas fa-arrow-down"></i> 8%
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">87%</div>
                    <div class="stat-label">Resolved Issues</div>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 5%
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">243</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> 15%
                </div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card chart-card">
            <div class="card-header">
                <h3>Reports by Category</h3>
                <div class="card-actions">
                    <button class="icon-btn"><i class="fas fa-sync-alt"></i></button>
                    <button class="icon-btn"><i class="fas fa-expand"></i></button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Recent Activity</h3>
                <a href="#" class="btn btn-link">View All</a>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Issue resolved in Zone 4B</div>
                        <div class="activity-meta">
                            <span>John Doe</span> • <span>2 hours ago</span>
                        </div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New report in Zone 2A</div>
                        <div class="activity-meta">
                            <span>Jane Smith</span> • <span>4 hours ago</span>
                        </div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon primary">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New user registered</div>
                        <div class="activity-meta">
                            <span>Robert Johnson</span> • <span>6 hours ago</span>
                        </div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon info">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Schedule updated for Zone 5</div>
                        <div class="activity-meta">
                            <span>Admin</span> • <span>Yesterday</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card map-card">
            <div class="card-header">
                <h3>Issue Heatmap</h3>
                <div class="map-controls">
                    <div class="map-legend">
                        <span class="legend-item low">Low</span>
                        <span class="legend-item medium">Medium</span>
                        <span class="legend-item high">High</span>
                    </div>
                </div>
            </div>
            <div class="map-container">
                <div class="map-area" data-zone="1" data-issues="12">
                    <div class="zone-label">Zone 1</div>
                </div>
                <div class="map-area" data-zone="2" data-issues="27">
                    <div class="zone-label">Zone 2</div>
                </div>
                <div class="map-area" data-zone="3" data-issues="8">
                    <div class="zone-label">Zone 3</div>
                </div>
                <div class="map-area" data-zone="4" data-issues="35">
                    <div class="zone-label">Zone 4</div>
                </div>
                <div class="map-area" data-zone="5" data-issues="15">
                    <div class="zone-label">Zone 5</div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="quick-actions">
                <button class="action-btn animate-bounce">
                    <div class="action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <span>Add Schedule</span>
                </button>
                
                <button class="action-btn animate-bounce">
                    <div class="action-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <span>Assign Task</span>
                </button>
                
                <button class="action-btn animate-bounce">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span>Generate Report</span>
                </button>
                
                <button class="action-btn animate-bounce">
                    <div class="action-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <span>Send Alert</span>
                </button>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>