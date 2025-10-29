<?php
$pageTitle = 'Medical Evaluation';
include APP_ROOT . '/views/layouts/header.php';
$errors = $errors ?? [];
$data = $data ?? [];
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Medical Evaluation Form</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-light border">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Reference ID:</strong><br>
                                        <code><?php echo $application['reference_id']; ?></code>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Applicant Name:</strong><br>
                                        <?php echo $application['full_name']; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>License Type:</strong><br>
                                        <?php echo ucfirst(str_replace('_', ' ', $application['license_type'])); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Date of Birth:</strong><br>
                                        <?php echo date('M d, Y', strtotime($application['date_of_birth'])); ?>
                                        (<?php echo floor((time() - strtotime($application['date_of_birth'])) / 31556926); ?> years)
                                    </div>
                                </div>
                            </div>
                            
                            <form method="POST" action="<?php echo BASE_URL; ?>/medical/evaluate/<?php echo $application['application_id']; ?>" class="needs-validation" novalidate>
                                <?php echo Session::csrfField(); ?>
                                <!-- Slot ID will be retrieved from application record -->
                                
                                <h5 class="mb-3">Test Results</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card <?php echo isset($errors['vision_test']) ? 'border-danger' : ''; ?>">
                                            <div class="card-body">
                                                <h6 class="card-title"><i class="bi bi-eye"></i> Vision Test</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="vision_test" 
                                                           id="vision_pass" value="pass" 
                                                           <?php echo ($data['vision_test'] ?? '') === 'pass' ? 'checked' : ''; ?> required>
                                                    <label class="form-check-label text-success" for="vision_pass">
                                                        <i class="bi bi-check-circle"></i> Pass
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="vision_test" 
                                                           id="vision_fail" value="fail"
                                                           <?php echo ($data['vision_test'] ?? '') === 'fail' ? 'checked' : ''; ?>>
                                                    <label class="form-check-label text-danger" for="vision_fail">
                                                        <i class="bi bi-x-circle"></i> Fail
                                                    </label>
                                                </div>
                                                <?php if (isset($errors['vision_test'])): ?>
                                                    <div class="text-danger small mt-2"><?php echo $errors['vision_test']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <div class="card <?php echo isset($errors['hearing_test']) ? 'border-danger' : ''; ?>">
                                            <div class="card-body">
                                                <h6 class="card-title"><i class="bi bi-ear"></i> Hearing Test</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="hearing_test" 
                                                           id="hearing_pass" value="pass"
                                                           <?php echo ($data['hearing_test'] ?? '') === 'pass' ? 'checked' : ''; ?> required>
                                                    <label class="form-check-label text-success" for="hearing_pass">
                                                        <i class="bi bi-check-circle"></i> Pass
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="hearing_test" 
                                                           id="hearing_fail" value="fail"
                                                           <?php echo ($data['hearing_test'] ?? '') === 'fail' ? 'checked' : ''; ?>>
                                                    <label class="form-check-label text-danger" for="hearing_fail">
                                                        <i class="bi bi-x-circle"></i> Fail
                                                    </label>
                                                </div>
                                                <?php if (isset($errors['hearing_test'])): ?>
                                                    <div class="text-danger small mt-2"><?php echo $errors['hearing_test']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <div class="card <?php echo isset($errors['physical_fitness']) ? 'border-danger' : ''; ?>">
                                            <div class="card-body">
                                                <h6 class="card-title"><i class="bi bi-heart-pulse"></i> Physical Fitness</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="physical_fitness" 
                                                           id="physical_pass" value="pass"
                                                           <?php echo ($data['physical_fitness'] ?? '') === 'pass' ? 'checked' : ''; ?> required>
                                                    <label class="form-check-label text-success" for="physical_pass">
                                                        <i class="bi bi-check-circle"></i> Pass
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="physical_fitness" 
                                                           id="physical_fail" value="fail"
                                                           <?php echo ($data['physical_fitness'] ?? '') === 'fail' ? 'checked' : ''; ?>>
                                                    <label class="form-check-label text-danger" for="physical_fail">
                                                        <i class="bi bi-x-circle"></i> Fail
                                                    </label>
                                                </div>
                                                <?php if (isset($errors['physical_fitness'])): ?>
                                                    <div class="text-danger small mt-2"><?php echo $errors['physical_fitness']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label for="blood_pressure" class="form-label">
                                            <i class="bi bi-activity"></i> Blood Pressure
                                        </label>
                                        <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" 
                                               placeholder="e.g., 120/80" value="<?php echo $data['blood_pressure'] ?? ''; ?>">
                                        <small class="text-muted">Enter systolic/diastolic reading</small>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="remarks" class="form-label">Remarks / Additional Notes</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="4" 
                                              placeholder="Enter any additional observations or notes..."><?php echo $data['remarks'] ?? ''; ?></textarea>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <strong>Note:</strong> The applicant must pass all three tests (Vision, Hearing, Physical Fitness) to proceed to the driving test.
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Submit Evaluation
                                    </button>
                                    <a href="<?php echo BASE_URL; ?>/dashboard/medical" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>