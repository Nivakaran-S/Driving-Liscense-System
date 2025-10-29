<?php
$pageTitle = 'License Details';
include APP_ROOT . '/views/layouts/header.php';

$isValid = strtotime($license['expiry_date']) >= time();
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>License Details</h2>
                <div>
                    <a href="<?php echo BASE_URL; ?>/license/download/<?php echo $license['license_id']; ?>" 
                       class="btn btn-primary">
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="card license-card shadow-lg mb-4">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-white"><?php echo APP_NAME; ?></h3>
                                <h5 class="text-white">DRIVING LICENSE</h5>
                                <p class="text-white-50 mb-0">Democratic Socialist Republic of Sri Lanka</p>
                            </div>
                            
                            <?php if ($license['is_temporary']): ?>
                                <div class="text-center mb-3">
                                    <span class="badge bg-danger fs-4">TEMPORARY LICENSE</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="license-number text-center mb-4">
                                <?php echo $license['license_number']; ?>
                            </div>
                            
                            <div class="row text-white">
                                <div class="col-md-6 mb-3">
                                    <strong>Full Name:</strong><br>
                                    <?php echo strtoupper($license['full_name']); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>National ID:</strong><br>
                                    <?php echo $license['national_id']; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Date of Birth:</strong><br>
                                    <?php echo date('F d, Y', strtotime($license['date_of_birth'])); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>License Type:</strong><br>
                                    <?php echo ucfirst(str_replace('_', ' ', $license['license_type'])); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Issue Date:</strong><br>
                                    <?php echo date('F d, Y', strtotime($license['issue_date'])); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Expiry Date:</strong><br>
                                    <span class="<?php echo $isValid ? '' : 'text-danger'; ?>">
                                        <?php echo date('F d, Y', strtotime($license['expiry_date'])); ?>
                                        <?php if (!$isValid): ?>
                                            <i class="bi bi-exclamation-triangle"></i> EXPIRED
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Important Information</h5>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li>This temporary driving license is valid for 6 months from the date of issue.</li>
                                <li>Your permanent license will be mailed to your registered address within 30 days.</li>
                                <li>You must carry this license while operating a <?php echo $license['license_type']; ?>.</li>
                                <li>This license is valid only for the specified vehicle category.</li>
                                <li>Violation of traffic rules may result in license suspension or cancellation.</li>
                                <li>Report any changes to your address or personal information immediately.</li>
                            </ul>
                        </div>
                    </div>
                    
                    
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> License Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="<?php echo $isValid ? 'text-success' : 'text-danger'; ?>">
                                        <i class="bi bi-<?php echo $isValid ? 'check-circle' : 'x-circle'; ?> display-4"></i>
                                        <h6 class="mt-2"><?php echo $isValid ? 'VALID' : 'EXPIRED'; ?></h6>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="text-info">
                                        <i class="bi bi-calendar-event display-4"></i>
                                        <h6 class="mt-2">
                                            <?php 
                                            $daysLeft = floor((strtotime($license['expiry_date']) - time()) / 86400);
                                            echo $daysLeft > 0 ? "$daysLeft Days Left" : "Expired";
                                            ?>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="text-warning">
                                        <i class="bi bi-<?php echo $license['is_temporary'] ? 'clock-history' : 'card-heading'; ?> display-4"></i>
                                        <h6 class="mt-2"><?php echo $license['is_temporary'] ? 'TEMPORARY' : 'PERMANENT'; ?></h6>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Application Reference:</strong><br>
                                    <code><?php echo $license['reference_id']; ?></code>
                                </div>
                                <div class="col-md-6">
                                    <strong>License Issued On:</strong><br>
                                    <?php echo date('F d, Y H:i', strtotime($license['created_at'])); ?>
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