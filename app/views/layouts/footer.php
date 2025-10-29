<?php
?>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><?php echo APP_NAME; ?></h5>
                    <p class="text-muted">
                        Streamlining the driving license application and issuance process.
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/checkStatus" class="text-muted text-decoration-none">Check Application Status</a></li>
                        <?php if (Auth::isLoggedIn()): ?>
                            <li><a href="<?php echo BASE_URL; ?>/dashboard/<?php echo Auth::getRole(); ?>" class="text-muted text-decoration-none">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo BASE_URL; ?>/auth/login" class="text-muted text-decoration-none">Login</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/auth/register" class="text-muted text-decoration-none">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p class="text-muted">
                        <i class="bi bi-envelope"></i> info@dls.gov.lk<br>
                        <i class="bi bi-telephone"></i> +94 11 234 5678<br>
                        <i class="bi bi-geo-alt"></i> Colombo, Sri Lanka
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    
    <?php if (isset($additionalJS)): ?>
        <?php echo $additionalJS; ?>
    <?php endif; ?>
    
</body>
</html>