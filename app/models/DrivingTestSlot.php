<?php 

class DrivingTestSlot {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        $this->db->query('INSERT INTO driving_test_slots 
                         (slot_date, slot_time, evaluator_id, license_type, max_capacity, created_by) 
                         VALUES (:slot_date, :slot_time, :evaluator_id, :license_type, :max_capacity, :created_by)');
        
        $this->db->bind(':slot_date', $data['slot_date']);
        $this->db->bind(':slot_time', $data['slot_time']);
        $this->db->bind(':evaluator_id', $data['evaluator_id']);
        $this->db->bind(':license_type', $data['license_type']);
        $this->db->bind(':max_capacity', $data['max_capacity'] ?? 1);
        $this->db->bind(':created_by', $data['created_by']);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function getAvailableSlots($licenseType, $fromDate = null) {
        $sql = 'SELECT ds.*, u.full_name as evaluator_name
                FROM driving_test_slots ds
                JOIN users u ON ds.evaluator_id = u.user_id
                WHERE ds.is_available = 1 
                AND ds.current_bookings < ds.max_capacity
                AND ds.license_type = :license_type';
        
        if ($fromDate) {
            $sql .= ' AND ds.slot_date >= :from_date';
        } else {
            $sql .= ' AND ds.slot_date >= CURDATE()';
        }
        
        $sql .= ' ORDER BY ds.slot_date ASC, ds.slot_time ASC';
        
        $this->db->query($sql);
        $this->db->bind(':license_type', $licenseType);
        
        if ($fromDate) {
            $this->db->bind(':from_date', $fromDate);
        }
        
        return $this->db->fetchAll();
    }

    public function getById($slotId) {
        $this->db->query('SELECT ds.*, u.full_name as evaluator_name
                         FROM driving_test_slots ds
                         JOIN users u ON ds.evaluator_id = u.user_id
                         WHERE ds.slot_id = :slot_id');
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->fetch();
    }

    public function bookSlot($slotId) {
        try {
            $this->db->beginTransaction();
            
            $slot = $this->getById($slotId);
            
            if (!$slot || !$slot['is_available'] || $slot['current_bookings'] >= $slot['max_capacity']) {
                $this->db->rollback();
                return false;
            }
            
            $this->db->query('UPDATE driving_test_slots 
                             SET current_bookings = current_bookings + 1
                             WHERE slot_id = :slot_id');
            $this->db->bind(':slot_id', $slotId);
            $this->db->execute();
            
            $this->db->query('UPDATE driving_test_slots 
                             SET is_available = 0
                             WHERE slot_id = :slot_id 
                             AND current_bookings >= max_capacity');
            $this->db->bind(':slot_id', $slotId);
            $this->db->execute();
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function cancelBooking($slotId) {
        try {
            $this->db->beginTransaction();
            
            $this->db->query('UPDATE driving_test_slots 
                             SET current_bookings = current_bookings - 1,
                                 is_available = 1
                             WHERE slot_id = :slot_id 
                             AND current_bookings > 0');
            $this->db->bind(':slot_id', $slotId);
            $this->db->execute();
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getAll($filters = []) {
        $sql = 'SELECT ds.*, u.full_name as evaluator_name,
                       creator.full_name as created_by_name
                FROM driving_test_slots ds
                JOIN users u ON ds.evaluator_id = u.user_id
                JOIN users creator ON ds.created_by = creator.user_id
                WHERE 1=1';
        
        if (!empty($filters['date'])) {
            $sql .= ' AND ds.slot_date = :date';
        }
        
        if (!empty($filters['evaluator_id'])) {
            $sql .= ' AND ds.evaluator_id = :evaluator_id';
        }
        
        if (!empty($filters['license_type'])) {
            $sql .= ' AND ds.license_type = :license_type';
        }
        
        if (isset($filters['is_available'])) {
            $sql .= ' AND ds.is_available = :is_available';
        }
        
        $sql .= ' ORDER BY ds.slot_date DESC, ds.slot_time DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['date'])) {
            $this->db->bind(':date', $filters['date']);
        }
        
        if (!empty($filters['evaluator_id'])) {
            $this->db->bind(':evaluator_id', $filters['evaluator_id']);
        }
        
        if (!empty($filters['license_type'])) {
            $this->db->bind(':license_type', $filters['license_type']);
        }
        
        if (isset($filters['is_available'])) {
            $this->db->bind(':is_available', $filters['is_available']);
        }
        
        return $this->db->fetchAll();
    }

    public function getByEvaluator($evaluatorId) {
        $this->db->query('SELECT * FROM driving_test_slots 
                         WHERE evaluator_id = :evaluator_id 
                         AND slot_date >= CURDATE()
                         ORDER BY slot_date ASC, slot_time ASC');
        $this->db->bind(':evaluator_id', $evaluatorId);
        
        return $this->db->fetchAll();
    }

    public function update($slotId, $data) {
        $this->db->query('UPDATE driving_test_slots 
                         SET slot_date = :slot_date,
                             slot_time = :slot_time,
                             evaluator_id = :evaluator_id,
                             license_type = :license_type,
                             max_capacity = :max_capacity
                         WHERE slot_id = :slot_id');
        
        $this->db->bind(':slot_date', $data['slot_date']);
        $this->db->bind(':slot_time', $data['slot_time']);
        $this->db->bind(':evaluator_id', $data['evaluator_id']);
        $this->db->bind(':license_type', $data['license_type']);
        $this->db->bind(':max_capacity', $data['max_capacity']);
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->execute();
    }

    public function delete($slotId) {
        $this->db->query('DELETE FROM driving_test_slots 
                         WHERE slot_id = :slot_id 
                         AND current_bookings = 0');
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->execute();
    }

    public function toggleAvailability($slotId) {
        $this->db->query('UPDATE driving_test_slots 
                         SET is_available = NOT is_available 
                         WHERE slot_id = :slot_id');
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->execute();
    }

}

?>