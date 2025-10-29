<?php

class Notification {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function create($userId, $message, $type = 'info', $applicationId = null) {
        $this->db->query('INSERT INTO notifications 
                         (user_id, message, type, related_application_id) 
                         VALUES (:user_id, :message, :type, :app_id)');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':message', $message);
        $this->db->bind(':type', $type);
        $this->db->bind(':app_id', $applicationId);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    public function getByUserId($userId, $limit = 10) {
        $this->db->query('SELECT * FROM notifications 
                         WHERE user_id = :user_id 
                         ORDER BY created_at DESC 
                         LIMIT :limit');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->fetchAll();
    }
    
    public function getUnread($userId) {
        $this->db->query('SELECT * FROM notifications 
                         WHERE user_id = :user_id AND is_read = 0 
                         ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->fetchAll();
    }
    
    public function getUnreadCount($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM notifications 
                         WHERE user_id = :user_id AND is_read = 0');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->fetch();
        
        return $result['count'] ?? 0;
    }
    
    public function markAsRead($notificationId) {
        $this->db->query('UPDATE notifications SET is_read = 1 
                         WHERE notification_id = :id');
        $this->db->bind(':id', $notificationId);
        
        return $this->db->execute();
    }
    
    public function markAllAsRead($userId) {
        $this->db->query('UPDATE notifications SET is_read = 1 
                         WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    public function delete($notificationId) {
        $this->db->query('DELETE FROM notifications WHERE notification_id = :id');
        $this->db->bind(':id', $notificationId);
        
        return $this->db->execute();
    }
    
  
    public function deleteOld() {
        $this->db->query('DELETE FROM notifications 
                         WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)');
        
        return $this->db->execute();
    }
}
?>