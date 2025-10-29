<?php 

class Application {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($userId, $licenseType) {
        if ($this->hasPendingApplication($userId)) {
            return false; 
        }

        $referenceId = $this->generateReferenceId();

        $this->db->query('INSERT INTO applications (user_id, reference_id, license_type, application_status) 
                         VALUES (:user_id, :reference_id, :license_type, :status)');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':reference_id', $referenceId);
        $this->db->bind(':license_type', $licenseType);
        $this->db->bind(':status', 'submitted');

        if ($this->db->execute()) {
            return [
                'application_id' => $this->db->lastInsertId(),
                'reference_id' => $referenceId
            ];
        }

        return false;
    }

    public function generateReferenceId() {
        do {
            $referenceId = 'DL' . date('Y') . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            $this->db->query('SELECT application_id FROM applications WHERE reference_id = :ref_id');
            $this->db->bind(':ref_id', $referenceId);
        } while($this->db->fetch());

        return $referenceId;
    }

    public function hasPendingApplication($userId) {
        $this->db->query('SELECT application_id FROM applications 
                         WHERE user_id = :user_id 
                         AND application_status NOT IN ("driving_test_failed", "medical_failed", "license_issued")');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->fetch() !== false;
    }

    public function getById($applicationId) {
        $this->db->query('SELECT a.*, u.full_name, u.email, u.phone, 
                         dp.date_of_birth, dp.address, dp.city, dp.national_id
                         FROM applications a
                         JOIN users u ON a.user_id = u.user_id
                         LEFT JOIN driver_profiles dp ON u.user_id = dp.user_id
                         WHERE a.application_id = :app_id');
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->fetch();
    }

    public function getByReferenceId($referenceId) {
        $this->db->query('SELECT a.*, u.full_name, u.email
                          FROM application a
                          JOIN users u ON a.user_id = u.user_id 
                          WHERE a.reference_id = :ref_id');
        $this->db->bind(':ref_id', $referenceId);

        return $this->db->fetch();
    }

    public function getByUserId($userId) {
        $this->db->query('SELECT * FROM applications 
                         WHERE user_id = :user_id 
                         ORDER BY submission_date DESC');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->fetchAll();
    }
    
    public function getAll($filters = []) {
        $sql = 'SELECT a.*, u.full_name, u.email 
                FROM applications a
                JOIN users u ON a.user_id = u.user_id
                WHERE 1=1';
        
        if (!empty($filters['status'])) {
            $sql .= ' AND a.application_status = :status';
        }
        
        if (!empty($filters['license_type'])) {
            $sql .= ' AND a.license_type = :license_type';
        }
        
        $sql .= ' ORDER BY a.submission_date DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        
        if (!empty($filters['license_type'])) {
            $this->db->bind(':license_type', $filters['license_type']);
        }
        
        return $this->db->fetchAll();
    }

    public function updateStatus($applicationId, $status) {
        $this->db->query('UPDATE applications SET application_status = :status 
                         WHERE application_id = :app_id');
        $this->db->bind(':status', $status);
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->execute();
    }


    public function scheduleMedicalTest($applicationId, $slotId, $testDate) {
        $this->db->query('UPDATE applications 
                         SET medical_test_date = :test_date, 
                             application_status = "medical_scheduled"
                         WHERE application_id = :app_id');
        
        $this->db->bind(':test_date', $testDate);
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->execute();
    }

    public function scheduleDrivingTest($applicationId, $slotId, $testDate) {
        $this->db->query('UPDATE applications 
                         SET driving_test_date = :test_date, 
                             application_status = "driving_test_scheduled"
                         WHERE application_id = :app_id');
        
        $this->db->bind(':test_date', $testDate);
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->execute();
    }

    public function updateMedicalPassed($applicationId) {
        return $this->updateStatus($applicationId, 'medical_passed');
    }

    public function updateMedicalFailed($applicationId) {
        return $this->updateStatus($applicationId, 'medical_failed');
    }

    public function updateDrivingTestPassed($applicationId) {
        return $this->updateStatus($applicationId, 'driving_test_passed');
    }

    public function updateDrivingTestFailed($applicationId) {
        return $this->updateStatus($applicationId, 'driving_test_failed');
    }

    public function updateLicenseIssued($applicationId) {
        $this->db->query('UPDATE applications 
                         SET application_status = "license_issued",
                             license_issue_date = NOW()
                         WHERE application_id = :app_id');
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->execute();
    }

    public function getApplicationsForMedicalScheduling() {
        $this->db->query('SELECT a.*, u.full_name, u.email, u.phone
                         FROM applications a
                         JOIN users u ON a.user_id = u.user_id
                         WHERE a.application_status = "submitted"
                         ORDER BY a.submission_date ASC');
        
        return $this->db->fetchAll();
    }

    public function getApplicationsForDrivingScheduling() {
        $this->db->query('SELECT a.*, u.full_name, u.email, u.phone
                         FROM applications a
                         JOIN users u ON a.user_id = u.user_id
                         WHERE a.application_status = "medical_passed"
                         ORDER BY a.submission_date ASC');
        
        return $this->db->fetchAll();
    }

    public function getMedicalScheduledApplications() {
        $this->db->query('SELECT a.*, u.full_name, u.email, 
                         ms.slot_date, ms.slot_time, mo.full_name as medical_officer_name
                         FROM applications a
                         JOIN users u ON a.user_id = u.user_id
                         LEFT JOIN medical_slots ms ON a.medical_test_date = CONCAT(ms.slot_date, " ", ms.slot_time)
                         LEFT JOIN users mo ON ms.medical_officer_id = mo.user_id
                         WHERE a.application_status = "medical_scheduled"
                         ORDER BY a.medical_test_date ASC');
        
        return $this->db->fetchAll();
    }

    public function getDrivingScheduledApplications() {
        $this->db->query('SELECT a.*, u.full_name, u.email,
                         ds.slot_date, ds.slot_time, ev.full_name as evaluator_name
                         FROM applications a
                         JOIN users u ON a.user_id = u.user_id
                         LEFT JOIN driving_test_slots ds ON a.driving_test_date = CONCAT(ds.slot_date, " ", ds.slot_time)
                         LEFT JOIN users ev ON ds.evaluator_id = ev.user_id
                         WHERE a.application_status = "driving_test_scheduled"
                         ORDER BY a.driving_test_date ASC');
        
        return $this->db->fetchAll();
    }

    public function addNotes($applicationId, $notes) {
        $this->db->query('UPDATE applications SET notes = :notes WHERE application_id = :app_id');
        $this->db->bind(':notes', $notes);
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->execute();
    }
}

?>