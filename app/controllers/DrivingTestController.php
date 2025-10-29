<?php

class DrivingTestController extends BaseController {
    private $drivingEvaluationModel;
    private $applicationModel;
    private $licenseModel;
    
    public function __construct() {
        $this->drivingEvaluationModel = new DrivingEvaluation();
        $this->applicationModel = new Application();
        $this->licenseModel = new License();
    }
    
    public function evaluate($applicationId) {
        $this->requireAuth();
        $this->requireRole('evaluator');
        
        $application = $this->applicationModel->getById($applicationId);
        
        if (!$application) {
            $this->setFlash('error', 'Application not found');
            $this->redirect('dashboard/evaluator');
            return;
        }
        
        if ($application['application_status'] !== 'driving_test_scheduled') {
            $this->setFlash('error', 'This application is not scheduled for driving test');
            $this->redirect('dashboard/evaluator');
            return;
        }
        
        if ($this->drivingEvaluationModel->isEvaluated($applicationId)) {
            $this->setFlash('error', 'This application has already been evaluated');
            $this->redirect('dashboard/evaluator');
            return;
        }
        
        if ($this->isPost()) {
            $user = $this->getCurrentUser();
            
            $data = [
                'application_id' => $applicationId,
                'evaluator_id' => $user['user_id'],
                'slot_id' => $application['driving_slot_id'] ?? 0,
                'evaluation_date' => date('Y-m-d H:i:s'),
                'vehicle_control_score' => (int)$this->sanitize($_POST['vehicle_control_score']),
                'traffic_rules_score' => (int)$this->sanitize($_POST['traffic_rules_score']),
                'parking_score' => (int)$this->sanitize($_POST['parking_score']),
                'road_safety_score' => (int)$this->sanitize($_POST['road_safety_score']),
                'remarks' => $this->sanitize($_POST['remarks'])
            ];
            
            $errors = [];
            foreach (['vehicle_control_score', 'traffic_rules_score', 'parking_score', 'road_safety_score'] as $field) {
                if ($data[$field] < 0 || $data[$field] > 100) {
                    $errors[$field] = 'Score must be between 0 and 100';
                }
            }
            
            if (empty($errors)) {
                $evaluationId = $this->drivingEvaluationModel->create($data);
                
                if ($evaluationId) {
                    $overallScore = round(
                        ($data['vehicle_control_score'] + 
                         $data['traffic_rules_score'] + 
                         $data['parking_score'] + 
                         $data['road_safety_score']) / 4
                    );
                    
                    $result = ($overallScore >= PASSING_SCORE) ? 'passed' : 'failed';
                    
                    if ($result === 'passed') {
                        $this->licenseModel->issueTemporaryLicense(
                            $applicationId, 
                            $application['user_id'], 
                            $application['license_type']
                        );
                    }
                    
                    $notificationModel = new Notification();
                    $message = "Your driving test has been evaluated. Score: $overallScore/100. Result: " . strtoupper($result);
                    $notificationModel->create($application['user_id'], $message, 
                                              $result === 'passed' ? 'success' : 'error', 
                                              $applicationId);
                    
                    $this->setFlash('success', 'Driving test evaluation submitted successfully');
                    $this->redirect('dashboard/evaluator');
                } else {
                    $this->setFlash('error', 'Failed to submit evaluation');
                    $this->view('driving_test/evaluate', ['application' => $application, 'errors' => $errors]);
                }
            } else {
                $this->view('driving_test/evaluate', ['application' => $application, 'errors' => $errors, 'data' => $data]);
            }
        } else {
            $this->view('driving_test/evaluate', ['application' => $application]);
        }
    }
    
    public function viewEvaluation($evaluationId) {
        $this->requireAuth();
        
        $evaluation = $this->drivingEvaluationModel->getById($evaluationId);
        
        if (!$evaluation) {
            $this->setFlash('error', 'Evaluation not found');
            $this->redirect('dashboard/' . Auth::getRole());
            return;
        }
        
        $user = $this->getCurrentUser();
        $role = Auth::getRole();
        
        if ($role === 'evaluator' && $evaluation['evaluator_id'] != $user['user_id']) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/evaluator');
            return;
        }
        
        if ($role === 'driver') {
            $application = $this->applicationModel->getById($evaluation['application_id']);
            if ($application['user_id'] != $user['user_id']) {
                $this->setFlash('error', 'Access denied');
                $this->redirect('dashboard/driver');
                return;
            }
        }
        
        $this->view('driving_test/view', ['evaluation' => $evaluation]);
    }
    
    public function list() {
        $this->requireAuth();
        
        $filters = [];
        if (isset($_GET['result'])) {
            $filters['result'] = $this->sanitize($_GET['result']);
        }
        if (isset($_GET['date_from'])) {
            $filters['date_from'] = $this->sanitize($_GET['date_from']);
        }
        if (isset($_GET['date_to'])) {
            $filters['date_to'] = $this->sanitize($_GET['date_to']);
        }
        if (isset($_GET['license_type'])) {
            $filters['license_type'] = $this->sanitize($_GET['license_type']);
        }
        
        $user = $this->getCurrentUser();
        $role = Auth::getRole();
        
        $data = [];
        
        if ($role === 'evaluator') {
            $evaluations = $this->drivingEvaluationModel->getByEvaluator($user['user_id'], $filters);
        } elseif ($role === 'admin') {
            if (isset($_GET['evaluator_id'])) {
                $filters['evaluator_id'] = $this->sanitize($_GET['evaluator_id']);
            }
            $evaluations = $this->drivingEvaluationModel->getAll($filters);
            
            $userModel = new User();
            $evaluators = $userModel->getUsersByRole('evaluator');
            $data['evaluators'] = $evaluators;
        } else {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/' . $role);
            return;
        }
        
        $data['evaluations'] = $evaluations;
        $data['filters'] = $filters;
        
        $this->view('driving_test/list', $data);
    }
}