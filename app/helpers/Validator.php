<?php

class Validator {
    
    private $data = [];
    private $errors = [];
    private $rules = [];
    
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    public function rules($rules) {
        $this->rules = $rules;
        return $this;
    }
    
    public function validate() {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    private function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        
    
        $params = [];
        if (strpos($rule, ':') !== false) {
            list($rule, $paramString) = explode(':', $rule, 2);
            $params = explode(',', $paramString);
        }
        
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, $this->getFieldName($field) . ' is required');
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'Please enter a valid email address');
                }
                break;
                
            case 'min':
                $min = $params[0] ?? 0;
                if (!empty($value) && strlen($value) < $min) {
                    $this->addError($field, $this->getFieldName($field) . " must be at least {$min} characters");
                }
                break;
                
            case 'max':
                $max = $params[0] ?? 255;
                if (!empty($value) && strlen($value) > $max) {
                    $this->addError($field, $this->getFieldName($field) . " must not exceed {$max} characters");
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, $this->getFieldName($field) . ' must be a number');
                }
                break;
                
            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, $this->getFieldName($field) . ' must be an integer');
                }
                break;
                
            case 'alpha':
                if (!empty($value) && !ctype_alpha(str_replace(' ', '', $value))) {
                    $this->addError($field, $this->getFieldName($field) . ' must contain only letters');
                }
                break;
                
            case 'alphanumeric':
                if (!empty($value) && !ctype_alnum(str_replace(' ', '', $value))) {
                    $this->addError($field, $this->getFieldName($field) . ' must contain only letters and numbers');
                }
                break;
                
            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->addError($field, $this->getFieldName($field) . ' must be a valid date');
                }
                break;
                
            case 'matches':
                $matchField = $params[0] ?? '';
                $matchValue = $this->data[$matchField] ?? null;
                if ($value !== $matchValue) {
                    $this->addError($field, $this->getFieldName($field) . ' must match ' . $this->getFieldName($matchField));
                }
                break;
                
            case 'in':
                if (!empty($value) && !in_array($value, $params)) {
                    $this->addError($field, $this->getFieldName($field) . ' must be one of: ' . implode(', ', $params));
                }
                break;
                
            case 'unique':
                    if (count($params) >= 2) {
                    $table = $params[0];
                    $column = $params[1];
                    $except = $params[2] ?? null;
                    
                    if ($this->isDuplicate($table, $column, $value, $except)) {
                        $this->addError($field, $this->getFieldName($field) . ' already exists');
                    }
                }
                break;
                
            case 'phone':
                if (!empty($value) && !preg_match('/^[0-9]{10}$/', $value)) {
                    $this->addError($field, 'Please enter a valid 10-digit phone number');
                }
                break;
                
            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, 'Please enter a valid URL');
                }
                break;
                
            case 'between':
                $min = $params[0] ?? 0;
                $max = $params[1] ?? 100;
                if (!empty($value) && ($value < $min || $value > $max)) {
                    $this->addError($field, $this->getFieldName($field) . " must be between {$min} and {$max}");
                }
                break;
        }
    }
    
    private function isDuplicate($table, $column, $value, $except = null) {
        $db = new Database();
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
        
        if ($except) {
            $sql .= " AND id != :except";
        }
        
        $db->query($sql);
        $db->bind(':value', $value);
        
        if ($except) {
            $db->bind(':except', $except);
        }
        
        $result = $db->fetch();
        return $result['count'] > 0;
    }
    
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }
    
    private function getFieldName($field) {
        return ucfirst(str_replace('_', ' ', $field));
    }
    
    public function errors() {
        return $this->errors;
    }
    
    public function error($field) {
        return $this->errors[$field] ?? null;
    }
    
    public function fails() {
        return !empty($this->errors);
    }
    
    public function passes() {
        return empty($this->errors);
    }
    
    public static function make($data, $rules) {
        $validator = new self($data);
        $validator->rules($rules);
        $validator->validate();
        return $validator;
    }
    
    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    
    public static function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function isPhone($phone) {
        return preg_match('/^0[0-9]{9}$/', $phone);
    }
    
    public static function isNIC($nic) {
    
        $oldFormat = preg_match('/^[0-9]{9}[VXvx]$/', $nic);
        
    
        $newFormat = preg_match('/^[0-9]{12}$/', $nic);
        
        return $oldFormat || $newFormat;
    }
    
    public static function isPastDate($date) {
        return strtotime($date) < time();
    }
    
    public static function isFutureDate($date) {
        return strtotime($date) > time();
    }
    
    public static function calculateAge($dob) {
        $birthDate = new DateTime($dob);
        $today = new DateTime('today');
        return $birthDate->diff($today)->y;
    }
    
    public static function isMinimumAge($dob, $minAge = 18) {
        $age = self::calculateAge($dob);
        return $age >= $minAge;
    }
}
?>