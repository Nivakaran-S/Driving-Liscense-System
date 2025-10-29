<?php

class PublicController extends BaseController {
    private $applicationModel;
    
    public function __construct() {
        $this->applicationModel = new Application();
    }
    
    public function index() {
        $this->view('public/home');
    }
    
    public function checkStatus() {
        if ($this->isPost()) {
            $referenceId = $this->sanitize($_POST['reference_id']);
            
            if (empty($referenceId)) {
                $this->setFlash('error', 'Please enter a reference ID');
                $this->view('public/check_status');
                return;
            }
            
            $application = $this->applicationModel->getByReferenceId($referenceId);
            
            if ($application) {
    
                $details = [];
                
                if (in_array($application['application_status'], ['medical_passed', 'driving_test_scheduled', 'driving_test_passed', 'driving_test_failed', 'license_issued'])) {
                    $medicalEvaluationModel = new MedicalEvaluation();
                    $details['medical'] = $medicalEvaluationModel->getByApplicationId($application['application_id']);
                }
                
                if (in_array($application['application_status'], ['driving_test_passed', 'driving_test_failed', 'license_issued'])) {
                    $drivingEvaluationModel = new DrivingEvaluation();
                    $details['driving'] = $drivingEvaluationModel->getByApplicationId($application['application_id']);
                }
                
                if ($application['application_status'] === 'license_issued') {
                    $licenseModel = new License();
                    $details['license'] = $licenseModel->getByApplicationId($application['application_id']);
                }
                
                $this->view('public/check_status', [
                    'application' => $application,
                    'details' => $details
                ]);
            } else {
                $this->setFlash('error', 'No application found with the provided reference ID');
                $this->view('public/check_status', ['reference_id' => $referenceId]);
            }
        } else {
            $this->view('public/check_status');
        }
    }
    
    public function about() {
        $this->view('public/about');
    }
    
    public function contact() {
        $this->view('public/contact');
    }
}
?>