<?php
$pageTitle = 'Driving Test Details';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Driving Test Evaluation Details</h2>
                <a href="<?php echo BASE_URL; ?>/driving/list" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-car-front"></i> Evaluation Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Reference ID:</strong><br>
                                    <code class="fs-5"><?php echo $evaluation['reference_id']; ?></code>
                                </div>
                                <div class="col-md-4">
                                    <strong>Overall Score:</strong><br>
                                    <div class="score-display <?php echo $evaluation['result'] == 'pass' ? 'score-pass' : 'score-fail'; ?>">
                                        <?php echo $evaluation['overall_score']; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <strong>Result:</strong><br>
                                    <span class="badge <?php echo $evaluation['result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?> fs-5">
                                        <?php echo strtoupper($evaluation['result']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Applicant Name:</strong><br>
                                    <?php echo $evaluation['applicant_name']; ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Evaluator:</strong><br>
                                    <?php echo $evaluation['evaluator_name']; ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Evaluation Date:</strong><br>
                                    <?php echo date('F d, Y H:i', strtotime($evaluation['evaluation_date'])); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>License Type:</strong><br>
                                    <span class="badge bg-secondary">
                                        <?php echo ucfirst(str_replace('_', ' ', $evaluation['license_type'])); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6 class="mb-3">Score Breakdown</h6>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-joystick"></i> <strong>Vehicle Control</strong></span>
                                    <span class="badge bg-primary"><?php echo $evaluation['vehicle_control_score']; ?>/100</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" style="width: <?php echo $evaluation['vehicle_control_score']; ?>%">
                                        <?php echo $evaluation['vehicle_control_score']; ?>%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-stoplights"></i> <strong>Traffic Rules Knowledge</strong></span>
                                    <span class="badge bg-success"><?php echo $evaluation['traffic_rules_score']; ?>/100</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo $evaluation['traffic_rules_score']; ?>%">
                                        <?php echo $evaluation['traffic_rules_score']; ?>%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-p-square"></i> <strong>Parking Skills</strong></span>
                                    <span class="badge bg-info"><?php echo $evaluation['parking_score']; ?>/100</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-info" style="width: <?php echo $evaluation['parking_score']; ?>%">
                                        <?php echo $evaluation['parking_score']; ?>%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><i class="bi bi-shield-check"></i> <strong>Road Safety Awareness</strong></span>
                                    <span class="badge bg-warning text-dark"><?php echo $evaluation['road_safety_score']; ?>/100</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-warning" style="width: <?php echo $evaluation['road_safety_score']; ?>%">
                                        <?php echo $evaluation['road_safety_score']; ?>%
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($evaluation['remarks']): ?>
                                <hr>
                                <h6>Evaluator Feedback</h6>
                                <div class="alert alert-light border">
                                    <?php echo nl2br(htmlspecialchars($evaluation['remarks'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $evaluation['application_id']; ?>" 
                               class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-file-earmark-text"></i> View Application
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-printer"></i> Print
                            </button>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="mb-0">Performance Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="display-4 fw-bold <?php echo $evaluation['result'] == 'pass' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $evaluation['overall_score']; ?>
                                </div>
                                <small class="text-muted">Overall Score</small>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Vehicle Control:</span>
                                <strong><?php echo $evaluation['vehicle_control_score']; ?>%</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Traffic Rules:</span>
                                <strong><?php echo $evaluation['traffic_rules_score']; ?>%</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Parking:</span>
                                <strong><?php echo $evaluation['parking_score']; ?>%</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Road Safety:</span>
                                <strong><?php echo $evaluation['road_safety_score']; ?>%</strong>
                            </div>
                            
                            <hr>
                            
                            <div class="text-center">
                                <span class="badge <?php echo $evaluation['result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?> fs-6">
                                    <?php echo $evaluation['result'] == 'pass' ? '✓ PASSED' : '✗ FAILED'; ?>
                                </span>
                                <div class="mt-2 small text-muted">
                                    Passing Score: <?php echo PASSING_SCORE; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>