<?php

class License {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    public function issueTemporaryLicense($applicationId, $userId, $licenseType) {
        try {
            $this->db->beginTransaction();
            

            $licenseNumber = $this->generateLicenseNumber($licenseType);
            
            
            $issueDate = date('Y-m-d');
            $expiryDate = date('Y-m-d', strtotime('+' . TEMP_LICENSE_VALIDITY_MONTHS . ' months'));
            
            
            $this->db->query('INSERT INTO issued_licenses 
                             (application_id, user_id, license_number, license_type, 
                              issue_date, expiry_date, is_temporary) 
                             VALUES (:app_id, :user_id, :license_number, :license_type, 
                                     :issue_date, :expiry_date, 1)');
            
            $this->db->bind(':app_id', $applicationId);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':license_number', $licenseNumber);
            $this->db->bind(':license_type', $licenseType);
            $this->db->bind(':issue_date', $issueDate);
            $this->db->bind(':expiry_date', $expiryDate);
            
            $this->db->execute();
            $licenseId = $this->db->lastInsertId();
            
            
            $applicationModel = new Application();
            $applicationModel->updateLicenseIssued($applicationId);
            
            $this->db->commit();
            return $licenseId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    
    private function generateLicenseNumber($licenseType) {
    
        $prefix = $this->getLicenseTypePrefix($licenseType);
        $year = date('Y');
        
        do {
            $uniqueNumber = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $licenseNumber = $prefix . '-' . $year . '-' . $uniqueNumber;
            
            $this->db->query('SELECT license_id FROM issued_licenses 
                             WHERE license_number = :license_number');
            $this->db->bind(':license_number', $licenseNumber);
        } while ($this->db->fetch());
        
        return $licenseNumber;
    }
    
    
    private function getLicenseTypePrefix($licenseType) {
        switch ($licenseType) {
            case 'car':
                return 'CAR';
            case 'motorcycle':
                return 'MCY';
            case 'heavy_vehicle':
                return 'HVY';
            default:
                return 'DL';
        }
    }
    
    
    public function getByApplicationId($applicationId) {
        $this->db->query('SELECT l.*, u.full_name, u.email, 
                         dp.date_of_birth, dp.address, dp.national_id,
                         a.reference_id
                         FROM issued_licenses l
                         JOIN users u ON l.user_id = u.user_id
                         JOIN applications a ON l.application_id = a.application_id
                         LEFT JOIN driver_profiles dp ON u.user_id = dp.user_id
                         WHERE l.application_id = :app_id');
        $this->db->bind(':app_id', $applicationId);
        
        return $this->db->fetch();
    }
    
    
    public function getById($licenseId) {
        $this->db->query('SELECT l.*, u.full_name, u.email, 
                         dp.date_of_birth, dp.address, dp.national_id,
                         a.reference_id
                         FROM issued_licenses l
                         JOIN users u ON l.user_id = u.user_id
                         JOIN applications a ON l.application_id = a.application_id
                         LEFT JOIN driver_profiles dp ON u.user_id = dp.user_id
                         WHERE l.license_id = :license_id');
        $this->db->bind(':license_id', $licenseId);
        
        return $this->db->fetch();
    }
    
    
    public function getByLicenseNumber($licenseNumber) {
        $this->db->query('SELECT l.*, u.full_name, u.email, 
                         dp.date_of_birth, dp.address, dp.national_id,
                         a.reference_id
                         FROM issued_licenses l
                         JOIN users u ON l.user_id = u.user_id
                         JOIN applications a ON l.application_id = a.application_id
                         LEFT JOIN driver_profiles dp ON u.user_id = dp.user_id
                         WHERE l.license_number = :license_number');
        $this->db->bind(':license_number', $licenseNumber);
        
        return $this->db->fetch();
    }
    
    public function getByUserId($userId) {
        $this->db->query('SELECT l.*, a.reference_id
                         FROM issued_licenses l
                         JOIN applications a ON l.application_id = a.application_id
                         WHERE l.user_id = :user_id
                         ORDER BY l.issue_date DESC');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->fetchAll();
    }
    
    public function getAll($filters = []) {
        $sql = 'SELECT l.*, u.full_name, u.email, a.reference_id
                FROM issued_licenses l
                JOIN users u ON l.user_id = u.user_id
                JOIN applications a ON l.application_id = a.application_id
                WHERE 1=1';
        
        if (!empty($filters['license_type'])) {
            $sql .= ' AND l.license_type = :license_type';
        }
        
        if (isset($filters['is_temporary'])) {
            $sql .= ' AND l.is_temporary = :is_temporary';
        }
        
        if (!empty($filters['expired'])) {
            if ($filters['expired'] === 'yes') {
                $sql .= ' AND l.expiry_date < CURDATE()';
            } else {
                $sql .= ' AND l.expiry_date >= CURDATE()';
            }
        }
        
        $sql .= ' ORDER BY l.created_at DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['license_type'])) {
            $this->db->bind(':license_type', $filters['license_type']);
        }
        
        if (isset($filters['is_temporary'])) {
            $this->db->bind(':is_temporary', $filters['is_temporary']);
        }
        
        return $this->db->fetchAll();
    }
    
    public function isValid($licenseId) {
        $this->db->query('SELECT license_id FROM issued_licenses 
                         WHERE license_id = :license_id 
                         AND expiry_date >= CURDATE()');
        $this->db->bind(':license_id', $licenseId);
        
        return $this->db->fetch() !== false;
    }
    
    public function updateFilePath($licenseId, $filePath) {
        $this->db->query('UPDATE issued_licenses 
                         SET license_file_path = :file_path 
                         WHERE license_id = :license_id');
        $this->db->bind(':file_path', $filePath);
        $this->db->bind(':license_id', $licenseId);
        
        return $this->db->execute();
    }
    
    public function getStatistics() {
        $this->db->query('SELECT 
                         COUNT(*) as total_licenses,
                         SUM(CASE WHEN is_temporary = 1 THEN 1 ELSE 0 END) as temporary_licenses,
                         SUM(CASE WHEN expiry_date < CURDATE() THEN 1 ELSE 0 END) as expired_licenses,
                         SUM(CASE WHEN license_type = "car" THEN 1 ELSE 0 END) as car_licenses,
                         SUM(CASE WHEN license_type = "motorcycle" THEN 1 ELSE 0 END) as motorcycle_licenses,
                         SUM(CASE WHEN license_type = "heavy_vehicle" THEN 1 ELSE 0 END) as heavy_vehicle_licenses
                         FROM issued_licenses');
        
        return $this->db->fetch();
    }
}
?>