<?php
$pageTitle = 'Driving Test Evaluation';
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
                        <div class="card-header bg-warning text-dark">
                            <h4 class="mb-0"><i class="bi bi-car-front"></i> Driving Test Evaluation Form</h4>
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
                                        <span class="badge bg-secondary">
                                            <?php echo ucfirst(str_replace('_', ' ', $application['license_type'])); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Test Date:</strong><br>
                                        <?php echo date('M d, Y H:i', strtotime($application['driving_test_date'])); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <form method="POST" action="<?php echo BASE_URL; ?>/driving/evaluate/<?php echo $application['application_id']; ?>" class="needs-validation" novalidate>
                                <?php echo Session::csrfField(); ?>
                                <!-- Slot ID will be retrieved from application record -->
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Scoring Guide:</strong> Rate each category from 0-100. Passing score is <?php echo PASSING_SCORE; ?> or above (average of all categories).
                                </div>
                                
                                <h5 class="mb-3">Evaluation Criteria</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <label for="vehicle_control_score" class="form-label">
                                                    <i class="bi bi-joystick"></i> <strong>Vehicle Control</strong>
                                                </label>
                                                <input type="number" class="form-control form-control-lg <?php echo isset($errors['vehicle_control_score']) ? 'is-invalid' : ''; ?>" 
                                                       id="vehicle_control_score" name="vehicle_control_score" 
                                                       min="0" max="100" value="<?php echo $data['vehicle_control_score'] ?? ''; ?>" required>
                                                <small class="text-muted">Steering, acceleration, braking</small>
                                                <div class="progress mt-2" style="height: 25px;">
                                                    <div class="progress-bar" id="vehicle_progress" style="width: 0%">0</div>
                                                </div>
                                                <?php if (isset($errors['vehicle_control_score'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['vehicle_control_score']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <label for="traffic_rules_score" class="form-label">
                                                    <i class="bi bi-stoplights"></i> <strong>Traffic Rules Knowledge</strong>
                                                </label>
                                                <input type="number" class="form-control form-control-lg <?php echo isset($errors['traffic_rules_score']) ? 'is-invalid' : ''; ?>" 
                                                       id="traffic_rules_score" name="traffic_rules_score" 
                                                       min="0" max="100" value="<?php echo $data['traffic_rules_score'] ?? ''; ?>" required>
                                                <small class="text-muted">Signs, signals, right of way</small>
                                                <div class="progress mt-2" style="height: 25px;">
                                                    <div class="progress-bar bg-success" id="traffic_progress" style="width: 0%">0</div>
                                                </div>
                                                <?php if (isset($errors['traffic_rules_score'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['traffic_rules_score']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <label for="parking_score" class="form-label">
                                                    <i class="bi bi-p-square"></i> <strong>Parking Skills</strong>
                                                </label>
                                                <input type="number" class="form-control form-control-lg <?php echo isset($errors['parking_score']) ? 'is-invalid' : ''; ?>" 
                                                       id="parking_score" name="parking_score" 
                                                       min="0" max="100" value="<?php echo $data['parking_score'] ?? ''; ?>" required>
                                                <small class="text-muted">Parallel, reverse, angle parking</small>
                                                <div class="progress mt-2" style="height: 25px;">
                                                    <div class="progress-bar bg-info" id="parking_progress" style="width: 0%">0</div>
                                                </div>
                                                <?php if (isset($errors['parking_score'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['parking_score']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <label for="road_safety_score" class="form-label">
                                                    <i class="bi bi-shield-check"></i> <strong>Road Safety Awareness</strong>
                                                </label>
                                                <input type="number" class="form-control form-control-lg <?php echo isset($errors['road_safety_score']) ? 'is-invalid' : ''; ?>" 
                                                       id="road_safety_score" name="road_safety_score" 
                                                       min="0" max="100" value="<?php echo $data['road_safety_score'] ?? ''; ?>" required>
                                                <small class="text-muted">Defensive driving, awareness</small>
                                                <div class="progress mt-2" style="height: 25px;">
                                                    <div class="progress-bar bg-warning" id="safety_progress" style="width: 0%">0</div>
                                                </div>
                                                <?php if (isset($errors['road_safety_score'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['road_safety_score']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4 bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="mb-3">Overall Score</h5>
                                        <div class="display-3 fw-bold" id="overallScore">0</div>
                                        <div class="mt-2">
                                            <span class="badge fs-5" id="resultBadge">Calculating...</span>
                                        </div>
                                        <small class="text-muted d-block mt-2">Passing score: <?php echo PASSING_SCORE; ?> or above</small>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="remarks" class="form-label"><strong>Evaluator Remarks / Feedback</strong></label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="4" 
                                              placeholder="Enter detailed feedback, strengths, areas for improvement..."><?php echo $data['remarks'] ?? ''; ?></textarea>
                                    <small class="text-muted">Provide constructive feedback for the applicant</small>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Submit Evaluation
                                    </button>
                                    <a href="<?php echo BASE_URL; ?>/dashboard/evaluator" class="btn btn-outline-secondary">
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

<script>
const scoreInputs = ['vehicle_control_score', 'traffic_rules_score', 'parking_score', 'road_safety_score'];
const progressBars = {
    'vehicle_control_score': 'vehicle_progress',
    'traffic_rules_score': 'traffic_progress',
    'parking_score': 'parking_progress',
    'road_safety_score': 'safety_progress'
};

function updateScores() {
    let total = 0;
    let count = 0;
    
    scoreInputs.forEach(id => {
        const input = document.getElementById(id);
        const value = parseInt(input.value) || 0;
        
        const progressBar = document.getElementById(progressBars[id]);
        if (progressBar) {
            progressBar.style.width = value + '%';
            progressBar.textContent = value;
            progressBar.className = 'progress-bar';
            if (value >= 75) progressBar.classList.add('bg-success');
            else if (value >= 60) progressBar.classList.add('bg-warning');
            else progressBar.classList.add('bg-danger');
        }
        
        if (value > 0) {
            total += value;
            count++;
        }
    });
    
    const average = count > 0 ? Math.round(total / count) : 0;
    const overallScoreEl = document.getElementById('overallScore');
    const resultBadge = document.getElementById('resultBadge');
    
    overallScoreEl.textContent = average;
    
    if (count === 4) {
        if (average >= <?php echo PASSING_SCORE; ?>) {
            resultBadge.textContent = 'PASS';
            resultBadge.className = 'badge bg-success fs-5';
        } else {
            resultBadge.textContent = 'FAIL';
            resultBadge.className = 'badge bg-danger fs-5';
        }
    } else {
        resultBadge.textContent = 'Enter all scores';
        resultBadge.className = 'badge bg-secondary fs-5';
    }
}

scoreInputs.forEach(id => {
    document.getElementById(id).addEventListener('input', updateScores);
});

updateScores();
</script>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>