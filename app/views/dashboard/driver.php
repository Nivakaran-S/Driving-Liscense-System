<?php
$pageTitle = 'Driver Dashboard';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <h2 class="mb-4">Welcome, <?php echo Auth::getUser()['full_name']; ?>!</h2>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <?php if (!$hasPending): ?>
                        <div class="alert alert-info">
                            <h5><i class="bi bi-info-circle"></i> Get Started</h5>
                            <p class="mb-2">You don't have any active application. Start by submitting a new application.</p>
                            <a href="<?php echo BASE_URL; ?>/application/create" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Submit New Application
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($latestApplication): ?>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Current Application</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Reference ID:</strong><br>
                                        <span class="badge bg-secondary"><?php echo $latestApplication['reference_id']; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>License Type:</strong><br>
                                        <?php echo ucfirst(str_replace('_', ' ', $latestApplication['license_type'])); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong><br>
                                        <span class="badge status-<?php echo $latestApplication['application_status']; ?>">
                                            <?php echo str_replace('_', ' ', strtoupper($latestApplication['application_status'])); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Submitted:</strong><br>
                                        <?php echo date('M d, Y', strtotime($latestApplication['submission_date'])); ?>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex gap-2">
                                    <a href="<?php echo BASE_URL; ?>/application/viewApplication/<?php echo $latestApplication['application_id']; ?>" 
                                        class="btn btn-primary">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                    
                                    <?php if ($latestApplication['application_status'] === 'submitted'): ?>
                                        <a href="<?php echo BASE_URL; ?>/application/bookMedicalSlot/<?php echo $latestApplication['application_id']; ?>" 
                                           class="btn btn-success">
                                            <i class="bi bi-calendar-plus"></i> Book Medical Slot
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($latestApplication['application_status'] === 'medical_passed'): ?>
                                        <a href="<?php echo BASE_URL; ?>/application/bookDrivingSlot/<?php echo $latestApplication['application_id']; ?>" 
                                           class="btn btn-success">
                                            <i class="bi bi-calendar-check"></i> Book Driving Test Slot
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($latestApplication['application_status'] === 'license_issued' && $license): ?>
                                        <a href="<?php echo BASE_URL; ?>/license/download/<?php echo $license['license_id']; ?>" 
                                           class="btn btn-info">
                                            <i class="bi bi-download"></i> Download License
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-list-ul"></i> My Applications</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($applications)): ?>
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No applications found</p>
                                    <a href="<?php echo BASE_URL; ?>/application/create" class="btn btn-primary">
                                        Submit Your First Application
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Reference ID</th>
                                                <th>License Type</th>
                                                <th>Status</th>
                                                <th>Submitted Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($applications as $app): ?>
                                                <tr>
                                                    <td><code><?php echo $app['reference_id']; ?></code></td>
                                                    <td><?php echo ucfirst(str_replace('_', ' ', $app['license_type'])); ?></td>
                                                    <td>
                                                        <span class="badge status-<?php echo $app['application_status']; ?>">
                                                            <?php echo str_replace('_', ' ', strtoupper($app['application_status'])); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('M d, Y', strtotime($app['submission_date'])); ?></td>
                                                    <td>
                                                        <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $app['application_id']; ?>" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>