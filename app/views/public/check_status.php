<?php
$pageTitle = 'Check Application Status';
include APP_ROOT . '/views/layouts/header.php';

$application = $application ?? null;
$details = $details ?? [];
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-search"></i> Check Application Status
                    </h2>
                    <p class="text-center text-muted mb-4">
                        Enter your reference ID to track your application
                    </p>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/public/checkStatus">
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" class="form-control" name="reference_id" 
                                   placeholder="Enter Reference ID (e.g., DL2025ABCD1234)" 
                                   value="<?php echo $reference_id ?? ''; ?>" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Check Status
                            </button>
                        </div>
                    </form>
                    
                    <?php if ($application): ?>
                        <hr class="my-4">
                        
                        <div class="alert alert-info">
                            <h5><i class="bi bi-info-circle"></i> Application Found</h5>
                            <p class="mb-0">Reference ID: <strong><?php echo $application['reference_id']; ?></strong></p>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Applicant Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> <?php echo $application['full_name']; ?></p>
                                        <p><strong>Email:</strong> <?php echo $application['email']; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>License Type:</strong> <?php echo ucfirst(str_replace('_', ' ', $application['license_type'])); ?></p>
                                        <p><strong>Submission Date:</strong> <?php echo date('F d, Y', strtotime($application['submission_date'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Application Progress</h5>
                                
                                <div class="timeline">
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker bg-success">
                                            <i class="bi bi-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Application Submitted</h6>
                                            <p class="text-muted small mb-0">
                                                <?php echo date('F d, Y H:i', strtotime($application['submission_date'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item <?php echo in_array($application['application_status'], ['medical_scheduled', 'medical_passed', 'medical_failed', 'driving_test_scheduled', 'driving_test_passed', 'driving_test_failed', 'license_issued']) ? 'completed' : ''; ?>">
                                        <div class="timeline-marker <?php echo in_array($application['application_status'], ['medical_passed', 'driving_test_scheduled', 'driving_test_passed', 'driving_test_failed', 'license_issued']) ? 'bg-success' : (in_array($application['application_status'], ['medical_scheduled']) ? 'bg-warning' : ($application['application_status'] == 'medical_failed' ? 'bg-danger' : 'bg-secondary')); ?>">
                                            <i class="bi <?php echo in_array($application['application_status'], ['medical_passed', 'driving_test_scheduled', 'driving_test_passed', 'driving_test_failed', 'license_issued']) ? 'bi-check' : ($application['application_status'] == 'medical_failed' ? 'bi-x' : 'bi-clock'); ?>"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Medical Evaluation</h6>
                                            <?php if (isset($details['medical'])): ?>
                                                <p class="text-muted small mb-1">
                                                    Evaluated on: <?php echo date('F d, Y', strtotime($details['medical']['evaluation_date'])); ?>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="badge <?php echo $details['medical']['overall_result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo strtoupper($details['medical']['overall_result']); ?>
                                                    </span>
                                                </p>
                                            <?php elseif ($application['application_status'] == 'medical_scheduled'): ?>
                                                <p class="text-muted small mb-0">
                                                    Scheduled for: <?php echo date('F d, Y H:i', strtotime($application['medical_test_date'])); ?>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-muted small mb-0">Pending</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item <?php echo in_array($application['application_status'], ['driving_test_scheduled', 'driving_test_passed', 'driving_test_failed', 'license_issued']) ? 'completed' : ''; ?>">
                                        <div class="timeline-marker <?php echo in_array($application['application_status'], ['driving_test_passed', 'license_issued']) ? 'bg-success' : (in_array($application['application_status'], ['driving_test_scheduled']) ? 'bg-warning' : ($application['application_status'] == 'driving_test_failed' ? 'bg-danger' : 'bg-secondary')); ?>">
                                            <i class="bi <?php echo in_array($application['application_status'], ['driving_test_passed', 'license_issued']) ? 'bi-check' : ($application['application_status'] == 'driving_test_failed' ? 'bi-x' : 'bi-clock'); ?>"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Driving Test</h6>
                                            <?php if (isset($details['driving'])): ?>
                                                <p class="text-muted small mb-1">
                                                    Evaluated on: <?php echo date('F d, Y', strtotime($details['driving']['evaluation_date'])); ?>
                                                </p>
                                                <p class="mb-0">
                                                    Score: <strong><?php echo $details['driving']['overall_score']; ?>/100</strong>
                                                    <span class="badge <?php echo $details['driving']['result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo strtoupper($details['driving']['result']); ?>
                                                    </span>
                                                </p>
                                            <?php elseif ($application['application_status'] == 'driving_test_scheduled'): ?>
                                                <p class="text-muted small mb-0">
                                                    Scheduled for: <?php echo date('F d, Y H:i', strtotime($application['driving_test_date'])); ?>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-muted small mb-0">Pending</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item <?php echo $application['application_status'] == 'license_issued' ? 'completed' : ''; ?>">
                                        <div class="timeline-marker <?php echo $application['application_status'] == 'license_issued' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <i class="bi <?php echo $application['application_status'] == 'license_issued' ? 'bi-check' : 'bi-clock'; ?>"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>License Issued</h6>
                                            <?php if (isset($details['license'])): ?>
                                                <p class="text-muted small mb-1">
                                                    Issued on: <?php echo date('F d, Y', strtotime($details['license']['issue_date'])); ?>
                                                </p>
                                                <p class="mb-0">
                                                    License Number: <strong><?php echo $details['license']['license_number']; ?></strong>
                                                </p>
                                                <div class="alert alert-success mt-2 mb-0">
                                                    <i class="bi bi-check-circle"></i> 
                                                    Your temporary license is ready! Please login to download.
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted small mb-0">Pending</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login to View Full Details
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 30px;
    position: relative;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    bottom: -30px;
    width: 2px;
    background: #dee2e6;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
    margin-right: 20px;
    z-index: 1;
}

.timeline-content {
    flex-grow: 1;
}

.timeline-item.completed .timeline-content h6 {
    color: #198754;
}
</style>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>