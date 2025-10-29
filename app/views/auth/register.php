<?php
$pageTitle = 'Register';
include APP_ROOT . '/views/layouts/header.php';

$data = $data ?? [];
$errors = $errors ?? [];
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Driver Registration</h2>
                    <p class="text-center text-muted mb-4">Create your account to apply for a driving license</p>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/register">
                        <?php echo Session::csrfField(); ?>
                        
                        <h5 class="mb-3"><i class="bi bi-person-circle"></i> Account Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                                       id="username" name="username" value="<?php echo $data['username'] ?? ''; ?>" required>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo $data['email'] ?? ''; ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" name="password" required>
                                <small class="text-muted">Minimum 6 characters</small>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                       id="confirm_password" name="confirm_password" required>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3"><i class="bi bi-person-badge"></i> Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control <?php echo isset($errors['full_name']) ? 'is-invalid' : ''; ?>" 
                                       id="full_name" name="full_name" value="<?php echo $data['full_name'] ?? ''; ?>" required>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['full_name']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="national_id" class="form-label">National ID (NIC) *</label>
                                <input type="text" class="form-control <?php echo isset($errors['national_id']) ? 'is-invalid' : ''; ?>" 
                                       id="national_id" name="national_id" value="<?php echo $data['national_id'] ?? ''; ?>" 
                                       placeholder="XXXXXXXXXV or XXXXXXXXXXXX" required>
                                <?php if (isset($errors['national_id'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['national_id']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control <?php echo isset($errors['date_of_birth']) ? 'is-invalid' : ''; ?>" 
                                       id="date_of_birth" name="date_of_birth" value="<?php echo $data['date_of_birth'] ?? ''; ?>" 
                                       max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
                                <small class="text-muted">Must be at least 18 years old</small>
                                <?php if (isset($errors['date_of_birth'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['date_of_birth']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                       id="phone" name="phone" value="<?php echo $data['phone'] ?? ''; ?>" 
                                       placeholder="07XXXXXXXX" required>
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>" 
                                      id="address" name="address" rows="2" required><?php echo $data['address'] ?? ''; ?></textarea>
                            <?php if (isset($errors['address'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['address']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control <?php echo isset($errors['city']) ? 'is-invalid' : ''; ?>" 
                                       id="city" name="city" value="<?php echo $data['city'] ?? ''; ?>" required>
                                <?php if (isset($errors['city'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['city']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control <?php echo isset($errors['postal_code']) ? 'is-invalid' : ''; ?>" 
                                       id="postal_code" name="postal_code" value="<?php echo $data['postal_code'] ?? ''; ?>">
                                <?php if (isset($errors['postal_code'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['postal_code']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="license_type" class="form-label">License Type *</label>
                            <select class="form-select <?php echo isset($errors['license_type']) ? 'is-invalid' : ''; ?>" 
                                    id="license_type" name="license_type" required>
                                <option value="">Select License Type</option>
                                <option value="car" <?php echo ($data['license_type'] ?? '') === 'car' ? 'selected' : ''; ?>>Car</option>
                                <option value="motorcycle" <?php echo ($data['license_type'] ?? '') === 'motorcycle' ? 'selected' : ''; ?>>Motorcycle</option>
                                <option value="heavy_vehicle" <?php echo ($data['license_type'] ?? '') === 'heavy_vehicle' ? 'selected' : ''; ?>>Heavy Vehicle</option>
                            </select>
                            <?php if (isset($errors['license_type'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['license_type']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#">Terms and Conditions</a> *
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-person-plus"></i> Register
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Already have an account? 
                            <a href="<?php echo BASE_URL; ?>/auth/login">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>