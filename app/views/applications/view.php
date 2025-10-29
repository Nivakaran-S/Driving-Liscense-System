<?php
$pageTitle = 'Application Details';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Application Details</h2>
                <a href="<?php echo BASE_URL; ?>/application/list" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
            
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Application Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Reference ID:</strong><br>
                                    <code class="fs-5"><?php echo $application['reference_id']; ?></code>
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong><br>
                                    <span class="badge status-<?php echo $application['application_status']; ?> fs-6">
                                        <?php echo str_replace('_', ' ', strtoupper($application['application_status'])); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Applicant Name:</strong><br>
                                    <?php echo $application['full_name']; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Email:</strong><br>
                                    <?php echo $application['email']; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Phone:</strong><br>
                                    <?php echo $application['phone']; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>National ID:</strong><br>
                                    <?php echo $application['national_id']; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>License Type:</strong><br>
                                    <?php echo ucfirst(str_replace('_', ' ', $application['license_type'])); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Submission Date:</strong><br>
                                    <?php echo date('F d, Y H:i', strtotime($application['submission_date'])); ?>
                                </div>
                            </div>
                            
                            <?php if ($application['medical_test_date']): ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Medical Test Date:</strong><br>
                                        <?php echo date('F d, Y H:i', strtotime($application['medical_test_date'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($application['driving_test_date']): ?>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Driving Test Date:</strong><br>
                                        <?php echo date('F d, Y H:i', strtotime($application['driving_test_date'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($application['notes']): ?>
                                <hr>
                                <div class="alert alert-warning">
                                    <strong>Notes:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($application['notes'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-lightning-charge"></i> Actions</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($application['application_status'] === 'submitted'): ?>
                                <a href="<?php echo BASE_URL; ?>/application/bookMedicalSlot/<?php echo $application['application_id']; ?>" 
                                   class="btn btn-success w-100 mb-2">
                                    <i class="bi bi-calendar-plus"></i> Book Medical Slot
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($application['application_status'] === 'medical_passed'): ?>
                                <a href="<?php echo BASE_URL; ?>/application/bookDrivingSlot/<?php echo $application['application_id']; ?>" 
                                   class="btn btn-success w-100 mb-2">
                                    <i class="bi bi-calendar-check"></i> Book Driving Test
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($license): ?>
                                <a href="<?php echo BASE_URL; ?>/license/download/<?php echo $license['license_id']; ?>" 
                                   class="btn btn-primary w-100 mb-2">
                                    <i class="bi bi-download"></i> Download License
                                </a>
                                <a href="<?php echo BASE_URL; ?>/license/view/<?php echo $license['license_id']; ?>" 
                                   class="btn btn-outline-primary w-100 mb-2">
                                    <i class="bi bi-eye"></i> View License
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($medicalEvaluation): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Medical Evaluation Results</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Evaluation Date:</strong><br>
                                        <?php echo date('F d, Y H:i', strtotime($medicalEvaluation['evaluation_date'])); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Medical Officer:</strong><br>
                                        <?php echo $medicalEvaluation['medical_officer_name']; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Overall Result:</strong><br>
                                        <span class="badge <?php echo $medicalEvaluation['overall_result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?> fs-6">
                                            <?php echo strtoupper($medicalEvaluation['overall_result']); ?>
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Vision Test:</strong> 
                                        <span class="badge <?php echo $medicalEvaluation['vision_test'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo strtoupper($medicalEvaluation['vision_test']); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Hearing Test:</strong> 
                                        <span class="badge <?php echo $medicalEvaluation['hearing_test'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo strtoupper($medicalEvaluation['hearing_test']); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Physical Fitness:</strong> 
                                        <span class="badge <?php echo $medicalEvaluation['physical_fitness'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo strtoupper($medicalEvaluation['physical_fitness']); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Blood Pressure:</strong> <?php echo $medicalEvaluation['blood_pressure']; ?>
                                    </div>
                                </div>
                                <?php if ($medicalEvaluation['remarks']): ?>
                                    <hr>
                                    <strong>Remarks:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($medicalEvaluation['remarks'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($drivingEvaluation): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="bi bi-car-front"></i> Driving Test Results</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <strong>Evaluation Date:</strong><br>
                                        <?php echo date('F d, Y H:i', strtotime($drivingEvaluation['evaluation_date'])); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Evaluator:</strong><br>
                                        <?php echo $drivingEvaluation['evaluator_name']; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Overall Score:</strong><br>
                                        <div class="score-display <?php echo $drivingEvaluation['result'] == 'pass' ? 'score-pass' : 'score-fail'; ?>">
                                            <?php echo $drivingEvaluation['overall_score']; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Result:</strong><br>
                                        <span class="badge <?php echo $drivingEvaluation['result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?> fs-6">
                                            <?php echo strtoupper($drivingEvaluation['result']); ?>
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <h6>Score Breakdown:</h6>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <strong>Vehicle Control:</strong>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?php echo $drivingEvaluation['vehicle_control_score']; ?>%">
                                                <?php echo $drivingEvaluation['vehicle_control_score']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <strong>Traffic Rules:</strong>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?php echo $drivingEvaluation['traffic_rules_score']; ?>%">
                                                <?php echo $drivingEvaluation['traffic_rules_score']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <strong>Parking:</strong>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?php echo $drivingEvaluation['parking_score']; ?>%">
                                                <?php echo $drivingEvaluation['parking_score']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <strong>Road Safety:</strong>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?php echo $drivingEvaluation['road_safety_score']; ?>%">
                                                <?php echo $drivingEvaluation['road_safety_score']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($drivingEvaluation['remarks']): ?>
                                    <hr>
                                    <strong>Evaluator Remarks:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($drivingEvaluation['remarks'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>