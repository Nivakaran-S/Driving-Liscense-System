<?php

class MedicalController extends BaseController {
    private $medicalEvaluationModel;
    private $applicationModel;
    
    public function __construct() {
        $this->medicalEvaluationModel = new MedicalEvaluation();
        $this->applicationModel = new Application();
    }
    
    public function evaluate($applicationId) {
        $this->requireAuth();
        $this->requireRole('medical_officer');
        
        $application = $this->applicationModel->getById($applicationId);
        
        if (!$application) {
            $this->setFlash('error', 'Application not found');
            $this->redirect('dashboard/medical');
            return;
        }
        
        if ($application['application_status'] !== 'medical_scheduled') {
            $this->setFlash('error', 'This application is not scheduled for medical evaluation');
            $this->redirect('dashboard/medical');
            return;
        }
        
        if ($this->medicalEvaluationModel->isEvaluated($applicationId)) {
            $this->setFlash('error', 'This application has already been evaluated');
            $this->redirect('dashboard/medical');
            return;
        }
        
        if ($this->isPost()) {
            $user = $this->getCurrentUser();
            
            $data = [
                'application_id' => $applicationId,
                'medical_officer_id' => $user['user_id'],
                'slot_id' => $this->sanitize($_POST['slot_id']),
                'evaluation_date' => date('Y-m-d H:i:s'),
                'vision_test' => $this->sanitize($_POST['vision_test']),
                'hearing_test' => $this->sanitize($_POST['hearing_test']),
                'physical_fitness' => $this->sanitize($_POST['physical_fitness']),
                'blood_pressure' => $this->sanitize($_POST['blood_pressure']),
                'remarks' => $this->sanitize($_POST['remarks'])
            ];
            
            $errors = [];
            if (empty($data['vision_test']) || !in_array($data['vision_test'], ['pass', 'fail'])) {
                $errors['vision_test'] = 'Vision test result is required';
            }
            if (empty($data['hearing_test']) || !in_array($data['hearing_test'], ['pass', 'fail'])) {
                $errors['hearing_test'] = 'Hearing test result is required';
            }
            if (empty($data['physical_fitness']) || !in_array($data['physical_fitness'], ['pass', 'fail'])) {
                $errors['physical_fitness'] = 'Physical fitness result is required';
            }
            
            if (empty($errors)) {
                $evaluationId = $this->medicalEvaluationModel->create($data);
                
                if ($evaluationId) {
            
                    $notificationModel = new Notification();
                    $overallResult = ($data['vision_test'] === 'pass' && 
                                    $data['hearing_test'] === 'pass' && 
                                    $data['physical_fitness'] === 'pass') ? 'passed' : 'failed';
                    
                    $message = "Your medical evaluation has been completed. Result: " . strtoupper($overallResult);
                    $notificationModel->create($application['user_id'], $message, 
                                              $overallResult === 'passed' ? 'success' : 'error', 
                                              $applicationId);
                    
                    $this->setFlash('success', 'Medical evaluation submitted successfully');
                    $this->redirect('dashboard/medical');
                } else {
                    $this->setFlash('error', 'Failed to submit evaluation');
                    $this->view('medical/evaluate', ['application' => $application, 'errors' => $errors]);
                }
            } else {
                $this->view('medical/evaluate', ['application' => $application, 'errors' => $errors, 'data' => $data]);
            }
        } else {
            $this->view('medical/evaluate', ['application' => $application]);
        }
    }
    
    public function view($evaluationId) {
        $this->requireAuth();
        
        $evaluation = $this->medicalEvaluationModel->getById($evaluationId);
        
        if (!$evaluation) {
            $this->setFlash('error', 'Evaluation not found');
            $this->redirect('dashboard/' . Auth::getRole());
            return;
        }
        
        $user = $this->getCurrentUser();
        $role = Auth::getRole();
        
        if ($role === 'medical_officer' && $evaluation['medical_officer_id'] != $user['user_id']) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/medical');
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
        
        $this->view('medical/view', ['evaluation' => $evaluation]);
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
        
        $user = $this->getCurrentUser();
        $role = Auth::getRole();
        
        if ($role === 'medical_officer') {
            $evaluations = $this->medicalEvaluationModel->getByOfficer($user['user_id'], $filters);
        } elseif ($role === 'admin') {
            if (isset($_GET['officer_id'])) {
                $filters['officer_id'] = $this->sanitize($_GET['officer_id']);
            }
            $evaluations = $this->medicalEvaluationModel->getAll($filters);
            
            $userModel = new User();
            $medicalOfficers = $userModel->getUsersByRole('medical_officer');
            $data['medicalOfficers'] = $medicalOfficers;
        } else {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/' . $role);
            return;
        }
        
        $data['evaluations'] = $evaluations;
        $data['filters'] = $filters;
        
        $this->view('medical/list', $data);
    }
}
?>