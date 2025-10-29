<?php
class AuthMiddleware {
    
    public static function handle() {
        if (!Auth::isLoggedIn()) {
    
            Session::set('redirect_url', $_SERVER['REQUEST_URI']);
            
    
            Session::setFlash('error', 'Please login to continue');
            
    
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }
        
        return true;
    }
    
    public static function guest() {
        if (Auth::isLoggedIn()) {
    
            Auth::redirectToDashboard();
        }
        
        return true;
    }
    
    public static function verifyCsrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            
            if (!Session::verifyToken($token)) {
                Session::setFlash('error', 'Invalid request. Please try again.');
                header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL);
                exit();
            }
        }
        
        return true;
    }
    
    public static function rateLimit($key, $maxAttempts = 5, $decayMinutes = 15) {
        $attempts = Session::get("rate_limit_{$key}", []);
        $now = time();
        
        $attempts = array_filter($attempts, function($timestamp) use ($now, $decayMinutes) {
            return ($now - $timestamp) < ($decayMinutes * 60);
        });
        
        if (count($attempts) >= $maxAttempts) {
            Session::setFlash('error', 'Too many attempts. Please try again later.');
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL);
            exit();
        }
        
        $attempts[] = $now;
        Session::set("rate_limit_{$key}", $attempts);
        
        return true;
    }
}
?>