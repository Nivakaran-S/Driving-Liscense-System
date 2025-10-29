<?php

class MedicalEvaluation {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            $overallResult = ($data['vision_test'] === 'pass' && 
                            $data['hearing_test'] === 'pass' && 
                            $data['physical_fitness'] === 'pass') ? 'pass' : 'fail';
            
            $this->db->query('INSERT INTO medical_evaluations 
                             (application_id, medical_officer_id, slot_id, evaluation_date,
                              vision_test, hearing_test, physical_fitness, blood_pressure, 
                              overall_result, remarks) 
                             VALUES (:app_id, :officer_id, :slot_id, :eval_date,
                                     :vision, :hearing, :physical, :bp, :result, :remarks)');
            
            $this->db->bind(':app_id', $data['application_id']);
            $this->db->bind(':officer_id', $data['medical_officer_id']);
            $this->db->bind(':slot_id', $data['slot_id']);
            $this->db->bind(':eval_date', $data['evaluation_date']);
            $this->db->bind(':vision', $data['vision_test']);
            $this->db->bind(':hearing', $data['hearing_test']);
            $this->db->bind(':physical', $data['physical_fitness']);
            $this->db->bind(':bp', $data['blood_pressure']);
            $this->db->bind(':result', $overallResult);
            $this->db->bind(':remarks', $data['remarks']);
            
            $this->db->execute();
            $evaluationId = $this->db->lastInsertId();
            
            $applicationModel = new Application();
            if ($overallResult === 'pass') {
                $applicationModel->updateMedicalPassed($data['application_id']);
            } else {
                $applicationModel->updateMedicalFailed($data['application_id']);
            }
            
            $this->db->commit();
            return $evaluationId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    public function getByApplicationId($applicationId) {
        $this->db->query('SELECT me.*, u.full_name as medical_officer_name,
                         a.reference_id, driver.full_name as applicant_name
                         FROM medical_evaluations me
                         JOIN users u ON me.medical_officer_id = u.user_id
                         JOIN applications a ON me.application_id = a.application_id
                         JOIN users driver ON a.user_id = driver.user_id
                         WHERE me.application_id = :app_id');
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->fetch();
    }
    
    public function getById($evaluationId) {
        $this->db->query('SELECT me.*, u.full_name as medical_officer_name,
                         a.reference_id, driver.full_name as applicant_name
                         FROM medical_evaluations me
                         JOIN users u ON me.medical_officer_id = u.user_id
                         JOIN applications a ON me.application_id = a.application_id
                         JOIN users driver ON a.user_id = driver.user_id
                         WHERE me.evaluation_id = :eval_id');
        $this->db->bind(':eval_id', $evaluationId);
        
        return $this->db->fetch();
    }
    
    public function getByOfficer($officerId, $filters = []) {
        $sql = 'SELECT me.*, a.reference_id, driver.full_name as applicant_name,
                       a.license_type
                FROM medical_evaluations me
                JOIN applications a ON me.application_id = a.application_id
                JOIN users driver ON a.user_id = driver.user_id
                WHERE me.medical_officer_id = :officer_id';
        
        if (!empty($filters['result'])) {
            $sql .= ' AND me.overall_result = :result';
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= ' AND DATE(me.evaluation_date) >= :date_from';
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= ' AND DATE(me.evaluation_date) <= :date_to';
        }
        
        $sql .= ' ORDER BY me.evaluated_at DESC';
        
        $this->db->query($sql);
        $this->db->bind(':officer_id', $officerId);
        
        if (!empty($filters['result'])) {
            $this->db->bind(':result', $filters['result']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->bind(':date_from', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->bind(':date_to', $filters['date_to']);
        }
        
        return $this->db->fetchAll();
    }
    
    public function getAll($filters = []) {
        $sql = 'SELECT me.*, a.reference_id, driver.full_name as applicant_name,
                       officer.full_name as medical_officer_name, a.license_type
                FROM medical_evaluations me
                JOIN applications a ON me.application_id = a.application_id
                JOIN users driver ON a.user_id = driver.user_id
                JOIN users officer ON me.medical_officer_id = officer.user_id
                WHERE 1=1';
        
        if (!empty($filters['result'])) {
            $sql .= ' AND me.overall_result = :result';
        }
        
        if (!empty($filters['officer_id'])) {
            $sql .= ' AND me.medical_officer_id = :officer_id';
        }
        
        $sql .= ' ORDER BY me.evaluated_at DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['result'])) {
            $this->db->bind(':result', $filters['result']);
        }
        
        if (!empty($filters['officer_id'])) {
            $this->db->bind(':officer_id', $filters['officer_id']);
        }
        
        return $this->db->fetchAll();
    }
    
    public function isEvaluated($applicationId) {
        $this->db->query('SELECT evaluation_id FROM medical_evaluations 
                         WHERE application_id = :app_id');
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->fetch() !== false;
    }
    
    public function getStatistics($officerId) {
        $this->db->query('SELECT 
                         COUNT(*) as total_evaluations,
                         SUM(CASE WHEN overall_result = "pass" THEN 1 ELSE 0 END) as passed,
                         SUM(CASE WHEN overall_result = "fail" THEN 1 ELSE 0 END) as failed
                         FROM medical_evaluations
                         WHERE medical_officer_id = :officer_id');
        $this->db->bind(':officer_id', $officerId);
        
        return $this->db->fetch();
    }
}
?>