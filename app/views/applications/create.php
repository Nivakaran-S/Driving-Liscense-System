<?php
$pageTitle = 'Submit New Application';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="bi bi-file-earmark-plus"></i> Submit New Application</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle"></i> Before You Begin</h6>
                                <ul class="mb-0">
                                    <li>Ensure you meet the minimum age requirement (18 years)</li>
                                    <li>Have your National ID ready</li>
                                    <li>Choose the correct license type for your needs</li>
                                    <li>You can only have one active application at a time</li>
                                </ul>
                            </div>
                            
                            <form method="POST" action="<?php echo BASE_URL; ?>/application/create" class="needs-validation" novalidate>
                                <?php echo Session::csrfField(); ?>
                                
                                <div class="mb-4">
                                    <label for="license_type" class="form-label">License Type *</label>
                                    <select class="form-select form-select-lg" id="license_type" name="license_type" required>
                                        <option value="">Select License Type</option>
                                        <option value="car">Car (Light Motor Vehicle)</option>
                                        <option value="motorcycle">Motorcycle (Two-Wheeler)</option>
                                        <option value="heavy_vehicle">Heavy Vehicle (Lorry/Bus)</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a license type</div>
                                </div>
                                
                                <div id="licenseInfo" class="alert alert-light" style="display: none;">
                                    <h6 class="mb-2">License Requirements:</h6>
                                    <div id="licenseDetails"></div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6>Application Process:</h6>
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary">1</div>
                                            <div class="timeline-content">
                                                <strong>Submit Application</strong>
                                                <p class="text-muted small mb-0">Complete this form and submit</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-secondary">2</div>
                                            <div class="timeline-content">
                                                <strong>Book Medical Slot</strong>
                                                <p class="text-muted small mb-0">Choose available date for medical evaluation</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-secondary">3</div>
                                            <div class="timeline-content">
                                                <strong>Medical Evaluation</strong>
                                                <p class="text-muted small mb-0">Attend and pass medical test</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-secondary">4</div>
                                            <div class="timeline-content">
                                                <strong>Book Driving Test</strong>
                                                <p class="text-muted small mb-0">Schedule your driving test</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-secondary">5</div>
                                            <div class="timeline-content">
                                                <strong>Driving Test</strong>
                                                <p class="text-muted small mb-0">Complete driving evaluation</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-secondary">6</div>
                                            <div class="timeline-content">
                                                <strong>Get License</strong>
                                                <p class="text-muted small mb-0">Download temporary license (valid 6 months)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="agreement" required>
                                    <label class="form-check-label" for="agreement">
                                        I confirm that all information provided is accurate and I agree to the terms and conditions *
                                    </label>
                                    <div class="invalid-feedback">You must agree to continue</div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Submit Application
                                    </button>
                                    <a href="<?php echo BASE_URL; ?>/dashboard/driver" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Cancel
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

<script>
document.getElementById('license_type').addEventListener('change', function() {
    const infoDiv = document.getElementById('licenseInfo');
    const detailsDiv = document.getElementById('licenseDetails');
    
    const requirements = {
        'car': '<ul><li>Minimum age: 18 years</li><li>Valid medical certificate</li><li>Pass theory test (if required)</li><li>Practical driving test</li></ul>',
        'motorcycle': '<ul><li>Minimum age: 18 years</li><li>Valid medical certificate</li><li>Pass theory test (if required)</li><li>Practical riding test</li></ul>',
        'heavy_vehicle': '<ul><li>Minimum age: 21 years</li><li>Valid light vehicle license (may be required)</li><li>Enhanced medical certificate</li><li>Specialized training certificate</li><li>Practical test with heavy vehicle</li></ul>'
    };
    
    if (this.value && requirements[this.value]) {
        detailsDiv.innerHTML = requirements[this.value];
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
});
</script>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>