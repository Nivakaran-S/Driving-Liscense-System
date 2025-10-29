<?php

class Session {
    
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function flash($key, $value = null) {
        self::start();
        
        if ($value !== null) {
            $_SESSION['flash_' . $key] = $value;
        } else {
            $flashKey = 'flash_' . $key;
            if (isset($_SESSION[$flashKey])) {
                $value = $_SESSION[$flashKey];
                unset($_SESSION[$flashKey]);
                return $value;
            }
        }
        
        return null;
    }
    
    public static function getFlash($key) {
        return self::flash($key);
    }
    
    public static function setFlash($key, $value) {
        self::flash($key, $value);
    }
    
    public static function destroy() {
        self::start();
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    public static function regenerate() {
        self::start();
        session_regenerate_id(true);
    }
    
    public static function flashInput($data) {
        self::set('old_input', $data);
    }
    
    public static function old($key, $default = '') {
        $oldInput = self::get('old_input', []);
        $value = $oldInput[$key] ?? $default;
        
    
        if (count($oldInput) > 0) {
            self::remove('old_input');
        }
        
        return $value;
    }
    
    public static function setErrors($errors) {
        self::set('errors', $errors);
    }
    
    public static function getErrors() {
        $errors = self::get('errors', []);
        self::remove('errors');
        return $errors;
    }
    
    public static function getError($key) {
        $errors = self::get('errors', []);
        return $errors[$key] ?? null;
    }
    
    public static function hasErrors() {
        return !empty(self::get('errors', []));
    }
    
    public static function setToken() {
        $token = bin2hex(random_bytes(32));
        self::set('csrf_token', $token);
        return $token;
    }
    
    public static function getToken() {
        if (!self::has('csrf_token')) {
            return self::setToken();
        }
        return self::get('csrf_token');
    }
    
    public static function verifyToken($token) {
        return hash_equals(self::getToken(), $token);
    }
    
    public static function csrfField() {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
?>