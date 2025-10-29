<?php
$pageTitle = 'Medical Evaluations';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <h2 class="mb-4">Medical Evaluations</h2>
            

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>/medical/list" class="row g-3">
                        <div class="col-md-3">
                            <label for="result" class="form-label">Result</label>
                            <select class="form-select" id="result" name="result">
                                <option value="">All Results</option>
                                <option value="pass" <?php echo ($filters['result'] ?? '') === 'pass' ? 'selected' : ''; ?>>Pass</option>
                                <option value="fail" <?php echo ($filters['result'] ?? '') === 'fail' ? 'selected' : ''; ?>>Fail</option>
                            </select>
                        </div>
                        
                        <?php if (Auth::getRole() === 'admin' && isset($medicalOfficers)): ?>
                        <div class="col-md-3">
                            <label for="officer_id" class="form-label">Medical Officer</label>
                            <select class="form-select" id="officer_id" name="officer_id">
                                <option value="">All Officers</option>
                                <?php foreach ($medicalOfficers as $officer): ?>
                                    <option value="<?php echo $officer['user_id']; ?>" 
                                            <?php echo ($filters['officer_id'] ?? '') == $officer['user_id'] ? 'selected' : ''; ?>>
                                        <?php echo $officer['full_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="<?php echo $filters['date_from'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="<?php echo $filters['date_to'] ?? ''; ?>">
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <a href="<?php echo BASE_URL; ?>/medical/list" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> All Evaluations (<?php echo count($evaluations); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($evaluations)): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>No evaluations found</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Reference ID</th>
                                        <th>Applicant</th>
                                        <?php if (Auth::getRole() === 'admin'): ?>
                                            <th>Medical Officer</th>
                                        <?php endif; ?>
                                        <th>License Type</th>
                                        <th>Evaluation Date</th>
                                        <th>Vision</th>
                                        <th>Hearing</th>
                                        <th>Physical</th>
                                        <th>Result</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($evaluations as $eval): ?>
                                        <tr>
                                            <td><code><?php echo $eval['reference_id']; ?></code></td>
                                            <td><?php echo $eval['applicant_name']; ?></td>
                                            <?php if (Auth::getRole() === 'admin'): ?>
                                                <td><?php echo $eval['medical_officer_name']; ?></td>
                                            <?php endif; ?>
                                            <td><?php echo ucfirst(str_replace('_', ' ', $eval['license_type'])); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($eval['evaluation_date'])); ?></td>
                                            <td>
                                                <i class="bi bi-<?php echo $eval['vision_test'] == 'pass' ? 'check-circle text-success' : 'x-circle text-danger'; ?>"></i>
                                            </td>
                                            <td>
                                                <i class="bi bi-<?php echo $eval['hearing_test'] == 'pass' ? 'check-circle text-success' : 'x-circle text-danger'; ?>"></i>
                                            </td>
                                            <td>
                                                <i class="bi bi-<?php echo $eval['physical_fitness'] == 'pass' ? 'check-circle text-success' : 'x-circle text-danger'; ?>"></i>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $eval['overall_result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo strtoupper($eval['overall_result']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>/medical/viewEvaluation/<?php echo $eval['evaluation_id']; ?>" 
                                                    class="btn btn-sm btn-outline-primary">
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

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>