<?php
$pageTitle = 'Manage Medical Slots';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <h2 class="mb-4">Manage Medical Evaluation Slots</h2>
            
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Slot</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>/slot/createMedical" class="needs-validation" novalidate>
                        <?php echo Session::csrfField(); ?>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="slot_date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="slot_date" name="slot_date" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <label for="slot_time" class="form-label">Time *</label>
                                <input type="time" class="form-control" id="slot_time" name="slot_time" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="medical_officer_id" class="form-label">Medical Officer *</label>
                                <select class="form-select" id="medical_officer_id" name="medical_officer_id" required>
                                    <option value="">Select Officer</option>
                                    <?php foreach ($medicalOfficers as $officer): ?>
                                        <option value="<?php echo $officer['user_id']; ?>">
                                            <?php echo $officer['full_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <label for="max_capacity" class="form-label">Capacity *</label>
                                <input type="number" class="form-control" id="max_capacity" name="max_capacity" 
                                       min="1" max="10" value="1" required>
                            </div>
                            
                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-plus"></i> Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>/slot/medical" class="row g-3">
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="<?php echo $filters['date'] ?? ''; ?>">
                        </div>
                        
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
                        
                        <div class="col-md-2">
                            <label for="is_available" class="form-label">Status</label>
                            <select class="form-select" id="is_available" name="is_available">
                                <option value="">All</option>
                                <option value="1" <?php echo (isset($filters['is_available']) && $filters['is_available'] === '1') ? 'selected' : ''; ?>>Available</option>
                                <option value="0" <?php echo (isset($filters['is_available']) && $filters['is_available'] === '0') ? 'selected' : ''; ?>>Full</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <a href="<?php echo BASE_URL; ?>/slot/medical" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-heart"></i> Medical Evaluation Slots (<?php echo count($slots); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($slots)): ?>
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p>No slots found. Create your first slot above.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Medical Officer</th>
                                        <th>Capacity</th>
                                        <th>Booked</th>
                                        <th>Available</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($slots as $slot): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($slot['slot_date'])); ?></td>
                                            <td><?php echo date('h:i A', strtotime($slot['slot_time'])); ?></td>
                                            <td><?php echo $slot['medical_officer_name']; ?></td>
                                            <td><?php echo $slot['max_capacity']; ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $slot['current_bookings']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo $slot['max_capacity'] - $slot['current_bookings']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($slot['is_available'] && $slot['current_bookings'] < $slot['max_capacity']): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Available
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle"></i> Full
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo BASE_URL; ?>/slot/toggleMedical/<?php echo $slot['slot_id']; ?>" 
                                                       class="btn btn-outline-warning" 
                                                       title="<?php echo $slot['is_available'] ? 'Disable' : 'Enable'; ?>">
                                                        <i class="bi bi-<?php echo $slot['is_available'] ? 'toggle-on' : 'toggle-off'; ?>"></i>
                                                    </a>
                                                    <?php if ($slot['current_bookings'] == 0): ?>
                                                        <a href="<?php echo BASE_URL; ?>/slot/deleteMedical/<?php echo $slot['slot_id']; ?>" 
                                                           class="btn btn-outline-danger" 
                                                           onclick="return confirm('Are you sure you want to delete this slot?')"
                                                           title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
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