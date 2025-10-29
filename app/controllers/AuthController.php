<?php

class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    
    public function login() {
        
        Auth::redirectIfLoggedIn();
        
        if ($this->isPost()) {
            
            $username = $this->sanitize($_POST['username']);
            $password = $_POST['password'];
            
            
            if (empty($username) || empty($password)) {
                $this->setFlash('error', 'Please enter both username and password');
                $this->view('auth/login', ['username' => $username]);
                return;
            }
            
            
            $user = $this->userModel->login($username, $password);
            
            if ($user) {
                
                Auth::login($user);
                
                
                if (isset($_SESSION['redirect_url'])) {
                    $redirectUrl = $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']);
                    header('Location: ' . $redirectUrl);
                    exit();
                } else {
                    Auth::redirectToDashboard();
                }
            } else {
                $this->setFlash('error', 'Invalid username or password');
                $this->view('auth/login', ['username' => $username]);
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    
    public function register() {
    
        Auth::redirectIfLoggedIn();
        
        if ($this->isPost()) {
    
            $data = [
                'username' => $this->sanitize($_POST['username']),
                'email' => $this->sanitize($_POST['email']),
                'password' => $_POST['password'],
                'confirm_password' => $_POST['confirm_password'],
                'full_name' => $this->sanitize($_POST['full_name']),
                'phone' => $this->sanitize($_POST['phone']),
                'date_of_birth' => $this->sanitize($_POST['date_of_birth']),
                'address' => $this->sanitize($_POST['address']),
                'city' => $this->sanitize($_POST['city']),
                'postal_code' => $this->sanitize($_POST['postal_code']),
                'national_id' => $this->sanitize($_POST['national_id']),
                'license_type' => $this->sanitize($_POST['license_type'])
            ];
            
    
            $errors = $this->validateRegistration($data);
            
            if (empty($errors)) {
    
                $userId = $this->userModel->register($data);
                
                if ($userId) {
                    $this->setFlash('success', 'Registration successful! Please login.');
                    $this->redirect('auth/login');
                } else {
                    $this->setFlash('error', 'Registration failed. Please try again.');
                    $this->view('auth/register', ['data' => $data]);
                }
            } else {
                $this->view('auth/register', ['data' => $data, 'errors' => $errors]);
            }
        } else {
            $this->view('auth/register');
        }
    }
    
    
    private function validateRegistration($data) {
        $errors = [];
        
        
        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($data['username']) < 4) {
            $errors['username'] = 'Username must be at least 4 characters';
        } elseif ($this->userModel->usernameExists($data['username'])) {
            $errors['username'] = 'Username already exists';
        }
        
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!$this->validateEmail($data['email'])) {
            $errors['email'] = 'Invalid email format';
        } elseif ($this->userModel->emailExists($data['email'])) {
            $errors['email'] = 'Email already registered';
        }
        
        
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Full name is required';
        }
        
        
        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        }
        
        
        if (empty($data['date_of_birth'])) {
            $errors['date_of_birth'] = 'Date of birth is required';
        } else {
            $dob = strtotime($data['date_of_birth']);
            $age = floor((time() - $dob) / 31556926); // Calculate age
            if ($age < 18) {
                $errors['date_of_birth'] = 'You must be at least 18 years old';
            }
        }
        
        
        if (empty($data['address'])) {
            $errors['address'] = 'Address is required';
        }
        
        
        if (empty($data['city'])) {
            $errors['city'] = 'City is required';
        }
        
        
        if (empty($data['national_id'])) {
            $errors['national_id'] = 'National ID is required';
        } elseif ($this->userModel->nationalIdExists($data['national_id'])) {
            $errors['national_id'] = 'National ID already registered';
        }
        
    
        if (empty($data['license_type'])) {
            $errors['license_type'] = 'License type is required';
        }
        
        return $errors;
    }
    
  
    public function logout() {
        Auth::logout();
    }
}
?>