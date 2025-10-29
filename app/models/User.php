<?php 

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        try {
            $this->db->beginTransaction();

            $this->db->query("INSERT INTO users (username, email, password_hash, role, full_name, phone) VALUES (:username, :email, :password, :role, :full_name, :phone)");
            $this->db->bind(':username', $data['username']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
            $this->db->bind(':role', 'driver');
            $this->db->bind(':full_name', $data['full_name']);
            $this->db->bind(':phone', $data['phone']);

            $this->db->execute();
            $userId = $this->db->lastInsertId();

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':dob', $data['date_of_birth']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':city', $data['city']);
            $this->db->bind(':postal_code', $data['postal_code']);
            $this->db->bind(':national_id', $data['national_id']);
            $this->db->bind(':license_type', $data['license_type']);

            $this->db->execute();
            return $userId;
        } catch(Exception $e) {
            $this->db->rollback();
            return false;
        }
    }


    public function login($username, $password) {
        $this->db->query("SELECT * FROM users WHERE username = :username AND is_active = 1");
        $this->db->bind(':username', $username);

        $user = $this->db->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        } 
        
        return false;
    }

    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->fetch();
    }

    public function getUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        return $this->db->fetch();
    }

    public function emailExists($email) {
        $this->db->query('SELECT user_id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->fetch() !== false;
    }

    public function usernameExists($username) {
        $this->db->query('SELECT user_id FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        return $this->db->fetch() !== false;
    }

    public function nationalIdExists($nationalId) {
        $this->db->query('SELECT profile_id FROM driver_profiles WHERE national_id = :national_id');
        $this->db->bind(':national_id', $nationalId);
        return $this->db->fetch() !== false;
    }

    public function getUsersByRole($role) {
        $this->db->query('SELECT * FROM users WHERE role = :role AND is_active = 1 ORDER BY full_name');
        $this->db->bind(':role', $role);
        return $this->db->fetchAll();
    }

    public function updateProfile($userId, $data) {
        $this->db->query('UPDATE users SET full_name = :full_name, phone = :phone WHERE user_id = :user_id');
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    public function updatePassword($userId, $newPassword) {
        $this->db->query('UPDATE users SET password_hash = :password WHERE user_id = :user_id');
        $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    public function createSystemUser($data) {
        $this->db->query("INSERT INTO users (username, email, password_hash, role, full_name, phone) VALUES (:username, :email, :password, :role, :full_name, :phone)");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':phone', $data['phone']);

        $this->db->execute();
        return $this->db->lastInsertId();
    }
}


?>