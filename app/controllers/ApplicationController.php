<?php

class ApplicationController extends BaseController {
    private $applicationModel;
    private $medicalSlotModel;
    private $drivingSlotModel;
    
    public function __construct() {
        $this->applicationModel = new Application();
        $this->medicalSlotModel = new MedicalSlot();
        $this->drivingSlotModel = new DrivingTestSlot();
    }
    
    public function create() {
        $this->requireAuth();
        $this->requireRole('driver');
        
        $user = $this->getCurrentUser();
        
        if ($this->applicationModel->hasPendingApplication($user['user_id'])) {
            $this->setFlash('error', 'You already have a pending application');
            $this->redirect('dashboard/driver');
            return;
        }
        
        if ($this->isPost()) {
            $licenseType = $this->sanitize($_POST['license_type']);
            
            if (empty($licenseType)) {
                $this->setFlash('error', 'Please select a license type');
                $this->view('applications/create');
                return;
            }
            
            $result = $this->applicationModel->create($user['user_id'], $licenseType);
            
            if ($result) {
                $this->setFlash('success', 'Application submitted successfully! Reference ID: ' . $result['reference_id']);
                $this->redirect('application/viewApplication/' . $result['application_id']);
            } else {
                $this->setFlash('error', 'Failed to submit application');
                $this->view('applications/create');
            }
        } else {
            $this->view('applications/create');
        }
    }
    
    public function viewApplication($applicationId) {
        $this->requireAuth();
        
        $application = $this->applicationModel->getById($applicationId);
        
        if (!$application) {
            $this->setFlash('error', 'Application not found');
            $this->redirect('dashboard/' . Auth::getRole());
            return;
        }
        
        $user = $this->getCurrentUser();
        
        if (Auth::getRole() === 'driver' && $application['user_id'] != $user['user_id']) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/driver');
            return;
        }
        
        $medicalEvaluationModel = new MedicalEvaluation();
        $medicalEvaluation = $medicalEvaluationModel->getByApplicationId($applicationId);
        
        $drivingEvaluationModel = new DrivingEvaluation();
        $drivingEvaluation = $drivingEvaluationModel->getByApplicationId($applicationId);
        
        $licenseModel = new License();
        $license = $licenseModel->getByApplicationId($applicationId);
        
        $this->view('applications/view', [
            'application' => $application,
            'medicalEvaluation' => $medicalEvaluation,
            'drivingEvaluation' => $drivingEvaluation,
            'license' => $license
        ]);
    }
    
    public function list() {
        $this->requireAuth();
        
        $filters = [];
        
        if (Auth::getRole() === 'driver') {
            $user = $this->getCurrentUser();
            $applications = $this->applicationModel->getByUserId($user['user_id']);
        } else {
            if (isset($_GET['status'])) {
                $filters['status'] = $this->sanitize($_GET['status']);
            }
            if (isset($_GET['license_type'])) {
                $filters['license_type'] = $this->sanitize($_GET['license_type']);
            }
            
            $applications = $this->applicationModel->getAll($filters);
        }
        
        $this->view('applications/list', ['applications' => $applications, 'filters' => $filters]);
    }
    
    public function bookMedicalSlot($applicationId) {
        $this->requireAuth();
        $this->requireRole('driver');
        
        $application = $this->applicationModel->getById($applicationId);
        
        if (!$application) {
            $this->setFlash('error', 'Application not found');
            $this->redirect('dashboard/driver');
            return;
        }
        
        $user = $this->getCurrentUser();
        
        if ($application['user_id'] != $user['user_id']) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/driver');
            return;
        }
        
        if ($application['application_status'] !== 'submitted') {
            $this->setFlash('error', 'Medical test slot cannot be booked at this stage');
            $this->redirect('application/viewApplication/' . $applicationId);
            return;
        }
        
        if ($this->isPost()) {
            $slotId = $this->sanitize($_POST['slot_id']);
            
            if (empty($slotId)) {
                $this->setFlash('error', 'Please select a slot');
                $this->redirect('application/bookMedicalSlot/' . $applicationId);
                return;
            }
            
            $slot = $this->medicalSlotModel->getById($slotId);
            
            if (!$slot || !$slot['is_available']) {
                $this->setFlash('error', 'Selected slot is not available');
                $this->redirect('application/bookMedicalSlot/' . $applicationId);
                return;
            }
            
            if ($this->medicalSlotModel->bookSlot($slotId)) {
                $testDateTime = $slot['slot_date'] . ' ' . $slot['slot_time'];
                
                if ($this->applicationModel->scheduleMedicalTest($applicationId, $slotId, $testDateTime)) {
                    $this->setFlash('success', 'Medical test slot booked successfully');
                    $this->redirect('application/viewApplication/' . $applicationId);
                } else {
                    $this->medicalSlotModel->cancelBooking($slotId);
                    $this->setFlash('error', 'Failed to book slot');
                    $this->redirect('application/bookMedicalSlot/' . $applicationId);
                }
            } else {
                $this->setFlash('error', 'Failed to book slot');
                $this->redirect('application/bookMedicalSlot/' . $applicationId);
            }
        } else {
            $availableSlots = $this->medicalSlotModel->getAvailableSlots();
            $this->view('applications/book_slot', [
                'application' => $application,
                'slots' => $availableSlots,
                'type' => 'medical'
            ]);
        }
    }
    
    public function bookDrivingSlot($applicationId) {
        $this->requireAuth();
        $this->requireRole('driver');
        
        $application = $this->applicationModel->getById($applicationId);
        
        if (!$application) {
            $this->setFlash('error', 'Application not found');
            $this->redirect('dashboard/driver');
            return;
        }
        
        $user = $this->getCurrentUser();
        
        if ($application['user_id'] != $user['user_id']) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('dashboard/driver');
            return;
        }
        
        if ($application['application_status'] !== 'medical_passed') {
            $this->setFlash('error', 'Driving test slot can only be booked after passing medical test');
            $this->redirect('application/viewApplication/' . $applicationId);
            return;
        }
        
        if ($this->isPost()) {
            $slotId = $this->sanitize($_POST['slot_id']);
            
            if (empty($slotId)) {
                $this->setFlash('error', 'Please select a slot');
                $this->redirect('application/bookDrivingSlot/' . $applicationId);
                return;
            }
            
            $slot = $this->drivingSlotModel->getById($slotId);
            
            if (!$slot || !$slot['is_available']) {
                $this->setFlash('error', 'Selected slot is not available');
                $this->redirect('application/bookDrivingSlot/' . $applicationId);
                return;
            }
            
            if ($this->drivingSlotModel->bookSlot($slotId)) {
                $testDateTime = $slot['slot_date'] . ' ' . $slot['slot_time'];
                
                if ($this->applicationModel->scheduleDrivingTest($applicationId, $slotId, $testDateTime)) {
                    $this->setFlash('success', 'Driving test slot booked successfully');
                    $this->redirect('application/viewApplication/' . $applicationId);
                } else {
                    $this->drivingSlotModel->cancelBooking($slotId);
                    $this->setFlash('error', 'Failed to book slot');
                    $this->redirect('application/bookDrivingSlot/' . $applicationId);
                }
            } else {
                $this->setFlash('error', 'Failed to book slot');
                $this->redirect('application/bookDrivingSlot/' . $applicationId);
            }
        } else {
            $availableSlots = $this->drivingSlotModel->getAvailableSlots($application['license_type']);
            $this->view('applications/book_slot', [
                'application' => $application,
                'slots' => $availableSlots,
                'type' => 'driving'
            ]);
        }
    }
}