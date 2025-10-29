<?php


class DrivingEvaluation {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            
            $overallScore = round(
                ($data['vehicle_control_score'] + 
                 $data['traffic_rules_score'] + 
                 $data['parking_score'] + 
                 $data['road_safety_score']) / 4
            );
            
        
            $result = ($overallScore >= PASSING_SCORE) ? 'pass' : 'fail';
            
        
            $this->db->query('INSERT INTO driving_evaluations 
                             (application_id, evaluator_id, slot_id, evaluation_date,
                              vehicle_control_score, traffic_rules_score, parking_score, 
                              road_safety_score, overall_score, result, remarks) 
                             VALUES (:app_id, :evaluator_id, :slot_id, :eval_date,
                                     :vehicle, :traffic, :parking, :safety, :overall, :result, :remarks)');
            
            $this->db->bind(':app_id', $data['application_id']);
            $this->db->bind(':evaluator_id', $data['evaluator_id']);
            $this->db->bind(':slot_id', $data['slot_id']);
            $this->db->bind(':eval_date', $data['evaluation_date']);
            $this->db->bind(':vehicle', $data['vehicle_control_score']);
            $this->db->bind(':traffic', $data['traffic_rules_score']);
            $this->db->bind(':parking', $data['parking_score']);
            $this->db->bind(':safety', $data['road_safety_score']);
            $this->db->bind(':overall', $overallScore);
            $this->db->bind(':result', $result);
            $this->db->bind(':remarks', $data['remarks']);
            
            $this->db->execute();
            $evaluationId = $this->db->lastInsertId();
            
            $applicationModel = new Application();
            if ($result === 'pass') {
                $applicationModel->updateDrivingTestPassed($data['application_id']);
            } else {
                $applicationModel->updateDrivingTestFailed($data['application_id']);
            }
            
            $this->db->commit();
            return $evaluationId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    public function getByApplicationId($applicationId) {
        $this->db->query('SELECT de.*, u.full_name as evaluator_name,
                         a.reference_id, driver.full_name as applicant_name, a.license_type
                         FROM driving_evaluations de
                         JOIN users u ON de.evaluator_id = u.user_id
                         JOIN applications a ON de.application_id = a.application_id
                         JOIN users driver ON a.user_id = driver.user_id
                         WHERE de.application_id = :app_id');
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->fetch();
    }
    
    public function getById($evaluationId) {
        $this->db->query('SELECT de.*, u.full_name as evaluator_name,
                         a.reference_id, driver.full_name as applicant_name, a.license_type
                         FROM driving_evaluations de
                         JOIN users u ON de.evaluator_id = u.user_id
                         JOIN applications a ON de.application_id = a.application_id
                         JOIN users driver ON a.user_id = driver.user_id
                         WHERE de.evaluation_id = :eval_id');
        $this->db->bind(':eval_id', $evaluationId);
        
        return $this->db->fetch();
    }
    
    public function getByEvaluator($evaluatorId, $filters = []) {
        $sql = 'SELECT de.*, a.reference_id, driver.full_name as applicant_name,
                       a.license_type
                FROM driving_evaluations de
                JOIN applications a ON de.application_id = a.application_id
                JOIN users driver ON a.user_id = driver.user_id
                WHERE de.evaluator_id = :evaluator_id';
        
        if (!empty($filters['result'])) {
            $sql .= ' AND de.result = :result';
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= ' AND DATE(de.evaluation_date) >= :date_from';
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= ' AND DATE(de.evaluation_date) <= :date_to';
        }
        
        $sql .= ' ORDER BY de.evaluated_at DESC';
        
        $this->db->query($sql);
        $this->db->bind(':evaluator_id', $evaluatorId);
        
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
        $sql = 'SELECT de.*, a.reference_id, driver.full_name as applicant_name,
                       evaluator.full_name as evaluator_name, a.license_type
                FROM driving_evaluations de
                JOIN applications a ON de.application_id = a.application_id
                JOIN users driver ON a.user_id = driver.user_id
                JOIN users evaluator ON de.evaluator_id = evaluator.user_id
                WHERE 1=1';
        
        if (!empty($filters['result'])) {
            $sql .= ' AND de.result = :result';
        }
        
        if (!empty($filters['evaluator_id'])) {
            $sql .= ' AND de.evaluator_id = :evaluator_id';
        }
        
        if (!empty($filters['license_type'])) {
            $sql .= ' AND a.license_type = :license_type';
        }
        
        $sql .= ' ORDER BY de.evaluated_at DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['result'])) {
            $this->db->bind(':result', $filters['result']);
        }
        
        if (!empty($filters['evaluator_id'])) {
            $this->db->bind(':evaluator_id', $filters['evaluator_id']);
        }
        
        if (!empty($filters['license_type'])) {
            $this->db->bind(':license_type', $filters['license_type']);
        }
        
        return $this->db->fetchAll();
    }
    
    public function isEvaluated($applicationId) {
        $this->db->query('SELECT evaluation_id FROM driving_evaluations 
                         WHERE application_id = :app_id');
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->fetch() !== false;
    }
    
    public function getStatistics($evaluatorId) {
        $this->db->query('SELECT 
                         COUNT(*) as total_evaluations,
                         SUM(CASE WHEN result = "pass" THEN 1 ELSE 0 END) as passed,
                         SUM(CASE WHEN result = "fail" THEN 1 ELSE 0 END) as failed,
                         AVG(overall_score) as average_score
                         FROM driving_evaluations
                         WHERE evaluator_id = :evaluator_id');
        $this->db->bind(':evaluator_id', $evaluatorId);
        
        return $this->db->fetch();
    }
}
?>