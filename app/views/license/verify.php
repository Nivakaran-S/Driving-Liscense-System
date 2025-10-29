<?php
$pageTitle = 'Verify License';
include APP_ROOT . '/views/layouts/header.php';

$license = $license ?? null;
$isValid = $license && (isset($isValid) ? $isValid : strtotime($license['expiry_date']) >= time());
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-shield-check"></i> Verify Driving License
                    </h2>
                    <p class="text-center text-muted mb-4">
                        Enter the license number to verify its authenticity and validity
                    </p>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/license/verify">
                        <div class="input-group input-group-lg mb-3">
                            <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                            <input type="text" class="form-control" name="license_number" 
                                   placeholder="Enter License Number (e.g., CAR-2025-123456)" 
                                   value="<?php echo $license_number ?? ''; ?>" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Verify
                            </button>
                        </div>
                    </form>
                    
                    <?php if ($license): ?>
                        <hr class="my-4">
                        
                        
                        <div class="alert <?php echo $isValid ? 'alert-success' : 'alert-danger'; ?> text-center">
                            <i class="bi bi-<?php echo $isValid ? 'check-circle' : 'x-circle'; ?> display-4"></i>
                            <h4 class="mt-3"><?php echo $isValid ? 'License is VALID' : 'License is EXPIRED or INVALID'; ?></h4>
                        </div>
                        
                        
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">License Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>License Number:</strong><br>
                                        <code class="fs-5"><?php echo $license['license_number']; ?></code>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>License Holder:</strong><br>
                                        <?php echo $license['full_name']; ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>License Type:</strong><br>
                                        <span class="badge bg-secondary">
                                            <?php echo ucfirst(str_replace('_', ' ', $license['license_type'])); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Issue Date:</strong><br>
                                        <?php echo date('F d, Y', strtotime($license['issue_date'])); ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Expiry Date:</strong><br>
                                        <span class="<?php echo $isValid ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo date('F d, Y', strtotime($license['expiry_date'])); ?>
                                        </span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>License Status:</strong><br>
                                        <?php if ($license['is_temporary']): ?>
                                            <span class="badge bg-warning text-dark">TEMPORARY</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">PERMANENT</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if ($isValid): ?>
                                    <?php 
                                    $daysLeft = floor((strtotime($license['expiry_date']) - time()) / 86400);
                                    ?>
                                    <div class="alert alert-info mt-3">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Validity:</strong> This license is valid for <?php echo $daysLeft; ?> more days.
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger mt-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <strong>Warning:</strong> This license has expired and is no longer valid for operating a motor vehicle.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="bi bi-<?php echo $isValid ? 'check-circle text-success' : 'x-circle text-danger'; ?> display-4"></i>
                                        <h6 class="mt-2">Validity</h6>
                                        <p class="mb-0"><?php echo $isValid ? 'Valid' : 'Expired'; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="bi bi-shield-check text-success display-4"></i>
                                        <h6 class="mt-2">Authenticity</h6>
                                        <p class="mb-0">Verified</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="bi bi-<?php echo $license['is_temporary'] ? 'clock-history text-warning' : 'card-heading text-info'; ?> display-4"></i>
                                        <h6 class="mt-2">Type</h6>
                                        <p class="mb-0"><?php echo $license['is_temporary'] ? 'Temporary' : 'Permanent'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Verified on <?php echo date('F d, Y H:i:s'); ?>
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-light">
                        <h6><i class="bi bi-info-circle"></i> How to Verify</h6>
                        <ul class="mb-0 small">
                            <li>Enter the complete license number as shown on the license document</li>
                            <li>License numbers are in the format: TYPE-YEAR-NUMBER (e.g., CAR-2025-123456)</li>
                            <li>This system verifies licenses issued through the official Driving License System</li>
                            <li>For any discrepancies, please contact the licensing authority</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>