<?php
$pageTitle = 'Admin Dashboard';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <h2 class="mb-4">Admin Dashboard</h2>
            
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card dashboard-stat-card primary shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Applications</h6>
                                    <div class="stat-number text-primary"><?php echo $stats['total_applications']; ?></div>
                                </div>
                                <i class="bi bi-file-earmark-text display-4 text-primary opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card dashboard-stat-card warning shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Pending Medical</h6>
                                    <div class="stat-number text-warning"><?php echo $stats['pending_medical']; ?></div>
                                </div>
                                <i class="bi bi-clipboard2-pulse display-4 text-warning opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card dashboard-stat-card danger shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Pending Driving</h6>
                                    <div class="stat-number text-danger"><?php echo $stats['pending_driving']; ?></div>
                                </div>
                                <i class="bi bi-car-front display-4 text-danger opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card dashboard-stat-card success shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Licenses Issued</h6>
                                    <div class="stat-number text-success"><?php echo $stats['licenses_issued']; ?></div>
                                </div>
                                <i class="bi bi-card-heading display-4 text-success opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?php echo BASE_URL; ?>/slot/medical" class="btn btn-outline-primary">
                                    <i class="bi bi-calendar-plus"></i> Manage Medical Slots
                                </a>
                                <a href="<?php echo BASE_URL; ?>/slot/driving" class="btn btn-outline-success">
                                    <i class="bi bi-calendar-check"></i> Manage Driving Slots
                                </a>
                                <a href="<?php echo BASE_URL; ?>/application/list" class="btn btn-outline-info">
                                    <i class="bi bi-list-ul"></i> View All Applications
                                </a>
                                <a href="<?php echo BASE_URL; ?>/license/list" class="btn btn-outline-warning">
                                    <i class="bi bi-card-list"></i> View All Licenses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Application Status Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th class="text-end">Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($statusCounts as $status): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge status-<?php echo $status['application_status']; ?>">
                                                        <?php echo str_replace('_', ' ', strtoupper($status['application_status'])); ?>
                                                    </span>
                                                </td>
                                                <td class="text-end"><strong><?php echo $status['count']; ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Today's Activity</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between">
                                    <span><i class="bi bi-file-plus text-primary"></i> New Applications</span>
                                    <strong><?php echo $stats['applications_today']; ?></strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span><i class="bi bi-clock-history text-warning"></i> Pending Actions</span>
                                    <strong><?php echo $stats['pending_medical'] + $stats['pending_driving']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Applications</h5>
                            <a href="<?php echo BASE_URL; ?>/application/list" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Reference ID</th>
                                            <th>Applicant</th>
                                            <th>License Type</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentApplications as $app): ?>
                                            <tr>
                                                <td><code><?php echo $app['reference_id']; ?></code></td>
                                                <td><?php echo $app['full_name']; ?></td>
                                                <td><?php echo ucfirst(str_replace('_', ' ', $app['license_type'])); ?></td>
                                                <td>
                                                    <span class="badge status-<?php echo $app['application_status']; ?>">
                                                        <?php echo str_replace('_', ' ', $app['application_status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($app['submission_date'])); ?></td>
                                                <td>
                                                    <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $app['application_id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>