<?php

class SlotController extends BaseController {
    private $medicalSlotModel;
    private $drivingSlotModel;
    private $userModel;
    
    public function __construct() {
        $this->medicalSlotModel = new MedicalSlot();
        $this->drivingSlotModel = new DrivingTestSlot();
        $this->userModel = new User();
    }
    
    
    public function medical() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $filters = [];
        if (isset($_GET['date'])) {
            $filters['date'] = $this->sanitize($_GET['date']);
        }
        if (isset($_GET['officer_id'])) {
            $filters['officer_id'] = $this->sanitize($_GET['officer_id']);
        }
        
        $slots = $this->medicalSlotModel->getAll($filters);
        $medicalOfficers = $this->userModel->getUsersByRole('medical_officer');
        
        $this->view('slots/medical_slots', [
            'slots' => $slots,
            'medicalOfficers' => $medicalOfficers,
            'filters' => $filters
        ]);
    }
    
    
    public function createMedical() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        if ($this->isPost()) {
            $user = $this->getCurrentUser();
            
            $data = [
                'slot_date' => $this->sanitize($_POST['slot_date']),
                'slot_time' => $this->sanitize($_POST['slot_time']),
                'medical_officer_id' => $this->sanitize($_POST['medical_officer_id']),
                'max_capacity' => (int)$this->sanitize($_POST['max_capacity']),
                'created_by' => $user['user_id']
            ];
            
    
            $errors = [];
            if (empty($data['slot_date'])) {
                $errors['slot_date'] = 'Date is required';
            } elseif (strtotime($data['slot_date']) < strtotime('today')) {
                $errors['slot_date'] = 'Date cannot be in the past';
            }
            
            if (empty($data['slot_time'])) {
                $errors['slot_time'] = 'Time is required';
            }
            
            if (empty($data['medical_officer_id'])) {
                $errors['medical_officer_id'] = 'Medical officer is required';
            }
            
            if ($data['max_capacity'] < 1) {
                $errors['max_capacity'] = 'Capacity must be at least 1';
            }
            
            if (empty($errors)) {
                $slotId = $this->medicalSlotModel->create($data);
                
                if ($slotId) {
                    $this->setFlash('success', 'Medical slot created successfully');
                    $this->redirect('slot/medical');
                } else {
                    $this->setFlash('error', 'Failed to create slot. Slot may already exist.');
                    $this->redirect('slot/medical');
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_data'] = $data;
                $this->redirect('slot/medical');
            }
        } else {
            $this->redirect('slot/medical');
        }
    }
    
    
    public function deleteMedical($slotId) {
        $this->requireAuth();
        $this->requireRole('admin');
        
        if ($this->medicalSlotModel->delete($slotId)) {
            $this->setFlash('success', 'Slot deleted successfully');
        } else {
            $this->setFlash('error', 'Cannot delete slot with existing bookings');
        }
        
        $this->redirect('slot/medical');
    }
    
    public function toggleMedical($slotId) {
        $this->requireAuth();
        $this->requireRole('admin');
        
        if ($this->medicalSlotModel->toggleAvailability($slotId)) {
            $this->setFlash('success', 'Slot availability updated');
        } else {
            $this->setFlash('error', 'Failed to update slot');
        }
        
        $this->redirect('slot/medical');
    }
    

    public function driving() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $filters = [];
        if (isset($_GET['date'])) {
            $filters['date'] = $this->sanitize($_GET['date']);
        }
        if (isset($_GET['evaluator_id'])) {
            $filters['evaluator_id'] = $this->sanitize($_GET['evaluator_id']);
        }
        if (isset($_GET['license_type'])) {
            $filters['license_type'] = $this->sanitize($_GET['license_type']);
        }
        
        $slots = $this->drivingSlotModel->getAll($filters);
        $evaluators = $this->userModel->getUsersByRole('evaluator');
        
        $this->view('slots/driving_slots', [
            'slots' => $slots,
            'evaluators' => $evaluators,
            'filters' => $filters
        ]);
    }
    

    public function createDriving() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        if ($this->isPost()) {
            $user = $this->getCurrentUser();
            
            $data = [
                'slot_date' => $this->sanitize($_POST['slot_date']),
                'slot_time' => $this->sanitize($_POST['slot_time']),
                'evaluator_id' => $this->sanitize($_POST['evaluator_id']),
                'license_type' => $this->sanitize($_POST['license_type']),
                'max_capacity' => (int)$this->sanitize($_POST['max_capacity']),
                'created_by' => $user['user_id']
            ];
            
            $errors = [];
            if (empty($data['slot_date'])) {
                $errors['slot_date'] = 'Date is required';
            } elseif (strtotime($data['slot_date']) < strtotime('today')) {
                $errors['slot_date'] = 'Date cannot be in the past';
            }
            
            if (empty($data['slot_time'])) {
                $errors['slot_time'] = 'Time is required';
            }
            
            if (empty($data['evaluator_id'])) {
                $errors['evaluator_id'] = 'Evaluator is required';
            }
            
            if (empty($data['license_type'])) {
                $errors['license_type'] = 'License type is required';
            }
            
            if ($data['max_capacity'] < 1) {
                $errors['max_capacity'] = 'Capacity must be at least 1';
            }
            
            if (empty($errors)) {
                $slotId = $this->drivingSlotModel->create($data);
                
                if ($slotId) {
                    $this->setFlash('success', 'Driving test slot created successfully');
                    $this->redirect('slot/driving');
                } else {
                    $this->setFlash('error', 'Failed to create slot. Slot may already exist.');
                    $this->redirect('slot/driving');
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_data'] = $data;
                $this->redirect('slot/driving');
            }
        } else {
            $this->redirect('slot/driving');
        }
    }
    
    public function deleteDriving($slotId) {
        $this->requireAuth();
        $this->requireRole('admin');
        
        if ($this->drivingSlotModel->delete($slotId)) {
            $this->setFlash('success', 'Slot deleted successfully');
        } else {
            $this->setFlash('error', 'Cannot delete slot with existing bookings');
        }
        
        $this->redirect('slot/driving');
    }
    
  
    public function toggleDriving($slotId) {
        $this->requireAuth();
        $this->requireRole('admin');
        
        if ($this->drivingSlotModel->toggleAvailability($slotId)) {
            $this->setFlash('success', 'Slot availability updated');
        } else {
            $this->setFlash('error', 'Failed to update slot');
        }
        
        $this->redirect('slot/driving');
    }
}
?>