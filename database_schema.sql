CREATE DATABASE IF NOT EXISTS driving_license_system;
USE driving_license_system;

CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'driver', 'evaluator', 'medical_officer') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_role (role),
    INDEX idx_email (email)
);

CREATE TABLE driver_profiles (
    profile_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    date_of_birth DATE NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    postal_code VARCHAR(10),
    national_id VARCHAR(20) UNIQUE NOT NULL,
    license_type ENUM('car', 'motorcycle', 'heavy_vehicle') NOT NULL,
    profile_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE applications (
    application_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    reference_id VARCHAR(20) UNIQUE NOT NULL,
    license_type ENUM('car', 'motorcycle', 'heavy_vehicle') NOT NULL,
    application_status ENUM('submitted', 'medical_scheduled', 'medical_passed', 'medical_failed', 
                           'driving_test_scheduled', 'driving_test_passed', 'driving_test_failed', 
                           'license_issued') DEFAULT 'submitted',
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    medical_test_date DATETIME NULL,
    driving_test_date DATETIME NULL,
    license_issue_date DATETIME NULL,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_reference_id (reference_id),
    INDEX idx_status (application_status),
    INDEX idx_user (user_id)
);

CREATE TABLE medical_slots (
    slot_id INT PRIMARY KEY AUTO_INCREMENT,
    slot_date DATE NOT NULL,
    slot_time TIME NOT NULL,
    medical_officer_id INT NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    max_capacity INT DEFAULT 1,
    current_bookings INT DEFAULT 0,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medical_officer_id) REFERENCES users(user_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    INDEX idx_date (slot_date),
    INDEX idx_availability (is_available),
    UNIQUE KEY unique_slot (slot_date, slot_time, medical_officer_id)
);

CREATE TABLE driving_test_slots (
    slot_id INT PRIMARY KEY AUTO_INCREMENT,
    slot_date DATE NOT NULL,
    slot_time TIME NOT NULL,
    evaluator_id INT NOT NULL,
    license_type ENUM('car', 'motorcycle', 'heavy_vehicle') NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    max_capacity INT DEFAULT 1,
    current_bookings INT DEFAULT 0,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evaluator_id) REFERENCES users(user_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    INDEX idx_date (slot_date),
    INDEX idx_availability (is_available),
    UNIQUE KEY unique_slot (slot_date, slot_time, evaluator_id)
);

CREATE TABLE medical_evaluations (
    evaluation_id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT UNIQUE NOT NULL,
    medical_officer_id INT NOT NULL,
    slot_id INT NOT NULL,
    evaluation_date DATETIME NOT NULL,
    vision_test ENUM('pass', 'fail') NOT NULL,
    hearing_test ENUM('pass', 'fail') NOT NULL,
    physical_fitness ENUM('pass', 'fail') NOT NULL,
    blood_pressure VARCHAR(20),
    overall_result ENUM('pass', 'fail') NOT NULL,
    remarks TEXT,
    evaluated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(application_id) ON DELETE CASCADE,
    FOREIGN KEY (medical_officer_id) REFERENCES users(user_id),
    FOREIGN KEY (slot_id) REFERENCES medical_slots(slot_id),
    INDEX idx_application (application_id)
);

CREATE TABLE driving_evaluations (
    evaluation_id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT UNIQUE NOT NULL,
    evaluator_id INT NOT NULL,
    slot_id INT NOT NULL,
    evaluation_date DATETIME NOT NULL,
    vehicle_control_score INT CHECK (vehicle_control_score BETWEEN 0 AND 100),
    traffic_rules_score INT CHECK (traffic_rules_score BETWEEN 0 AND 100),
    parking_score INT CHECK (parking_score BETWEEN 0 AND 100),
    road_safety_score INT CHECK (road_safety_score BETWEEN 0 AND 100),
    overall_score INT CHECK (overall_score BETWEEN 0 AND 100),
    result ENUM('pass', 'fail') NOT NULL,
    remarks TEXT,
    evaluated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(application_id) ON DELETE CASCADE,
    FOREIGN KEY (evaluator_id) REFERENCES users(user_id),
    FOREIGN KEY (slot_id) REFERENCES driving_test_slots(slot_id),
    INDEX idx_application (application_id)
);

CREATE TABLE issued_licenses (
    license_id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT UNIQUE NOT NULL,
    user_id INT NOT NULL,
    license_number VARCHAR(20) UNIQUE NOT NULL,
    license_type ENUM('car', 'motorcycle', 'heavy_vehicle') NOT NULL,
    issue_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    is_temporary BOOLEAN DEFAULT TRUE,
    license_file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(application_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    INDEX idx_license_number (license_number),
    INDEX idx_user (user_id)
);

CREATE TABLE audit_log (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_created_at (created_at)
);

CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    related_application_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (related_application_id) REFERENCES applications(application_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read)
);


INSERT INTO users (username, email, password_hash, role, full_name, phone) 
VALUES ('admin', 'admin@dls.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator', '0771234567');