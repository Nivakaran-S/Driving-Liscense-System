<?php
$pageTitle = 'Medical Evaluation Details';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Medical Evaluation Details</h2>
                <a href="<?php echo BASE_URL; ?>/medical/list" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Evaluation Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Reference ID:</strong><br>
                                    <code class="fs-5"><?php echo $evaluation['reference_id']; ?></code>
                                </div>
                                <div class="col-md-6">
                                    <strong>Overall Result:</strong><br>
                                    <span class="badge <?php echo $evaluation['overall_result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?> fs-5">
                                        <?php echo strtoupper($evaluation['overall_result']); ?>
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
                                    <strong>Medical Officer:</strong><br>
                                    <?php echo $evaluation['medical_officer_name']; ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Evaluation Date:</strong><br>
                                    <?php echo date('F d, Y H:i', strtotime($evaluation['evaluation_date'])); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Blood Pressure:</strong><br>
                                    <?php echo $evaluation['blood_pressure'] ?: 'Not recorded'; ?>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6 class="mb-3">Test Results</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <i class="bi bi-eye display-4 <?php echo $evaluation['vision_test'] == 'pass' ? 'text-success' : 'text-danger'; ?>"></i>
                                            <h6 class="mt-2">Vision Test</h6>
                                            <span class="badge <?php echo $evaluation['vision_test'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo strtoupper($evaluation['vision_test']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <i class="bi bi-ear display-4 <?php echo $evaluation['hearing_test'] == 'pass' ? 'text-success' : 'text-danger'; ?>"></i>
                                            <h6 class="mt-2">Hearing Test</h6>
                                            <span class="badge <?php echo $evaluation['hearing_test'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo strtoupper($evaluation['hearing_test']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <i class="bi bi-heart-pulse display-4 <?php echo $evaluation['physical_fitness'] == 'pass' ? 'text-success' : 'text-danger'; ?>"></i>
                                            <h6 class="mt-2">Physical Fitness</h6>
                                            <span class="badge <?php echo $evaluation['physical_fitness'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo strtoupper($evaluation['physical_fitness']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($evaluation['remarks']): ?>
                                <hr>
                                <h6>Medical Officer Remarks</h6>
                                <div class="alert alert-light border">
                                    <?php echo nl2br(htmlspecialchars($evaluation['remarks'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card shadow-sm">
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
                    
                    <div class="card shadow-sm mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Evaluation Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Vision:</span>
                                <strong class="<?php echo $evaluation['vision_test'] == 'pass' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo strtoupper($evaluation['vision_test']); ?>
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Hearing:</span>
                                <strong class="<?php echo $evaluation['hearing_test'] == 'pass' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo strtoupper($evaluation['hearing_test']); ?>
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Physical:</span>
                                <strong class="<?php echo $evaluation['physical_fitness'] == 'pass' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo strtoupper($evaluation['physical_fitness']); ?>
                                </strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span><strong>Final Result:</strong></span>
                                <strong class="<?php echo $evaluation['overall_result'] == 'pass' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo strtoupper($evaluation['overall_result']); ?>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>