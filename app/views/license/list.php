<?php
$pageTitle = 'Issued Licenses';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <h2 class="mb-4">Issued Licenses</h2>
            
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>/license/list" class="row g-3">
                        <div class="col-md-3">
                            <label for="license_type" class="form-label">License Type</label>
                            <select class="form-select" id="license_type" name="license_type">
                                <option value="">All Types</option>
                                <option value="car" <?php echo ($filters['license_type'] ?? '') === 'car' ? 'selected' : ''; ?>>Car</option>
                                <option value="motorcycle" <?php echo ($filters['license_type'] ?? '') === 'motorcycle' ? 'selected' : ''; ?>>Motorcycle</option>
                                <option value="heavy_vehicle" <?php echo ($filters['license_type'] ?? '') === 'heavy_vehicle' ? 'selected' : ''; ?>>Heavy Vehicle</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="is_temporary" class="form-label">Status</label>
                            <select class="form-select" id="is_temporary" name="is_temporary">
                                <option value="">All</option>
                                <option value="1" <?php echo ($filters['is_temporary'] ?? '') === '1' ? 'selected' : ''; ?>>Temporary</option>
                                <option value="0" <?php echo ($filters['is_temporary'] ?? '') === '0' ? 'selected' : ''; ?>>Permanent</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="expired" class="form-label">Validity</label>
                            <select class="form-select" id="expired" name="expired">
                                <option value="">All</option>
                                <option value="no" <?php echo ($filters['expired'] ?? '') === 'no' ? 'selected' : ''; ?>>Valid</option>
                                <option value="yes" <?php echo ($filters['expired'] ?? '') === 'yes' ? 'selected' : ''; ?>>Expired</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <a href="<?php echo BASE_URL; ?>/license/list" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-card-heading"></i> All Licenses (<?php echo count($licenses); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($licenses)): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>No licenses found</p>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Search by license number, name, or email..." 
                                   data-table-search="licensesTable">
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover" id="licensesTable">
                                <thead>
                                    <tr>
                                        <th>License Number</th>
                                        <th>Holder Name</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Issue Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($licenses as $license): ?>
                                        <?php 
                                        $isValid = strtotime($license['expiry_date']) >= time();
                                        $daysLeft = floor((strtotime($license['expiry_date']) - time()) / 86400);
                                        ?>
                                        <tr>
                                            <td><code><?php echo $license['license_number']; ?></code></td>
                                            <td><?php echo $license['full_name']; ?></td>
                                            <td><?php echo $license['email']; ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo ucfirst(str_replace('_', ' ', $license['license_type'])); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($license['issue_date'])); ?></td>
                                            <td>
                                                <?php echo date('M d, Y', strtotime($license['expiry_date'])); ?>
                                                <?php if ($daysLeft <= 30 && $daysLeft > 0): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <?php echo $daysLeft; ?> days left
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($isValid): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Valid
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle"></i> Expired
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if ($license['is_temporary']): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        Temporary
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo BASE_URL; ?>/license/viewLicense/<?php echo $license['license_id']; ?>" 
                                                        class="btn btn-outline-primary" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>/license/download/<?php echo $license['license_id']; ?>" 
                                                       class="btn btn-outline-success" title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $license['application_id']; ?>" 
                                                       class="btn btn-outline-info" title="View Application">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                    </a>
                                                </div>
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