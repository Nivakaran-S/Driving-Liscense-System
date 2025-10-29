<?php

class DashboardController extends BaseController {
    private $applicationModel;
    private $userModel;
    
    public function __construct() {
        $this->applicationModel = new Application();
        $this->userModel = new User();
    }
    
    public function admin() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $stats = [
            'total_applications' => 0,
            'pending_medical' => 0,
            'pending_driving' => 0,
            'licenses_issued' => 0,
            'applications_today' => 0
        ];
        
        $db = new Database();
        $db->query('SELECT COUNT(*) as total FROM applications');
        $result = $db->fetch();
        $stats['total_applications'] = $result['total'];
        
        $db->query('SELECT COUNT(*) as total FROM applications WHERE application_status = "submitted"');
        $result = $db->fetch();
        $stats['pending_medical'] = $result['total'];
        
        $db->query('SELECT COUNT(*) as total FROM applications WHERE application_status = "medical_passed"');
        $result = $db->fetch();
        $stats['pending_driving'] = $result['total'];
        
        $db->query('SELECT COUNT(*) as total FROM issued_licenses');
        $result = $db->fetch();
        $stats['licenses_issued'] = $result['total'];
        
        $db->query('SELECT COUNT(*) as total FROM applications WHERE DATE(submission_date) = CURDATE()');
        $result = $db->fetch();
        $stats['applications_today'] = $result['total'];
        
    
        $recentApplications = $this->applicationModel->getAll([]);
        $recentApplications = array_slice($recentApplications, 0, 10);
        
    
        $db->query('SELECT application_status, COUNT(*) as count 
                   FROM applications 
                   GROUP BY application_status');
        $statusCounts = $db->fetchAll();
        
        $this->view('dashboard/admin', [
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'statusCounts' => $statusCounts
        ]);
    }
    
    public function driver() {
        $this->requireAuth();
        $this->requireRole('driver');
        
        $user = $this->getCurrentUser();
        
        $applications = $this->applicationModel->getByUserId($user['user_id']);
        
        $hasPending = $this->applicationModel->hasPendingApplication($user['user_id']);
        
        $latestApplication = !empty($applications) ? $applications[0] : null;
        
        $license = null;
        if ($latestApplication && $latestApplication['application_status'] === 'license_issued') {
            $licenseModel = new License();
            $license = $licenseModel->getByApplicationId($latestApplication['application_id']);
        }
        
        $this->view('dashboard/driver', [
            'applications' => $applications,
            'hasPending' => $hasPending,
            'latestApplication' => $latestApplication,
            'license' => $license
        ]);
    }
    
    public function medical() {
        $this->requireAuth();
        $this->requireRole('medical_officer');
        
        $user = $this->getCurrentUser();
        
        $db = new Database();
        $db->query('SELECT a.*, u.full_name as applicant_name, ms.slot_date, ms.slot_time
                   FROM applications a
                   JOIN users u ON a.user_id = u.user_id
                   JOIN medical_slots ms ON a.medical_test_date = CONCAT(ms.slot_date, " ", ms.slot_time)
                   WHERE ms.medical_officer_id = :officer_id
                   AND a.application_status = "medical_scheduled"
                   ORDER BY ms.slot_date ASC, ms.slot_time ASC');
        $db->bind(':officer_id', $user['user_id']);
        $scheduledEvaluations = $db->fetchAll();
        
        $medicalEvaluationModel = new MedicalEvaluation();
        $evaluationHistory = $medicalEvaluationModel->getByOfficer($user['user_id'], []);
        $evaluationHistory = array_slice($evaluationHistory, 0, 10);
        
        $stats = $medicalEvaluationModel->getStatistics($user['user_id']);
        
        $this->view('dashboard/medical_officer', [
            'scheduledEvaluations' => $scheduledEvaluations,
            'evaluationHistory' => $evaluationHistory,
            'stats' => $stats
        ]);
    }
    
    public function evaluator() {
        $this->requireAuth();
        $this->requireRole('evaluator');
        
        $user = $this->getCurrentUser();
        
        $db = new Database();
        $db->query('SELECT a.*, u.full_name as applicant_name, ds.slot_date, ds.slot_time, ds.license_type
                   FROM applications a
                   JOIN users u ON a.user_id = u.user_id
                   JOIN driving_test_slots ds ON a.driving_test_date = CONCAT(ds.slot_date, " ", ds.slot_time)
                   WHERE ds.evaluator_id = :evaluator_id
                   AND a.application_status = "driving_test_scheduled"
                   ORDER BY ds.slot_date ASC, ds.slot_time ASC');
        $db->bind(':evaluator_id', $user['user_id']);
        $scheduledTests = $db->fetchAll();
        
    
        $drivingEvaluationModel = new DrivingEvaluation();
        $evaluationHistory = $drivingEvaluationModel->getByEvaluator($user['user_id'], []);
        $evaluationHistory = array_slice($evaluationHistory, 0, 10);
        
    
        $stats = $drivingEvaluationModel->getStatistics($user['user_id']);
        
        $this->view('dashboard/evaluator', [
            'scheduledTests' => $scheduledTests,
            'evaluationHistory' => $evaluationHistory,
            'stats' => $stats
        ]);
    }
    
    public function index() {
        $this->requireAuth();
        Auth::redirectToDashboard();
    }
}
?>