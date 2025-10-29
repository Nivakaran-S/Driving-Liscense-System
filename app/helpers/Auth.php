<?php 

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getRole() {
        return $_SESSION['role'] ?? null;
    }

    public static function getUser() {
        return [
            'user_id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null
        ];
    }

    public static function hasRole($role) {
        if (is_array($role)) {
            return in_array(self::getRole(), $role);
        }
        return self::getRole() === $role;
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public static function redirectIfLoggedIn() {
        if (self::isLoggedIn()) {
            self::redirectToDashboard();
        }
    }

    public static function requireRole($role) {
        self::requireLogin();
        if (!self::hasRole($role)) {
            $_SESSION['error'] = 'Access denied. Insufficient permissions';
            self::redirectToDashboard();
        }
    }

    public static function redirectToDashboard() {
        $role = self::getRole();
        switch ($role) {
            case 'admin':
                header('Location: ' . BASE_URL . '/dashboard/admin');
                break;
            case 'driver':
                header('Location: ' . BASE_URL . '/dashboard/driver');
                break;
            case 'evaluator':
                header('Location: ' . BASE_URL . '/dashboard/evaluator');
                break;
            case 'medical_officer':
                header('Location: ' . BASE_URL . '/dashboard/medical');
                break;
            default:
                header('Location: ' . BASE_URL . '/');
        }
        exit();
    }

    public static function login($userData) {
        $_SESSION['user_id'] = $userData['user_id'];
        $SESSION['username'] = $userData['username'];
        $SESSION['email'] = $userData['email'];
        $SESSION['role'] = $userData['role'];
        $SESSION['full_name'] = $userData['full_name'];

        session_regenerate_id(true);
        return true;
    }

    public static function logout() {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit();
    }
}

?>