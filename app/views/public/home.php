<?php
$pageTitle = 'Home';
include APP_ROOT . '/views/layouts/header.php';
?>

<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Get Your Driving License Online</h1>
                <p class="lead mb-4">
                    Apply for your driving license from the comfort of your home. 
                    Our streamlined process makes it easy to get licensed quickly and efficiently.
                </p>
                <div class="d-grid gap-2 d-md-flex">
                    <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-person-plus"></i> Get Started
                    </a>
                    <a href="<?php echo BASE_URL; ?>/public/checkStatus" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-search"></i> Check Status
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-car-front-fill" style="font-size: 15rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <h2 class="text-center mb-5">How It Works</h2>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="feature-icon bg-primary text-white rounded-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-plus" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="card-title">1. Register</h5>
                    <p class="card-text">Create your account with personal details and documents</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-clipboard2-pulse" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="card-title">2. Medical Test</h5>
                    <p class="card-text">Book and attend your medical evaluation appointment</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="feature-icon bg-warning text-white rounded-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-car-front" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="card-title">3. Driving Test</h5>
                    <p class="card-text">Pass your driving test with our qualified evaluators</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="feature-icon bg-info text-white rounded-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-card-heading" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="card-title">4. Get License</h5>
                    <p class="card-text">Download your temporary license instantly</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose Us?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex">
                    <i class="bi bi-clock-history text-primary me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h5>Save Time</h5>
                        <p class="text-muted">Apply online and book slots at your convenience</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="d-flex">
                    <i class="bi bi-shield-check text-success me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h5>Secure Process</h5>
                        <p class="text-muted">Your data is protected with industry-standard security</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="d-flex">
                    <i class="bi bi-graph-up-arrow text-info me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h5>Track Progress</h5>
                        <p class="text-muted">Monitor your application status in real-time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center g-4">
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="display-4 text-primary fw-bold">10K+</h2>
                <p class="text-muted">Licenses Issued</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="display-4 text-success fw-bold">95%</h2>
                <p class="text-muted">Pass Rate</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="display-4 text-warning fw-bold">24/7</h2>
                <p class="text-muted">Online Access</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="display-4 text-info fw-bold">48hrs</h2>
                <p class="text-muted">Avg. Processing</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="mb-4">Ready to Get Your License?</h2>
        <p class="lead mb-4">Join thousands of satisfied drivers who got their license through our platform</p>
        <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-light btn-lg px-5">
            <i class="bi bi-arrow-right-circle"></i> Start Your Application
        </a>
    </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>