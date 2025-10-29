<?php
class RoleMiddleware {
    
    public static function check($allowedRoles) {
        AuthMiddleware::handle();
        
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        
        $userRole = Auth::getRole();
        
        if (!in_array($userRole, $allowedRoles)) {
            Session::setFlash('error', 'Access denied. You do not have permission to access this resource.');
            
            Auth::redirectToDashboard();
        }
        
        return true;
    }
    
    public static function admin() {
        return self::check('admin');
    }
    
    public static function driver() {
        return self::check('driver');
    }
    
    public static function medicalOfficer() {
        return self::check('medical_officer');
    }
    
    public static function evaluator() {
        return self::check('evaluator');
    }
    
    public static function staff() {
        return self::check(['admin', 'medical_officer', 'evaluator']);
    }
    
    public static function can($action, $resource = null) {
        $userRole = Auth::getRole();
        $userId = Auth::getUserId();
        
        $permissions = [
            'admin' => [
                'create_slots',
                'manage_users',
                'view_all_applications',
                'view_reports',
                'delete_records',
            ],
            'driver' => [
                'submit_application',
                'book_slots',
                'view_own_application',
                'download_license',
            ],
            'medical_officer' => [
                'view_scheduled_medical',
                'conduct_medical_evaluation',
                'view_own_evaluations',
            ],
            'evaluator' => [
                'view_scheduled_tests',
                'conduct_driving_evaluation',
                'view_own_evaluations',
            ],
        ];
        
        if (isset($permissions[$userRole]) && in_array($action, $permissions[$userRole])) {
            return true;
        }
        
        if ($resource) {
            if ($action === 'view_application' && $userRole === 'driver') {
                return $resource['user_id'] == $userId;
            }
            
            if ($action === 'evaluate_medical' && $userRole === 'medical_officer') {
                return $resource['medical_officer_id'] == $userId;
            }
            
            if ($action === 'evaluate_driving' && $userRole === 'evaluator') {
                return $resource['evaluator_id'] == $userId;
            }
        }
        
        return false;
    }
    
    private static function denyAccess() {
        Session::setFlash('error', 'Access denied. Insufficient permissions.');
        Auth::redirectToDashboard();
    }
}
?>