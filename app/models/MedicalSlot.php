<?php 

class MedicalSlot {
    private $db;

    public function __contruct() {
        $this->db = new Database();
    }

    public function create($data) {
        $this->db->query('INSERT INTO medical_slots 
                         (slot_date, slot_time, medical_officer_id, max_capacity, created_by) 
                         VALUES (:slot_date, :slot_time, :officer_id, :max_capacity, :created_by)');
        
        $this->db->bind(':slot_date', $data['slot_date']);
        $this->db->bind(':slot_time', $data['slot_time']);
        $this->db->bind(':officer_id', $data['medical_officer_id']);
        $this->db->bind(':max_capacity', $data['max_capacity'] ?? 1);
        $this->db->bind(':created_by', $data['created_by']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function getAvailableSlots($fromDate = null) {
        $sql = 'SELECT ms.*, u.full_name as medical_officer_name
                FROM medical_slots ms
                JOIN users u ON ms.medical_officer_id = u.user_id
                WHERE ms.is_available = 1 
                AND ms.current_bookings < ms.max_capacity';
        
        if ($fromDate) {
            $sql .= ' AND ms.slot_date >= :from_date';
        } else {
            $sql .= ' AND ms.slot_date >= CURDATE()';
        }
        
        $sql .= ' ORDER BY ms.slot_date ASC, ms.slot_time ASC';
        
        $this->db->query($sql);
        
        if ($fromDate) {
            $this->db->bind(':from_date', $fromDate);
        }
        
        return $this->db->fetchAll();
    }

    public function getById($slotId) {
        $this->db->query('SELECT ms.*, u.full_name as medical_officer_name
                         FROM medical_slots ms
                         JOIN users u ON ms.medical_officer_id = u.user_id
                         WHERE ms.slot_id = :slot_id');
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
            
            
            $this->db->query('UPDATE medical_slots 
                             SET current_bookings = current_bookings + 1
                             WHERE slot_id = :slot_id');
            $this->db->bind(':slot_id', $slotId);
            $this->db->execute();
            
            
            $this->db->query('UPDATE medical_slots 
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
            
            $this->db->query('UPDATE medical_slots 
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
        $sql = 'SELECT ms.*, u.full_name as medical_officer_name,
                       creator.full_name as created_by_name
                FROM medical_slots ms
                JOIN users u ON ms.medical_officer_id = u.user_id
                JOIN users creator ON ms.created_by = creator.user_id
                WHERE 1=1';
        
        if (!empty($filters['date'])) {
            $sql .= ' AND ms.slot_date = :date';
        }
        
        if (!empty($filters['officer_id'])) {
            $sql .= ' AND ms.medical_officer_id = :officer_id';
        }
        
        if (isset($filters['is_available'])) {
            $sql .= ' AND ms.is_available = :is_available';
        }
        
        $sql .= ' ORDER BY ms.slot_date DESC, ms.slot_time DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['date'])) {
            $this->db->bind(':date', $filters['date']);
        }
        
        if (!empty($filters['officer_id'])) {
            $this->db->bind(':officer_id', $filters['officer_id']);
        }
        
        if (isset($filters['is_available'])) {
            $this->db->bind(':is_available', $filters['is_available']);
        }
        
        return $this->db->fetchAll();
    }

    public function getByOfficer($officerId) {
        $this->db->query('SELECT * FROM medical_slots 
                         WHERE medical_officer_id = :officer_id 
                         AND slot_date >= CURDATE()
                         ORDER BY slot_date ASC, slot_time ASC');
        $this->db->bind(':officer_id', $officerId);
        
        return $this->db->fetchAll();
    }

    public function update($slotId, $data) {
        $this->db->query('UPDATE medical_slots 
                         SET slot_date = :slot_date,
                             slot_time = :slot_time,
                             medical_officer_id = :officer_id,
                             max_capacity = :max_capacity
                         WHERE slot_id = :slot_id');
        
        $this->db->bind(':slot_date', $data['slot_date']);
        $this->db->bind(':slot_time', $data['slot_time']);
        $this->db->bind(':officer_id', $data['medical_officer_id']);
        $this->db->bind(':max_capacity', $data['max_capacity']);
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->execute();
    }

    public function delete($slotId) {
        $this->db->query('DELETE FROM medical_slots 
                         WHERE slot_id = :slot_id 
                         AND current_bookings = 0');
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->execute();
    }

    public function toggleAvailability($slotId) {
        $this->db->query('UPDATE medical_slots 
                         SET is_available = NOT is_available 
                         WHERE slot_id = :slot_id');
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->execute();
    }
}
?>