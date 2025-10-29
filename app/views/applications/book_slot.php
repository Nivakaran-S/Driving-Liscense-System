<?php
$pageTitle = 'Book ' . ucfirst($type) . ' Slot';
include APP_ROOT . '/views/layouts/header.php';
$isMedical = $type === 'medical';
?>

<div class="dashboard-layout">
    <?php include APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">
                                <i class="bi bi-calendar-<?php echo $isMedical ? 'heart' : 'check'; ?>"></i>
                                Book <?php echo ucfirst($type); ?> <?php echo $isMedical ? 'Evaluation' : 'Test'; ?> Slot
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Reference ID:</strong><br>
                                        <code><?php echo $application['reference_id']; ?></code>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Applicant:</strong><br>
                                        <?php echo $application['full_name']; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>License Type:</strong><br>
                                        <?php echo ucfirst(str_replace('_', ' ', $application['license_type'])); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6><i class="bi bi-info-circle"></i> Important Information</h6>
                                <ul class="mb-0">
                                    <li>Slots are allocated on a first-come-first-served basis</li>
                                    <li>Once booked, please arrive 15 minutes before your scheduled time</li>
                                    <li>Bring your National ID and any required documents</li>
                                    <?php if ($isMedical): ?>
                                        <li>The medical evaluation includes vision, hearing, and physical fitness tests</li>
                                    <?php else: ?>
                                        <li>Ensure you are prepared for both theory and practical driving assessment</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            
                            <?php if (empty($slots)): ?>
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    <h5>No Available Slots</h5>
                                    <p>There are currently no available slots. Please check back later or contact the administrator.</p>
                                    <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $application['application_id']; ?>" 
                                       class="btn btn-primary">
                                        Back to Application
                                    </a>
                                </div>
                            <?php else: ?>
                                <form method="POST" action="<?php echo BASE_URL; ?>/application/book<?php echo $isMedical ? 'MedicalSlot' : 'DrivingSlot'; ?>/<?php echo $application['application_id']; ?>">
                                    <?php echo Session::csrfField(); ?>
                                    
                                    <h5 class="mb-3">Available Slots</h5>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="50">Select</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th><?php echo $isMedical ? 'Medical Officer' : 'Evaluator'; ?></th>
                                                    <th>Availability</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $currentDate = '';
                                                foreach ($slots as $slot): 
                                                    $slotDate = date('l, F d, Y', strtotime($slot['slot_date']));
                                                    $showDateHeader = $slotDate !== $currentDate;
                                                    $currentDate = $slotDate;
                                                    
                                                    if ($showDateHeader):
                                                ?>
                                                    <tr class="table-active">
                                                        <td colspan="5">
                                                            <strong><?php echo $slotDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                                
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="slot_id" id="slot_<?php echo $slot['slot_id']; ?>" 
                                                                   value="<?php echo $slot['slot_id']; ?>" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <label for="slot_<?php echo $slot['slot_id']; ?>" class="form-check-label">
                                                            <?php echo date('M d, Y', strtotime($slot['slot_date'])); ?>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label for="slot_<?php echo $slot['slot_id']; ?>" class="form-check-label">
                                                            <?php echo date('h:i A', strtotime($slot['slot_time'])); ?>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label for="slot_<?php echo $slot['slot_id']; ?>" class="form-check-label">
                                                            <?php echo $slot[$isMedical ? 'medical_officer_name' : 'evaluator_name']; ?>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">
                                                            <?php echo ($slot['max_capacity'] - $slot['current_bookings']); ?> spots left
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-check-circle"></i> Confirm Booking
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>/application/view/<?php echo $application['application_id']; ?>" 
                                           class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>