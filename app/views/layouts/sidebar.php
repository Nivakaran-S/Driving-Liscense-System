<?php
$role = Auth::getRole();
$currentPage = $_SERVER['REQUEST_URI'];
?>

<div class="sidebar bg-light border-end" style="min-height: calc(100vh - 56px);">
    <div class="p-3">
        <h6 class="text-muted mb-3">MENU</h6>
        
        <?php if ($role === 'admin'): ?>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/dashboard/admin') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/dashboard/admin">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/application') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/application/list">
                        <i class="bi bi-file-earmark-text"></i> Applications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/slot/medical') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/slot/medical">
                        <i class="bi bi-calendar-heart"></i> Medical Slots
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/slot/driving') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/slot/driving">
                        <i class="bi bi-calendar-check"></i> Driving Test Slots
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/license') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/license/list">
                        <i class="bi bi-card-heading"></i> Licenses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/medical/list') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/medical/list">
                        <i class="bi bi-clipboard2-pulse"></i> Medical Evaluations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/driving/list') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/driving/list">
                        <i class="bi bi-car-front"></i> Driving Evaluations
                    </a>
                </li>
            </ul>
            
        <?php elseif ($role === 'driver'): ?>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/dashboard/driver') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/dashboard/driver">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/application/create') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/application/create">
                        <i class="bi bi-plus-circle"></i> New Application
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/application/list') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/application/list">
                        <i class="bi bi-list-ul"></i> My Applications
                    </a>
                </li>
            </ul>
            
        <?php elseif ($role === 'medical_officer'): ?>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/dashboard/medical') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/dashboard/medical">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/medical/list') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/medical/list">
                        <i class="bi bi-clipboard2-pulse"></i> Evaluation History
                    </a>
                </li>
            </ul>
            
        <?php elseif ($role === 'evaluator'): ?>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/dashboard/evaluator') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/dashboard/evaluator">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($currentPage, '/driving/list') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/driving/list">
                        <i class="bi bi-car-front"></i> Evaluation History
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>

<style>
.sidebar .nav-link {
    color: #495057;
    padding: 0.75rem 1rem;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
    transition: all 0.3s;
}

.sidebar .nav-link:hover {
    background-color: #e9ecef;
    color: #0d6efd;
}

.sidebar .nav-link.active {
    background-color: #0d6efd;
    color: white;
}

.sidebar .nav-link i {
    margin-right: 0.5rem;
    width: 20px;
    text-align: center;
}
</style>