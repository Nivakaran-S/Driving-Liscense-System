<?php

class BaseController {
    
    
    protected function view($view, $data = []) {
    
        extract($data);
        
        $viewFile = APP_ROOT . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die('View does not exist: ' . $view);
        }
    }
    
    
    protected function model($model) {
        $modelFile = APP_ROOT . '/models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die('Model does not exist: ' . $model);
        }
    }
    
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . $url);
        exit();
    }
    
    
    protected function setFlash($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }
    
    
    protected function getFlash($type) {
        if (isset($_SESSION['flash_' . $type])) {
            $message = $_SESSION['flash_' . $type];
            unset($_SESSION['flash_' . $type]);
            return $message;
        }
        return null;
    }
    
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    
    protected function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        } else {
            $data = htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
    
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    
    protected function validateRequired($fields) {
        $errors = [];
        foreach ($fields as $field => $value) {
            if (empty($value)) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        return $errors;
    }
    
    
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    
    protected function uploadFile($file, $uploadPath, $allowedTypes = []) {
    
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload failed'];
        }
        
        
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!empty($allowedTypes) && !in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type'];
        }
        
        
        $fileName = uniqid() . '_' . time() . '.' . $fileType;
        $targetPath = $uploadPath . $fileName;
        
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'filename' => $fileName, 'path' => $targetPath];
        }
        
        return ['success' => false, 'message' => 'Failed to save file'];
    }
    
    
    protected function getCurrentUser() {
        return Auth::getUser();
    }
    
    protected function requireAuth() {
        Auth::requireLogin();
    }
    
    
    protected function requireRole($role) {
        Auth::requireRole($role);
    }
}
?>