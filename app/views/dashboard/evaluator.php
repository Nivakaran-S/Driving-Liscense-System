<?php
$pageTitle = 'Evaluator Dashboard';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <h2 class="mb-4">Evaluator Dashboard</h2>
            
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card dashboard-stat-card primary shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Tests</h6>
                            <div class="stat-number text-primary"><?php echo $stats['total_evaluations'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card dashboard-stat-card success shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Passed</h6>
                            <div class="stat-number text-success"><?php echo $stats['passed'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card dashboard-stat-card danger shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Failed</h6>
                            <div class="stat-number text-danger"><?php echo $stats['failed'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card dashboard-stat-card info shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Avg Score</h6>
                            <div class="stat-number text-info"><?php echo round($stats['average_score'] ?? 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Scheduled Driving Tests</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($scheduledTests)): ?>
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    <p>No scheduled driving tests</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Reference ID</th>
                                                <th>Applicant Name</th>
                                                <th>License Type</th>
                                                <th>Test Date</th>
                                                <th>Test Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($scheduledTests as $test): ?>
                                                <tr>
                                                    <td><code><?php echo $test['reference_id']; ?></code></td>
                                                    <td><?php echo $test['applicant_name']; ?></td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            <?php echo ucfirst(str_replace('_', ' ', $test['license_type'])); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('M d, Y', strtotime($test['slot_date'])); ?></td>
                                                    <td><?php echo date('h:i A', strtotime($test['slot_time'])); ?></td>
                                                    <td>
                                                        <a href="<?php echo BASE_URL; ?>/driving/evaluate/<?php echo $test['application_id']; ?>" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="bi bi-clipboard-check"></i> Evaluate
                                                        </a>
                                                        <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $test['application_id']; ?>" 
                                                           class="btn btn-sm btn-outline-secondary">
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
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Evaluations</h5>
                            <a href="<?php echo BASE_URL; ?>/driving/list" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($evaluationHistory)): ?>
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No evaluation history</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Reference ID</th>
                                                <th>Applicant</th>
                                                <th>License Type</th>
                                                <th>Evaluation Date</th>
                                                <th>Score</th>
                                                <th>Result</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($evaluationHistory as $eval): ?>
                                                <tr>
                                                    <td><code><?php echo $eval['reference_id']; ?></code></td>
                                                    <td><?php echo $eval['applicant_name']; ?></td>
                                                    <td><?php echo ucfirst(str_replace('_', ' ', $eval['license_type'])); ?></td>
                                                    <td><?php echo date('M d, Y H:i', strtotime($eval['evaluation_date'])); ?></td>
                                                    <td>
                                                        <div class="score-display <?php echo $eval['result'] == 'pass' ? 'score-pass' : 'score-fail'; ?>">
                                                            <?php echo $eval['overall_score']; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?php echo $eval['result'] == 'pass' ? 'bg-success' : 'bg-danger'; ?>">
                                                            <?php echo strtoupper($eval['result']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo BASE_URL; ?>/driving/view/<?php echo $eval['evaluation_id']; ?>" 
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
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>