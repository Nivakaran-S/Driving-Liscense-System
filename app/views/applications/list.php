<?php
$pageTitle = 'Applications';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Applications</h2>
                <?php if (Auth::getRole() === 'driver'): ?>
                    <a href="<?php echo BASE_URL; ?>/application/create" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> New Application
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>/application/list" class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="submitted" <?php echo ($filters['status'] ?? '') === 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                                <option value="medical_scheduled" <?php echo ($filters['status'] ?? '') === 'medical_scheduled' ? 'selected' : ''; ?>>Medical Scheduled</option>
                                <option value="medical_passed" <?php echo ($filters['status'] ?? '') === 'medical_passed' ? 'selected' : ''; ?>>Medical Passed</option>
                                <option value="medical_failed" <?php echo ($filters['status'] ?? '') === 'medical_failed' ? 'selected' : ''; ?>>Medical Failed</option>
                                <option value="driving_test_scheduled" <?php echo ($filters['status'] ?? '') === 'driving_test_scheduled' ? 'selected' : ''; ?>>Driving Test Scheduled</option>
                                <option value="driving_test_passed" <?php echo ($filters['status'] ?? '') === 'driving_test_passed' ? 'selected' : ''; ?>>Driving Test Passed</option>
                                <option value="driving_test_failed" <?php echo ($filters['status'] ?? '') === 'driving_test_failed' ? 'selected' : ''; ?>>Driving Test Failed</option>
                                <option value="license_issued" <?php echo ($filters['status'] ?? '') === 'license_issued' ? 'selected' : ''; ?>>License Issued</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="license_type" class="form-label">License Type</label>
                            <select class="form-select" id="license_type" name="license_type">
                                <option value="">All Types</option>
                                <option value="car" <?php echo ($filters['license_type'] ?? '') === 'car' ? 'selected' : ''; ?>>Car</option>
                                <option value="motorcycle" <?php echo ($filters['license_type'] ?? '') === 'motorcycle' ? 'selected' : ''; ?>>Motorcycle</option>
                                <option value="heavy_vehicle" <?php echo ($filters['license_type'] ?? '') === 'heavy_vehicle' ? 'selected' : ''; ?>>Heavy Vehicle</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <a href="<?php echo BASE_URL; ?>/application/list" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Applications (<?php echo count($applications); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($applications)): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>No applications found</p>
                            <?php if (Auth::getRole() === 'driver'): ?>
                                <a href="<?php echo BASE_URL; ?>/application/create" class="btn btn-primary">
                                    Submit Your First Application
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchTable" 
                                   placeholder="Search by reference ID, name, or email..." 
                                   data-table-search="applicationsTable">
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover" id="applicationsTable">
                                <thead>
                                    <tr>
                                        <th>Reference ID</th>
                                        <?php if (Auth::getRole() !== 'driver'): ?>
                                            <th>Applicant</th>
                                            <th>Email</th>
                                        <?php endif; ?>
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
                                            <?php if (Auth::getRole() !== 'driver'): ?>
                                                <td><?php echo $app['full_name'] ?? 'N/A'; ?></td>
                                                <td><?php echo $app['email'] ?? 'N/A'; ?></td>
                                            <?php endif; ?>
                                            <td><?php echo ucfirst(str_replace('_', ' ', $app['license_type'])); ?></td>
                                            <td>
                                                <span class="badge status-<?php echo $app['application_status']; ?>">
                                                    <?php echo str_replace('_', ' ', strtoupper($app['application_status'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($app['submission_date'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $app['application_id']; ?>" 
                                                       class="btn btn-outline-primary" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if ($app['application_status'] === 'submitted' && Auth::getRole() === 'driver'): ?>
                                                        <a href="<?php echo BASE_URL; ?>/application/bookMedicalSlot/<?php echo $app['application_id']; ?>" 
                                                           class="btn btn-outline-success" title="Book Medical Slot">
                                                            <i class="bi bi-calendar-plus"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($app['application_status'] === 'medical_passed' && Auth::getRole() === 'driver'): ?>
                                                        <a href="<?php echo BASE_URL; ?>/application/bookDrivingSlot/<?php echo $app['application_id']; ?>" 
                                                           class="btn btn-outline-success" title="Book Driving Test">
                                                            <i class="bi bi-calendar-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
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

<script>
document.getElementById('searchTable')?.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const table = document.getElementById('applicationsTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>